@extends('layouts.app')
@section('title', 'Janaushadhi Listing')
@section('styles')
    <style>
    </style>
@endsection
@section('content')
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center rounded-top">
            <h4 class="card-title mb-0 text-white">Janaushadhi Kendra Medicine</h4>
            <div>
                <form action="{{ route('janaushadhi.import') }}" id="importForm" method="POST" enctype="multipart/form-data"
                    class="d-flex gap-2" data-parsley-validate>

                    @csrf
                    <div class="error-msg">

                        <input type="file" name="file" class="form-control" id="file" accept=".xlsx, .xls, .csv"
                            required data-parsley-required-message="Please select a valid file to import."
                            data-parsley-fileextension="xlsx,xls,csv">
                    </div>
                    <div>

                        <button type="submit" class="btn btn-primary addButton text-nowrap px-5">
                            + Import Janaushadhi
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show mb-0 mt-3" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-striped" id="janaushadhi_table">
                    <div id="overlay"></div>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Action</th>
                            <th>Sr. No.</th>
                            <th>drug code</th>
                            <th>generic name</th>
                            <th>unit size</th>
                            <th>mrp</th>
                            <th>group name</th>
                            {{-- <th>Date</th> --}}
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/parsley.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            fill_datatable();

            $("#overlay").show();

            function fill_datatable(name = '', id = '', created_at = '') {
                var dataTable = $('#janaushadhi_table').DataTable({
                    searching: true,
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    lengthMenu: [10, 25, 50, 100, 1000, 10000],
                    ajax: {
                        url: "{{ route('janaushadhi.index') }}",
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'drug_code',
                            name: 'drug_code'
                        },
                        {
                            data: 'generic_name',
                            name: 'generic_name'
                        },
                        {
                            data: 'unit_size',
                            name: 'unit_size'
                        },
                        {
                            data: 'mrp',
                            name: 'mrp'
                        },
                        {
                            data: 'group_name',
                            name: 'group_name'
                        },
                        /* {
                            data: 'created_at',
                            name: 'created_at'
                        }, */
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
                            targets: [0, 2, 3],
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

                let debounceTimer;
                $(".dataTables_filter input").off('input keyup').on("keyup", function(e) {
                    var searchTerm = this.value;
                    clearTimeout(debounceTimer);

                    if (searchTerm === "") {
                        dataTable.search("").draw();
                    } else {
                        debounceTimer = setTimeout(function() {
                            dataTable.search(searchTerm).draw();
                        }, 500);
                    }
                });
            }

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);

        });

        function deleteJanaushadhi(id, name) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete " + name + ".",
                icon: 'warning',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, Please!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-outline-secondary waves-effect'
                },
                buttonsStyling: false
            }).then(function(result) {

                if (result.value) {
                    $.ajax({
                        url: 'janaushadhi/delete/' + id,
                        type: "get"
                    }).done(function(data) {
                        if (!data.status) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cancelled!',
                                text: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect'
                                }
                            });
                            $('#janaushadhi_table').DataTable().ajax.reload();
                        }
                    }).fail(function(jqXHR, ajaxOptions, thrownError) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cancelled!',
                            text: 'Something wrong.',
                            customClass: {
                                confirmButton: 'btn btn-primary waves-effect'
                            }
                        });
                    })
                } else {
                    Swal.fire({
                        title: 'Cancelled!',
                        text: 'Record is safe',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    });
                }
            });
        }
    </script>
@endsection
