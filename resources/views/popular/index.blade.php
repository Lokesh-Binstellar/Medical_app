@extends('layouts.app')
@section('styles')
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}
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
                            <h4 class="card-title mb-0 ">Popular Brands </h4>
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
                                <form action="{{ route('popular.store') }}" method="POST" enctype="multipart/form-data"
                                    class="row g-3 align-items-center" id="importForm">
                                    @csrf
                                    <div class="error-msg col-md-4">
                                        <label for="name" class="form-label">Select Category</label>
                                        <select name="name" class="form-control select2 " id="brand-select">
                                            <option value="">Select Brand</option>
                                            @foreach ($popularBrands as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="error-msg col-md-4">
                                        <label for="logo" class="form-label">Category Logo
                                            (jpeg,png,jpg,gif,svg)</label>
                                        <input type="file" name="logo" class="form-control" id="logo">
                                    </div>

                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary">+ Add Brand</button>
                                    </div>

                                </form>
                            </div>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif


                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover data-table">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Brand Name</th>
                                                <th>Logo</th>
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
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            // ✅ DataTable Initialization
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('popular.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'logo',
                        name: 'logo'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // ✅ Select2 Initialization
            $('#brand-select').select2({
                placeholder: "Select Brand",
                allowClear: true
            });

            // ✅ SweetAlert Delete Function
            window.deleteUser = function(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This brand will be deleted permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false,
                    reverseButtons: false // Confirm (Yes) on left, Cancel on right
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('popular.destroy', '') }}/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Brand deleted successfully.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                table.ajax.reload();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }
        });
    </script>
    <script src="{{ asset('js/popularbrands/popularbrands_form.js') }}"></script>
@endsection
