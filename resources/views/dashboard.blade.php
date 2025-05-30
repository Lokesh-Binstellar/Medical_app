@extends('layouts.app')

@section('content')
    <div class="page-inner">
        @php
            use App\Models\Permission;
            use App\Models\User;

            $loggedInUser = Auth::user();
            $permissions = [];
            $roleId = User::where('id', Auth::user()->id)->value('role_id');
            $data = Permission::where('role_id', $roleId)->pluck('module', 'id')->toArray();
            $permissions = array_unique($data);

            $isSuperAdmin = $loggedInUser->role_id == 1 ? 1 : 0;
        @endphp

        <div class="row dassbord">
            <div class="col-lg-9">
                <div class="card h-100">
                    <div class="card-header-das">
                        <div class="d-flex justify-content-between">
                            <h4 class="mb-2">Users Overview</h4>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="me-2">Total {{ $totalUsers }} Users</small>

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
                                    <h4 class="mb-3 mb-lg-2 mb-xl-3 text-nowrap">Average Rating</h4>
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
                ajax: "{{ route('dashboard.dasindex') }}",
                columns: [ {data: 'id', name:'id'},{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name', orderable: false },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role', orderable: false },

                ]
            });

        });
    </script>
@endsection
