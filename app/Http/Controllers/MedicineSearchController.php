<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineSearchController extends Controller
{
    public function index()
    {
        return view('Pharmacist.add-medicine');
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
    

    public function store(Request $request)
    {
        // Validate if needed
        $data = $request->input('medician');

        // Just show JSON for testing
        return response()->json($data);

        // Optionally save in DB (as JSON)
        /*
        DB::table('medicine_orders')->insert([
            'user_id' => auth()->id(),
            'medicines_json' => json_encode($data),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        */
    }
}
