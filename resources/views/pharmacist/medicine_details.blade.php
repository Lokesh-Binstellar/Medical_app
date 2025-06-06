@extends('layouts.app')

@section('content')
    <div>
        <div class="card">
            <div class="card-header text-white bg-primary">
                <strong>Order #{{ $order->order_id }} - Medicines</strong>
            </div>
            <div class="card-body">
                <div class="card mb-4" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                    <div class="card-header fw-bold text-black bg-light">
                        Customer: {{ $order->customer->firstName }} {{ $order->customer->lastName }}
                        ({{ $order->customer->mobile_no }})
                    </div>

                    <div class="card-body p-3">
                        @if (!empty($medicines))
                            <div style="overflow-x: auto; width: 100%;">
                                <table class="table table-bordered border-dark mb-0" style="min-width: 900px;">
                                    <thead>
                                        <tr>
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
                                            <tr>
                                                <td style="max-width: 300px; word-break: break-word;" class="fw-bold text-black">
                                                    {{ $item['medicine_name'] ?? 'N/A' }}
                                                </td>
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
                            </div>
                        @else
                            <p class="text-muted">No medicine details found for this order.</p>
                        @endif
                    </div>
                </div>

                @if ($patient)
                    <div class="card mb-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                        <div class="card-header fw-bold text-black bg-light">
                            Patient Details
                        </div>
                        <div class="card-body p-3">
                            <div style="overflow-x: auto; width: 100%;">
                                <table class="table table-bordered border-dark mb-0" style="min-width: 500px;">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold text-black fs-6">Name</th>
                                            <th class="fw-bold text-black fs-6">Birth Date</th>
                                            <th class="fw-bold text-black fs-6">Gender</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold text-black">{{ preg_replace('/\s+/', ' ', trim($patient->name)) }}</td>
                                            <td class="fw-bold text-black">
                                                {{ \Carbon\Carbon::createFromFormat('d/m/Y', $patient->birth_date)->format('d-m-Y') }}
                                            </td>
                                            <td class="fw-bold text-black">{{ ucfirst($patient->gender) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>
            </div>
        </div>
    </div>
@endsection
