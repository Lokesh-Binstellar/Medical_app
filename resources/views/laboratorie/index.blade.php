@extends('layouts.app')
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <div class="container">
        <div class="page-inner px-0">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header rounded-top">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title mb-0  text-white">Laboratory </h4>
                                <a href="{{ route('laboratorie.create') }}" class="btn btn-primary text-white  addButton ">+ Add
                                    Laboratory</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover data-table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th style="width: 10%">Action</th>
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
        ajax: "{{ route('laboratorie.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'lab_name', name: 'lab_name' },
            { data: 'email', name: 'email' },
            { data: 'address', name: 'address' },
            { data: 'phone', name: 'phone' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    // SweetAlert2 delete confirmation
    $(document).on('click', '.btn-delete-laboratory', function() {
        let button = $(this);
        let url = button.data('url');

        Swal.fire({
            title: "Are you sure?",
            text: "This laboratory will be deleted permanently!",
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
                                text: response.message || 'Laboratory deleted successfully.',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Something went wrong!', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Could not delete laboratory.', 'error');
                    }
                });
            }
        });
    });
});
</script>

@endsection
