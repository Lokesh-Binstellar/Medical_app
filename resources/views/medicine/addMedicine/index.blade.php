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
                                <!-- Prescription Dropdown -->
                                <!-- Prescription Dropdown (Already using select2) -->
                                <div class="d-flex selectCustomer align-items-center gap-3">
                                    <label class="font-bold">Please Select Prescription :</label>
                                    <select class="form-control customer-search" name="prescription_id"
                                        id="prescription-select">
                                        <option value="">Search customer...</option>
                                    </select>
                                </div>

                                <div>
                                    <div class="header">Prescriptions Preview</div>

                                    <div class="pdf-wrapper" id="prescription-preview"></div>

                                    <div class="footer" id="download-links"></div>
                                </div>

                                <div id="cart-details" style="display:none;" class="mt-3">
                                    <h5 class="text-black">Customer Cart Details</h5>
                                    <table class="table table-bordered ">
                                        <thead>
                                            <tr class="table-dark">
                                                <th>Product ID</th>
                                                <th>Product Name</th>
                                                <th>Packaging Detail</th>
                                                <th>Quantity</th>
                                                <th>Is Substitute</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cart-product-body">
                                        </tbody>
                                    </table>
                                </div>





                                <div class="table-responsive">
                                    <table class="display table table-striped table-hover data-table " id="medicine-table">
                                        <thead>
                                            <tr>
                                                <th>Select Medicine</th>
                                                <th>Packaging</th>
                                                <th>Quantity</th>
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
                                                    <input type="text" name="medicine[0][packaging_detail]"
                                                        class="form-control packaging-info" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" name="medicine[0][quantity]" class="form-control" min="1"> 
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

            let index = 1;
            initSelect2($('.medicine-search'));
            initCustomerSelect2($('.customer-search'));

            $('#add-row').on('click', function() {
                const $newRow = $('.medicine-row').first().clone();

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

                $newRow.find('.select2-container').remove();
                $newRow.find('.medicine-search').remove();

                const $newSelect = $(`<select class="form-control medicine-search" style="width: 100%;" name="medicine[${index}][medicine_id]">
                <option value="">Search medicine...</option>
            </select>`);

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
                $('.medicine-error').remove();
                $('.medicine-search').next('.select2-container').css('border', '');

                $('#medicine-body .medicine-row').each(function() {
                    const $medicineSelect = $(this).find('.medicine-search');
                    const medicineVal = $medicineSelect.val();

                    if (!medicineVal) {
                        isValid = false;
                        $medicineSelect.next('.select2-container').css('border', '1px solid red');

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
            // Fetch packaging detail on medicine select
            $(document).on('change', '.medicine-search', function() {
                var id = $(this).val();
                var $row = $(this).closest('tr');

                if (id) {
                    $.ajax({
                        url: '{{ route('medicine.strip') }}',
                        type: 'GET',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            if (response.status) {
                                $row.find('.packaging-info').val(response.packaging_detail ||
                                    '');
                            } else {
                                $row.find('.packaging-info').val('');
                            }
                        },
                        error: function() {
                            $row.find('.packaging-info').val('');
                        }
                    });
                } else {
                    $row.find('.packaging-info').val('');
                }
            });

        });


        $('#prescription-select').on('select2:select', function(e) {
            var prescriptionId = e.params.data.id;

            $.ajax({
                url: '/addMedicine/fetch-customer-cart',
                method: 'GET',
                data: {
                    prescription_id: prescriptionId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        let products = response.data;
                        console.log("products",products);
                        
                        let html = '';

                        products.forEach(function(product) {
                            html += `<tr data-product-id="${product.product_id}">
                        <td>${product.product_id}</td>
                        <td>${product.name}</td>
                        <td>${product.packaging_detail}</td>
                        <td>${product.quantity}</td>
                        <td>${product.is_substitute}</td>
                         <td><button class="btn btn-primary delete-row">Delete</button></td>
                    </tr>`;
                        });

                        $('#cart-product-body').html(html);
                        $('#cart-details').show();
                    } else {
                        $('#cart-product-body').html('');
                        $('#cart-details').hide();
                    }
                },
                error: function() {
                    $('#cart-product-body').html('');
                    $('#cart-details').hide();
                }
            });
        });
        
        
        // Handle delete button click
        $(document).on('click', '.delete-row', function() {
            let row = $(this).closest('tr');
            let productId = row.data('product-id');
            let prescriptionId = $('#prescription-select').val(); // Get selected prescription ID

            if (confirm('Are you sure you want to delete this product from the cart?')) {
                $.ajax({
                    url: '/delete-cart-product',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        prescription_id: prescriptionId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            row.remove(); // Remove from table
                            alert('Product deleted successfully!');
                        } else {
                            alert('Failed to delete product');
                        }
                    },
                    error: function() {
                        alert('Error deleting product');
                    }
                });
            }
        });









        // $(document).on('change', '.customer-search', function() {
        $('#prescription-select').on('select2:select', function(e) {
            //$('.customer-search').on('select2:select', function(e) {

            var prescriptionId = e.params.data.id; // Assuming customer_id is available


            $.ajax({
                url: '/addMedicine/fetch-prescription-files',
                method: 'GET',
                data: {
                    prescriptionId: prescriptionId
                },
                success: function(response) {
                    //console.log(response.files);
                    if (response.status === 'success') {
                        let html = '';
                        var fileUrl = response.files;
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

                        $('.pdf-wrapper').html(html); // Inject the generated HTML into the container
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
    </script>
@endsection
