@extends('layouts.app')

@section('content')
    @if ($order->delivery_person_id)
        @php
            $assignedPerson = $deliveryPersons->firstWhere('user_id', $order->delivery_person_id);
            $pickupAddress = $order->selected_pharmacy_address ?? 'N/A';
            $deliveryAddress = $order->delivery_address ?? 'N/A';
        @endphp

        @if ($assignedPerson)
            
                <div class="card shadow-lg ">
                    <h5 class="card-header bg-primary text-white fw-bold">
                        <i class="mdi mdi-truck-fast me-2"></i> Delivery Information
                    </h5>

                    <div class="card-body bg-light">
                        <div class="border rounded p-4 bg-white border-dark">
                            <h6 class="text-danger mb-4">
                                <i class="mdi mdi-truck-fast me-1"></i>
                                Delivery Details
                            </h6>

                            <div class="mb-4">
                                <strong><i class="mdi mdi-account-badge-outline me-2 text-primary"></i>Assigned To:</strong>
                                <span class="text-dark fw-semibold">
                                    {{ $assignedPerson->name }}
                                    @if ($assignedPerson->deliveryProfile && $assignedPerson->deliveryProfile->phone)
                                        ({{ $assignedPerson->deliveryProfile->phone }})
                                    @endif
                                </span>
                                <div class="text-dark small mt-1">Order ID: <strong>#{{ $order->order_id }}</strong></div>
                            </div>

                            <div class="mb-4">
                                <strong><i class="mdi mdi-flask me-2 text-success"></i>Pickup Location (Pharmacy):</strong>
                                <div class="text-dark mt-1">{{ $pickupAddress }}</div>
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($pickupAddress) }}"
                                    target="_blank" class="badge bg-success mt-2 text-white text-decoration-none">
                                    <i class="mdi mdi-directions"></i> Get Directions
                                </a>
                            </div>

                            <div>
                                <strong><i class="mdi mdi-home-map-marker me-2 text-danger"></i>Delivery Location (Customer):</strong>
                                <div class="text-dark mt-1">{{ $deliveryAddress }}</div>
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($deliveryAddress) }}"
                                    target="_blank" class="badge bg-primary mt-2 text-white text-decoration-none">
                                    <i class="mdi mdi-directions"></i> Get Directions
                                </a>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            
        @endif
    @endif
@endsection
