@extends('layouts.app')

@section('content')
<div>
    <div class="card">
        <div class="card-header text-white bg-primary">
            <strong>Order #{{ $order->order_id }} - Medicines</strong>
        </div>
        <div class="card-body">
            <p><strong>Customer:</strong> {{ $order->customer->firstName }} {{ $order->customer->lastName }} ({{ $order->customer->mobile_no }})</p>

            @if (!empty($medicines))
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>MRP (₹)</th>
                            <th>Discount (%)</th>
                            <th>Final Price (₹)</th>
                            <th>Substitute</th>
                            <th>Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicines as $item)
                            @php
                                $mrp = (float) ($item['mrp'] ?? 0);
                                $discount = (float) ($item['discount'] ?? 0);
                                $finalPrice = $mrp - $discount;
                            @endphp
                            <tr>
                                <td style="max-width: 300px; word-break: break-word;">{{ $item['medicine_name'] ?? 'N/A' }}</td>
                                <td>{{ number_format($mrp, 2) }}</td>
                                <td>{{ $item['discount_percent'] ?? '0' }}%</td>
                                <td>{{ number_format($finalPrice, 2) }}</td>
                                <td>{{ ucfirst($item['is_substitute'] ?? 'no') }}</td>
                                <td>
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
