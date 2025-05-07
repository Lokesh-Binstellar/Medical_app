@extends('layouts.app')
@section('content')
    <div class="container ">
        <div class="page-inner px-0">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center rounded-top">
                            <h4 class="card-title mb-0 text-white">Pharmacy</h4>
                            {{-- <a href="{{ route('pharmacist.create') }}" class="btn btn-primary">Create Pharmacist</a> --}}
                            @php
                                $chk = \App\Models\Permission::checkCRUDPermissionToUser('Pharmacies', 'create');
                                // dd($chk);
                                if ($chk) {
                                    echo '<div class="col-sm-12 col-md-6 col-lg-6 text-end "><a href="' .
                                        route('pharmacist.create') .
                                        '" class="btn btn-primary addButton">+ Add Pharmacy </a></div>';
                                }
                            @endphp
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover data-table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>pharmacy name</th>
                                            <th>owner name</th>
                                            <th>email </th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pharmacist.index') }}",
                columns: [{
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
                        data: 'owner_name',
                        name: 'owner_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Delete  function
            window.deleteUser = function(id) {
                if (confirm('Are you sure you want to delete this pharmacist?')) {
                    $.ajax({
                        url: '{{ route('pharmacist.destroy', '') }}/' + id, // Dynamic URL with ID
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}', // CSRF token
                        },
                        success: function(response) {
                            alert('Pharmacist deleted successfully!');
                            table.ajax.reload(); // Refresh the table to reflect the changes
                        },
                        error: function(xhr, status, error) {
                            alert('Error: ' + error);
                        }
                    });
                }
            }

        });
    </script>
@endsection
