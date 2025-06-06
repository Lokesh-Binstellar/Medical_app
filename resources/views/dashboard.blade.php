@extends('layouts.app')


@section('styles')
    <style>
        #commissionFilterDateRange {
            background: #ffffff;
            border-radius: 10px;
            cursor: pointer;
            border: 1px solid #d8d8dd;
            height: 42px;
            padding: 0 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
            font-size: 18px;
            font-weight: 500;
        }

        #salesFilterDateRange {
            background: #ffffff;
            border-radius: 10px;
            cursor: pointer;
            border: 1px solid #d8d8dd;
            height: 42px;
            padding: 0 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
            font-size: 18px;
            font-weight: 500;
        }
    </style>
@endsection
@section('content')
    <div class="page-inner">
        {{-- @php
            use App\Models\Permission;
            use App\Models\User;

            $loggedInUser = Auth::user();
            $permissions = [];
            $roleId = User::where('id', Auth::user()->id)->value('role_id');
            $data = Permission::where('role_id', $roleId)->pluck('module', 'id')->toArray();
            $permissions = array_unique($data);

            $isSuperAdmin = $loggedInUser->role_id == 1 ? 1 : 0;
        @endphp --}}



        @if (auth()->user()->role_id == 1)
            <div class="row ">
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Users Overview</h4>

                            <h5 class="m-0">Total {{ $totalUsers }} Users</h5>

                        </div>

                        <div class="card-body d-flex justify-content-between flex-wrap gap-3 dassbord">
                            <div class="d-flex gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-info rounded">
                                        <i class="mdi mdi-shield-account-outline mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h4 class="mb-0">{{ $totalAdmins }}</h4>
                                    <small>Total Admin</small>
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-success rounded">
                                        <i class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h4 class="mb-0">{{ $totalCustomers }}</h4>
                                    <small>Total Customers</small>
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-warning rounded">
                                        <i class="mdi mdi-hospital-building mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h4 class="mb-0">{{ $totalPharmacies }}</h4>
                                    <small>Total Pharmacies</small>
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-danger rounded">
                                        <i class="mdi mdi-microscope mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h4 class="mb-0">{{ $totalLabs }}</h4>
                                    <small>Total Laboratories</small>
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-secondary rounded">
                                        <i class="mdi mdi-truck-delivery-outline mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h4 class="mb-0">{{ $totalDelivery }}</h4>
                                    <small>Total Delivery Persons</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card h-100">
                        <div class="row dassbord">
                            <div class="col-6">
                                <div class="card-body">
                                    <div class="card-info mb-3 py-2 mb-lg-1 mb-xl-3">
                                        <h4 class="mb-3 mb-lg-2 mb-xl-3 text-nowrap">Average App Rating</h4>
                                        <div class="badge bg-label-primary rounded-pill lh-xs">All Time</div>
                                    </div>
                                    <div class="d-flex align-items-end flex-wrap gap-1">
                                        <h4 class="mb-0">{{ $averageRating ?? '0.00' }}</h4>
                                        <small class="text-success">Average</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-end d-flex align-items-end justify-content-center">
                                <div class="card-body pb-0 pt-3 position-absolute bottom-0">
                                    <img src="{{ asset('assets/img/artingdasmg.png') }}" alt="Ratings" width="95">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6">
                    <div class="card overflow-hidden h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">User Data</h4>
                        </div>
                        <div class="card-body dassbord">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover " id="usersTable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>id</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6">
                    <div class="card overflow-hidden h-100">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Average Pharmacy/Laboratory Rating</h4>
                        </div>
                        <div class="card-body">

                            <!-- Dropdown to select Pharmacy or Laboratory -->
                            <ul class="nav nav-tabs mb-3" id="ratingTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pharmacy-tab" data-type="Pharmacy" type="button"
                                        role="tab">Pharmacy</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="laboratory-tab" data-type="Laboratory" type="button"
                                        role="tab">Laboratory</button>
                                </li>
                            </ul>

                            <!-- Ratings Table -->
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover" id="ratingTable">
                                    <thead>
                                        <tr>
                                            <th></th> <!-- For responsive control icon -->
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Average Rating</th>
                                            <th>Total User Ratings</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card overflow-hidden h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Top Pharmacies</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover "id="topPharmaciesTable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>ID</th>
                                            <th>Pharmacies Name</th>
                                            <th>total orders accepted</th>
                                            <th>total orders completed</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        @if (auth()->user()->role_id == 2)
            <div class="row g-6 mb-6 ">

                {{-- <div class="col-lg-3 col-sm-6">
                    <div class="card ">

                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h4 class="mb-1">Sales Overview</h4>

                            </div>
                            <div class="d-flex align-items-center card-subtitl-das">
                                <small class="me-2">Total ₹{{ number_format($salesData->total_sales, 2 ?? 0.0) }}
                                    Sales</small>

                            </div>
                        </div>

                        <div class="card-body d-flex justify-content-between flex-wrap gap-4 dassbord">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $salesData->total_customers ?? 0 }}</h5>
                                    <p class="mb-0">New Customers</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> --}}


                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-1">Sales Overview</h5>
                            </div>
                        </div>
                        <div class="card-body d-flex justify-content-between flex-wrap gap-4 dassbord">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-account-star-outline icon-24px"></i>

                                    </div>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $salesData->total_customers ?? 0 }}</h5>
                                    <p class="mb-0">New Customers</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-warning rounded">
                                        <i class="mdi mdi-chart-pie-outline icon-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0"> ₹{{ number_format($salesData->total_sales, 2 ?? 0.0) }}</h5>
                                    <p class="mb-0">Total Sales</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-info rounded">
                                        <i class="mdi mdi-swap-horizontal icon-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">₹{{ number_format($commissionData->total_commission, 2 ?? 0.0) }}
                                    </h5>
                                    <p class="mb-0">Total Commission </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Sales This Month</h5>
                        </div>
                        <div class="card-body dassbord">
                            <div class="card-info mb-4">
                                <p class="mb-0">Total Sales This Month</p>
                                <h5 class="mb-0">₹{{ number_format($totalSalesForMonth, 2) }}
                                </h5>
                            </div>
                            {{-- <div id="saleThisMonth" style="min-height: 100px;"></div> --}}
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-sm-6">
                    <div class="card h-100">
                        <div class="row">
                            <div class="col-6">
                                <div class="card-body dassbord">
                                    <div class="card-info mb-3 py-2 mb-lg-1 mb-xl-3">
                                        <h4 class="mb-3 mb-lg-2 mb-xl-3 text-nowrap">Pharmacy Rating</h4>
                                        <div class="badge bg-label-primary rounded-pill lh-xs">
                                            {{ $ratingPharma->total_viewers ?? 0 }} Reviews
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end flex-wrap gap-1">
                                        <h4 class="mb-0">
                                            {{ number_format($ratingPharma->total_rating, 2 ?? 0.0) }}
                                        </h4>
                                        <small class="text-success">Average</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-end d-flex align-items-end justify-content-center">
                                <div class="card-body pb-0 pt-3 position-absolute bottom-0">
                                    <img src="{{ asset('assets/img/artingdasmg.png') }}" alt="Ratings" width="95">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6 col-md-12 col-12 mb-4 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between py-2 pe-2">
                            <h5 class="card-title m-0 me-2 text-secondary">Commission This Month</h5>

                            <div id="commissionFilterDateRange" class="form-floating form-floating-outline"
                                style="cursor:pointer;">
                                <span class="text-primary"></span>
                                <i class="mdi mdi-calendar text-primary"></i>
                            </div>

                            <input type="hidden" name="commission_start_date" value="" id="commission_start_date"
                                readonly>
                            <input type="hidden" name="commission_end_date" value="" id="commission_end_date"
                                readonly>
                        </div>


                        <div class="card-body">
                            <div class="card-info py-3">
                                <p class="mb-0">Total Commission This Month</p>
                                <h5 id="commissionText" class="mb-0">₹0.00</h5>
                            </div>
                            <div class="">
                                <canvas id="commissionChart" height="130"></canvas>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-lg-6 col-md-12 col-12 mb-4 mb-lg-0">
                    <div class="card ">
                        <div class="card-header d-flex align-items-center justify-content-between py-2 pe-2">
                            <h5 class="card-title m-0 me-2 text-secondary">Sales This Month</h5>

                            <div id="salesFilterDateRange" class="form-floating form-floating-outline"
                                style="cursor:pointer;">
                                <span class="text-primary"></span>
                                <i class="mdi mdi-calendar text-primary"></i>
                            </div>

                            <input type="hidden" name="sales_start_date" id="sales_start_date" readonly>
                            <input type="hidden" name="sales_end_date" id="sales_end_date" readonly>
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
                </div>


                <div class="col-lg-6">
                    <div class="card overflow-hidden h-100">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Recent Orders (Today's orders)</h4>
                        </div>

                        <div class="card-body dassbord">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover " id="customerDetailsTable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Order id</th>
                                            <th>Customer Details</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6">
                    <div class="card overflow-hidden">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Pending Quotes ( <span id="quoteCount">0</span> )</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover "id="pendingQuotesTable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>ID</th>
                                            <th>Customer Details</th>
                                            <th>Status</th>
                                            <th>Requested Date</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        @endif
    </div>
@endsection


@section('scripts')
    <script>
        // admin User data 
        $(document).ready(function() {
            var table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dashboard') }}",
                columns: [{
                        data: 'id'
                    }, {
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: false
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role',
                        orderable: false
                    },

                ],
                columnDefs: [{
                    // For Responsive
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 1,
                    targets: 0,
                    render: function(data, type, full, meta) {
                        return '';
                    }
                }],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Details of ' + data['name'];
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.title !==
                                    '' // ? Do not show row in modal popup if title is blank (for check box)
                                    ?
                                    '<tr data-dt-row="' +
                                    col.rowIndex +
                                    '" data-dt-column="' +
                                    col.columnIndex +
                                    '">' +
                                    '<td>' +
                                    col.title +
                                    ':' +
                                    '</td> ' +
                                    '<td>' +
                                    col.data +
                                    '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }

            });

        });


        // topPharmaciesTable
        $(document).ready(function() {
            var table = $('#topPharmaciesTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: "/top-pharmacies", // adjust route if needed
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'pharmacy_name',
                        name: 'pharmacy_name',
                        orderable: false
                    },
                    {
                        data: 'total_accepted',
                        name: 'total_accepted'
                    },
                    {
                        data: 'total_completed',
                        name: 'total_completed'
                    }
                ],
                columnDefs: [{
                    // For Responsive (optional first col blank)
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 1,
                    targets: 0,
                    render: function(data, type, full, meta) {
                        return '';
                    }
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
                                    col.columnIndex + '">' +
                                    '<td>' + col.title + ':</td>' +
                                    '<td>' + col.data + '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }
            });
        });


        // Average Pharmacy/Laboratory Rating
        let ratingTable;

        $(document).ready(function() {
            let selectedType = 'Pharmacy'; // default tab

            // Initialize DataTable
            ratingTable = $('#ratingTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/fetch-ratings',
                    data: function(d) {
                        d.type = selectedType;
                    }
                },
                columns: [{
                        data: null,
                        defaultContent: '',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'rating',
                        name: 'rating',
                        render: function(data, type, row) {
                            if (data === null || data === undefined) return 'N/A';
                            return renderStars(parseFloat(data));
                        }
                    },
                    {
                        data: 'total_ratings',
                        name: 'total_ratings'
                    }
                ],
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 1,
                    targets: 0,
                    render: function(data, type, full, meta) {
                        return '';
                    }
                }],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Details of ' + data['name'];
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.title !== '' ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                                    col.columnIndex + '">' +
                                    '<td>' + col.title + ':</td><td>' + col.data +
                                    '</td></tr>' :
                                    '';
                            }).join('');
                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }
            });

            // Handle tab switching
            $('#ratingTabs button').on('click', function() {
                $('#ratingTabs button').removeClass('active');
                $(this).addClass('active');

                selectedType = $(this).data('type');
                ratingTable.ajax.reload(); // Reload DataTable with new type
            });
        });


        function renderStars(rating) {
            let displayRating = rating ? parseFloat(rating).toFixed(1) : "0.0";

            if (!rating || rating <= 0) {
                return '<i class="far fa-star" style="color:gold;"></i> '.repeat(5) + ` (${displayRating})`;
            }

            let fullStars = Math.floor(rating);
            let halfStar = (rating - fullStars) >= 0.5 ? 1 : 0;
            let emptyStars = 5 - fullStars - halfStar;

            let starsHtml = '';

            for (let i = 0; i < fullStars; i++) {
                starsHtml += '<i class="fas fa-star" style="color:gold;"></i> ';
            }
            if (halfStar) {
                starsHtml += '<i class="fas fa-star-half-alt" style="color:gold;"></i> ';
            }
            for (let i = 0; i < emptyStars; i++) {
                starsHtml += '<i class="far fa-star" style="color:gold;"></i> ';
            }

            return starsHtml + ` (${displayRating})`;
        }






        // customer orders data 
        $(document).ready(function() {
    $('#customerDetailsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('dashboard.orders.data') }}",
        columns: [
            {
                data: null,
                defaultContent: '',
                orderable: false
            }, // control column for responsive toggle
            {
                data: 'order_id',
                name: 'order_id'
            },
            {
                data: 'name',
                name: 'name',
                orderable: false
            },
            {
                data: 'status',
                name: 'status',
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
        columnDefs: [{
            // For Responsive (first col is blank control)
            targets: 0,
            className: 'control',
            orderable: false,
            searchable: false,
            responsivePriority: 1,
            render: function(data, type, full, meta) {
                return '';
            }
        }],
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details of ' + (data.pharmacy_name || data.name || 'Customer');
                    }
                }),
                type: 'column',
                renderer: function(api, rowIdx, columns) {
                    var data = $.map(columns, function(col, i) {
                        return col.title !== '' ?
                            '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                            col.columnIndex + '">' +
                            '<td>' + col.title + ':</td>' +
                            '<td>' + col.data + '</td>' +
                            '</tr>' : '';
                    }).join('');

                    return data ? $('<table class="table"/><tbody />').append(data) : false;
                }
            }
        }
    });
});


        $(document).ready(function() {
            var table = $('#pendingQuotesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pending.quotes') }}",
                columns: [{
                        data: null,
                        defaultContent: '',
                        className: 'control',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer_details',
                        name: 'customer_details',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 1,
                    targets: 0,
                    render: function(data, type, full, meta) {
                        return '';
                    }
                }],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Details of ' + (data.customer_details || 'N/A');
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.title !== '' ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                                    col.columnIndex + '">' +
                                    '<td>' + col.title + ':</td>' +
                                    '<td>' + col.data + '</td>' +
                                    '</tr>' : '';
                            }).join('');

                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                },
                drawCallback: function(settings) {
                    $('#quoteCount').text(settings._iRecordsTotal); // Set total pending quotes count
                }
            });
        });




        // Commission This Month 
        let commissionChart; // chart instance

        function renderCommissionChart(data) {
            const ctx = document.getElementById('commissionChart').getContext('2d');

            const labels = data.map(item => item.label);
            const values = data.map(item => item.value);

            // Get index of highest value to highlight
            const maxIndex = values.indexOf(Math.max(...values));

            // Generate background colors (highlight max bar)
            const backgroundColors = values.map((_, index) =>
                index === maxIndex ? '#033a62' : 'rgba(115, 103, 240, 0.2)'
            );

            if (commissionChart) {
                commissionChart.destroy();
            }

            commissionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '', // remove label to match clean look
                        data: values,
                        backgroundColor: backgroundColors,
                        borderRadius: 10, // rounded bars
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6E6B7B',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        y: {
                            display: true, // ✅ show y-axis
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10,
                                color: '#6E6B7B',
                                font: {
                                    size: 14
                                },
                                callback: value => `₹${value}` // Optional: add ₹ symbol
                            },
                            grid: {
                                drawBorder: false
                            }
                        }

                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.raw.toLocaleString()} ₹  Commission`
                            }
                        }
                    }
                }
            });
        }

        $(document).ready(function() {
            var start = moment().startOf('month');
            var end = moment().endOf('month');
            commissionDate(start, end); // this already calls fetchcommissionData()

            function commissionDate(start, end) {
                var formattedRange = start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY');
                $('#commissionFilterDateRange span').text(formattedRange);
                $('#commission_start_date').val(start.format('YYYY-MM-DD'));
                $('#commission_end_date').val(end.format('YYYY-MM-DD'));

                fetchcommissionData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            }

            $('#commissionFilterDateRange').daterangepicker({
                startDate: start.format('DD/MM/YYYY'),
                endDate: end.format('DD/MM/YYYY'),
                locale: {
                    format: 'DD/MM/YYYY'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, commissionDate);

            // ❌ Remove this line: it's redundant and has error
            // fetchcommissionData(startDate, endDate);
        });


        function fetchcommissionData(startDate, endDate) {
            $('.spinner-border').show();

            $.get("get-dashboard-graph-data", {
                commission_start_date: startDate,
                commission_end_date: endDate
            }, function(data) {
                $('.spinner-border').hide();

                let commission = parseFloat(data.commissionGraphData.totalCommission) || 0;
                $('#commissionText').text('₹' + commission.toFixed(2));

                if (data.commissionGraphData.chartData.length > 0) {
                    renderCommissionChart(data.commissionGraphData.chartData);
                }
            });
        }




        // Sales This Month 
        let salesChart;

        function renderSalesChart(data) {
            const ctx = document.getElementById('salesChart').getContext('2d');

            const labels = data.map(item => item.label);
            const values = data.map(item => item.value);

            const maxIndex = values.indexOf(Math.max(...values));

            const backgroundColors = values.map((_, index) =>
                index === maxIndex ? '#033a62' : 'rgba(115, 103, 240, 0.2)'
            );

            if (salesChart) {
                salesChart.destroy();
            }

            salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '',
                        data: values,
                        backgroundColor: backgroundColors,
                        borderRadius: 10,
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6E6B7B',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        y: {
                            display: true,
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10,
                                color: '#6E6B7B',
                                font: {
                                    size: 14
                                },
                                callback: value => `₹${value}`
                            },
                            grid: {
                                drawBorder: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.raw.toLocaleString()} ₹ Sales`
                            }
                        }
                    }
                }
            });
        }

        $(document).ready(function() {
            var start = moment().startOf('month');
            var end = moment().endOf('month');
            salesDate(start, end);

            function salesDate(start, end) {
                var formattedRange = start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY');
                $('#salesFilterDateRange span').text(formattedRange);
                $('#sales_start_date').val(start.format('YYYY-MM-DD'));
                $('#sales_end_date').val(end.format('YYYY-MM-DD'));

                fetchsalesData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            }

            $('#salesFilterDateRange').daterangepicker({
                startDate: start.format('DD/MM/YYYY'),
                endDate: end.format('DD/MM/YYYY'),
                locale: {
                    format: 'DD/MM/YYYY'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, salesDate);

            // Initialize with current month range
            fetchsalesData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });

        function fetchsalesData(startDate, endDate) {
            $('.spinner-border').show();

            $.get("get-dashboard-graph-data", {
                sales_start_date: startDate,
                sales_end_date: endDate
            }, function(data) {
                $('.spinner-border').hide();

                // Safely parse total sales number and chart data
                var salesTotal = parseFloat(data.salesGraphData.totalSales) || 0;
                $('#salesText').text('₹' + salesTotal.toFixed(2));

                if (data.salesGraphData.chartData.length > 0) {
                    renderSalesChart(data.salesGraphData.chartData);
                }
            });
        }



        // document.addEventListener("DOMContentLoaded", function() {
        //     var salesData = {!! json_encode($dailySalesData) !!};

        //     var options = {
        //         chart: {
        //             type: 'line',
        //             height: 100,
        //             sparkline: {
        //                 enabled: true // hides axes
        //             }
        //         },
        //         stroke: {
        //             curve: 'smooth',
        //             width: 3,
        //             colors: ['#4f46e5']
        //         },
        //         series: [{
        //             name: "Daily Sales",
        //             data: salesData
        //         }],
        //         tooltip: {
        //             enabled: true,
        //             // custom tooltip to show day number + sales value
        //             custom: function({
        //                 series,
        //                 seriesIndex,
        //                 dataPointIndex,
        //                 w
        //             }) {
        //                 var day = dataPointIndex + 1;
        //                 var sales = series[seriesIndex][dataPointIndex];
        //                 return `<div style="padding:5px;">
    //                     <strong>Day ${day}</strong><br/>
    //                     Sales: ₹${sales.toFixed(2)}
    //                 </div>`;
        //             }
        //         }
        //     };

        //     var chart = new ApexCharts(document.querySelector("#saleThisMonth"), options);
        //     chart.render();
        // });
    </script>
@endsection
