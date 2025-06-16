@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Bookings for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h4>

    @if ($slots->isEmpty())
        <p>No slots found for this date.</p>
    @else
        @foreach ($slots as $slot)
            <div class="card mb-3">
                <div class="card-header">
                    {{ \Carbon\Carbon::parse($slot->eventStartDate)->format('h:i A') }} - 
                    {{ \Carbon\Carbon::parse($slot->eventEndDate)->format('h:i A') }}
                    <span class="badge bg-primary float-end">{{ $slot->bookings->count() }} Booked</span>
                </div>
                <div class="card-body">
                    @if ($slot->bookings->count())
                        <ul>
                            @foreach ($slot->bookings as $booking)
                                <li><strong>{{ $booking->customer->firstName ?? 'Unknown User' }}</strong> 
                                    {{-- <span class="text-muted">({{ $booking->status }})</span> --}}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No bookings for this slot.</p>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>
</div>
@endsection
