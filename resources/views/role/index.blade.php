@extends('layouts.app')
@section('content')

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Role Table</h4>
                            <a href="{{ route('roles.create') }}" class="btn addButton btn-primary ">+ Create Role</a>
                        </div>
                       
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover data-table" >
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Role Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

@endsection

@section('scripts')
    <!-- Include jQuery & DataTables JS if not already -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> --}}

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#add-row').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('roles.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // SweetAlert for delete confirmation
        $(document).on('click', '.btn-delete-role', function(e) {
            e.preventDefault();
            let button = $(this);
            let url = button.data('url');
            let rowId = button.data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "This role will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status) {
                                $('#add-row').DataTable().ajax.reload();

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message || 'Role has been deleted.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Error', response.message || 'Something went wrong!', 'error');
                            }
                        }
                    });
                }
            });
        });
    });
</script>

@endsection
