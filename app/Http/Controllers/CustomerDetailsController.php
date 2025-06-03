<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

class CustomerDetailsController extends Controller
{
public function index(Request $request)
{
    $customer=Customers::all();
    return view('customer.index',compact('customer'));
}
public function show($id)
{
    $customer = Customers::with(['addresses', 'orders'])->findOrFail($id);
    return view('customer.show', compact('customer'));
}

}
