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
                        <div class="table-responsive text-wrap" style="overflow-x:auto;">
                            @if (!empty($medicines))
                                <div style="overflow-x: auto; width: 100%;">
                                    <table class="table table-bordered border-dark mb-0" style="min-width: 1000px;">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold text-black fs-5">Medicine</th>
                                                <th class="fw-bold text-black fs-5">MRP (₹)</th>
                                                <th class="fw-bold text-black fs-5">Discount (%)</th>
                                                <th class="fw-bold text-black fs-5">Final Price (₹)</th>
                                                <th class="fw-bold text-black fs-5">Substitute</th>
                                                <th class="fw-bold text-black fs-5">Availability</th>
                                                <th class="fw-bold text-black fs-5">Return Accepted</th> <!-- ✅ New -->
                                                <th class="fw-bold text-black fs-5">Return Amount (₹)</th> <!-- ✅ New -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $totalDiscount = 0; @endphp
                                            @foreach ($medicines as $key => $item)
                                                @php
                                                    $mrp = (float) ($item['mrp'] ?? 0);
                                                    $discount = (float) ($item['discount'] ?? 0);
                                                    $discountPercent = (float) ($item['discount_percent'] ?? 0);
                                                    $totalDiscount += $discount;
                                                @endphp
                                                <tr>
                                                    <td class="fw-bold text-black">{{ $item['medicine_name'] ?? 'N/A' }}
                                                    </td>
                                                    <td class="fw-bold text-black">{{ number_format($mrp, 2) }}</td>
                                                    <td class="fw-bold text-black">{{ $discountPercent }}%</td>
                                                    <td class="fw-bold text-black">{{ number_format($discount, 2) }}</td>
                                                    <td class="fw-bold text-black">
                                                        {{ ucfirst($item['is_substitute'] ?? 'no') }}</td>
                                                    <td class="fw-bold text-black">
                                                        @if (($item['available'] ?? 'no') == 'yes')
                                                            <span class="badge bg-success">Available</span>
                                                        @else
                                                            <span class="badge bg-danger">Unavailable</span>
                                                        @endif
                                                    </td>
                                                    <!-- ✅ For each item: -->
                                                    <td>
                                                        @if (!empty($item['return_status']))
                                                            @if ($item['return_status'] === 'accepted')
                                                                <span class="badge bg-success">Accepted</span>
                                                            @elseif ($item['return_status'] === 'rejected')
                                                                <span class="badge bg-danger">Rejected</span>
                                                            @endif
                                                        @else
                                                            <select class="return-accepted form-select"
                                                                data-id="{{ $item['medicine_id'] }}"
                                                                data-discount="{{ $item['discount'] ?? 0 }}">
                                                                <option value="">-- Select --</option>
                                                                <option value="accepted">Accepted</option>
                                                                <option value="rejected">Rejected</option>
                                                            </select>
                                                        @endif
                                                    </td>


                                                    <!-- ✅ Return Amount cell -->
                                                    <td>
                                                        @if (!empty($item['return_status']))
                                                            <span class="return-amount">
                                                                {{ number_format($item['return_amount'] ?? 0, 2) }}
                                                            </span>
                                                        @else
                                                            <span class="return-amount">0</span>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- ✅ Total Return Amount -->
                                <div class="mt-3">
                                    <strong>Total Return Amount: ₹<span id="totalReturnAmount">
                                            {{ number_format($order->total_return_amount ?? 0, 2) }}
                                        </span></strong>
                                </div>
                                <button id="saveReturnAccepted" class="btn btn-primary mt-3"
                                    @if (!empty($order->return_accepted_items)) disabled @endif>
                                    Save Return Accepted
                                </button>
                            @else
                                <p class="text-muted">No medicine details found for this order.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ route('returnorderdetails') }}" class="btn btn-secondary mt-3">Back</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('return-accepted')) {
                let total = 0;
                document.querySelectorAll('.return-accepted').forEach(function(select) {
                    const discount = parseFloat(select.dataset.discount) || 0;
                    const amountSpan = select.closest('tr').querySelector('.return-amount');

                    if (select.value === 'accepted') {
                        amountSpan.textContent = discount.toFixed(2);
                        total += discount;
                    } else {
                        amountSpan.textContent = '0';
                    }
                });
                document.getElementById('totalReturnAmount').textContent = total.toFixed(2);
            }
        });
        let returnData = [];

        // On select change
        $('.return-accepted').on('change', function() {
            const medicine_id = $(this).data('id');
            const return_status = $(this).val();
            const discount = parseFloat($(this).data('discount')) || 0;

            // Remove old
            returnData = returnData.filter(item => item.medicine_id !== medicine_id);

            returnData.push({
                medicine_id: medicine_id,
                return_status: return_status,
                return_amount: return_status === 'accepted' ? discount : 0
            });

            // Update this row's amount
            const rowAmount = return_status === 'accepted' ? discount : 0;
            $(this).closest('tr').find('.return-amount').text(rowAmount.toFixed(2));

            // Update total
            const total = returnData.reduce((sum, item) => sum + item.return_amount, 0);
            $('#totalReturnAmount').text(total.toFixed(2));
        });

        // Save click
        $('#saveReturnAccepted').on('click', function() {
            // ✅ First, check if all selects are filled
            let allFilled = true;
            $('.return-accepted').each(function() {
                if ($(this).val() === '') {
                    allFilled = false;
                    return false; // stop loop
                }
            });

            if (!allFilled) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete!',
                    text: 'Please select Return Accepted status for all medicines before saving.'
                });
                return; // stop here
            }

            // ✅ Now show confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to save these changes.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Save it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-success me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ User clicked Yes — DO SAVE
                    $.ajax({
                        url: '{{ route('orders.saveReturnAccepted') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            order_id: '{{ $order->id }}',
                            data: returnData
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong.'
                            });
                        }
                    });
                } else {
                    // ❌ User clicked Cancel — DO NOTHING
                    console.log('Save cancelled by user.');
                }
            });
        });
    </script>
@endsection
