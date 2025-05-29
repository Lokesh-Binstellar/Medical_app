@extends('layouts.app')
@section('styles')
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <style>
        /* .select2 {
            width: 300px !important;
        }

        body>span.select2-container.select2-container--default.select2-container--open {
            width: auto !important;
        } */
          .fv-plugins-message-container.fv-plugins-message-container--enabled.invalid-feedback {
            min-height: 1.5rem;
        }
    </style>
@endsection
@section('content')
    <div class="container ">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0 ">Home Screen Banner </h4>
                           

                        </div>

                        <div class="card-body">
                            <div class="">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('homebanner.store') }}" method="POST"
                                    enctype="multipart/form-data" class="row g-3 align-items-center" id="importForm">
                                    @csrf
                                    <div class="error-msg col-md-4">
                                        <label for="logo" class="form-label"> Banner
                                            (jpeg,png,jpg,gif,svg)</label>
                                        <input type="file" name="image" class="form-control" required>

                                    </div>
                                    <div class="error-msg col-md-4">
                                        <label for="name" class="form-label">Select priority</label>
                                        <input type="number" name="priority" class="form-control" placeholder="Priority"
                                            min="0" required>
                                    </div>

                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary ">+ Add Banner</button>
                                    </div>

                                </form>
                            </div>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif



                            <div class="table-responsive">
                                <table id="add-row"
                                    class="display table table-striped table-hover data-table sortingclose">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Banner</th>
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
            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('homebanner.index') }}",
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            window.deleteBanner = function(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false,
                    reverseButtons: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/homebanners/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'The banner has been deleted.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                $('.data-table').DataTable().ajax.reload();
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Failed!',
                                    text: 'Something went wrong.',
                                    icon: 'error',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            };



            window.editBanner = function(id) {
                $.get('/home-banners/' + id + '/edit', function(data) {
                    // You can open a modal and populate image for editing
                    console.log(data);
                });
            }
        });

    </script>
    <script src="{{ asset('js/homebanner/homebanner_form.js') }}"></script>
@endsection
