<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Events\Pharmacymedicine;
use App\Models\Carts;
use App\Models\Customers;
use App\Models\Medicine;
use App\Models\Otcmedicine;
use App\Models\Pharmacies;
use App\Models\Phrmacymedicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicineSearchController extends Controller
{
    public function index()
    {

        //  echo 'fdfg';die;
        // dd(Pharmacies::all());
        $pharmacy = Pharmacies::where('user_id', Auth::user()->id)->first();

        $medicines = Phrmacymedicine::where('phrmacy_id', $pharmacy->id)->get();


        return view('Pharmacist.add-medicine', compact('medicines'));
    }




    public function store(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        // Validate basic structure (you can add more rules as needed)
        // dd($data['customer'][0]['customer_id']);

        $pharmacy = Pharmacies::where('user_id', $user->id)->first();


        // Store in database
        // 'customer_id' => $customerId,
        $medicine = new Phrmacymedicine(); // your model
        $medicine->medicine = json_encode($data['medicine']);
        $medicine->total_amount = $data['total_amount'];
        $medicine->mrp_amount = $data['mrp_amount'];
        $medicine->commission_amount = $data['commission_amount'];
        $medicine->phrmacy_id = $pharmacy->id;
        $medicine->customer_id = $data['customer'][0]['customer_id'];
        $medicine->save();

        return redirect()->back()->with('success', 'Medicine added successfully!');
    }
    
    public function search(Request $request)
    {
        $term = $request->get('q');

        $medicines = DB::table('medicines')
            ->select('id', 'product_name', 'salt_composition')
            ->when($term, function ($query, $term) {
                return $query->where('product_name', 'like', "%$term%")
                    ->orWhere('salt_composition', 'like', "%$term%");
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->product_name . ' + ' . $item->salt_composition,
                ];
            });

        $otc = DB::table('otcmedicines')
            ->select('id', 'name')
            ->when($term, function ($query, $term) {
                return $query->where('name', 'like', "%$term%");
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'otc-' . $item->id,
                    'text' => $item->name,
                ];
            });
        // return response()->json($medicines);
        return response()->json($medicines->merge($otc));
    }

    public function customerSelect(Request $request)
    {
        $search = $request->input('query');

        // Step 1: Get matching customers from request_quotes + customers
        $customers = DB::table('request_quotes')
            ->join('customers', 'request_quotes.customer_id', '=', 'customers.id')
            ->where(function ($query) use ($search) {
                $query->where('customers.firstName', 'like', "%{$search}%")
                    ->orWhere('customers.mobile_no', 'like', "%{$search}%");
            })
            ->select('customers.id', DB::raw("CONCAT(customers.firstName, ' (', customers.mobile_no, ')') as text"))
            ->distinct()
            ->limit(10)
            ->get();

        // Step 2: (Optional) Get cart for the first matched customer
        $firstCustomerId = $customers->first()?->id;
        $product = null;

        if ($firstCustomerId) {
            $cart = Carts::where('customer_id', $firstCustomerId)->first();
            if ($cart && $cart->products_details) {
                $product = json_decode($cart->products_details, true);
            }
        }

        return response()->json([
            'results' => $customers,
            'product' => $product
        ]);
    }

    public function fetchCartByCustomer(Request $request)
    {
        $customerId = $request->input('customer_id');

        $cart = Carts::where('customer_id', $customerId)->first();

        if (!$cart || !$cart->products_details) {
            return response()->json(['status' => 'error', 'message' => 'Cart not found']);
        }

        $products = json_decode($cart->products_details, true);
        $result = [];

        foreach ($products as $item) {
            $productId = $item['product_id'];
            $isSubstitute = $item['is_substitute'] ?? 0;
            $packagingDetail = $item['packaging_detail'] ?? '';
            $quantity = $item['quantity'] ?? 1;

            $medicine = Medicine::where('product_id', $productId)->first();
            $medName = $medicine->product_name . ' + ' . $medicine->salt_composition;
            $type = 'medicine';

            // If not found in medicines, try otcmedicines
            if (!$medicine) {
                $medicine = Otcmedicine::where('otc_id', $productId)->first();
                $medName = $medicine->name;
                $type = 'otc';
            }

            if ($medicine) {
                $result[] = [
                    'product_id' => $productId,
                    'type' => $type,
                    'name' => $medName ?? 'N/A',
                    'packaging_detail' => $packagingDetail,
                    'quantity' => $quantity,
                    'is_substitute' => $isSubstitute,
                ];
            }
        }
        // dd($result);

        return response()->json(['status' => 'success', 'data' => $result]);
    }

   public function customerGetMedicine()
{
    try {
        $getMedicine = Phrmacymedicine::all();

        if ($getMedicine->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No medicine data found.',
                'data' => []
            ], 404);
        }
        $formatted = $getMedicine->map(function ($not) {
            try {
                // Only decode if it's a JSON string
                if (is_string($not->medicine)) {
                    return json_decode($not->medicine, true);
                }

                return $not->medicine;
            } catch (\Exception $e) {
                return ['error' => 'Invalid JSON format'];
            }
        });
        return response()->json([
            'status' => true,
            'pharmacy_id' => $getMedicine->first()->phrmacy_id ?? null,
            'medicine' => $formatted
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
}




}
