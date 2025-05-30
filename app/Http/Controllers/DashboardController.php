<?php

namespace App\Http\Controllers;

use App\Models\AppRating;
use App\Models\Customers;
use App\Models\DeliveryPerson;
use App\Models\Laboratories;
use App\Models\Pharmacies;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role_id', 1)->count();
        $totalCustomers = Customers::count();
        $totalPharmacies = Pharmacies::count();
        $totalLabs = Laboratories::count();
        $totalDelivery = DeliveryPerson::count();

        $averageRating = AppRating::avg('rating');
        $averageRating = round($averageRating, 2);




        return view('dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalCustomers',
            'totalPharmacies',
            'totalLabs',
            'totalDelivery',
            'averageRating'
        ));
    }

    public function dasindex(Request $request)
    {
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

        return view('dashboard.dasindex');
    }
}
