@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
@endsection

@section('content')
    <div class="card">
        <h5 class="card-header fw-bold">Order Details</h5><br>
        @if (Auth::user()->role->name === 'pharmacy')
        <div class="alert alert-warning" role="alert">
            <strong>Note:</strong> Once an order is <strong>Accepted</strong>, its details will be visible here.<br>
            Please ensure to update the order status to <strong>Completed</strong> after the customer has picked up the
            medicines, or to <strong>Cancelled</strong> if the pickup does not occur within 24 hours.
        </div>
        @elseif (Auth::user()->role->name === 'admin')
        <div class="alert alert-warning" role="alert">
            <strong>Note:</strong> Once an order is <strong>Accepted</strong>, its details will be visible here.<br>
            Please ensure to update the order status to <strong>Completed</strong> after the delivery boy has delivered the medicines, or to <strong>Cancelled</strong>.
        </div>
        @endif
        <div class="card-body">
            <div class="table-responsive text-nowrap" style="overflow-x: auto;">
                <table class="table table-bordered" style="border: 1px solid black;">
                    <thead>
                        <tr style="background-color: rgb(245, 245, 247);">
                            <th class="fw-bold">Order ID</th>
                            <th class="fw-bold">Customer Details</th>
                            <th class="fw-bold">Total Price</th>
                            <th class="fw-bold">Order Status</th>
                            <th class="fw-bold">Delivery Method</th>
                            @if (Auth::user()->role->name === 'admin')
                                <th class="fw-bold">Assign Delivery Person</th>
                            @endif

                            <th class="fw-bold">Payment Method</th>
                            <th class="fw-bold">Update Status</th>
                            <th class="fw-bold">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->order_id }}</td>
                                <td>
                                    @if($order->customer)
                                        {{ $order->customer->firstName }} {{ $order->customer->lastName }}<br>
                                        <small>{{ $order->customer->mobile_no }}</small>
                                    @else
                                        <em>Not found</em>
                                    @endif
                                </td>
                                <td>
                                    ₹{{ number_format($order->total_price, 2) }}
                                </td>
                                <td>@if ($order->status == 0)
                                    <span class="badge bg-warning">Request Accepted</span>
                                @elseif ($order->status == 1)
                                        <span class="badge bg-success">Completed</span>
                                    @elseif ($order->status == 2)
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    {{ ucfirst($order->delivery_options) }}
                                </td>
                                @if (Auth::user()->role->name === 'admin')
                                        @if($order->delivery_options === 'home_delivery')
                                            <td>
                                                <form action="{{ route('orders.assignDeliveryPerson', $order->id) }}" method="POST">
                                                    @csrf
                                                    <div class="d-flex align-items-center">
                                                        <select name="delivery_person_id" class="form-select form-select-sm me-2" required>
                                                            <option value="">-- Select Delivery Person --</option>
                                                            @foreach($deliveryPersons as $person)
                                                                <option value="{{ $person->id }}" {{ $order->delivery_person_id == $person->id ? 'selected' : '' }}>
                                                                    {{ $person->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-primary">Assign</button>
                                                    </div>
                                                    
                                                </form>
                                            </td>
                                        @else
                                            <td>{{ ucfirst($order->delivery_options) }}</td>
                                        @endif
                                    @endif
                                
                                <td>
                                    {{ ucfirst($order->payment_option) }}
                                </td>
                                <td>

                                    @if ($order->status == 0)
                                        <form action="{{ route('pharmacy.updateOrderStatus', $order->id) }}" method="POST"
                                            class="d-flex align-items-center">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm me-2"
                                                onchange="this.form.submit()">
                                                <option value="">-- Update Status --</option>
                                                <option value="1">Complete</option>
                                                <option value="2">Cancel</option>
                                            </select>
                                        </form>
                                    @else
                                        @if ($order->status == 1)
                                            <span class="badge bg-success">Delivered to Customer</span>
                                        @elseif ($order->status == 2)
                                            <span class="badge bg-danger">Order Cancelled</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('orders.medicines', $order->id) }}"
                                    class="btn btn-sm view-meds-btn bg-primary text-white"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="View Medicines Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                           @if($order->delivery_person_id)
                            @php
                                $assignedPerson = $deliveryPersons->firstWhere('id', $order->delivery_person_id);
                            @endphp
                            @if($assignedPerson)
                                <tr>
                                    <td colspan="9">
                                        <div class="text-danger small bg-secondary-subtle p-2 rounded border">
                                            <strong>
                                                Delivery Assigned to:
                                                <span class="text-primary">
                                                    {{ $assignedPerson->name }}
                                                    @if($assignedPerson->deliveryProfile && $assignedPerson->deliveryProfile->phone)
                                                        ({{ $assignedPerson->deliveryProfile->phone }})
                                                    @endif
                                                </span>
                                                has been assigned to Order #{{ $order->order_id }}
                                            </strong>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endif

                        @empty
                            <div class="alert alert-info">No orders found.</div>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Add custom JS if needed -->
@endsection