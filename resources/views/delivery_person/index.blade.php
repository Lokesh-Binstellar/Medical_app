@extends('layouts.app')
@section('content')
 
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center rounded-top">
                            <h4 class="card-title mb-0 text-white">Delivery Person</h4>
                            @php
                                $chk = \App\Models\Permission::checkCRUDPermissionToUser('DeliveryPerson', 'create');
                                if ($chk) {
                                    echo '<div class="col-sm-12 col-md-6 col-lg-6 text-end ">
                                        <a href="' . route('delivery_person.create') . '" class="btn btn-primary addButton">+ Add Delivery Person </a>
                                        </div>';
                                }
                            @endphp
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover data-table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Delivery Person Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>City</th>
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
<script>
    $(function() {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('delivery_person.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'delivery_person_name', name: 'delivery_person_name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'city', name: 'city' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // SweetAlert delete confirmation
        $(document).on('click', '.btn-delete-delivery', function() {
            let button = $(this);
            let url = button.data('url');

            Swal.fire({
                title: "Are you sure?",
                text: "This delivery person will be deleted permanently!",
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
                                    text: response.message || 'Delivery person deleted successfully.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Error', response.message || 'Something went wrong!', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Could not delete delivery person.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>

@endsection
