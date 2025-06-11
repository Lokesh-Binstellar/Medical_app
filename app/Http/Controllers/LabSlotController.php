<?php

namespace App\Http\Controllers;

use App\Models\LabSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LabSlotController extends Controller
{
    // Show all slots and pass to calendar
    public function index()
    {
        $slots = LabSlot::where('laboratory_id', auth()->id())->get();

        $events = $slots->map(function ($slot) {
            return [
                'id' => $slot->id,
                'title' => $slot->is_active ? Carbon::parse($slot->eventStartDate)->format('h:i A') . ' Slot Available' : 'Slot Disabled',
                'start' => Carbon::parse($slot->eventStartDate)->toDateTimeString(),
                'end' => Carbon::parse($slot->eventEndDate)->toDateTimeString(),
                'color' => $slot->is_active ? '#28a745' : '#dc3545',
                'className' => $slot->is_active ? 'slot-active' : 'slot-disabled',
            ];
        });

        return view('lab_slots.index', [
            'events' => $events,
            'slots' => $slots,
        ]);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'eventStartDate' => 'required|date',
    //         'eventEndDate' => 'required|date',
    //     ]);

    //     $startDate = Carbon::parse($request->eventStartDate)->startOfDay();
    //     $endDate = Carbon::parse($request->eventEndDate)->endOfDay();

    //     $fromTime = '09:30';
    //     $toTime = '17:30';

    //     if ($request->has('autoSlots')) {
    //         // Generate hourly slots for each date in range
    //         for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
    //             $from = Carbon::parse($date->format('Y-m-d') . ' ' . $fromTime);
    //             $to = Carbon::parse($date->format('Y-m-d') . ' ' . $toTime);

    //             while ($from < $to) {
    //                 LabSlot::create([
    //                     'laboratory_id' => auth()->id(),
    //                     'eventStartDate' => $from->copy(),
    //                     'eventEndDate' => $from->copy()->addHour(),
    //                     'is_active' => true,
    //                 ]);
    //                 $from->addHour();
    //             }
    //         }
    //     } else {
    //         // Save only single slot
    //         LabSlot::create([
    //             'laboratory_id' => auth()->id(),
    //             'eventStartDate' => Carbon::parse($request->eventStartDate),
    //             'eventEndDate' => Carbon::parse($request->eventEndDate),
    //             'is_active' => true,
    //         ]);
    //     }

    //     return redirect()->route('lab_slots.index')->with('success', 'Slots created successfully!');
    // }




public function store(Request $request)
{
    $request->validate([
        'eventStartDate' => 'required|date',
        'eventEndDate' => 'required|date',
    ]);

    $startDate = Carbon::parse($request->eventStartDate)->startOfDay();
    $endDate = Carbon::parse($request->eventEndDate)->endOfDay();

    $fromTime = '09:30';
    $toTime = '17:30';

    $createdSlots = [];

    if ($request->has('autoSlots')) {
        // Generate hourly slots for each date in range
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $from = Carbon::parse($date->format('Y-m-d') . ' ' . $fromTime);
            $to = Carbon::parse($date->format('Y-m-d') . ' ' . $toTime);

            while ($from < $to) {
                $slot = LabSlot::create([
                    'laboratory_id' => auth()->id(),
                    'eventStartDate' => $from->copy(),
                    'eventEndDate' => $from->copy()->addHour(),
                    'is_active' => true,
                ]);

                $slotDate = $from->format('Y-m-d');
                $createdSlots[$slotDate][] = [
                    'start' => $slot->eventStartDate->format('H:i'),
                    'end' => $slot->eventEndDate->format('H:i'),
                    'id' => $slot->id,
                    'is_active' => $slot->is_active,
                ];

                $from->addHour();
            }
        }
    } else {
        // Save only single slot
        $slot = LabSlot::create([
            'laboratory_id' => auth()->id(),
            'eventStartDate' => Carbon::parse($request->eventStartDate),
            'eventEndDate' => Carbon::parse($request->eventEndDate),
            'is_active' => true,
        ]);

        $slotDate = $slot->eventStartDate->format('Y-m-d');
        $createdSlots[$slotDate][] = [
            'start' => $slot->eventStartDate->format('H:i'),
            'end' => $slot->eventEndDate->format('H:i'),
            'id' => $slot->id,
            'is_active' => $slot->is_active,
        ];
    }

    // Return JSON response (instead of redirect)
    return response()->json([
        'success' => true,
        'message' => 'Slots created successfully!',
        'slots' => $createdSlots,
    ]);
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'eventStartDate' => 'required|date',
            'eventEndDate' => 'required|date|after_or_equal:eventStartDate',
        ]);

        $slot = LabSlot::findOrFail($id);
        $slot->update([
            'eventStartDate' => Carbon::parse($request->eventStartDate),
            'eventEndDate' => Carbon::parse($request->eventEndDate),
        ]);

        return redirect()->route('lab_slots.index')->with('success', 'Slot updated!');
    }

    public function fetch()
    {
        $slots = LabSlot::where('laboratory_id', auth()->id())->get();

        return $slots->map(function ($slot) {
            return [
                'title' => Carbon::parse($slot->eventStartDate)->format('h:i A') . ' Slot Available',
                'start' => $slot->eventStartDate,
                'end' => $slot->eventEndDate,
                'color' => '#28a745',
                'className' => 'slot-enabled',
            ];
        });
    }

    public function getSlotsByDate(Request $request)
    {
        $date = $request->date;
        $laboratoryId = auth()->id();

        $slots = LabSlot::whereDate('eventStartDate', $date)
            ->where('laboratory_id', $laboratoryId)
            ->get()
            ->map(function ($slot) {
                return [
                    'start' => Carbon::parse($slot->eventStartDate)->format('Y-m-d\TH:i'),
                    'end' => Carbon::parse($slot->eventEndDate)->format('Y-m-d\TH:i'),
                    'is_active' => $slot->is_active,
                ];
            });

        return response()->json($slots);
    }

    // Delete a slot
    public function destroy($id)
    {
        $slot = LabSlot::findOrFail($id);
        $slot->delete();

        return response()->json(['status' => 'deleted']);
    }
}
