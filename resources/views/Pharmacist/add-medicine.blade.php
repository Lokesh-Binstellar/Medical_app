@extends('layouts.app')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
@section('content')

<div class="container mt-4">
    <div class="card shadow-sm rounded-3">
        <!-- Blue Header Title -->
        <div class="card-header bg-info text-white fw-bold fs-5">
            Add Medicine
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('medicines.store') }}">
                @csrf

                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped align-middle" id="medicine-table">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Search Medicine</th>
                                <th>MRP</th>
                                <th>Final Amount</th>
                                <th>Discount %</th>
                                <th>Available</th>
                                <th>Substitute</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="medicine-body">
                            <tr class="medicine-row">
                                <td>
                                    <select class="form-select medicine_search" name="medicine[0][medicine_id]"></select>
                                    <input type="hidden" class="medicine_name" name="medicine[0][medicine_name]">
                                </td>
                                <td>
                                    <input type="number" name="medicine[0][mrp]" class="form-control mrp" step="0.01" placeholder="MRP">
                                </td>
                                <td>
                                    <input type="number" name="medicine[0][discount]" class="form-control discount" step="0.01" placeholder="Final Amount">
                                </td>
                                <td>
                                    <input type="number" name="medicine[0][discount_percent]" class="form-control discount_percent" step="0.01" placeholder="%">
                                </td>
                                <td>
                                    <select name="medicine[0][available]" class="form-select">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="medicine[0][is_substitute]" class="form-select">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-row">−</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between flex-wrap gap-3">
                    <div class="d-flex gap-2 justify-center align-items-center">
                        <button type="button" id="add-row" class="btn btn-primary">+</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>

                    <div class="d-flex flex-column text-end">
                        <div>
                            <input type="hidden" id="mrp_amount" name="mrp_amount">
                            <strong>Total MRP:</strong>
                            <span class="text-primary fw-bold mrp-amount">0.00</span>
                        </div>
                        <div>
                            <input type="hidden" id="total_amount" name="total_amount">
                            <strong>Total Final Amount:</strong>
                            <span class="text-success fw-bold total-amount">0.00</span>
                        </div>
                        <div>
                            <input type="hidden" id="commission_amount" name="commission_amount">
                            <strong>Total Commission (5% on MRP):</strong>
                            <span class="text-danger fw-bold commission-amount">0.00</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


    <div class="container ">
        <div class="card shadow-sm my-5">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Medicine Data</h5>
            </div>
    
            <div class="card-body">
                @if ($medicines->count() > 0)
                    <div class="accordion" id="medicineAccordion">
                        @foreach ($medicines as $entry)
                            @php
                                $accordionId = $entry->id;
                                $pharmacyId = $entry->phrmacy_id;
                                $medData = json_decode($entry->medicine, true);
                            @endphp
    
                            <div class="accordion-item border-0 shadow-sm mb-3">
                                <h2 class="accordion-header" id="heading{{ $accordionId }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $accordionId }}" aria-expanded="false"
                                        aria-controls="collapse{{ $accordionId }}">
                                        Record #{{ $accordionId }} (Pharmacy ID: {{ $pharmacyId }})
                                    </button>
                                </h2>
                                <div id="collapse{{ $accordionId }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $accordionId }}" data-bs-parent="#medicineAccordion">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Medicine Name</th>
                                                        <th>MRP</th>
                                                        <th>Final Price</th>
                                                        <th>Discount %</th>
                                                        <th>Available</th>
                                                        <th>Substitute</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $counter = 1; @endphp
                                                    @foreach ($medData as $med)
                                                        <tr>
                                                            <td>{{ $counter++ }}</td>
                                                            <td>{{ $med['medicine_name'] ?? '-' }}</td>
                                                            <td>₹{{ $med['mrp'] ?? '0.00' }}</td>
                                                            <td>₹{{ $med['discount'] ?? '0.00' }}</td>
                                                            <td>{{ $med['discount_percent'] ?? '0' }}%</td>
                                                            <td>{{ ucfirst($med['available'] ?? '-') }}</td>
                                                            <td>{{ ucfirst($med['is_substitute'] ?? '-') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-2">
                                            <div><strong>Total MRP:</strong> ₹{{ $entry->mrp_amount }}</div>
                                            <div><strong>Total Final Amount:</strong> ₹{{ $entry->total_amount }}</div>
                                            <div><strong>Commission (5%):</strong> ₹{{ $entry->commission_amount }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    




@endsection
@section('script')
    <script>
        function initSelect2($el) {
            $el.select2({
                placeholder: 'Search by name or salt',
                minimumInputLength: 0,
                ajax: {
                    
                    url: "/search-medicine",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term || ''
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            }).on('select2:select', function(e) {
                const selectedText = e.params.data.text;
                const $row = $(this).closest('tr');
                $row.find('.medicine_name').val(selectedText);
            });
            // ✅ CSS fix for new select2 dropdowns
            $el.each(function() {
                const container = $(this).data('select2')?.$container;
                if (container) {
                    container.css('width', '350px');
                }
            });
        }

        function calculateTotal() {
            let total = 0;

            $('.discount').each(function() {
                const value = parseFloat($(this).val()) || 0;
                total += value;
            });

            $('#total_amount').val(total.toFixed(2));
            $('.total-amount').text(total.toFixed(2));

            calculateMRPTotal(); // use this instead of calculateCommission
        }

        function calculateMRPTotal() {
            let mrpTotal = 0;

            $('.mrp').each(function() {
                const value = parseFloat($(this).val()) || 0;
                mrpTotal += value;
            });

            $('#mrp_amount').val(mrpTotal.toFixed(2));
            $('.mrp-amount').text(mrpTotal.toFixed(2));

            // Commission based on total MRP
            const commission = mrpTotal * 0.05;

            $('#commission_amount').val(commission.toFixed(2));
            $('.commission-amount').text(commission.toFixed(2));
        }


        $(document).ready(function() {
            let index = 1;

            initSelect2($('.medicine_search'));

            $('#add-row').on('click', function() {
                const $newRow = $('.medicine-row').first().clone();

                $newRow.find('input').val('');
                $newRow.find('select').not('.medicine_search').val('yes');

                $newRow.find('select, input').each(function() {
                    const name = $(this).attr('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        $(this).attr('name', newName);
                    }
                });

                $newRow.find('.select2-container').remove();
                const $newSelect = $newRow.find('.medicine_search').clone().val('');
                $newRow.find('.medicine_search').replaceWith($newSelect);
                initSelect2($newSelect);

                $('#medicine-body').append($newRow);
                index++;
                calculateTotal();
            });

            $(document).on('click', '.remove-row', function() {
                if ($('#medicine-body .medicine-row').length > 1) {
                    $(this).closest('tr').remove();
                    calculateTotal();
                }
            });

            $(document).on('input', '.mrp, .discount', function() {
                const $row = $(this).closest('tr');
                const mrp = parseFloat($row.find('.mrp').val()) || 0;
                const discountAmount = parseFloat($row.find('.discount').val()) || 0;
                if (mrp > 0 && discountAmount <= mrp) {
                    const percent = ((mrp - discountAmount) / mrp) * 100;
                    $row.find('.discount_percent').val(percent.toFixed(2));
                }
                calculateTotal();
            });

            $(document).on('input', '.mrp, .discount_percent', function() {
                const $row = $(this).closest('tr');
                const mrp = parseFloat($row.find('.mrp').val()) || 0;
                const percent = parseFloat($row.find('.discount_percent').val()) || 0;
                if (mrp > 0 && percent >= 0 && percent <= 100) {
                    const finalPrice = mrp - ((percent / 100) * mrp);
                    $row.find('.discount').val(finalPrice.toFixed(2));
                }
                calculateTotal();
            });

            $(document).on('input', '.discount', calculateTotal); // final amount changes
        });
    </script>
@endsection
