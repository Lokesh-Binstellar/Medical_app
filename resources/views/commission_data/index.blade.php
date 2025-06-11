@extends('layouts.app')
@section('style')
    <style>
        td.dt-control {
            text-align: center;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header rounded-top">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0  text-white">Commission Data </h4>
                <a href="{{ route('commission_data.create') }}" class="btn btn-primary text-white  addButton ">+
                    Add
                    Commission Data</a>
            </div>
        </div>
        <div class="card-body">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-striped data-table">
                    <div id="overlay"></div>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Id</th>
                            <th style="width: 10%">Action</th>
                            <th>commonAmount</th>
                            <th>gstRate</th>
                            <th>commissionBelowAmount(with gst)</th>
                            <th>commissionAboveAmount(with gst)</th>
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
                ajax: {
                    url: "{{ route('commission_data.index') }}",
                    data: {
                        themeCategoryId: $('#themeCategoryId').val(),
                    }
                },
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
                        data: 'commonAmount',
                        name: 'commonAmount'
                    },
                    {
                        data: 'gstRate',
                        name: 'gstRate'
                    },
                    {
                        data: 'commissionBelowAmount',
                        name: 'commissionBelowAmount'
                    },
                    {
                        data: 'commissionAboveAmount',
                        name: 'commissionAboveAmount'
                    },

                ],
                columnDefs: [{
                        // For Responsive
                        className: 'control',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                        targets: 0,
                        render: function() {
                            return '';
                        }
                    }, { // Ensure "Action" appears first in mobile
                        targets: 1,
                        responsivePriority: 3
                    },
                    { // Ensure "Name" appears second in mobile
                        targets: 3,
                        responsivePriority: 2
                    },
                    { // Reduce priority for other columns (they appear after Action & Name in mobile)
                        targets: [0, 2, 4, 5, 6],
                        responsivePriority: 99
                    }
                ],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Details of ' + data['name'];
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.title !==
                                    '' // ? Do not show row in modal popup if title is blank (for check box)
                                    ?
                                    '<tr data-dt-row="' +
                                    col.rowIndex +
                                    '" data-dt-column="' +
                                    col.columnIndex +
                                    '">' +
                                    '<td>' +
                                    col.title +
                                    ':' +
                                    '</td> ' +
                                    '<td>' +
                                    col.data +
                                    '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                },
                fnInitComplete: function() {
                    $("#overlay").hide();
                },
            });

        });
        // SweetAlert2 delete confirmation
        // $(document).on('click', '.btn-delete-laboratory', function() {
        //     let button = $(this);
        //     let url = button.data('url');

        //     Swal.fire({
        //         title: "Are you sure?",
        //         text: "This laboratory will be deleted permanently!",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonText: "Yes, delete it!",
        //         cancelButtonText: "Cancel",
        //         customClass: {
        //             confirmButton: 'btn btn-danger me-2',
        //             cancelButton: 'btn btn-secondary'
        //         },
        //         buttonsStyling: false
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 url: url,
        //                 type: 'POST',
        //                 data: {
        //                     _method: 'DELETE',
        //                     _token: '{{ csrf_token() }}'
        //                 },
        //                 success: function(response) {
        //                     if (response.status) {
        //                         table.ajax.reload();
        //                         Swal.fire({
        //                             title: 'Deleted!',
        //                             text: response.message ||
        //                                 'Laboratory deleted successfully.',
        //                             icon: 'success',
        //                             timer: 1500,
        //                             showConfirmButton: false
        //                         });
        //                     } else {
        //                         Swal.fire('Error', response.message ||
        //                             'Something went wrong!', 'error');
        //                     }
        //                 },
        //                 error: function(xhr) {
        //                     Swal.fire('Error', 'Could not delete laboratory.',
        //                         'error');
        //                 }
        //             });
        //         }
        //     });
        // });
    </script>
@endsection
