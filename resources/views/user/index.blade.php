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
  $('#usersTable').DataTable({
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
});
</script>
@endsection
