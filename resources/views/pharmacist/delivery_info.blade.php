@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="text-danger mb-4">
        <i class="mdi mdi-truck-fast me-1"></i> Delivery Information
    </h4>

    @if ($assignedPerson)
        <div class="mb-4">
            <strong><i class="mdi mdi-account-badge-outline me-1 text-primary"></i>Assigned To:</strong>
            <span class="text-dark">{{ $assignedPerson->name }}
                @if ($assignedPerson->deliveryProfile && $assignedPerson->deliveryProfile->phone)
                    ({{ $assignedPerson->deliveryProfile->phone }})
                @endif
            </span><br>
            <small class="text-dark d-block mb-1">Order ID: #{{ $order->order_id }}</small>
        </div>

        <div class="mb-4">
            <strong><i class="mdi mdi-flask me-1 text-success"></i>Pickup Location (Pharmacy):</strong><br>
            <div class="text-dark mb-1" style="white-space: pre-line;">{{ wordwrap($pickupAddress, 40, "\n") }}</div>
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($pickupAddress) }}"
               target="_blank" class="badge bg-success text-white text-decoration-none">
                <i class="mdi mdi-directions"></i> Direction
            </a>
        </div>

        <div>
            <strong><i class="mdi mdi-home-map-marker me-1 text-danger"></i>Delivery Location (Customer):</strong><br>
            <div class="text-dark mb-1" style="white-space: pre-line;">{{ wordwrap($deliveryAddress, 40, "\n") }}</div>
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($deliveryAddress) }}"
               target="_blank" class="badge bg-primary text-white text-decoration-none">
                <i class="mdi mdi-directions"></i> Direction
            </a>
        </div>
    @else
        <div class="alert alert-warning">
            Delivery person not assigned or not found.
        </div>
    @endif
</div>
@endsection
