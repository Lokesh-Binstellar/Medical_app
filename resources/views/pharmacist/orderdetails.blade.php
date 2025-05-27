@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />

    <style>
        .order-card {
            border-left: 5px solid #0d6efd;
            background-color: #f8f9fa;
            transition: 0.3s ease;
        }

        .order-card:hover {
            background-color: #e9f1ff;
        }

        .order-header {
            background-color: #0d6efd;
            color: #fff;
            font-size: 1.1rem;
            padding: 0.6rem 1rem;
            border-top-left-radius: 0.3rem;
            border-top-right-radius: 0.3rem;
        }

        .order-info-label {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .order-info-value {
            font-weight: 600;
        }

        .view-meds-btn {
            
            color: #fff;
            border-radius: 0.25rem;
        }

        .view-meds-btn:hover {
            background-color: #033a62;
            color: #fff;
        }
    </style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg rounded-3">
        <div class="card-header  fw-bold">
            Order Details
        </div>

        <input type="hidden" name="current_pharmacy_id" id="current_pharmacy_id" value="{{ Auth::id() }}">

        <div class="card-body">
            <form method="POST" action="{{ route('medicines.store') }}" id="medicineCreateForm" class="d-flex flex-column gap-4">
                @csrf

                <h5><strong>Pharmacy Name:</strong> {{ $pharmacy->pharmacy_name ?? 'N/A' }}</h5>

                <div class="alert alert-warning" role="alert">
                    <strong>Note:</strong> Once an order is <strong>Accepted</strong>, its details will be visible here.<br>
                    Please ensure to update the order status to <strong>Completed</strong> after the customer has picked up the medicines, or to <strong>Cancelled</strong> if the pickup does not occur within 2 days.
                </div>

                @forelse ($orders as $order)
                    <div class="card mb-4 shadow-sm order-card p-3 border border-primary">
                        <div class="row">
                            <div class="col-md-2 border-end">
    <div class="order-info-label">Order ID</div>
    <div class="order-info-value">{{ $order->order_id }}</div>
</div>
                            <div class="col-md-3">
                                <div class="order-info-label">Customer</div>
                                <div class="order-info-value">
                                    @if($order->customer)
                                        {{ $order->customer->firstName }} {{ $order->customer->lastName }}<br>
                                        <small>{{ $order->customer->mobile_no }}</small>
                                    @else
                                        <em>Not found</em>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="order-info-label">Total Price</div>
                                <div class="order-info-value">₹{{ number_format($order->total_price, 2) }}</div>
                            </div>
                            <div class="col-md-2">
                                <div class="order-info-label">Status</div>
                                @if ($order->status == 0)
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($order->status == 1)
                                    <span class="badge bg-success">Completed</span>
                                @elseif ($order->status == 2)
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <div class="order-info-label">Delivery & Payment</div>
                                <div class="order-info-value">
                                    {{ ucfirst($order->delivery_options) }}<br>
                                    <small>{{ ucfirst($order->payment_option) }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            @if ($order->status == 0)
                                <form action="{{ route('pharmacy.updateOrderStatus', $order->id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                                        <option value="">-- Update Status --</option>
                                        <option value="1">Complete</option>
                                        <option value="2">Cancel</option>
                                    </select>
                                </form>
                            @else
                                <em class="text-muted">No action available</em>
                            @endif

                            <a href="{{ route('orders.medicines', $order->id) }}" class="btn btn-sm view-meds-btn bg-primary">
                                View Medicines
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">No orders found.</div>
                @endforelse
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Add custom JS if needed -->
@endsection
