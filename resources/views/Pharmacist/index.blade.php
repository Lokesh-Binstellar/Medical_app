@extends('layouts.app')
@section('content')

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
                            <div class="card-datatable table-responsive pt-0">
                                <table id="add-row" class="datatables-basic table table-striped data-table">
                                    <div id="overlay"></div>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Id</th>
                                            <th>Action</th>
                                            <th>pharmacy name</th>
                                            <th>owner name</th>
                                            <th>email </th>
                                            <th>Phone</th>
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
                searching: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                lengthMenu: [10, 25, 50, 100, 1000, 10000],
                ajax: "{{ route('pharmacist.index') }}",

                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
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
                    }
                ],

                columnDefs: [{
                        className: 'control',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                        targets: 0,
                        render: function() {
                            return '';
                        }
                    },
                    {
                        targets: 1,
                        responsivePriority: 4
                    }, // Row index
                    {
                        targets: 2,
                        responsivePriority: 1
                    }, // Action
                    {
                        targets: 3,
                        responsivePriority: 2
                    }, // Pharmacy name
                    {
                        targets: 4,
                        responsivePriority: 3
                    }, // Owner name
                    {
                        targets: [5, 6],
                        responsivePriority: 99
                    } // Email, Phone
                ],

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
                            var data = $.map(columns, function(col) {
                                return col.title !== '' ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                                    col.columnIndex + '">' +
                                    '<td>' + col.title + ':</td> ' +
                                    '<td>' + col.data + '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                },

                fnInitComplete: function() {
                    $("#overlay").hide();
                }
            });
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
                                    text: response.message ||
                                        'Pharmacist deleted successfully.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Error', response.message ||
                                    'Something went wrong!', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Could not delete pharmacist.',
                                'error');
                        }
                    });
                }
            });
        });
    </script>
@endsection
