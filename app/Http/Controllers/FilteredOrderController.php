<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pharmacies;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class FilteredOrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleName = Auth::user()->role->name;

            // Instead of eager loading by pharmacy_id, join pharmacies table on user_id
            $query = Order::query()
                ->select('orders.*') // select orders.* to avoid ambiguity

                // Join pharmacies on pharmacies.user_id = orders.pharmacy_id
                ->join('pharmacies', 'pharmacies.user_id', '=', 'orders.pharmacy_id');

            // Add select for pharmacy_name (avoid full pharmacy object)
            $query->addSelect('pharmacies.pharmacy_name');

            // Date range filter
            if ($request->filled('dateRange')) {
                $dates = explode(' to ', $request->dateRange);
                if (count($dates) === 2) {
                    $start = Carbon::parse(trim($dates[0]))->startOfDay();
                    $end = Carbon::parse(trim($dates[1]))->endOfDay();
                    $query->whereBetween('orders.created_at', [$start, $end]);
                }
            }

            // Filter by city on pharmacies table
            if ($request->filled('city')) {
                $city = $request->city;
                $query->where('pharmacies.city', $city);
            }
            if ($request->filled('status')) {
                $query->where('orders.status', $request->status);
            }

            // Filter by pharmacy_id (which actually is user_id of pharmacy here)
            if ($request->filled('pharmacy_id')) {
                $query->where('orders.pharmacy_id', $request->pharmacy_id);
            }

            // Role based filtering (assuming pharmacy_id is user_id of pharmacy)
            if ($roleName === 'admin') {
                $query->whereNotNull('orders.pharmacy_id');
            } elseif ($roleName === 'pharmacy') {
                $query->where('orders.pharmacy_id', Auth::user()->pharmacy_id);
            } else {
                $query->whereRaw('0 = 1'); // no results for others
            }

            $query->orderBy('orders.created_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('date_formatted', function ($order) {
                    return $order->created_at ? $order->created_at->format('d M Y h:i A') : 'N/A';
                })
                ->addColumn('customer_name', function ($order) {
                    if (!$order->customer) return 'N/A';
                    $fullName = $order->customer->firstName . ' ' . $order->customer->lastName;
                    $phone = $order->customer->mobile_no;
                    return '<div style="white-space: normal; word-wrap: break-word; max-width: 150px;">'
                        . e($fullName) . ' (' . e($phone) . ')'
                        . '</div>';
                })
                ->addColumn('status', function ($order) {
                    return match ($order->status) {
                        0 => '<span class="badge bg-warning">Request Accepted</span>',
                        1 => '<span class="badge bg-success">Completed</span>',
                        2 => '<span class="badge bg-danger">Cancelled</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('action', function ($order) {
                    return '
                <div class="">
                    <a href="' . route('orders.medicines', $order->id) . '" 
                       class="btn btn-sm btn-primary control me-2">
                        <i class="mdi mdi-eye"></i> View
                    </a>
                </div>';
                })
                // Use the joined pharmacy_name directly here
                ->addColumn('pharmacy_name', function ($order) {
                    return ($order->pharmacy_name ?? 'N/A');
                })
                ->addColumn('pharmacy_city', function ($order) {
                    if (!$order->selected_pharmacy_address) return 'N/A';

                    // Extract city from address (third-last item from comma-separated address)
                    $parts = explode(',', $order->selected_pharmacy_address);
                    $parts = array_map('trim', $parts);
                    $count = count($parts);
                    return $count >= 3 ? $parts[$count - 3] : 'N/A';
                })
                ->addColumn('commission', function ($order) {
                    return ($order->commission ?? 'N/A'); // Keep blank for now
                })
                ->rawColumns(['customer_name', 'status', 'action'])
                ->make(true);
        }

        // For filters dropdown, load pharmacies by user_id and pharmacy_name only
        $cities = Order::selectRaw("
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(selected_pharmacy_address, ',', -3), ',', 1)) as city
     ")
            ->whereNotNull('selected_pharmacy_address')
            ->groupBy('city')
            ->pluck('city');

        $pharmacies = Pharmacies::select('user_id as id', 'pharmacy_name')->get(); // Note: user_id as id for dropdown
        $orders = [
            ['id' => 0, 'label' => 'Request Accepted'],
            ['id' => 1, 'label' => 'Completed'],
            ['id' => 2, 'label' => 'Cancelled'],
        ];

        return view('ordersFilter.index', compact('cities', 'pharmacies', 'orders'));
    }



    public function salesData(Request $request)
    {
        $query = Order::query();

        // Apply filters (same as in index)
        if ($request->filled('dateRange')) {
            $dates = explode(' to ', $request->dateRange);
            if (count($dates) === 2) {
                $start = Carbon::parse(trim($dates[0]))->startOfDay();
                $end = Carbon::parse(trim($dates[1]))->endOfDay();
                $query->whereBetween('orders.created_at', [$start, $end]);
            }
        }

        if ($request->filled('city')) {
            $query->whereHas('pharmacy', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pharmacy_id')) {
            $query->where('pharmacy_id', $request->pharmacy_id);
        }

        // Get filtered orders
        $orders = $query->get();

        // Summary counts
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 1)->count();       // Completed
        $cancelledOrders = $orders->where('status', 2)->count();       // Cancelled
        $acceptedOrders = $orders->where('status', 0)->count();        // Request Accepted


        $totalSales = $orders->sum('total_price');
        $totalCommission = $orders->sum('commission'); // make sure 'commission' exists

        // Grouped sales data for chart
        $chartData = $orders
            ->groupBy(function ($order) {
                return Carbon::parse($order->created_at)->format('Y-m-d');
            })
            ->map(function ($group, $date) {
                return [
                    'label' => $date,
                    'value' => $group->sum('total_price'),
                ];
            })
            ->values();

        return response()->json([
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'request_accepted_orders' => $acceptedOrders,
            'total_sales' => number_format($totalSales, 2, '.', ''),
            'total_commission' => number_format($totalCommission, 2, '.', ''),
            'chartData' => $chartData // this is grouped daily total sales data
        ]);
    }



    // public function getPharmacyOrderStats()
    // {
    //     // Accepted orders from phrmacymedicines (using correct column name: phrmacy_id)
    //     $acceptedData = DB::table('phrmacymedicines as pm')
    //         ->join('request_quotes as rq', function ($join) {
    //             $join->on('pm.customer_id', '=', 'rq.customer_id')
    //                 ->on('pm.phrmacy_id', '=', 'rq.pharmacy_id')
    //                 ->whereRaw('rq.created_at = (
    //                 SELECT MAX(rq2.created_at)
    //                 FROM request_quotes as rq2
    //                 WHERE rq2.customer_id = pm.customer_id
    //                   AND rq2.pharmacy_id = pm.phrmacy_id
    //                   AND rq2.created_at <= pm.created_at
    //             )');
    //         })
    //         ->select(
    //             'pm.phrmacy_id',
    //             DB::raw('COUNT(*) as accepted_orders'),
    //             DB::raw('ROUND(AVG(TIMESTAMPDIFF(MINUTE, rq.created_at, pm.created_at)), 2) as avg_accept_time')
    //         )
    //         ->groupBy('pm.phrmacy_id');

    //     $report = DB::table('orders as o')
    //         ->join('phrmacymedicines as rq', function ($join) {
    //             $join->on('o.user_id', '=', 'rq.customer_id')
    //                 ->on('o.pharmacy_id', '=', 'rq.phrmacy_id') // ✅ use correct column name
    //                 ->whereRaw('rq.created_at = (
    //             SELECT MAX(rq2.created_at)
    //             FROM phrmacymedicines as rq2
    //             WHERE rq2.customer_id = o.user_id
    //               AND rq2.phrmacy_id = o.pharmacy_id
    //               AND rq2.created_at <= o.created_at
    //         )');
    //         })
    //         ->join('pharmacies as p', 'o.pharmacy_id', '=', 'p.user_id')
    //         ->leftJoinSub($acceptedData, 'accepts', function ($join) {
    //             $join->on('o.pharmacy_id', '=', 'accepts.phrmacy_id');
    //         })
    //         ->select(
    //             'p.pharmacy_name',
    //             DB::raw('IFNULL(accepts.accepted_orders, 0) as accepted_orders'),
    //             DB::raw('IFNULL(accepts.avg_accept_time, "N/A") as avg_accept_time'),

    //             DB::raw('SUM(CASE WHEN o.status = 1 THEN 1 ELSE 0 END) as placed_orders'),
    //             DB::raw('SUM(CASE WHEN o.status = 2 THEN 1 ELSE 0 END) as cancelled_orders'),

    //             DB::raw('IFNULL(ROUND(AVG(CASE WHEN o.status = 1 THEN TIMESTAMPDIFF(MINUTE, rq.created_at, o.updated_at) ELSE NULL END), 2), "N/A") as avg_placed_time'),
    //             DB::raw('IFNULL(ROUND(AVG(CASE WHEN o.status = 2 THEN TIMESTAMPDIFF(MINUTE, rq.created_at, o.updated_at) ELSE NULL END), 2), "N/A") as avg_cancel_time')
    //         )

    //         ->groupBy(
    //             'o.pharmacy_id',
    //             'p.pharmacy_name',
    //             'accepts.accepted_orders',
    //             'accepts.avg_accept_time'
    //         )
    //         ->get();

    //     return DataTables::of($acceptedData)
    //         ->addIndexColumn()
    //         ->make(true);
    // }

    public function getPharmacyOrderStats()
    {
        $acceptedData = DB::table('orders')
            ->select(
                'pharmacy_id',
                DB::raw('COUNT(*) as accepted_orders'),
                DB::raw('ROUND(AVG(quote_diff_minutes), 2) as avg_accept_time')
            )
            ->whereNotNull('quote_diff_minutes')
            ->groupBy('pharmacy_id');

        $report = DB::table('orders as o')
            ->join('pharmacies as p', 'o.pharmacy_id', '=', 'p.user_id')
            ->leftJoinSub($acceptedData, 'accepts', function ($join) {
                $join->on('o.pharmacy_id', '=', 'accepts.pharmacy_id');
            })
            ->select(
                'p.pharmacy_name',
                DB::raw('IFNULL(accepts.accepted_orders, 0) as accepted_orders'),
                DB::raw('IFNULL(accepts.avg_accept_time, "N/A") as avg_accept_time'),
                DB::raw('SUM(CASE WHEN o.status = 1 THEN 1 ELSE 0 END) as placed_orders'),
                DB::raw('SUM(CASE WHEN o.status = 2 THEN 1 ELSE 0 END) as cancelled_orders'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->groupBy(
                'o.pharmacy_id',
                'p.pharmacy_name',
                'accepts.accepted_orders',
                'accepts.avg_accept_time'
            )
            ->orderByDesc('total_orders')
            ->get();

        return DataTables::of($report)
            ->addIndexColumn()
            ->make(true);
    }
    public function getPharmacyOrderResponce()
    {
        $report = DB::table('orders as o')
            ->join('pharmacies as p', 'o.pharmacy_id', '=', 'p.user_id')
            ->select(
                'p.pharmacy_name',
                'o.order_id', // ← Add this line
                'o.quote_diff_minutes'
            )
            ->whereNotNull('o.quote_diff_minutes')
            ->orderBy('o.created_at', 'desc')
            ->get();

        return DataTables::of($report)
            ->addIndexColumn()
            ->addColumn('order_response_time', function ($row) {
                return $row->quote_diff_minutes . ' min';
            })
            ->rawColumns(['order_response_time'])
            ->make(true);
    }


    public function getTopPharmacyStats()
    {
        $report = DB::table('orders as o')
            ->join('pharmacies as p', 'o.pharmacy_id', '=', 'p.user_id')
            ->select(
                'o.pharmacy_id',
                'p.pharmacy_name',
                // Total accepted orders
                DB::raw('SUM(CASE WHEN o.status = 0 THEN 1 ELSE 0 END) as accepted_orders'),
                // Total completed orders
                DB::raw('SUM(CASE WHEN o.status = 1 THEN 1 ELSE 0 END) as completed_orders'),
                // Average delivery time for completed orders (in minutes)
                DB::raw('ROUND(AVG(CASE WHEN o.status = 1 THEN TIMESTAMPDIFF(MINUTE, o.created_at, o.updated_at) ELSE NULL END), 2) as avg_delivery_time')
            )
            ->groupBy('o.pharmacy_id', 'p.pharmacy_name')
            ->orderByDesc('completed_orders')
            ->get();

        return DataTables::of($report)
            ->addIndexColumn()
            ->make(true);
    }

    public function getRepeatCustomerStats()
    {
        // Subquery to count orders per pharmacy-user combo
        $userOrders = DB::table('orders')
            ->select('pharmacy_id', 'user_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('pharmacy_id', 'user_id');

        // Subquery for repeat customers (more than 1 order)
        $repeatOrders = DB::table(DB::raw("({$userOrders->toSql()}) as uo"))
            ->mergeBindings($userOrders)
            ->where('uo.order_count', '>', 1)
            ->select(
                'uo.pharmacy_id',
                DB::raw('COUNT(*) as repeat_customers'),
                DB::raw('SUM(uo.order_count) as total_repeat_orders')
            )
            ->groupBy('uo.pharmacy_id');

        // Subquery to get top repeat customer per pharmacy
        $topRepeat = DB::table('orders')
            ->select('pharmacy_id', 'user_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('pharmacy_id', 'user_id');

        $topRepeatCustomer = DB::table(DB::raw("({$topRepeat->toSql()}) as tr"))
            ->mergeBindings($topRepeat)
            ->join('customers as c', 'c.id', '=', 'tr.user_id')
            ->select(
                'tr.pharmacy_id',
                DB::raw("CONCAT(c.firstName, ' ', c.lastName) as top_repeat_customer"),
                'tr.order_count'
            )
            ->orderByDesc('tr.order_count');


        // Main query
        $report = DB::table('pharmacies as p')
            ->leftJoinSub($repeatOrders, 'r', 'r.pharmacy_id', '=', 'p.user_id')
            ->leftJoinSub($topRepeatCustomer, 't', 't.pharmacy_id', '=', 'p.user_id')
            ->select(
                'p.user_id as pharmacy_id',
                'p.pharmacy_name',
                DB::raw('IFNULL(r.repeat_customers, 0) as repeat_customers'),
                DB::raw('IFNULL(r.total_repeat_orders, 0) as total_repeat_orders'),
                DB::raw('IFNULL(t.top_repeat_customer, "N/A") as top_repeat_customer')
            )
            ->groupBy('p.user_id', 'p.pharmacy_name', 'r.repeat_customers', 'r.total_repeat_orders', 't.top_repeat_customer')
            ->orderByDesc('repeat_customers')
            ->get();

        return DataTables::of($report)
            ->addIndexColumn()
            ->make(true);
    }
}
