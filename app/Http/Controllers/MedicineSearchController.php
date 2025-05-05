<?php
namespace App\Http\Controllers;

use App\Models\Pharmacies;
use App\Models\Phrmacymedicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicineSearchController extends Controller
{
    public function index()
    {
        // dd(Pharmacies::all());
        $pharmacy = Pharmacies::where('user_id',Auth::user()->id)->first();
      
        $medicines = Phrmacymedicine::where('phrmacy_id',$pharmacy->id)->get();
        
      
        return view('Pharmacist.add-medicine',compact('medicines'));

        
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
        $data = $request->all();
        $user = Auth::user();
        // Validate basic structure (you can add more rules as needed)
        // dd($data);
        
        $pharmacy = Pharmacies::where('user_id',$user->id)->first();
        
        
        // Store in database
        $medicine = new Phrmacymedicine(); // your model
        $medicine->medicine = json_encode($data['medicine']);
        $medicine->total_amount = $data['total_amount'];
        $medicine->mrp_amount = $data['mrp_amount'];
        $medicine->commission_amount = $data['commission_amount'];
        $medicine->phrmacy_id = $pharmacy->id;
        $medicine->save();
    
        return redirect()->back()->with('success', 'Medicine added successfully!');
    }
    
}
