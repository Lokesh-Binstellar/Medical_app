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
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'pharmacy_name', name: 'pharmacy_name' },
            { data: 'owner_name', name: 'owner_name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // SweetAlert delete confirmation
    $(document).on('click', '.btn-delete-pharmacist', function() {
        let button = $(this);
        let url = button.data('url');

        Swal.fire({
            title: "Are you sure?",
            text: "This pharmacist will be deleted permanently!",
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
                                text: response.message || 'Pharmacist deleted successfully.',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Something went wrong!', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Could not delete pharmacist.', 'error');
                    }
                });
            }
        });
    });
});
</script>

@endsection
