<?php

namespace App\Http\Controllers;

use App\Models\Laboratories;
use App\Models\Pharmacies;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller /* implements HasMiddleware */
{
   /*  public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'role:pharmacies'),
        ];
    } */

    public function index()
    {
        $pharmacyCount = Pharmacies::count();
        $labCount = Laboratories::count();
        $userCount = User::count();

        return view('dashboard', compact('pharmacyCount', 'labCount', 'userCount'));
    }
}
