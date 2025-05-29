@extends('layouts.app')

@section('content')
<div>
    <div class="card">
        <div class="card-header text-white bg-primary">
            <strong>Order #{{ $order->order_id }} - Medicines</strong>
        </div>
        <div class="card-body">
            <p class="fw-bold text-black">Customer: {{ $order->customer->firstName }} {{ $order->customer->lastName }} ({{ $order->customer->mobile_no }})</p>

            @if (!empty($medicines))
                <table class="table table-bordered border border-dark">
                    <thead class="">
                        <tr >
                            <th class="fw-bold text-black fs-5">Medicine</th>
                            <th class="fw-bold text-black fs-5">MRP (₹)</th>
                            <th class="fw-bold text-black fs-5">Discount (%)</th>
                            <th class="fw-bold text-black fs-5">Final Price (₹)</th>
                            <th class="fw-bold text-black fs-5">Substitute</th>
                            <th class="fw-bold text-black fs-5">Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicines as $item)
                            @php
                                $mrp = (float) ($item['mrp'] ?? 0);
                                $discount = (float) ($item['discount'] ?? 0);
                                $finalPrice = $mrp - $discount;
                            @endphp
                            <tr >
                                <td style="max-width: 300px; word-break: break-word;" class="fw-bold text-black">{{ $item['medicine_name'] ?? 'N/A' }}</td>
                                <td class="fw-bold text-black">{{ number_format($mrp, 2) }}</td>
                                <td class="fw-bold text-black">{{ $item['discount_percent'] ?? '0' }}%</td>
                                <td class="fw-bold text-black">{{ number_format($finalPrice, 2) }}</td>
                                <td class="fw-bold text-black">{{ ucfirst($item['is_substitute'] ?? 'no') }}</td>
                                <td class="fw-bold text-black">
                                    @if (($item['available'] ?? 'no') == 'yes')
                                        <span class="badge bg-success">Available</span>
                                    @else
                                        <span class="badge bg-danger">Unavailable</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No medicine details found for this order.</p>
            @endif

            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
</div>
@endsection
