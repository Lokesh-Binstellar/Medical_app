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
use App\Models\User;
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
        $totalCommission = null;

        $pharmacyexists = Pharmacies::where('user_id', Auth::user()->id)->exists();
        // dd($pharmacyexists);
        if ($pharmacyexists) {
            $pharmacyId = Auth::user()->id;
            $salesData = Order::where('pharmacy_id', $pharmacyId)
                ->selectRaw('SUM(items_price) as total_sales')
                ->selectRaw('COUNT(DISTINCT user_id) as total_customers')
                ->first();
            // dd($salesData);


            $ratingPharma = Rating::where('rateable_id', $pharmacyId)
                ->selectRaw('avg(rating) as total_rating')
                ->selectRaw('COUNT( customer_id) as total_viewers')
                ->first();
            $month = $request->input('month', now()->month);
            $year = $request->input('year', now()->year);
            $totalCommission = Phrmacymedicine::where('phrmacy_id', $pharmacyId)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->selectRaw('SUM(commission_amount) as total_commission_amount')
                ->first();
        }



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
            'totalCommission'
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
        $paymentStartDate = $this->getTimestamp($request->payment_start_date);
        $paymentEndDate = $this->getTimestamp($request->payment_end_date);

        $data = [
            'paymentGraphData' => $this->paymentGraphData($paymentStartDate, $paymentEndDate),
        ];
        return response()->json($data);
    }

    public function paymentGraphData($startDate, $endDate)
    {
        $totalCommission = 0;
        $pharmacyExists = Pharmacies::where('user_id', Auth::id())->exists();

        if ($pharmacyExists && $startDate && $endDate) {
            $pharmacyId = Auth::id();

            $totalCommission = Phrmacymedicine::where('phrmacy_id', $pharmacyId)
                ->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$startDate, $endDate])
                ->sum('commission_amount');
            //dd($startDate);

        }

        return $totalCommission;
    }


    // public function dasindex(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = User::with('role')->latest()->get();

    //         return DataTables::of($data)
    //             ->addIndexColumn()

    //             // Name column with random avatar and name only
    //             ->addColumn('name', function ($row) {
    //                 // Random avatar from 1.png to 5.png (you can adjust range)
    //                 $randomAvatar = asset('assets/img/dasuseravtar/' . rand(1, 7) . '.png');

    //                 return '
    //                 <div class="d-flex align-items-center">
    //                     <div class="avatar avatar-sm me-3">
    //                         <img src="' . $randomAvatar . '" class="rounded-circle" alt="Avatar" width="36" height="36">
    //                     </div>
    //                     <div>
    //                         <h6 class="mb-0 text-truncate">' . e($row->name) . '</h6>
    //                     </div>
    //                 </div>';
    //             })

    //             // Role column with icon
    //             ->addColumn('role', function ($row) {
    //                 $roleName = strtolower($row->role->name ?? '');

    //                 switch ($roleName) {
    //                     case 'admin':
    //                         $icon = '<i class="menu-icon tf-icons mdi mdi-crown-outline text-primary icon-22px me-2"></i>';
    //                         break;
    //                     case 'pharmacy':
    //                         $icon = '<i class="menu-icon tf-icons mdi mdi-pill text-success icon-22px me-2"></i>';
    //                         break;
    //                     case 'laboratory':
    //                         $icon = '<i class="menu-icon tf-icons mdi mdi-microscope text-info icon-22px me-2"></i>';
    //                         break;
    //                     case 'delivery boy':
    //                         $icon = '<i class="menu-icon tf-icons mdi mdi-truck-delivery-outline text-warning icon-22px me-2"></i>';
    //                         break;
    //                     default:
    //                         $icon = '<i class="menu-icon tf-icons mdi mdi-account-outline text-muted icon-22px me-2"></i>';
    //                         break;
    //                 }


    //                 return '<div class="d-flex align-items-center">' . $icon . '<span>' . ucfirst($roleName) . '</span></div>';
    //             })

    //             ->rawColumns(['name', 'role']) // Important for rendering HTML
    //             ->make(true);
    //     }

    //     return view('dashboard.dasindex');
    // }


    // public function getSalesOverview()
    // {
    //     $pharmacyId = Pharmacies::where('user_id', Auth::user()->id)->first();
    //     dd($pharmacyId);
    //     // $pharmacyId = auth()->user()->id;
    //     $salesData = Order::where('pharmacy_id', $pharmacyId)
    //         ->selectRaw('SUM(items_price) as total_sales')
    //         ->selectRaw('COUNT(DISTINCT user_id) as total_customers')
    //         ->first();

    //     return view('getSalesOverview.', [
    //         'totalSales' => $salesData->total_sales ?? 0,
    //         'totalCustomers' => $salesData->total_customers ?? 0
    //     ]);
    // }
    // public function pharmacyDashboard()
    // {
    //     $pharmacyId = Auth::user()->id;
    //     dd($pharmacyId);

    //     $salesData = Order::where('pharmacy_id', $pharmacyId)
    //         ->selectRaw('SUM(items_price) as total_sales')
    //         ->selectRaw('COUNT(DISTINCT user_id) as total_customers')
    //         ->first();

    //     return view('pharmacy.dashboard', [
    //         'totalSales' => $salesData->total_sales ?? 0,
    //         'totalCustomers' => $salesData->total_customers ?? 0
    //     ]);
    // }
}
