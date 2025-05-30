@extends('layouts.app')


@section('styles')
    <style>
        #paymentFilterDateRange {
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
            <div class="row dassbord">
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-header-das">
                            <div class="d-flex justify-content-between">
                                <h4 class="mb-2">Users Overview</h4>
                            </div>
                            <div class="d-flex align-items-center">
                                <h5 class="me-2">Total {{ $totalUsers }} Users</h5>

                            </div>
                        </div>

                        <div class="card-body d-flex justify-content-between flex-wrap gap-3">
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
                        <div class="row">
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

                <div class="col-xl-8 col-md-6">
                    <div class="card overflow-hidden">
                        <div class="card-body">
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

            </div>
        @endif

        @if (auth()->user()->role_id == 2)
            <div class="row g-6 mb-6 dassbord">

                <div class="col-lg-3">
                    <div class="card h-100">
                        <div class="card-header-das">
                            <div class="d-flex justify-content-between">
                                <h4 class="mb-1">Sales Overview</h4>

                            </div>
                            <div class="d-flex align-items-center card-subtitl-das">
                                <small class="me-2">Total ₹{{ number_format($salesData->total_sales, 2 ?? 0.0) }}
                                    Sales</small>
                                {{-- <div class="d-flex align-items-center text-success">
                                    <p class="mb-0 fw-medium">+18%</p>
                                    <i class="icon-base ri ri-arrow-up-s-line"></i>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-body d-flex justify-content-between flex-wrap gap-4">
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
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card h-100">
                        <div class="row">
                            <div class="col-6">
                                <div class="card-body">
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


                <div class="col-lg-5 col-md-12 col-12 mb-4 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-header-das d-flex align-items-center justify-content-between py-2 pe-2">
                            <h5 class="card-title m-0 me-2 text-secondary">Commission This Month</h5>

                            <div id="paymentFilterDateRange" class="form-floating form-floating-outline"
                                style="cursor:pointer;">
                                <span class="text-primary"></span>
                                <i class="mdi mdi-calendar text-primary"></i>
                            </div>

                            <input type="hidden" name="payment_start_date" value="" id="payment_start_date"
                                readonly>
                            <input type="hidden" name="payment_end_date" value="" id="payment_end_date" readonly>
                        </div>


                        <div class="card-body">
                            <div class="card-info">
                                <p class="mb-0">Total Commission This Month</p>
                                <h5 id="commissionText" class="mb-0">₹0.00</h5>
                            </div>
                        </div>

                    </div>
                </div>






            </div>
        @endif
    </div>
@endsection


@section('scripts')
    <!-- DataTables Scripts -->



    <script>
        $(document).ready(function() {
            var table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: {
                    details: {
                        type: 'column',
                        target: 0 // Set the control to column 0
                    }
                },
                columnDefs: [{
                    targets: 0,
                    className: 'control', // Enables the plus icon in this column
                    orderable: false
                }],
                ajax: "{{ route('dashboard') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    }, {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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

                ]
            });

        });




        $(document).ready(function() {
            var start = moment().startOf('month');
            var end = moment().endOf('month');
            paymentDate(start,end);
            function paymentDate(start, end) {
                var formattedRange = start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY');
                $('#paymentFilterDateRange span').text(formattedRange);
                $('#payment_start_date').val(start.format('YYYY-MM-DD'));
                $('#payment_end_date').val(end.format('YYYY-MM-DD'));

                fetchPaymentData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            }

            $('#paymentFilterDateRange').daterangepicker({

                
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
            }, paymentDate);

            // Initialize with current month range
            fetchPaymentData(startDate, endDate);
            

        });

        function fetchPaymentData(startDate, endDate) {
            // Optional: Show loader if you have one
            $('.spinner-border').show();

            $.get("get-dashboard-graph-data", {
                payment_start_date: startDate,
                payment_end_date: endDate
            }, function(data) {
                $('.spinner-border').hide();

                // Safely parse commission number
                var commission = parseFloat(data.paymentGraphData) || 0;
                $('#commissionText').text('₹' + commission.toFixed(2));
            });
        }
    </script>
@endsection
