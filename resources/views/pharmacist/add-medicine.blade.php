@extends('layouts.app')

@section('styles')
    {{--
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

    <style>
        .Show-Medicine:hover {
            color: #fefefe !important;
            background-color: #033a62 !important;
            /* border-color: #fefefe !important; */
        }

        /* Fix column widths */
        #medicine-table td,
        #medicine-table th {
            max-width: 200px;
            width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }


        /* Style Select2 container for fixed width */
        .select2-container--default .select2-selection--single {
            width: 100% !important;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Optional: Hide the full text tooltip on hover */
        .select2-selection__rendered {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .selectCustomer {
            font-weight: 600;
            text-wrap: nowrap;
        }

        body>span.select2-container.select2-container--default.select2-container--open {
            width: auto !important;
        }

        .accordion-item:nth-child(odd) .accordion-button {
            background-color: #ffffff;
            /* border: 1px solid #e8ebee; */
            /* white */
        }

        .accordion-item:nth-child(even) .accordion-button {
            background-color: #e8ebee;
            /* border: 1px solid #ffffff; */
            border-radius: 0px !important;
            /* light gray */
        }

        .accordion-button::after {
            display: none;
        }

        .header {
            padding: 16px;
            background-color: #2c3e50;
            color: #fff;
            font-size: 1.5rem;
            border-radius: 6px 6px 0 0;
            margin-bottom: 20px;
        }

        .pdf-wrapper {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pdf-box {
            flex: 1 1 45%;
            min-width: 300px;
        }

        .pdf-title {
            margin-bottom: 8px;
            font-size: 1.1rem;
            color: #34495e;
            text-align: center;
        }

        .pdf-viewer {
            width: 100%;
            height: 400px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
        }

        .footer a {
            text-decoration: none;
            color: #2980b9;
            margin-left: 10px;
        }

        @media (max-width: 900px) {
            .pdf-box {
                flex: 1 1 100%;
            }

            .pdf-viewer {
                height: 500px;
            }
        }
    </style>
@endsection
@section('content')


    <div class="card shadow-xl rounded-3">
        <!-- Blue Header Title -->
        <div class="card-header text-white fw-bold fs-5">
            Add Medicine
        </div>
        <input type="hidden" name="current_pharmacy_id" id="current_pharmacy_id" value="{{ Auth::id() }}">

        <div class="card-body">
            <form method="POST" action="{{ route('medicines.store') }}" id="medicineCreateForm"
                class="d-flex flex-column gap-4">
                @csrf
                <div class="d-flex selectCustomer align-items-center gap-5">
                    <label class="font-bold">Please Select Customer :</label>
                    <select class="form-control customer-search customerDropdown" name="customer[0][customer_id]"
                        id="prescription-select">
                        <option value="">Search customer...</option>
                    </select>
                </div>

                <div>
                    <div class="header">Prescriptions Preview</div>

                    <div class="pdf-wrapper" id="prescription-preview"></div>

                    <div class="footer" id="download-links"></div>
                </div>

                <div class="card" style="display:none;" id="cart-details">
                    <h5 class="card-header">Customer Cart Details</h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Packaging Detail</th>
                                        <th>Quantity</th>
                                        <th>Is Substitute</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-product-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ✅ Responsive Note ABOVE the Table -->
                <div class="table-responsive mb-3">
                    <div
                        style="border-left: 5px solid #f44336; background-color: #ffe6e6; padding: 10px 15px; margin-bottom: 10px; border-radius: 6px; font-family: Arial, sans-serif;">
                        <strong style="color: #d32f2f;">Note :</strong><br>
                        <strong>Please make sure to enter the total price manually, not the per unit price based on the
                            quantity requested by the customer.</strong><br>
                        <strong>If requested quantity is not available, kindly mark the medicine as not available.</strong>
                    </div>

                    <table class="display table table-striped table-hover data-table nowrap" id="medicine-table">
                        <thead>
                            <tr>
                                {{-- <th></th> --}}
                                <th>Search Medicine</th>
                                <th>Substitute Medicine</th>
                                <th>MRP</th>
                                <th>Final Amount</th>
                                <th>Discount %</th>
                                <th>Quantity</th>
                                <th>Available</th>
                                <th>Substitute</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="medicine-body" class="cart-medicine-body">
                            <tr class="medicine-row">
                                {{-- <td></td>  --}}
                                <td>
                                    <select class="form-select medicine_search medicineDropdown" disabled
                                        name="medicine[0][medicine_id]"></select>
                                        <input type="hidden" class="medicine_add_id" name="medicine[0][medicine_id]">
                                    <input type="hidden" class="medicine_name" name="medicine[0][medicine_name]">
                                </td>
                                <td>
                                    <select name="medicine[0][substitute_medicine]"
                                        class="form-select substitute_medicine_dropdown substitute_search" disabled>
                                        <option value="">N/A</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="medicine[0][mrp]" class="form-control mrp" step="0.01"
                                        placeholder="MRP">
                                </td>
                                <td>
                                    <input type="number" name="medicine[0][discount]" class="form-control discount"
                                        step="0.01" placeholder="Final Amount">
                                </td>
                                <td>
                                    <input type="number" name="medicine[0][discount_percent]"
                                        class="form-control discount_percent" step="0.01" placeholder="%">
                                </td>
                                <td>
                                    <input type="number" name="medicine[0][quantity]" class="form-control quantity"
                                        step="0" placeholder="Quantity" readonly style="background-color: #eef0f3;">
                                </td>
                                <td>
                                    <select name="medicine[0][available]" class="form-select">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="medicine[0][is_substitute]" class="form-select is_saltsubstitute">
                                        <option value="yes">Yes</option>
                                        <option value="no" selected>No</option>
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
                        {{-- <button type="button" id="add-row" class="btn btn-primary">+</button> --}}
                        <button type="submit" class="btn btn-primary">Save</button>
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
                            <strong>Total Commission :</strong>
                            <span class="text-danger fw-bold commission-amount">0.00</span>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>



    <div class="card shadow-xl my-5">
        <div class="card-header text-white">
            <h5 class="mb-0">Medicine Data</h5>
        </div>

        <div class="card-body px-0">
            @if ($medicines->count() > 0)
                <div class="accordion" id="medicineAccordion">
                    @foreach ($medicines as $entry)
                        @php
                            $accordionId = $entry->id;
                            $pharmacyId = $entry->phrmacy_id;
                            $medData = json_decode($entry->medicine, true);
                            $customer = $entry->customer;
                            $statusText = '';
                            if ($entry->status == 0) {
                                $statusText =
                                    '<span class="badge rounded-pill bg-label-warning me-1">Request Sent</span>';
                            } elseif ($entry->status == 1) {
                                $statusText =
                                    '<span class="badge rounded-pill bg-label-success me-1">Request Accepted</span>';
                            } elseif ($entry->status == 2) {
                                $statusText =
                                    '<span class="badge rounded-pill bg-label-danger me-1">Request Cancelled</span>';
                            }
                        @endphp

                        <div class="accordion-item border-0 shadow-sm mb-3">
                            <h2 class="accordion-header" id="heading{{ $accordionId }}">
                                <button
                                    class="accordion-button collapsed d-flex justify-content-between align-items-center w-100"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $accordionId }}" aria-expanded="false"
                                    aria-controls="collapse{{ $accordionId }}">

                                    <table class="table table-bordered table-sm w-auto" style="border: 1px solid black;">
                                        <tbody>
                                            <tr>
                                                <th><strong>Record #</strong></th>
                                                <td><strong>{{ $accordionId }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th><strong>Customer Details</strong></th>
                                                <td><strong>Name: {{ $customer->firstName ?? 'N/A' }} | Phone:
                                                        {{ $customer->mobile_no ?? 'N/A' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th><strong>Status</strong></th>
                                                <td><strong>{!! $statusText !!}</strong></td>
                                            </tr>
                                            @if ($entry->status == 1)
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="text-danger">
                                                            <strong>Note:</strong> Accepted order details will be
                                                            displayed under
                                                            <strong>Orders</strong>.
                                                            You need to update the order status to
                                                            <strong>Completed</strong> or
                                                            <strong>Cancelled</strong> from there.
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                    <a type="button" class="btn btn-primary Show-Medicine">Show Medicine<i
                                            class="fa-solid fa-arrow-down pl-1"></i></a>
                                </button>
                            </h2>

                            <div id="collapse{{ $accordionId }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $accordionId }}" data-bs-parent="#medicineAccordion">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Medicine Name</th>
                                                    <th>Substitute Medicine Name</th>
                                                    <th>MRP</th>
                                                    <th>Final Price</th>
                                                    <th>Discount %</th>
                                                    <th>Quantity</th>
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
                                                        <td>{{ $med['substitute_medicine'] ?? '-' }}</td>
                                                        <td>₹{{ $med['mrp'] ?? '0.00' }}</td>
                                                        <td>₹{{ $med['discount'] ?? '0.00' }}</td>
                                                        <td>{{ $med['discount_percent'] ?? '0' }}%</td>
                                                        <td>{{ $med['quantity'] ?? '0' }}</td>
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
                                        <div><strong>Commission Amount:</strong> ₹{{ $entry->commission_amount }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        function initSelect2($el) {
            $el.select2({
                placeholder: 'Search by name or salt',
                minimumInputLength: 0,
                ajax: {

                    url: `{{ route('search.medicine') }}`,
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

        function initSubstituteSelect2($el) {
            $el.select2({
                placeholder: 'Select substitute medicine',
                allowClear: true,
                minimumInputLength: 0,
                width: '100%'
            }).on('select2:select', function(e) {
                // Optional: If you want to store selected substitute name in a hidden input, you can add logic here
            });

            // Optional: Set fixed width like medicine dropdown (350px)
            $el.each(function() {
                const container = $(this).data('select2')?.$container;
                if (container) {
                    container.css('width', '350px');
                }
            });
        }

        // function initSubstituteSelect2($el) {
        //     $el.select2({
        //         placeholder: 'Select substitute medicine',
        //         allowClear: true,
        //         width: 'resolve' // makes it adapt to container
        //     });

        //     // Force correct width styling for new rows (optional fix)
        //     $el.each(function() {
        //         let $container = $(this).data('select2')?.$container;
        //         if ($container) {
        //             $container.css('width', '350px');
        //         }
        //     });
        // }


        function calculateTotal() {
            let total = 0;

            $('.discount').each(function() {
                const value = parseFloat($(this).val()) || 0;
                total += value;
            });

            $('#total_amount').val(total.toFixed(2));
            $('.total-amount').text(total.toFixed(2));

            // Commission based on total MRP
            // const commission = total >= 300 ? 15 : 10;
            // const gstRate = {{ $commissionData->gstRate ?? 0}}; //  18
            const commissionAboveAmount = {{ $commissionData->commissionAboveAmount ?? 0 }}; //  18
            const commissionBelowAmount = {{ $commissionData->commissionBelowAmount ?? 0}}; //  10
            const commonAmount = {{ $commissionData->commonAmount ?? 0}}; // 300

            const commission = total >= commonAmount ?
                // commissionAboveAmount + (commissionAboveAmount * gstRate / 100) :
                commissionAboveAmount :
                // (commissionBelowAmount - (commissionBelowAmount * gstRate / 100)) + (commissionBelowAmount * gstRate / 100);
                // (commissionBelowAmount ) + (commissionBelowAmount * gstRate / 100);
                commissionBelowAmount;

            $('#commission_amount').val(commission.toFixed(2));
            $('.commission-amount').text(commission.toFixed(2));

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

                $newRow.find('.substitute_medicine_dropdown')
                    .html('<option value="">Select Substitute</option>')
                    .prop('disabled', true);

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

            $(document).on('input', '.discount', calculateTotal);

            $(document).on('change', '.form-select[name$="[is_substitute]"]', function() {
                const row = $(this).closest('.medicine-row');
                const isSubstitute = $(this).val();
                const substituteDropdown = row.find('.substitute_medicine_dropdown');
                const selectedMedicineId = row.find('.medicineDropdown').val();



                if (isSubstitute === 'no') {
                    substituteDropdown.select2('destroy');
                    substituteDropdown.html('<option>N/A</option>');
                    substituteDropdown.prop('disabled', true);
                    substituteDropdown.css('border', '');
                    substituteDropdown.closest('td').find('.substitute-medicine-error').remove();
                } else {
                    substituteDropdown.prop('disabled', false);
                    substituteDropdown.html('<option>Loading...</option>');

                    $.ajax({
                        url: "{{ route('get-medicine-salt') }}",
                        type: 'GET',
                        data: {
                            medicine_id: selectedMedicineId

                        },
                        success: function(response) {
                            const salt = response.salt;
                            if (!salt) {
                                substituteDropdown.html(
                                    '<option>No substitutes found</option>');
                                return;
                            }

                            $.ajax({
                                url: "{{ route('get-substitute-medicines') }}",
                                type: 'GET',
                                data: {
                                    salt: salt,
                                    selectedMedicineId: selectedMedicineId
                                },
                                success: function(res) {
                                    let options =
                                        '<option value="">Select Substitute</option>';
                                    if (res.results.length > 0) {
                                        res.results.forEach(item => {
                                            options +=
                                                `<option value="${item.id}">${item.text}</option>`;
                                        });
                                    } else {
                                        options =
                                            '<option>No substitutes found</option>';
                                    }

                                    substituteDropdown.html(options).prop(
                                        'disabled', false);

                                    // ⛔️ Avoid destroy unless previously applied
                                    if ($.fn.select2 && substituteDropdown.hasClass(
                                            "select2-hidden-accessible")) {
                                        substituteDropdown.select2('destroy');
                                    }

                                    initSubstituteSelect2(substituteDropdown);

                                },
                                error: function() {
                                    substituteDropdown.html(
                                        '<option>Error loading substitutes</option>'
                                    );
                                }
                            });
                        },
                        error: function() {
                            substituteDropdown.html('<option>Error fetching salt</option>');
                        }
                    });
                }
            });

            function initCustomerSelect2($el) {
                $el.select2({
                    placeholder: 'Search customer...',
                    ajax: {
                        url: '{{ route('customers.select') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                query: params.term,
                                current_pharmacy_id: $('#current_pharmacy_id').val()
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results
                            };
                        },
                        cache: true
                    }
                });
            }

            initCustomerSelect2($('.customer-search'));

            $('form').on('submit', function(e) {
                let isValid = true;

                $('.medicine-error, .mrp-error, .discount-error, .discount-percent-error, .quantity-error, .available-error, .substitute-error, .substitute-medicine-error, .customer-error')
                    .remove();
                $('.form-control, .form-select').css('border', '');
                $('.medicine_search').next('.select2-container').css('border', '');

                $('#medicine-body .medicine-row').each(function(index) {
                    const $row = $(this);

                    const $medicineSelect = $row.find('.medicine_search');
                    const medicineVal = $medicineSelect.val();
                    if (!medicineVal) {
                        isValid = false;
                        $medicineSelect.next('.select2-container').css('border', '1px solid red');
                        $row.find('td:nth-child(1)').append(
                            '<div class="text-danger small medicine-error mt-1">Please select a medicine.</div>'
                        );
                    }

                    const $substituteMedicineSelect = $row.find(
                        `select[name="medicine[${index}][substitute_medicine]"]`);
                    const $substituteSelect = $row.find(
                        `select[name="medicine[${index}][is_substitute]"]`);

                    if (!$substituteSelect.val()) {
                        isValid = false;
                        $substituteSelect.css('border', '1px solid red');
                        $row.find('td:nth-child(8)').append(
                            '<div class="text-danger small substitute-error mt-1">Please select if it is a substitute.</div>'
                        );
                    }

                    if ($substituteSelect.val() === 'yes' && !$substituteMedicineSelect.val()) {
                        isValid = false;
                        $substituteMedicineSelect.css('border', '1px solid red');
                        $row.find('td:nth-child(2)').append(
                            '<div class="text-danger small substitute-medicine-error mt-1">Please select a substitute medicine.</div>'
                        );
                    }

                    const $mrp = $row.find(`input[name="medicine[${index}][mrp]"]`);
                    if (!$mrp.val() || parseFloat($mrp.val()) <= 0) {
                        isValid = false;
                        $mrp.css('border', '1px solid red');
                        $row.find('td:nth-child(3)').append(
                            '<div class="text-danger small mrp-error mt-1">Please enter a valid MRP.</div>'
                        );
                    }

                    const $discountPercent = $row.find(
                        `input[name="medicine[${index}][discount_percent]"]`);
                    const dpVal = parseFloat($discountPercent.val());
                    if (!$discountPercent.val() || dpVal < 0 || dpVal > 100) {
                        isValid = false;
                        $discountPercent.css('border', '1px solid red');
                        $row.find('td:nth-child(5)').append(
                            '<div class="text-danger small discount-percent-error mt-1">Please enter a valid discount percentage (0–100).</div>'
                        );
                    }

                    const $discount = $row.find(`input[name="medicine[${index}][discount]"]`);
                    if (!$discount.val() || parseFloat($discount.val()) < 0) {
                        isValid = false;
                        $discount.css('border', '1px solid red');
                        $row.find('td:nth-child(4)').append(
                            '<div class="text-danger small discount-error mt-1">Please enter a valid discount amount.</div>'
                        );
                    }

                    const $quantity = $row.find(`input[name="medicine[${index}][quantity]"]`);
                    if (!$quantity.val() || parseFloat($quantity.val()) <= 0) {
                        isValid = false;
                        $quantity.css('border', '1px solid red');
                        $row.find('td:nth-child(6)').append(
                            '<div class="text-danger small quantity-error mt-1">Please enter a valid quantity.</div>'
                        );
                    }

                    const $available = $row.find(`select[name="medicine[${index}][available]"]`);
                    if (!$available.val()) {
                        isValid = false;
                        $available.css('border', '1px solid red');
                        $row.find('td:nth-child(7)').append(
                            '<div class="text-danger small available-error mt-1">Please select availability.</div>'
                        );
                    }
                });

                const $customerSelect = $('select[name="customer[0][customer_id]"]');
                if (!$customerSelect.val()) {
                    isValid = false;
                    $customerSelect.css('border', '1px solid red');
                    if ($('.customer-error').length === 0) {
                        $customerSelect.closest('.d-flex').append(
                            '<div class="text-danger small customer-error mt-1">Please select a customer.</div>'
                        );
                    }
                }

                if (!isValid) e.preventDefault();
            });


            $(document).on('change', '.medicine_search', function() {
                if ($(this).val()) {
                    $(this).next('.select2-container').css('border', '');
                    $(this).closest('td').find('.medicine-error').remove();
                }
            });

            $(document).on('input', 'input[name*="[mrp]"]', function() {
                if ($(this).val() > 0) {
                    $(this).css('border', '');
                    $(this).closest('td').find('.mrp-error').remove();
                }
            });

            $(document).on('input', 'input[name*="[discount]"]', function() {
                if ($(this).val() >= 0) {
                    $(this).css('border', '');
                    $(this).closest('td').find('.discount-error').remove();
                }
            });

            $(document).on('input', 'input[name*="[discount_percent]"]', function() {
                const val = parseFloat($(this).val());
                if (val >= 0 && val <= 100) {
                    $(this).css('border', '');
                    $(this).closest('td').find('.discount-percent-error').remove();
                }
            });

            $(document).on('input', 'input[name*="[quantity]"]', function() {
                if ($(this).val() && parseFloat($(this).val()) > 0) {
                    $(this).css('border', '');
                    $(this).closest('td').find('.quantity-error').remove();
                }
            });

            $(document).on('change', 'select[name*="[available]"]', function() {
                if ($(this).val()) {
                    $(this).css('border', '');
                    $(this).closest('td').find('.available-error').remove();
                }
            });

            $(document).on('change', 'select[name*="[is_substitute]"]', function() {
                if ($(this).val()) {
                    $(this).css('border', '');
                    $(this).closest('td').find('.substitute-error').remove();
                }
            });

            $(document).on('change', 'select[name*="[substitute_medicine]"]', function() {
                if ($(this).val()) {
                    $(this).css('border', '');
                    $(this).closest('td').find('.substitute-medicine-error').remove();
                }
            });

            $(document).on('change', 'select.customer-search', function() {
                if ($(this).val()) {
                    $(this).css('border', '');
                    $('.customer-error').remove();
                }
            });

        });

        // $(document).ready(function() {
        //     $('#medicine-table').DataTable({
        //         responsive: {
        //             details: {
        //                 display: $.fn.dataTable.Responsive.display.modal({
        //                     header: function(row) {
        //                         return 'Medicine Row Details';
        //                     }
        //                 }),
        //                 renderer: function(api, rowIdx, columns) {
        //                     var data = $.map(columns, function(col) {
        //                         return col.title ?
        //                             `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
    //                             <td><strong>${col.title}</strong></td>
    //                             <td>${col.data}</td>
    //                        </tr>` :
        //                             '';
        //                     }).join('');

        //                     return $('<table class="table table-striped mb-0"><tbody>' + data +
        //                         '</tbody></table>');
        //                 }
        //             }
        //         },
        //         columnDefs: [{
        //                 className: 'control',
        //                 orderable: false,
        //                 searchable: false,
        //                 responsivePriority: 1,
        //                 targets: 0
        //             },
        //             {
        //                 targets: -1, // Action column
        //                 orderable: false,
        //                 searchable: false
        //             }
        //         ]
        //     });
        // });

        // Enhanced customer selection handler - Auto-populate medicine table from cart
        $(document).ready(function() {
            $('#prescription-select').on('select2:select', function(e) {
                const customerId = e.params.data.id;

                // Fetch cart data and auto-populate medicine table
                $.ajax({
                    url: '/search-medicine/fetch-cart-by-customer',
                    method: 'GET',
                    data: {
                        customer_id: customerId,
                        current_pharmacy_id: $('#current_pharmacy_id').val()
                    },
                    success: function(response) {
                        if (response.status === 'error') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Quote Timeout',
                                text: 'Request quote exceeded 15 minutes',
                                showCancelButton: true,
                                confirmButtonText: 'OK',
                                cancelButtonText: 'Cancel',
                                showDenyButton: true, // keep true if DOM me lana zaruri ho
                                denyButtonText: 'NO'
                            }).then(() => {
                                // Disable fields
                                $('#medicine-body .medicine-row').find('input, select')
                                    .prop('disabled', true);
                                $('#add-medicine-button').prop('disabled', true);
                                $('#save-quote-button').hide();

                                // ✅ Disable customer dropdown also
                                // $('#prescription-select').prop('disabled', true);
                            });

                            // Immediately after fire, hide deny button forcefully
                            setTimeout(() => {
                                document.querySelector('.swal2-deny')?.style
                                    .setProperty('display', 'none', 'important');
                            }, 10);



                            return;
                        }



                        if (response.status === 'success') {
                            const products = response.data;

                            // Clear existing medicine rows except the first one
                            $('#medicine-body .medicine-row:not(:first)').remove();

                            // Clear the first row
                            const $firstRow = $('#medicine-body .medicine-row:first');
                            $firstRow.find('input').val('');
                            $firstRow.find('select').not('.medicine_search').prop(
                                'selectedIndex', 0);
                            $firstRow.find('.medicine_search').val(null).trigger('change');

                            // Populate rows based on cart products
                            products.forEach((product, productIndex) => {
                                let $targetRow;

                                if (productIndex === 0) {
                                    // Use the first existing row
                                    $targetRow = $firstRow;
                                } else {
                                    // Clone the first row for additional products
                                    $targetRow = $firstRow.clone();

                                    // Update name attributes for the new row
                                    $targetRow.find('select, input').each(function() {
                                        const name = $(this).attr('name');
                                        if (name) {
                                            const newName = name.replace(
                                                /\[\d+\]/,
                                                `[${productIndex}]`);
                                            $(this).attr('name', newName);
                                        }
                                    });

                                    // Remove existing Select2 container and reinitialize
                                    $targetRow.find('.select2-container').remove();
                                    const $newSelect = $targetRow.find(
                                        '.medicine_search').clone().val('');
                                    $targetRow.find('.medicine_search').replaceWith(
                                        $newSelect);

                                    // Append to table body
                                    $('#medicine-body').append($targetRow);

                                    // Initialize Select2 for the new dropdown
                                    initSelect2($newSelect);
                                }

                                // Set the medicine dropdown value and trigger change
                                const $medicineSelect = $targetRow.find(
                                    '.medicine_search');

                                // Create option and add to select
                                const option = new Option(product.name, product
                                    .product_id, true, true);
                                $medicineSelect.append(option).trigger('change');

                                // Set the hidden medicine name field
                                $targetRow.find('.medicine_name').val(product.name);
                                $targetRow.find('.medicine_add_id').val(product.product_id);

                                // Set quantity
                                $targetRow.find('.quantity').val(product.quantity);

                                // Set substitute status if available
                                if (product.is_substitute !== undefined) {
                                    const substituteValue = product.is_substitute == 1 ?
                                        'yes' : 'no';
                                    $targetRow.find('select[name*="[is_substitute]"]')
                                        .val(substituteValue);
                                }

                                // Set availability to 'yes' by default
                                $targetRow.find('select[name*="[available]"]').val(
                                    'yes');
                            });

                            // Update index for future manual additions
                            index = products.length;

                            // Show cart details table
                            let cartHtml = '';
                            products.forEach(product => {
                                cartHtml += `<tr>
                                    <td>${product.product_id}</td>
                                    <td>${product.name}</td>
                                    <td>${product.packaging_detail}</td>
                                    <td>${product.quantity}</td>
                                    <td>${product.is_substitute}</td>
                                </tr>`;
                            });

                            $('#cart-product-body').html(cartHtml);
                            $('#cart-details').show();

                            // Recalculate totals
                            calculateTotal();

                        } else {
                            // Clear medicine table if no cart data
                            $('#medicine-body .medicine-row:not(:first)').remove();
                            const $firstRow = $('#medicine-body .medicine-row:first');
                            $firstRow.find('input').val('');
                            $firstRow.find('select').not('.medicine_search').prop(
                                'selectedIndex', 0);
                            $firstRow.find('.medicine_search').val(null).trigger('change');

                            $('#cart-product-body').html('');
                            $('#cart-details').hide();
                        }
                    },
                    error: function() {
                        console.error('Error fetching cart data');
                        $('#cart-product-body').html('');
                        $('#cart-details').hide();
                    }
                });

                // Keep the existing prescription preview functionality
                $.ajax({
                    url: `{{ route('search.prescription') }}`,
                    method: 'GET',
                    data: {
                        customer_id: customerId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            let html = '';
                            response.files.forEach(function(fileUrl, index) {
                                html += `
                                    <div class="pdf-box">
                                        <div class="pdf-title">Prescription ${index + 1}</div>
                                        <iframe class="pdf-viewer" src="${fileUrl}">
                                            This browser does not support PDFs. 
                                            <a href="${fileUrl}" download>Download PDF</a>.
                                        </iframe>
                                    </div>
                                `;
                            });

                            $('.pdf-wrapper').html(html);
                            $('#pdf-details').show();
                        } else {
                            $('.pdf-wrapper').html('No files available.');
                            $('#pdf-details').hide();
                        }
                    },
                    error: function() {
                        $('.pdf-wrapper').html('Error loading files.');
                        $('#pdf-details').hide();
                    }
                });
            });
        });




        // $(document).on('change', '.form-select[name$="[is_substitute]"]', function() {
        //     const row = $(this).closest('.medicine-row');
        //     const isSubstitute = $(this).val();
        //     const substituteDropdown = row.find('.substitute_medicine_dropdown');
        //     const selectedMedicineId = row.find('.medicineDropdown').val();

        //     if (isSubstitute === 'no') {
        //         substituteDropdown.select2('destroy');
        //         substituteDropdown.html('<option>N/A</option>');
        //         substituteDropdown.prop('disabled', true);
        //     } else {
        //         substituteDropdown.prop('disabled', false);
        //         substituteDropdown.html('<option>Loading...</option>');

        //         $.ajax({
        //             url: "{{ route('get-medicine-salt') }}",
        //             type: 'GET',
        //             data: {
        //                 medicine_id: selectedMedicineId
        //             },
        //             success: function(response) {
        //                 const salt = response.salt;
        //                 if (!salt) {
        //                     substituteDropdown.html('<option>No substitutes found</option>');
        //                     return;
        //                 }

        //                 $.ajax({
        //                     url: "{{ route('get-substitute-medicines') }}",
        //                     type: 'GET',
        //                     data: {
        //                         salt: salt
        //                     },
        //                     success: function(res) {
        //                         let options = '<option value="">Select Substitute</option>';
        //                         if (res.results.length > 0) {
        //                             res.results.forEach(item => {
        //                                 options +=
        //                                     `<option value="${item.id}">${item.text}</option>`;
        //                             });
        //                         } else {
        //                             options = '<option>No substitutes found</option>';
        //                         }

        //                         substituteDropdown.html(options).prop('disabled', false);
        //                         substituteDropdown.select2('destroy');
        //                         initSubstituteSelect2(substituteDropdown);
        //                     },
        //                     error: function() {
        //                         substituteDropdown.html(
        //                             '<option>Error loading substitutes</option>');
        //                     }
        //                 });
        //             },
        //             error: function() {
        //                 substituteDropdown.html('<option>Error fetching salt</option>');
        //             }
        //         });
        //     }
        // });
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script> --}}
@endsection
