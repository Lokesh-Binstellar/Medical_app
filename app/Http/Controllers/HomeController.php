<?php

namespace App\Http\Controllers;

use App\Models\Pharmacies;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $pharmacyCount = Pharmacies::count();
        // $labCount = La::count();
        // $userCount=User::count();
        // return view('home', compact('pharmacyCount', 'labCount','userCount'));
      
    }
}
