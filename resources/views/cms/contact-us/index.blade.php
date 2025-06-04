@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Contact Us List</h4>
            <a href="{{ route('cms.contact-us.create') }}" class="btn addButton btn-primary">+ Add Contact Us</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="aboutTable" class="display table table-striped table-hover data-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description (Preview)</th>
                            <th>Actions</th>
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
            var table = $('#aboutTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('cms.contact-us.index') }}", // Make sure your controller handles AJAX requests
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        render: function(data, type, row) {
                            // Strip HTML tags and limit to 100 chars
                            var div = document.createElement("div");
                            div.innerHTML = data;
                            var text = div.textContent || div.innerText || "";
                            return text.length > 100 ? text.substr(0, 100) + '...' : text;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });

            // Delete button AJAX handler
            $('#aboutTable').on('click', '.btn-delete', function() {
                var url = $(this).data('url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                Swal.fire(
                                    'Deleted!',
                                    res.message || 'Deleted successfully.',
                                    'success'
                                );
                                table.ajax.reload(null, false);
                            },
                            error: function() {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong while deleting!',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
