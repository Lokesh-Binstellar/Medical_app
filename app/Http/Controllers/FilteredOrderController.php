<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pharmacies;
use Carbon\Carbon;
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
                    return e($order->pharmacy_name ?? 'N/A');
                })
                ->addColumn('pharmacy_city', function ($order) {
                    if (!$order->selected_pharmacy_address) return 'N/A';

                    // Extract city from address (third-last item from comma-separated address)
                    $parts = explode(',', $order->selected_pharmacy_address);
                    $parts = array_map('trim', $parts);
                    $count = count($parts);
                    return $count >= 3 ? $parts[$count - 3] : 'N/A';
                })
                ->addColumn('commission', function () {
                    return ''; // Keep blank for now
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
}
