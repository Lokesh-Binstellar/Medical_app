@extends('layouts.app')

@section('styles')
    <style>
        /* Add any custom styles if needed */
        text.highcharts-credits {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow ">
                <div class="card-header d-flex justify-content-between align-items-center rounded-top">
                    <h4 class="card-title mb-0 text-white">Pharmacy Ledger Report</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-2">
                            <label for="name" class="form-label">Select Date</label>
                            <div class="input-group">
                                <input type="text" id="filterDate" class="form-control" placeholder="Select date range"
                                    autocomplete="off" />
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="name" class="form-label">Select Pharmacies</label>
                            <select name="name" class="form-control select2" id="filterPharmacy"
                                data-placeholder="Select Pharmacies" required>
                                <option value="">Select Pharmacies</option>
                                @foreach ($pharmacies as $pharmacy)
                                    <option value="{{ $pharmacy->id }}">{{ $pharmacy->pharmacy_name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-2">
                            <label for="name" class="form-label">Select Cities</label>
                            <select name="name" class="form-control select2" id="filterCity" required
                                data-placeholder="Select Cities">>
                                <option value="">Select Cities</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city }}">{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="filterStatus" class="form-label">Select Order Status</label>
                            <select name="status" class="form-control select2" id="filterStatus"
                                data-placeholder="Select Order Status">>
                                <option value="">Select Order Status</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order['id'] }}">{{ $order['label'] }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-2">
                            <button id="filterClear" class="btn btn-secondary addButton">Clear Filters</button>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-lg-6 col-md-12 col-12 mb-4 mb-lg-0">
                    <div class="card ">
                        <div class="card-header d-flex align-items-center justify-content-between py-2 pe-2">
                            <h5 class="card-title m-0 me-2 text-secondary">Sales This Month</h5>
                        </div>

                        <div class="card-body">
                            <div class="card-info py-3">
                                <p class="mb-0">Total Sales This Month</p>
                                <h5 id="salesText" class="mb-0">₹0.00</h5>
                            </div>
                            <div>
                                <canvas id="salesChart" height="130"></canvas>
                            </div>
                        </div>
                    </div>
                </div> --}}


                {{-- <div class="col-lg-6 col-md-12 col-12 mb-4 mb-lg-0">
                    <div class="card h-100">

                        <div class="card-header d-flex justify-content-between align-items-center rounded-top">
                            <h4 class="card-title mb-0 text-white">Order Overview</h4>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6 p-0" style="position: relative;">
                                    <div id="orderChart"></div>
                                    <div class="spinner-border spinner-border-lg text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="col-md-3 p-0">
                                    <span class="mdi mdi-circle me-2 " style="color: #5066f7;"></span>
                                    <span class="title">Total Orders</span><br>
                                    <p class="pt-3 sub-title" id="total_orders"></p>
                                    <hr>
                                    <span class="mdi mdi-circle me-2 " style="color: #7082f8;"></span>
                                    <span class="title">Completed</span><br>
                                    <p class="pt-3 sub-title" id="completed_orders"></p>
                                    <hr>
                                    <span class="mdi mdi-circle me-2" style="color: #6e7cd8;"></span>
                                    <span class="title">Accepted</span><br>
                                    <p class="pt-3 sub-title" id="accepted_orders"></p>
                                </div>
                                <div class="col-md-3 p-0">
                                    <span class="mdi mdi-circle me-2" style="color: #94a0f3;"></span>
                                    <span class="title">Cancelled</span><br>
                                    <p class="pt-3 sub-title" id="cancelled_orders"></p>
                                    <hr>
                                    <span class="mdi mdi-circle me-2" style="color: #a9b3f5;"></span>
                                    <span class="title">Total Sales</span><br>
                                    <p class="pt-3 sub-title" id="total_sales"></p>
                                    <hr>
                                    <span class="mdi mdi-circle me-2" style="color: #cbe7f8;"></span>
                                    <span class="title">Commission</span><br>
                                    <p class="pt-3 sub-title" id="total_commission"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-12 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center rounded-top">
                                    <h4 class="card-title mb-0 text-white">Order Overview</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 p-0" style="position: relative;">
                                            <div id="orderChart"></div>
                                            <div class="spinner-border spinner-border-lg text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 p-0">
                                            <span class="mdi mdi-circle me-2" style="color: #5066f7;"></span>
                                            <span class="title">Total Orders</span><br>
                                            <p class="pt-3 sub-title" id="total_orders"></p>
                                            <hr>

                                            <span class="mdi mdi-circle me-2" style="color: #6e7cd8;"></span>
                                            <span class="title">Accepted</span><br>
                                            <p class="pt-3 sub-title" id="accepted_orders"></p>
                                        </div>
                                        <div class="col-md-3 p-0">
                                            <span class="mdi mdi-circle me-2" style="color: #7082f8;"></span>
                                            <span class="title">Completed</span><br>
                                            <p class="pt-3 sub-title" id="completed_orders"></p>
                                            <hr>
                                            <span class="mdi mdi-circle me-2" style="color: #94a0f3;"></span>
                                            <span class="title">Cancelled</span><br>
                                            <p class="pt-3 sub-title" id="cancelled_orders"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 col-12 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center rounded-top">
                                    <h4 class="card-title mb-0 text-white">Sales & Commission Overview</h4>
                                </div>
                                <div class="card-body">
                                    <div id="salesCommissionPieChart" style="height: 350px;"></div>
                                    <div class="row pt-4">
                                        <div class="col-md-6">
                                            <span class="mdi mdi-circle me-2" style="color: #a9b3f5;"></span>
                                            <span class="title">Total Sales</span><br>
                                            <p class="pt-3 sub-title" id="total_sales"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="mdi mdi-circle me-2" style="color: #cbe7f8;"></span>
                                            <span class="title">Commission</span><br>
                                            <p class="pt-3 sub-title" id="total_commission"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display table table-striped table-hover data-table" id="ordersTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Order Details</th>
                                    <th>Order Status</th>
                                    <th>Medicine Details</th>
                                    <th>Commission</th>
                                    <th>Pharmacy City</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<div class="col-lg-12">
        <div class="card ">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Pharmacy Order Statistics</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display table table-striped table-hover" id="perPharmacyTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Pharmacy Name</th>
                                <th>Accepted Orders</th>
                                <th>Placed Orders</th>
                                <th>Cancelled Orders</th>
                                <th>Avg Accept Time (min)</th>
                                {{-- <th>Avg Placed Time (min)</th>
                                    <th>Avg Cancel Time (min)</th> --}}
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

<div class="col-lg-6">
        <div class="card ">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Order Response Time</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display table table-striped table-hover" id="perPharmacyOrderResponseTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Order Id</th>
                                <th>Pharmacy Name</th>
                                <th>Accept Time (min)</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

<div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Top Pharmacy By Orders</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display table table-striped table-hover" id="topPharmaciesTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Pharmacy Name</th>
                                <th>Accepted Orders</th>
                                <th>Completed Orders</th>
                                <th>Avg Delivery Time (min)</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

<div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Top Pharmacies by Repeat Customers</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display table table-striped table-hover" id="repeatOrdersTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Pharmacy Name</th>
                                <th>Repeat Customers</th>
                                <th>Total Repeat Orders</th>
                                <th>Top Repeat Customer</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

        {{-- Modal for viewing details --}}
        <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsLabel"
            aria-hidden="true">
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
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // drop down
        $(function() {
            // Initialize date range picker
            $('#filterDate').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });

            // Apply and clear date range
            $('#filterDate').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(
                    `${picker.startDate.format('YYYY-MM-DD')} to ${picker.endDate.format('YYYY-MM-DD')}`
                );
                table.draw();
                fetchSalesData();
            }).on('cancel.daterangepicker', function() {
                $(this).val('');
                table.draw();
                fetchSalesData();
            });

            // Initialize DataTable
            let table = $('#ordersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('filtered.orders') }}',
                    data: function(d) {
                        d.dateRange = $('#filterDate').val();
                        d.city = $('#filterCity').val();
                        d.pharmacy_id = $('#filterPharmacy').val();
                        d.status = $('#filterStatus').val();
                    }
                },
                columns: [{
                        data: null,
                        className: 'control',
                        orderable: false,
                        searchable: false,
                        defaultContent: ''
                    },
                    {
                        data: null,
                        name: 'serial_no',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'order_id',
                        name: 'order_id'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        className: 'customer-name'
                    },
                    {
                        data: null,
                        name: 'order_details',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const safeDetails = JSON.stringify({
                                date: row.date_raw || '',
                                payment_mode: row.payment_mode || '',
                                delivery_method: row.delivery_method || '',
                                total_price: row.total_price || '',
                                selected_pharmacy_address: row.selected_pharmacy_address ||
                                    '',
                                delivery_address: row.delivery_address || ''
                            }).replace(/'/g, "&#39;");

                            return `
                <a href="#" class="order-details-link btn btn-sm btn-primary" data-details='${safeDetails}'>
                    <i class="mdi mdi-eye"></i> View
                </a>`;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'commission',
                        name: 'commission',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pharmacy_city',
                        name: 'pharmacy_city',
                        orderable: false,
                        searchable: false
                    }
                ],

                columnDefs: [{
                    targets: 0,
                    className: 'control',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                const data = row.data();
                                return 'Details of ' + (data.customer_name || 'Order');
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            return $('<table class="table"><tbody/>').append(
                                $.map(columns, function(col) {
                                    return col.title ?
                                        `<tr><td>${col.title}:</td><td>${col.data}</td></tr>` :
                                        '';
                                }).join('')
                            );
                        }
                    }
                }
            });

            // Filter changes
            $('#filterCity, #filterPharmacy, #filterStatus').on('change', function() {
                table.draw();
                fetchSalesData();
            });

            $('#filterClear').on('click', function() {
                $('#filterDate, #filterCity, #filterPharmacy, #filterStatus').val('');
                table.draw();
                fetchSalesData();
            });

            // Modal for order details
            $('#ordersTable').on('click', '.order-details-link', function(e) {
                e.preventDefault();
                let details = $(this).data('details');
                if (typeof details === 'string') {
                    details = JSON.parse(details.replace(/&#39;/g, "'"));
                }

                const formattedDate = details.date ?
                    new Date(details.date).toLocaleString() :
                    'N/A';

                const html = `
            <p><strong>Date:</strong> ${formattedDate}</p>
            <p><strong>Payment Mode:</strong> ${details.payment_mode}</p>
            <p><strong>Delivery Method:</strong> ${details.delivery_method}</p>
            <p><strong>Total Price:</strong> ₹${details.total_price}</p>
            <p><strong>Pharmacy Address:</strong> ${details.selected_pharmacy_address}</p>
            <p><strong>Delivery Address:</strong> ${details.delivery_address}</p>`;

                $('#orderDetailsModal .modal-body').html(html);
                new bootstrap.Modal(document.getElementById('orderDetailsModal')).show();
            });

            fetchSalesData(); // Initial fetch
        });

        function fetchSalesData() {
            $('.spinner-border').show(); // show

            $.ajax({
                url: '{{ route('orders.salesData') }}',
                type: 'GET',
                data: {
                    dateRange: $('#filterDate').val(),
                    city: $('#filterCity').val(),
                    pharmacy_id: $('#filterPharmacy').val(),
                    status: $('#filterStatus').val(),
                },
                success: function(response) {
                    $('.spinner-border').addClass('d-none'); // hide
                    orderGraph(response);
                    renderSalesCommissionPieChart(response);
                },
                error: function() {
                    $('.spinner-border').addClass('d-none'); // hide on error too
                    console.error("Failed to fetch data.");
                }
            });
        }


        // Order Overview graph 

        // function orderGraph(data) {
        //     var totalOrders = data.total_orders || 0;

        //     // Update summary stats
        //     $('#total_orders').text(totalOrders);
        //     $('#completed_orders').text(data.completed_orders);
        //     $('#cancelled_orders').text(data.cancelled_orders);
        //     $('#accepted_orders').text(data.request_accepted_orders);
        //     $('#total_sales').text('₹' + (data.total_sales || 0));
        //     $('#total_commission').text('₹' + (data.total_commission || 0));

        //     // Prepare chart data with all key metrics
        //     const chartData = [{
        //             name: "Total Orders",
        //             y: 100,
        //             count: totalOrders,
        //             color: '#4B91D3'
        //         },
        //         {
        //             name: "Completed",
        //             y: totalOrders ? Math.round((data.completed_orders / totalOrders) * 100) : 0,
        //             count: data.completed_orders || 0,
        //             color: '#66A9E0'
        //         },
        //         {
        //             name: "Accepted",
        //             y: totalOrders ? Math.round((data.request_accepted_orders / totalOrders) * 100) : 0,
        //             count: data.request_accepted_orders || 0,
        //             color: '#85BFF0'
        //         },
        //         {
        //             name: "Cancelled",
        //             y: totalOrders ? Math.round((data.cancelled_orders / totalOrders) * 100) : 0,
        //             count: data.cancelled_orders || 0,
        //             color: '#A5D2F5'
        //         },
        //         {
        //             name: "Sales ₹",
        //             y: 100,
        //             count: '₹' + (data.total_sales || 0),
        //             color: '#CBE6FA'
        //         },
        //         {
        //             name: "Commission ₹",
        //             y: 100,
        //             count: '₹' + (data.total_commission || 0),
        //             color: '#E5F3FC'
        //         }
        //     ];


        //     // Render polar flat column chart
        //     Highcharts.chart('orderChart', {
        //         chart: {
        //             type: 'column',
        //             inverted: true,
        //             polar: true,
        //             backgroundColor: 'transparent'
        //         },
        //         title: {
        //             text: ''
        //         },
        //         pane: {
        //             innerSize: '20%',
        //             endAngle: 270
        //         },
        //         tooltip: {
        //             pointFormat: '<b>{point.count}</b>'
        //         },

        //         xAxis: {
        //             categories: ['Total Orders', 'Completed', 'Accepted', 'Cancelled',
        //                 'Sales ₹', 'Commission ₹'
        //             ],
        //             tickInterval: 1,
        //             labels: {
        //                 align: 'right',
        //                 step: 1,
        //                 style: {
        //                     fontSize: '13px'
        //                 }
        //             },
        //             lineWidth: 0,
        //             gridLineWidth: 0,
        //         },
        //         yAxis: {
        //             visible: false
        //         },
        //         plotOptions: {
        //             column: {
        //                 stacking: 'normal',
        //                 borderWidth: 0,
        //                 pointPadding: 0,
        //                 groupPadding: 0,
        //                 borderRadius: 0
        //             }
        //         },
        //         // colors: ['#FFF7E3', '#FCE9B8', '#F0D285', '#E1B369', '#A27835', '#FFE4B5'],
        //         series: [{
        //             showInLegend: false,
        //             data: chartData,
        //             dataLabels: {
        //                 enabled: true,
        //                 useHTML: true,
        //                 format: '{y}%',
        //                 align: 'center',
        //                 inside: false,
        //                 style: {
        //                     fontSize: '14px',
        //                     color: '#000'
        //                 }
        //             }
        //         }]
        //     });
        // }

        // Order Overview graph 

        function orderGraph(data) {
            var totalOrders = data.total_orders || 0;

            // Update summary stats
            $('#total_orders').text(totalOrders);
            $('#completed_orders').text(data.completed_orders);
            $('#cancelled_orders').text(data.cancelled_orders);
            $('#accepted_orders').text(data.request_accepted_orders);
            $('#total_sales').text('₹' + (data.total_sales || 0));
            $('#total_commission').text('₹' + (data.total_commission || 0));

            // Prepare chart data (excluding Sales and Commission)
            const chartData = [{
                    name: "Total Orders",
                    y: 100,
                    count: totalOrders,
                    color: '#4B91D3'
                },
                {
                    name: "Completed",
                    y: totalOrders ? Math.round((data.completed_orders / totalOrders) * 100) : 0,
                    count: data.completed_orders || 0,
                    color: '#66A9E0'
                },
                {
                    name: "Accepted",
                    y: totalOrders ? Math.round((data.request_accepted_orders / totalOrders) * 100) : 0,
                    count: data.request_accepted_orders || 0,
                    color: '#85BFF0'
                },
                {
                    name: "Cancelled",
                    y: totalOrders ? Math.round((data.cancelled_orders / totalOrders) * 100) : 0,
                    count: data.cancelled_orders || 0,
                    color: '#A5D2F5'
                }
            ];

            // Render polar flat column chart
            Highcharts.chart('orderChart', {
                chart: {
                    type: 'column',
                    inverted: true,
                    polar: true,
                    backgroundColor: 'transparent'
                },
                title: {
                    text: ''
                },
                pane: {
                    innerSize: '20%',
                    endAngle: 270
                },
                tooltip: {
                    pointFormat: '<b>{point.count}</b>'
                },
                xAxis: {
                    categories: ['Total Orders', 'Completed', 'Accepted', 'Cancelled'],
                    tickInterval: 1,
                    labels: {
                        align: 'right',
                        step: 1,
                        style: {
                            fontSize: '13px'
                        }
                    },
                    lineWidth: 0,
                    gridLineWidth: 0,
                },
                yAxis: {
                    visible: false
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        borderWidth: 0,
                        pointPadding: 0,
                        groupPadding: 0,
                        borderRadius: 0
                    }
                },
                series: [{
                    showInLegend: false,
                    data: chartData,
                    dataLabels: {
                        enabled: true,
                        useHTML: true,
                        format: '{y}%',
                        align: 'center',
                        inside: false,
                        style: {
                            fontSize: '14px',
                            color: '#000'
                        }
                    }
                }]
            });
        }

        // Sales & Commission Overview graph 

        function renderSalesCommissionPieChart(data) {
            const totalSales = parseFloat(data.total_sales || 0);
            const totalCommission = parseFloat(data.total_commission || 0);

            Highcharts.chart('salesCommissionPieChart', {
                chart: {
                    type: 'pie',
                    backgroundColor: 'transparent'
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '<b>{point.name}</b>: ₹{point.y:.2f} ({point.percentage:.1f}%)'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            distance: 30,
                            connectorShape: 'crookedLine',
                            connectorColor: '#666', // Optional: line color
                            format: '<b>{point.name}</b>: ₹{point.y:.2f}',
                            style: {
                                fontSize: '14px',
                                color: '#000' // 🔴 This makes the label text black
                            }
                        },
                        showInLegend: false
                    }
                },
                series: [{
                    name: 'Amount',
                    colorByPoint: true,
                    data: [{
                            name: 'Total Sales',
                            y: totalSales,
                            color: '#A9B3F5'
                        },
                        {
                            name: 'Commission',
                            y: totalCommission,
                            color: '#CBE7F8'
                        }
                    ]
                }]
            });
        }


        // Pharmacy Order Statistics

        $(document).ready(function() {
            $('#perPharmacyTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('orders.stats') }}",
                columns: [{ // Responsive control column
                        data: null,
                        defaultContent: '',
                        orderable: false,
                        searchable: false
                    },
                    { // Serial number
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pharmacy_name',
                        name: 'pharmacy_name'
                    },
                    {
                        data: 'accepted_orders',
                        name: 'accepted_orders'
                    },
                    {
                        data: 'placed_orders',
                        name: 'placed_orders'
                    },
                    {
                        data: 'cancelled_orders',
                        name: 'cancelled_orders'
                    },
                    {
                        data: 'avg_accept_time',
                        name: 'avg_accept_time'
                    }
                ],
                columnDefs: [{
                    targets: 0, // control column
                    className: 'control'
                }],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Details of ' + data['pharmacy_name'];
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col) {
                                return col.title !== '' ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                                    col.columnIndex + '"><td>' + col.title + ':</td> <td>' + col
                                    .data + '</td></tr>' : '';
                            }).join('');
                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }
            });
        });


        // Order Response Time
        $(document).ready(function() {
            $('#perPharmacyOrderResponseTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('orders.response') }}",
                columns: [{ // 👇 for responsive control column (matches <th></th>)
                        data: null,
                        defaultContent: '',
                        orderable: false,
                        searchable: false
                    },
                    { // 👇 this is your actual serial number (#)
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'order_id',
                        name: 'order_id'
                    },
                    {
                        data: 'pharmacy_name',
                        name: 'pharmacy_name'
                    },
                    {
                        data: 'order_response_time',
                        name: 'order_response_time'
                    }
                ],
                columnDefs: [{
                    targets: 0, // 👈 responsive control column
                    className: 'control',
                    orderable: false,
                    searchable: false
                }],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Details of ' + data['pharmacy_name'];
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col) {
                                return col.title !== '' ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                                    col.columnIndex + '"><td>' +
                                    col.title + ':</td> <td>' + col.data + '</td></tr>' : '';
                            }).join('');
                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }
            });
        });


        // Top Pharmacy By Orders

        $(document).ready(function() {
            $('#topPharmaciesTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('orders.top') }}",
                columns: [{
                        data: null,
                        defaultContent: '',
                        orderable: false,
                        searchable: false
                    }, // control column
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, // serial #
                    {
                        data: 'pharmacy_name',
                        name: 'pharmacy_name'
                    },
                    {
                        data: 'accepted_orders',
                        name: 'accepted_orders'
                    },
                    {
                        data: 'completed_orders',
                        name: 'completed_orders'
                    },
                    {
                        data: 'avg_delivery_time',
                        name: 'avg_delivery_time'
                    }
                ],
                columnDefs: [{
                    targets: 0, // control column
                    className: 'control'
                }],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Details of ' + data['pharmacy_name'];
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col) {
                                return col.title !== '' ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                                    col.columnIndex + '"><td>' + col.title + ':</td> <td>' + col
                                    .data + '</td></tr>' :
                                    '';
                            }).join('');
                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }
            });
        });



        // Top Pharmacies by Repeat Customers

        $(document).ready(function() {
            $('#repeatOrdersTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('orders.repeat') }}",
                columns: [{
                        data: null,
                        defaultContent: '',
                        orderable: false,
                        searchable: false
                    }, // responsive control column
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, // serial number
                    {
                        data: 'pharmacy_name',
                        name: 'pharmacy_name'
                    },
                    {
                        data: 'repeat_customers',
                        name: 'repeat_customers'
                    },
                    {
                        data: 'total_repeat_orders',
                        name: 'total_repeat_orders'
                    },
                    {
                        data: 'top_repeat_customer',
                        name: 'top_repeat_customer'
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    className: 'control'
                }],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Details of ' + data['pharmacy_name'];
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.title !== '' ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                                    col.columnIndex + '"><td>' +
                                    col.title + ':</td> <td>' + col.data + '</td></tr>' :
                                    '';
                            }).join('');
                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }
            });
        });
    </script>
@endsection
