<?php

namespace App\Http\Controllers;

use App\Models\LabSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LabSlotController extends Controller
{
    public function index()
    {
        $slots = LabSlot::where('laboratory_id', auth()->id())
            // ->whereBetween('slot_date', [now(), now()->addDays(10)])
            ->get();

        return view('lab_slots.index', compact('slots'));
    }

    public function store(Request $request)
    {
        dd("okk");
        // $request->validate([
        //     'slot_date' => 'required|date',
        //     'start_time' => 'required',
        //     'end_time' => 'required',
        // ]);

        LabSlot::create([
            'laboratory_id' => auth()->id(),
            'eventStartDate' => $request->eventStartDate,
            'eventEndDate' => $request->eventEndDate,
            'is_active' => true,
        ]);

        return response()->json(['message' => 'Slot created successfully']);
    }

    // public function toggle(Request $request)
    // {
    //     $slot = LabSlot::findOrFail($request->id);
    //     $slot->is_active = !$slot->is_active;
    //     $slot->save();

    //     return response()->json(['status' => 'toggled']);
    // }
}