@extends('layouts.app')

@section('styles')
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0">Filter By Organ</h4>
                        </div>

                        <div class="card-body">
                            <!-- Form for adding a new package category -->
                            <div class="">
                                @if ($errors->any())
                                    {{ implode('', $errors->all('<div>:message</div>')) }}
                                @endif
                                <form action="{{ route('packageCategory.store') }}" method="POST"
                                    enctype="multipart/form-data" class="row g-3 align-items-center" id="importForm">
                                    @csrf
                                    <div class="col-md-4">
                                        <label for="name" class="form-label">Organ Name</label>
                                        <input type="text" name="name" class="form-control" id="name" 
                                            placeholder="Enter organ name">
                                        {{-- <label for="name" class="form-label">Package Category Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter category name"> --}}
                                    </div>

                                    <div class="col-md-4">
                                        <label for="package_image" class="form-label">Organ Logo(jpeg,png,jpg,gif,svg)
                                        </label>
                                        <input type="file" name="package_image" class="form-control" id="package_image">
                                    </div>

                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary  ">+ SUBMIT</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Success message -->
                            @if (session('success'))
                                <div class="alert alert-success mb-3">{{ session('success') }}</div>
                            @endif

                            <!-- Table to display added categories -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover data-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Organ Name</th>
                                                <th>Organ Image</th>
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
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('packageCategory.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'package_image',
                        name: 'package_image'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('body').on('click', '.deleteCategory', function() {
                var category_id = $(this).data("id");

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "/packageCategory/" + category_id,
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (typeof table !== 'undefined') {
                                    table.draw();
                                }
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Category deleted successfully',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function(xhr) {
                                console.log('Error:', xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Failed to delete category!',
                                });
                            }
                        });
                    }
                });
            });


        });
    </script>
    <script src="{{ asset('js/packagecategory/packagecategory_form.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script> --}}
@endsection
