@extends('layouts.app')
@section('styles')
    <style>
        /* Ensure black text color for all cells */
        table#ordersTable th,
        table#ordersTable td {
            color: #000 !important;
            background-color: #fff !important;
            text-align: center !important;
            /* Default center alignment */
            vertical-align: middle !important;
        }


        /* Style only the table header */
        table#ordersTable thead th {
            background-color: #f0f0f0 !important;
            /* Light grey */
            font-weight: bold !important;
            color: #000 !important;
            /* Ensure header text is black too */
        }

        #ordersTable,
        #ordersTable th,
        #ordersTable td {
            border: 1px solid black !important;
        }

        /* Updated Child row styles for responsive */
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.child {
            padding: 1rem !important;
            background-color: #f9f9f9 !important;
            border-top: 1px solid #ddd !important;
            font-size: 14px;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.child table {
            width: 100% !important;
            table-layout: fixed;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.child td {
            padding: 0.5rem !important;
            word-wrap: break-word;
            border: none !important;
            vertical-align: top !important;
        }

        /* Target the specific table cell for Customer column */
        #ordersTable td.customer-name {
            white-space: normal !important;
            word-wrap: break-word;
            max-width: 150px;
            /* adjust as needed */
        }

        .btn-primary:hover {
            background-color: #033a62 !important;
            color: #ffffff !important;
            border-color: #ffffff !important;
            /* optional: to make the border visible */
        }

        /* For small screens, align text left */
        @media (max-width: 1600px) {

            table#ordersTable th,
            table#ordersTable td {
                /* display: table-cell !important; */
                vertical-align: middle !important;
                text-align: left !important;
                /* vertical-align: middle !important; */
            }
        }
    </style>
@endsection


