@extends('layouts.app')
@section('content')
<div class="container">
  <div class="page-inner">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card shadow">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Users</h4>
            <a href="{{ route('user.create') }}" class="btn btn-primary addButton">+ Add User</a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="display table table-striped table-hover " id="usersTable">
                <thead >
                  <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody> <!-- Filled by DataTables -->
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
<!-- DataTables Scripts -->
<script>
$(document).ready(function () {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Delete with SweetAlert confirmation
    $(document).on('click', '.btn-delete-user', function(e) {
        e.preventDefault();
        let button = $(this);
        let url = button.data('url');

        Swal.fire({
            title: "Are you sure?",
            text: "This user will be deleted permanently!",
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
                            table.ajax.reload();
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message || 'User has been deleted.',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Something went wrong!', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });
});
</script>

@endsection
