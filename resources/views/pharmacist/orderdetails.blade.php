@extends('layouts.app')

@section('styles')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}
    <style>
        .btn.btn-primary:hover .mdi {
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <h5 class="card-header fw-bold">Order Details</h5><br>
        @if (Auth::user()->role->name === 'pharmacy')
        <div class="alert alert-warning text-black" role="alert">
            <strong>Note:</strong> Once an order is <strong>Accepted</strong>, its details will be visible here.<br>
            Please ensure to update the order status to <strong>Completed</strong> after the customer has picked up the
            medicines, or to <strong>Cancelled</strong> if the pickup does not occur within 24 hours.
        </div>
        @elseif (Auth::user()->role->name === 'admin')
        <div class="alert alert-warning text-black" role="alert">
            <strong>Note:</strong> Once an order is <strong>Accepted</strong>, its details will be visible here.<br>
            Please ensure to update the order status to <strong>Completed</strong> after the delivery boy has delivered the medicines, or to <strong>Cancelled</strong>.
        </div>
        @elseif (Auth::user()->role->name === 'delivery_person')
        <div class="alert alert-warning text-black" role="alert">
            <strong>Note:</strong> Once an order is <strong>assigned</strong>, its details will be visible here.<br>
            Please ensure to update the order status to <strong>Completed</strong> after the delivery.
        </div>
        @endif
        <div class="card-body">
            <div class="table-responsive text-nowrap" style="overflow-x: auto;">
                <table class="table table-bordered text-align-center " style="border: 1px solid black;">
                    <thead>
                        <tr style="background-color: rgb(245, 245, 247);" class="align-middle">
                            <th class="fw-bold fs-6">Order ID</th>
                            <th class="fw-bold fs-6">Customer Details</th>
                            <th class="fw-bold fs-6">Total Price</th>
                            <th class="fw-bold fs-6">Order Status</th>
                            <th class="fw-bold fs-6">Delivery Method</th>
                            @if (Auth::user()->role->name === 'admin')
                                <th class="fw-bold fs-6 ">Assign Delivery Person</th>
                            @endif

                            <th class="fw-bold fs-6">Payment Method</th>
                            <th class="fw-bold fs-6">Update Status</th>
                            <th class="fw-bold fs-6">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="fw-bold text-black">{{ $order->order_id }}</td>
                                <td class="fw-bold text-black">
                                    @if($order->customer)
                                        {{ $order->customer->firstName }} {{ $order->customer->lastName }}<br>
                                        <small>{{ $order->customer->mobile_no }}</small>
                                    @else
                                        <em>Not found</em>
                                    @endif
                                </td>
                                <td class="fw-bold text-black">
                                    ₹{{ number_format($order->total_price, 2) }}
                                </td>
                                <td class="text-center">@if ($order->status == 0)
                                    <span class="badge bg-warning">Request Accepted</span>
                                @elseif ($order->status == 1)
                                        <span class="badge bg-success">Completed</span>
                                    @elseif ($order->status == 2)
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-black">
                                    {{ ucfirst(str_replace('_', ' ', $order->delivery_options)) }}
                                </td>
                                @if (Auth::user()->role->name === 'admin')
                                @if($order->delivery_options === 'home_delivery' && $order->status == 0)
                                    <td >
                                        <form action="{{ route('orders.assignDeliveryPerson', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="d-flex align-items-center">
                                                <select name="delivery_person_id" class="form-select form-select-sm me-2 fw-bold text-black border border-dark"required>
                                                    <option  value=""> - Select Delivery Person - </option>
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
                                    <td class="fw-bold text-black text-center">
                                        @if($order->status == 1)
                                            <span class="badge bg-success">Order is Delivered</span>
                                        @elseif($order->status == 2)
                                            <span class="badge bg-danger">Order is Cancelled</span>
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $order->delivery_options)) }}
                                        @endif
                                    </td>
                                @endif
                            @endif

                                
                                <td class="fw-bold text-black">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_option)) }}
                                </td>
                                <td class="text-center">
                                @if ($order->status == 0)
                                    <form action="{{ route('pharmacy.updateOrderStatus', $order->id) }}" method="POST"
                                        class="d-inline-block status-form">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm me-2 fw-bold text-black border border-dark status-select">
                                            <option value="">-- Update Status --</option>

                                            @if (Auth::user()->role->name === 'delivery_person')
                                                <option value="1">Complete</option>
                                            @else
                                                <option value="1">Complete</option>
                                                <option value="2">Cancel</option>
                                            @endif

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
                                    class="btn btn-primary waves-effect waves-light"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="View Medicines Details">
                                        <i class="tf-icons mdi mdi-eye"></i>
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
     <!-- SweetAlert Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function () {
            const form = this.closest('form');
            const selectedValue = this.value;

            if (selectedValue === "1") {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to mark this order as completed.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, mark as complete!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-success me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        select.value = '';
                    }
                });
            } else if (selectedValue === "2") {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to cancel this order.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, cancel it!',
                    cancelButtonText: 'No',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        select.value = '';
                    }
                });
            }
        });
    });
});

</script>
@endsection