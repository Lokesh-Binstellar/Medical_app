<?php

namespace App\Http\Controllers;

use App\Models\AppRating;
use App\Models\Customers;
use App\Models\DeliveryPerson;
use App\Models\Laboratories;
use App\Models\Order;
use App\Models\Pharmacies;
use App\Models\Phrmacymedicine;
use App\Models\Rating;
use App\Models\RequestQuote;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role_id', 1)->count();
        $totalCustomers = Customers::count();
        $totalPharmacies = Pharmacies::count();
        $totalLabs = Laboratories::count();
        $totalDelivery = DeliveryPerson::count();

        $averageRating = AppRating::avg('rating');
        $averageRating = round($averageRating, 2);
        $salesData = null;
        $ratingPharma = null;
        $commissionData = null;
        $salesPerMonthData = null;
        $dailySalesData = null;
        $totalSalesForMonth = null;
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $daysInMonth = Carbon::now()->daysInMonth;
        // $totalCommission = null;

        $pharmacyexists = Pharmacies::where('user_id', Auth::user()->id)->exists();

        if ($pharmacyexists) {
            $pharmacyId = Auth::user()->id;
            $salesData = Order::where('pharmacy_id', $pharmacyId)
                ->selectRaw('SUM(items_price) as total_sales')
                ->selectRaw('COUNT(DISTINCT user_id) as total_customers')
                ->first();

            $ratingPharma = Rating::where('rateable_id', $pharmacyId)
                ->selectRaw('avg(rating) as total_rating')
                ->selectRaw('COUNT( customer_id) as total_viewers')
                ->first();

            $commissionData = Phrmacymedicine::where('phrmacy_id', $pharmacyId)
                ->selectRaw('SUM(commission_amount) as total_commission')
                ->first();

           $salesPerMonthData = Order::where('pharmacy_id', $pharmacyId)
    ->whereMonth('created_at', $currentMonth)
    ->whereYear('created_at', $currentYear)
    ->selectRaw('DAY(created_at) as day, SUM(items_price) as total_sales')
    ->groupBy('day')
    ->orderBy('day')
    ->pluck('total_sales', 'day')
    ->toArray();

$dailySalesData = [];
for ($i = 1; $i <= $daysInMonth; $i++) {
    $dailySalesData[] = $salesPerMonthData[$i] ?? 0;
}

// Calculate total sales of the month
$totalSalesForMonth = array_sum($salesPerMonthData);
        }


        // User data 
        if ($request->ajax()) {
            $data = User::with('role')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                // Name column with random avatar and name only
                ->addColumn('name', function ($row) {
                    // Random avatar from 1.png to 5.png (you can adjust range)
                    $randomAvatar = asset('assets/img/dasuseravtar/' . rand(1, 7) . '.png');

                    return '
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                            <img src="' . $randomAvatar . '" class="rounded-circle" alt="Avatar" width="36" height="36">
                        </div>
                        <div>
                            <h6 class="mb-0 text-truncate">' . e($row->name) . '</h6>
                        </div>
                    </div>';
                })

                // Role column with icon
                ->addColumn('role', function ($row) {
                    $roleName = strtolower($row->role->name ?? '');

                    switch ($roleName) {
                        case 'admin':
                            $icon = '<i class="menu-icon tf-icons mdi mdi-crown-outline text-primary icon-22px me-2"></i>';
                            break;
                        case 'pharmacy':
                            $icon = '<i class="menu-icon tf-icons mdi mdi-pill text-success icon-22px me-2"></i>';
                            break;
                        case 'laboratory':
                            $icon = '<i class="menu-icon tf-icons mdi mdi-microscope text-info icon-22px me-2"></i>';
                            break;
                        case 'delivery boy':
                            $icon = '<i class="menu-icon tf-icons mdi mdi-truck-delivery-outline text-warning icon-22px me-2"></i>';
                            break;
                        default:
                            $icon = '<i class="menu-icon tf-icons mdi mdi-account-outline text-muted icon-22px me-2"></i>';
                            break;
                    }


                    return '<div class="d-flex align-items-center">' . $icon . '<span>' . ucfirst($roleName) . '</span></div>';
                })

                ->rawColumns(['name', 'role']) // Important for rendering HTML
                ->make(true);
        }

        return view('dashboard', compact(

            'totalUsers',
            'totalAdmins',
            'totalCustomers',
            'totalPharmacies',
            'totalLabs',
            'totalDelivery',
            'averageRating',
            'salesData',
            'ratingPharma',
            'commissionData',
            'salesPerMonthData',
            'dailySalesData',
            'totalSalesForMonth',
            // 'totalCommission'
        ));
    }



    public static function getTimestamp($datetime)
    {
        if (!$datetime) {
            return null;
        }
        return date("Y-m-d", strtotime($datetime));
    }

    public function getAllGraphData(Request $request)
    {
        $commissionStartDate = $this->getTimestamp($request->commission_start_date);
        $commissionEndDate = $this->getTimestamp($request->commission_end_date);
        $salesStartDate = $this->getTimestamp($request->sales_start_date);
        $salesEndDate = $this->getTimestamp($request->sales_end_date);

        $data = [
            'commissionGraphData' => $this->commissionGraphData($commissionStartDate, $commissionEndDate),
            'salesGraphData' => $this->salesGraphData($salesStartDate, $salesEndDate),
        ];
        return response()->json($data);
    }


    // Commission This Month
    public function commissionGraphData($startDate, $endDate)
    {
        $pharmacyExists = Pharmacies::where('user_id', Auth::id())->exists();
        if (!$pharmacyExists || !$startDate || !$endDate) {
            return [
                'totalCommission' => 0,
                'chartData' => []
            ];
        }

        $pharmacyId = Auth::id();

        // Parse dates with Carbon and set start/end of day
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();

        // Calculate difference in days
        $diffInDays = $start->diffInDays($end);

        // Fetch records in the date range
        $records = Phrmacymedicine::where('phrmacy_id', $pharmacyId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalCommission = $records->sum('commission_amount');

        $chartData = [];

        if ($diffInDays <= 7) {
            // Day wise grouping
            $period = \Carbon\CarbonPeriod::create($start, $end);

            foreach ($period as $date) {
                $label = $date->format('l'); // Day name
                $daySum = $records->filter(function ($item) use ($date) {
                    return \Carbon\Carbon::parse($item->created_at)->isSameDay($date);
                })->sum('commission_amount');

                $chartData[] = [
                    'label' => $label,
                    'value' => $daySum
                ];
            }
        } elseif ($diffInDays <= 31) {
            // Week wise grouping
            $weeks = [];

            $period = \Carbon\CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $weekNum = $date->weekOfMonth;
                $yearMonth = $date->format('Ym');
                $weekLabel = "Week {$weekNum}";

                $key = $yearMonth . '-' . $weekNum;

                if (!isset($weeks[$key])) {
                    $weeks[$key] = [
                        'label' => $weekLabel,
                        'value' => 0,
                        'start_date' => $date->copy()->startOfWeek(),
                        'end_date' => $date->copy()->endOfWeek()
                    ];
                }
            }

            // Sum commission per week within the date range
            foreach ($weeks as $key => &$week) {
                // Clamp week start and end to original date range
                if ($week['start_date']->lt($start)) {
                    $week['start_date'] = $start->copy();
                }
                if ($week['end_date']->gt($end)) {
                    $week['end_date'] = $end->copy();
                }

                $week['value'] = $records->filter(function ($item) use ($week) {
                    $date = \Carbon\Carbon::parse($item->created_at);
                    return $date->between($week['start_date'], $week['end_date']);
                })->sum('commission_amount');
            }
            unset($week);

            $chartData = array_values(array_map(function ($week) {
                return [
                    'label' => $week['label'],
                    'value' => $week['value']
                ];
            }, $weeks));
        } else {
            // Month wise grouping with clamping
            $months = [];

            $period = \Carbon\CarbonPeriod::create($start->copy()->startOfMonth(), $end->copy()->endOfMonth(), '1 month');
            foreach ($period as $date) {
                $monthLabel = $date->format('F Y');

                // Calculate clamped start and end dates for this month segment
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();

                // Clamp to overall start and end
                if ($monthStart->lt($start)) {
                    $monthStart = $start->copy();
                }
                if ($monthEnd->gt($end)) {
                    $monthEnd = $end->copy();
                }

                $months[$monthLabel] = [
                    'start_date' => $monthStart,
                    'end_date' => $monthEnd,
                    'value' => 0
                ];
            }

            // Sum commissions for each month with clamped ranges
            foreach ($months as $label => &$month) {
                $month['value'] = $records->filter(function ($item) use ($month) {
                    $date = \Carbon\Carbon::parse($item->created_at);
                    return $date->between($month['start_date'], $month['end_date']);
                })->sum('commission_amount');
            }
            unset($month);

            // Prepare chart data
            foreach ($months as $label => $month) {
                $chartData[] = [
                    'label' => $label,
                    'value' => $month['value']
                ];
            }
        }

        return [
            'totalCommission' => $totalCommission,
            'chartData' => $chartData
        ];
    }


    // Sales This Month
    public function salesGraphData($startDate, $endDate)
    {
        $pharmacyId = Auth::id();

        $pharmacyExists = Pharmacies::where('user_id', $pharmacyId)->exists();

        if (!$pharmacyExists || !$startDate || !$endDate) {
            return [
                'totalSales' => 0,
                'chartData' => []
            ];
        }

        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();

        $diffInDays = $start->diffInDays($end);

        $records = Order::where('pharmacy_id', $pharmacyId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalSales = $records->sum('items_price');

        $chartData = [];

        if ($diffInDays <= 7) {
            // Day-wise grouping
            $period = \Carbon\CarbonPeriod::create($start, $end);

            foreach ($period as $date) {
                $label = $date->format('l'); // Day name

                $daySum = $records->filter(function ($item) use ($date) {
                    return \Carbon\Carbon::parse($item->created_at)->isSameDay($date);
                })->sum('items_price');

                $chartData[] = [
                    'label' => $label,
                    'value' => $daySum
                ];
            }
        } elseif ($diffInDays <= 31) {
            // Week-wise grouping
            $weeks = [];

            $period = \Carbon\CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $weekNum = $date->weekOfMonth;
                $yearMonth = $date->format('Ym');
                $weekLabel = "Week {$weekNum}";

                $key = $yearMonth . '-' . $weekNum;

                if (!isset($weeks[$key])) {
                    $weeks[$key] = [
                        'label' => $weekLabel,
                        'value' => 0,
                        'start_date' => $date->copy()->startOfWeek(),
                        'end_date' => $date->copy()->endOfWeek()
                    ];
                }
            }

            // Sum sales per week within the date range
            foreach ($weeks as $key => &$week) {
                // Clamp week start and end to original date range
                if ($week['start_date']->lt($start)) {
                    $week['start_date'] = $start->copy();
                }
                if ($week['end_date']->gt($end)) {
                    $week['end_date'] = $end->copy();
                }

                $week['value'] = $records->filter(function ($item) use ($week) {
                    $date = \Carbon\Carbon::parse($item->created_at);
                    return $date->between($week['start_date'], $week['end_date']);
                })->sum('items_price');
            }
            unset($week);

            $chartData = array_values(array_map(function ($week) {
                return [
                    'label' => $week['label'],
                    'value' => $week['value']
                ];
            }, $weeks));
        } else {
            // Month-wise grouping with clamping
            $months = [];

            $period = \Carbon\CarbonPeriod::create($start->copy()->startOfMonth(), $end->copy()->endOfMonth(), '1 month');
            foreach ($period as $date) {
                $monthLabel = $date->format('F Y');

                // Calculate clamped start and end dates for this month segment
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();

                // Clamp to overall start and end
                if ($monthStart->lt($start)) {
                    $monthStart = $start->copy();
                }
                if ($monthEnd->gt($end)) {
                    $monthEnd = $end->copy();
                }

                $months[$monthLabel] = [
                    'start_date' => $monthStart,
                    'end_date' => $monthEnd,
                    'value' => 0
                ];
            }

            // Sum sales for each month with clamped ranges
            foreach ($months as $label => &$month) {
                $month['value'] = $records->filter(function ($item) use ($month) {
                    $date = \Carbon\Carbon::parse($item->created_at);
                    return $date->between($month['start_date'], $month['end_date']);
                })->sum('items_price');
            }
            unset($month);

            // Prepare chart data
            foreach ($months as $label => $month) {
                $chartData[] = [
                    'label' => $label,
                    'value' => $month['value']
                ];
            }
        }

        return [
            'totalSales' => $totalSales,
            'chartData' => $chartData
        ];
    }





    // customer order details 
    public function getOrdersData(Request $request)
    {
        $date = $request->input('date') ?? Carbon::today()->toDateString(); // fallback to today if not provided

        $orders = Order::with('customer')
            ->whereDate('created_at', $date)
            ->select('orders.*');

        return datatables()->of($orders)
            ->addColumn('order_id', fn($order) => $order->order_id)
            ->addColumn('name', function ($order) {
                if ($order->customer) {
                    return $order->customer->firstName . ' ' . $order->customer->lastName . '<br><small>' . $order->customer->mobile_no . '</small>';
                } else {
                    return '<em>Not found</em>';
                }
            })
            ->addColumn('status', function ($order) {
                return match ($order->status) {
                    0 => '<span class="badge bg-warning">Request Accepted</span>',
                    1 => '<span class="badge bg-success">Completed</span>',
                    2 => '<span class="badge bg-danger">Cancelled</span>',
                };
            })
            ->addColumn('action', function ($order) {
                $url = url('/search-medicine/pharmacist/order-details') . '?order_id=' . $order->id;
                // return '<a href="' . $url . '" class="btn btn-primary">View Full Details</a>';
                return '
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="' . $url . '" class="dropdown-item">View Full Details</a>
                            </li>
                           
                        </ul>
                    </div>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->make(true);
    }

    // Pending Quotes
    public function pendingQuotesData(Request $request)
    {
        $quotes = RequestQuote::where('pharmacy_address_status', 0);

        return DataTables::of($quotes)
            ->addIndexColumn()
            ->addColumn('quote_id', function ($quote) {
                return $quote->id;
            })
            ->addColumn('customer_details', function ($quote) {
                $customer = Customers::find($quote->customer_id);
                if ($customer) {
                    return $customer->firstName . ' ' . $customer->lastName;
                }
                return 'N/A';
            })
            ->addColumn('status', function ($quote) {
                return match ($quote->pharmacy_address_status) {
                    0 => '<span class="badge bg-warning">Pending</span>',
                    1 => '<span class="badge bg-success">Completed</span>',
                    default => '<span class="badge bg-secondary">Unknown</span>',
                };
            })
            ->addColumn('created_at', function ($quote) {
                return $quote->created_at->format('d-m-Y h:i A');
            })
            ->rawColumns(['status'])
            ->make(true);
    }


    // Average Pharmacy/Laboratory Rating
    public function fetchRatings(Request $request)
    {
        $type = $request->get('type');

        if ($type === 'Pharmacy') {
            $data = Pharmacies::with('user')->get()->map(function ($item) {
                $avg = Rating::where('rateable_type', 'Pharmacy')
                    ->where('rateable_id', $item->user_id)
                    ->avg('rating');

                $count = Rating::where('rateable_type', 'Pharmacy')
                    ->where('rateable_id', $item->user_id)
                    ->count();

                return [
                    'name' => $item->pharmacy_name ?? 'Unknown',
                    'rating' => $avg ? number_format($avg, 1) : 'N/A',
                    'total_ratings' => $count,
                    'avg_rating' => $avg ?? 0 // sorting ke liye numeric value rakhte hain
                ];
            });

            // Sort descending by avg_rating (jo numeric hai)
            $data = $data->sortByDesc('avg_rating')->values()->all();
        } elseif ($type === 'Laboratory') {
            $data = Laboratories::with('user')->get()->map(function ($item) {
                $avg = Rating::where('rateable_type', 'Laboratory')
                    ->where('rateable_id', $item->user_id)
                    ->avg('rating');

                $count = Rating::where('rateable_type', 'Laboratory')
                    ->where('rateable_id', $item->user_id)
                    ->count();

                return [
                    'name' => $item->lab_name ?? 'Unknown',
                    'rating' => $avg ? number_format($avg, 1) : 'N/A',
                    'total_ratings' => $count,
                    'avg_rating' => $avg ?? 0
                ];
            });

            $data = $data->sortByDesc('avg_rating')->values()->all();
        } else {
            return datatables()->of(collect([]))->make(true);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    // Top Pharmacies
    public function getTopPharmaciesData()
    {
        $topRated = Rating::where('rateable_type', 'Pharmacy')
            ->selectRaw('rateable_id, AVG(rating) as avg_rating')
            ->groupBy('rateable_id')
            ->orderByDesc('avg_rating')
            ->get()
            ->filter(function ($rating) {
                return Pharmacies::where('user_id', $rating->rateable_id)->exists();
            })
            ->take(3);

        $data = $topRated->map(function ($rating, $index) {
            $pharmacy = Pharmacies::where('user_id', $rating->rateable_id)->first();

            $accepted = Order::where('pharmacy_id', $pharmacy->user_id)->where('status', 0)->count();
            $completed = Order::where('pharmacy_id', $pharmacy->user_id)->where('status', 1)->count();


            return [

                'id' => $index + 1,
                'pharmacy_name' => $pharmacy->pharmacy_name ?? 'Unknown',
                'total_accepted' => $accepted,
                'total_completed' => $completed
            ];
        })->values();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
