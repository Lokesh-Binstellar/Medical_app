@extends('layouts.app')
@section('styles')
    <style>
        /* Fix column widths */
        #medicine-table td,
        #medicine-table th {
            max-width: 300px;
            width: 300px;
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
    </style>
@endsection
@section('content')
    <div class="container">
        <div class=" page-inner px-0">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center rounded-top">
                            <h4 class="card-title mb-0 text-white">Add Medicine to Cart</h4>
                        </div>

                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('addMedicine.store') }}" class="d-flex flex-column gap-3">
                                @csrf
                                <div class="d-flex selectCustomer align-items-center gap-3">
                                    <label class="font-bold">Please Select Prescription :</label>
                                    <select class="form-control customer-search " name="prescription_id">
                                        <option value="">Search customer...</option>
                                    </select>
                                </div>
                                <div class="table-responsive">
                                    <table class="display table table-striped table-hover data-table" id="medicine-table">
                                        <thead >
                                            <tr>
                                                <th>Select Medicine</th>
                                                <th>Substitute</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="medicine-body">
                                            <tr class="medicine-row">
                                                <td class="customWidth">
                                                    <select class="form-control medicine-search"
                                                        name="medicine[0][medicine_id]">
                                                        <option value="">Search
                                                            medicine...</option>
                                                    </select>
                                                </td>


                                                <td>
                                                    <select name="medicine[0][is_substitute]" class="form-select">
                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </td>

                                                <td>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger remove-row">−</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between flex-wrap gap-3">
                                    <div class="d-flex gap-2 justify-center align-items-center">
                                        <button type="button" id="add-row" class="btn btn-primary">+</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function initSelect2($el) {
                $el.select2({
                    placeholder: 'Search medicine...',
                    ajax: {
                        url: '{{ route('medicines.search') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                query: params.term
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

            let index = 1;
            initSelect2($('.medicine-search'));

            $('#add-row').on('click', function() {
                const $newRow = $('.medicine-row').first().clone();

                // Clear input/select values
                $newRow.find('input').val('');
                $newRow.find('select').not('.medicine-search').val('yes');

                // Update name attributes
                $newRow.find('select, input').each(function() {
                    const name = $(this).attr('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        $(this).attr('name', newName);
                    }
                });

                // Remove the old Select2 container and select
                $newRow.find('.select2-container').remove();
                $newRow.find('.medicine-search').remove();

                // Create a new select and append
                const $newSelect = $(`
        <select class="form-control medicine-search" style="width: 100%;" name="medicine[${index}][medicine_id]">
            <option value="">Search medicine...</option>
        </select>
    `);
                $newRow.find('td:first').append($newSelect);
                initSelect2($newSelect);

                $('#medicine-body').append($newRow);
                index++;
            });

            $(document).on('click', '.remove-row', function() {
                if ($('#medicine-body .medicine-row').length > 1) {
                    $(this).closest('tr').remove();
                }
            });


            $('form').on('submit', function(e) {
                let isValid = true;

                // Remove previous error messages and red borders
                $('.medicine-error').remove();
                $('.medicine-search').next('.select2-container').css('border', '');

                $('#medicine-body .medicine-row').each(function(index) {
                    const $medicineSelect = $(this).find('.medicine-search');
                    const medicineVal = $medicineSelect.val();

                    if (!medicineVal) {
                        isValid = false;

                        $medicineSelect.next('.select2-container').css('border', '1px solid red');

                        // Show inline error
                        if ($(this).find('.medicine-error').length === 0) {
                            $(this).find('td:first').append(
                                '<div class="text-danger small medicine-error mt-1">Please select a medicine.</div>'
                            );
                        }
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });

            function initCustomerSelect2($el) {
                $el.select2({
                    placeholder: 'Search customer...',
                    ajax: {
                        url: '{{ route('prescription.select') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                query: params.term
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

        });
    </script>
@endsection