@section('content')
    <div class="card">
        <h5 class="card-header fw-bold">Order Details</h5><br>
        @if (Auth::user()->role->name === 'pharmacy')
            <div class="border border-warning rounded-3 p-3 mb-4 shadow-sm" style="background-color: #fffbe6;">
                <div class="d-flex align-items-start">
                    <i class="mdi mdi-alert-circle-outline text-warning fs-3 me-3"></i>
                    <div>
                        <h6 class="mb-1 fw-bold text-dark">Important Notice</h6>
                        <p class="mb-0 text-dark">
                            Once an order is <span class="fw-bold text-primary">Accepted</span>, its details will be shown
                            below.<br>
                            Please <span class="fw-bold text-danger">mark the order as Completed</span> after the customer picks
                            up the medicines,
                            or <span class="fw-bold text-danger">Cancelled</span> if the pickup doesn’t occur within <strong>24
                                hours</strong>.
                        </p>
                    </div>
                </div>
            </div>
        @elseif (Auth::user()->role->name === 'admin')
            <div class="border border-warning rounded-3 p-3 mb-4 shadow-sm" style="background-color: #fffbe6;">
                <div class="d-flex align-items-start">
                    <i class="mdi mdi-alert-circle-outline text-warning fs-3 me-3"></i>
                    <div>
                        <h6 class="mb-1 fw-bold text-dark">Important Notice</h6>
                        <p class="mb-0 text-dark">
                            Once an order is <span class="fw-bold text-primary">Accepted</span>, its details will be shown
                            below.<br>
                            Please <span class="fw-bold text-danger">mark the order as Completed</span> after the delivery is
                            done,
                            or <span class="fw-bold text-danger">Cancelled</span> if the delivery cannot be fulfilled.
                        </p>
                    </div>
                </div>
            </div>
        @elseif (Auth::user()->role->name === 'delivery_person')
            <div class="border border-warning rounded-3 p-3 mb-4 shadow-sm" style="background-color: #fffbe6;">
                <div class="d-flex align-items-start">
                    <i class="mdi mdi-alert-circle-outline text-warning fs-3 me-3"></i>
                    <div>
                        <h6 class="mb-1 fw-bold text-dark">Important Notice</h6>
                        <p class="mb-0 text-dark">
                            Once an order is <span class="fw-bold text-primary">assigned</span>, its details will be shown
                            below.<br>
                            Please remember to <span class="fw-bold text-danger">mark the order as Completed</span> once
                            delivery is done.
                        </p>
                    </div>
                </div>
            </div>
        @endif


        <div class="card-body">
            <div class="table-responsive text-nowrap" style="overflow-x: auto;">
                <table class="table table-bordered text-align-center" style="border: 1px solid black; color: black;"
                    id="ordersTable">
                    <div class="mb-3">
                        <label for="orderDate" class="form-label fw-bold">Filter by Order Date:</label>
                        <input type="date" id="orderDate" class="form-control" style="width: 250px;">
                    </div>

                    <thead class="bg-light fw-bold">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            @if (Auth::user()->role->name === 'admin')
                                <th class="fw-bold fs-6 ">Assign Delivery Boy</th>
                            @endif
                            <th>Order Details</th> {{-- new combined column --}}
                            <th>Order Status</th>
                            <th>Update Status</th>
                            <th>Medicine Details</th>
                            <th>Invoice</th>
                            @if (Auth::user()->role->name === 'admin')
                                <th>Delivery Information</th>
                            @endif

                        </tr>
                    </thead>

                </table>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Base columns that everyone sees
            let columns = [
                {
                    data: 'order_id',
                    name: 'order_id'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name',
                    className: 'customer-name'
                }
            ];

            // Admin-only columns
            @if(Auth::user()->role->name === 'admin')
                columns.push({
                    data: 'assign_delivery',
                    name: 'assign_delivery',
                    orderable: false,
                    searchable: false
                });
            @endif

            // Common columns for all users
            columns.push(
                {
                    data: null,
                    name: 'order_details',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        const details = {
                            date: row.date_raw,
                            payment_mode: row.payment_mode,
                            delivery_method: row.delivery_method,
                            total_price: row.total_price
                        };
                        return `
                                    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                                        <a href="#" class="order-details-link btn btn-sm btn-primary control me-2" 
                                        data-details='${JSON.stringify(details)}'>
                                            <i class="mdi mdi-eye"></i>View
                                        </a>
                                    </div>
                                `;
                    }
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'status_control',
                    name: 'status_control',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'invoice',
                    name: 'invoice',
                    orderable: false,
                    searchable: false
                }
            );

            // Add delivery_info column only for admin
            @if(Auth::user()->role->name === 'admin')
                columns.push({
                    data: 'delivery_info',
                    name: 'delivery_info',
                    orderable: false,
                    searchable: false
                });
            @endif


            let table = $('#ordersTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: {
                    details: {
                        renderer: function (api, rowIdx, columns) {
                            let data = $.map(columns, function (col) {
                                if (col.hidden) {
                                    let label = $('<strong>').text(col.title + ': ').prop('outerHTML');
                                    return '<tr><td colspan="2" style="padding: 5px 10px;">' + label + col.data + '</td></tr>';
                                }
                                return '';
                            }).join('');
                            return data ? $('<table/>').append(data).prop('outerHTML') : false;
                        }
                    }
                },
                ajax: {
                    url: '{{ route('orderdetails') }}',
                    data: function (d) {
                        d.order_date = $('#orderDate').val();
                    }
                },
                columns: columns,
            });

            $('#orderDate').on('change', function () {
                table.draw();
            });



            // Handle delivery person assignment
            $('#ordersTable').on('change', '.assign-delivery', function () {
                const orderId = $(this).data('id');
                const deliveryPersonId = $(this).val();

                $.ajax({
                    url: '{{ route("orders.assignDeliveryPerson") }}',
                    type: 'POST',
                    data: {
                        order_id: orderId,
                        delivery_person_id: deliveryPersonId
                    },
                    success: function (response) {
                        alert(response.message);
                        table.ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        alert('Failed to assign delivery person.');
                    }
                });
            });
        });
        // Create modal markup once on page
        if (!$('#orderDetailsModal').length) {
            $('body').append(`
                            <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="orderDetailsLabel">Order Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body"></div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                                </div>
                            </div>`);
        }

        // Handle click on "View Details"
        $('#ordersTable').on('click', '.order-details-link', function (e) {
            e.preventDefault();
            let details = $(this).data('details');

            // Format date nicely (you can customize)
            let formattedDate = new Date(details.date).toLocaleString(); // now details.date is ISO string


            let html = `
                                <p><strong>Date:</strong> ${formattedDate}</p>
                                <p><strong>Payment Mode:</strong> ${details.payment_mode}</p>
                                <p><strong>Delivery Method:</strong> ${details.delivery_method}</p>
                                <p><strong>Total Price:</strong> ₹${details.total_price}</p>
                            `;

            $('#orderDetailsModal .modal-body').html(html);
            let modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            modal.show();
        });


        document.addEventListener('change', function (e) {
            if (e.target && e.target.classList.contains('status-select')) {
                const select = e.target;
                const form = select.closest('form');
                const selectedValue = select.value;
                const userRole = select.dataset.role;

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
                            if (userRole === 'pharmacy') {
                                Swal.fire({
                                    title: 'Prescription Verified?',
                                    text: "Have you checked a valid prescription for this order?",
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, Verified',
                                    cancelButtonText: 'No',
                                    customClass: {
                                        confirmButton: 'btn btn-primary me-2',
                                        cancelButton: 'btn btn-secondary'
                                    },
                                    buttonsStyling: false
                                }).then((prescriptionConfirm) => {
                                    if (prescriptionConfirm.isConfirmed) {
                                        form.submit();
                                    } else {
                                        select.value = '';
                                    }
                                });
                            } else {
                                form.submit();
                            }
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
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.status-select').forEach(function (select) {
                select.addEventListener('change', function () {
                    const form = select.closest('form');
                    const cancelInput = form.querySelector('.cancel-by-input');

                    if (select.value === "2") {
                        cancelInput.value = "admin";
                    } else {
                        cancelInput.value = "";
                    }

                    form.submit(); // auto-submit on change (optional, or you can add a button)
                });
            });
        });

    </script>
@endsection