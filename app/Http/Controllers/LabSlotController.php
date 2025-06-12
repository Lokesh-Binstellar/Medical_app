<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\LabSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;

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

    //update slots
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

        return redirect()->route('calendar.index')->with('success', 'Slot updated!');
    }

    //fetch slots fro the calnder grid
 public function fetch()
{
    $slots = LabSlot::where('laboratory_id', auth()->id())
        ->where('is_active', true)
        ->get();

    return $slots->map(function ($slot) {
        $bookedCount = $slot->bookings()->count(); 
        return [
            'title' => Carbon::parse($slot->eventStartDate)->format('h:i A') . 'Slot (' . $bookedCount . ')',
            'start' => $slot->eventStartDate,
            'end' => $slot->eventEndDate,
            'color' => '#28a745',
            'className' => 'slot-enabled',
            'slot_id' => $slot->id
        ];
    });
}


//date wise slot data
public function viewBookingsByDate(Request $request)
{
    $date = $request->date;
    if (!$date) {
        return redirect()->back()->with('error', 'Date is required');
    }
    
    $labId = auth()->id(); // Or pass lab_id in request if admin use
    
    $slots = LabSlot::with(['bookings.customer'])
    ->where('laboratory_id', $labId)
    ->whereDate('eventStartDate', $date)
    ->orderBy('eventStartDate')
    ->get();
    
    // dd($slots);
    return view('lab_slots.show', compact('slots', 'date'));
}


    //get slots By date
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

    //disable slots on calendar
    public function disable(Request $request)
    {
        $request->validate([
            'eventStartDate' => 'required|date',
            'eventEndDate' => 'required|date',
        ]);

        $slot = LabSlot::where('laboratory_id', auth()->id())
            ->where('eventStartDate', $request->eventStartDate)
            ->where('eventEndDate', $request->eventEndDate)
            ->first();

        if ($slot) {
            $slot->update(['is_active' => false]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Slot not found'], 404);
    }
    //api for get date wise slots

    public function getSlotsByLabAndDate(Request $request)
{
    try {
        // Validate inputs
        // $request->validate([
        //     'laboratory_id' => 'required|integer|exists:laboratories,id',
        //     'date' => 'required|date_format:Y-m-d',
        // ]);

        // Fetch slots
        $slots = LabSlot::where('laboratory_id', $request->laboratory_id)
            ->whereDate('eventStartDate', $request->date)
            ->get()
            ->map(function ($slot) {
                return [
                    // 'start' => $slot->eventStartDate,
                    // 'end' => $slot->eventEndDate,
                    'is_active' => $slot->is_active,
                    'title' => Carbon::parse($slot->eventStartDate)->format('h:i A') . ' - ' .
                               Carbon::parse($slot->eventEndDate)->format('h:i A'),
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Slots fetched successfully.',
            'data' => $slots,
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        Log::error('Error fetching lab slots: ' . $e->getMessage());

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
        ], 500);
    }
}





public function book(Request $request)
{

    $customerId = $request->get('user_id');
    // echo     $customerId ;die;
    $request->validate([
        'lab_slot_id' => 'required|exists:lab_slots,id',
    ]);

    $slot = LabSlot::find($request->lab_slot_id);

    // Optional: Check if already booked
    $alreadyBooked = Booking::where('lab_slot_id', $slot->id)
                            ->where('customer_id', $customerId)
                            ->exists();

    if ($alreadyBooked) {
        return response()->json(['message' => 'Slot already booked by you'], 409);
    }

    // Optional: You can add capacity checks here

    $booking = Booking::create([
        'customer_id' => $customerId,
        'lab_slot_id' => $slot->id,
        'status' => 'confirmed',
    ]);

    return response()->json(['message' => 'Slot booked successfully', 'data' => $booking], 201);
}

public function fetchBookingSlots()
{
    $slots = LabSlot::withCount('bookings')
                    ->where('laboratory_id', auth()->id())
                    ->where('is_active', true)
                    ->get();

    return $slots->map(function ($slot) {
        return [
            'title' => Carbon::parse($slot->eventStartDate)->format('h:i A') . ' - ' . $slot->bookings_count . ' Booked',
            'start' => $slot->eventStartDate,
            'end' => $slot->eventEndDate,
            'color' => '#28a745',
            'extendedProps' => [
                'slot_id' => $slot->id
            ]
        ];
    });
}



public function getSlotCustomers(Request $request)
{
    $request->validate([
        'slot_id' => 'required|exists:lab_slots,id'
    ]);

    $customers = Booking::where('lab_slot_id', $request->slot_id)
        ->with('customer:id,firstName,email') // Make sure Customers table has these fields
        ->get()
        ->pluck('customer') // This will extract related customers
        ->filter(); // Remove nulls

    return response()->json(['customers' => $customers->values()]);
}











    // Delete a slot
    public function destroy($id)
    {
        $slot = LabSlot::findOrFail($id);
        $slot->delete();

        return response()->json(['status' => 'deleted']);
    }
}
