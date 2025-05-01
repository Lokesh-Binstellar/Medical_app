@extends('layouts.app')

@section('styles')
    <style>
        /* Add any custom styles here */
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title">Package Category</h4>
                        </div>

                        <div class="card-body">
                            <!-- Form for adding a new package category -->
                            <div class="mb-4 ">
                                @if ($errors->any())
                                    {{ implode('', $errors->all('<div>:message</div>')) }}
                                @endif
                                <form action="{{ route('packageCategory.store') }}" method="POST"
                                    enctype="multipart/form-data" class="row g-3">
                                    @csrf
                                    <div class="col-md-4">
                                        <label for="name" class="form-label">Package Category Name</label>
                                        <input type="text" name="name" class="form-control" id="name" required
                                            placeholder="Enter category name">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="package_image" class="form-label">Category Logo(jpeg,png,jpg,gif,svg)
                                        </label>
                                        <input type="file" name="package_image" class="form-control" id="package_image">
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <button type="submit" class="btn btn-primary w-100">Add Package Category</button>
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
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Package Image</th>
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

@section('script')
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
                        data: 'id',
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

                if (!confirm("Are you sure you want to delete?")) {
                    return;
                }

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
                        alert('Category deleted successfully');
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr.responseText);
                        alert('Failed to delete category.');
                    }
                });


            });


        });
    </script>
@endsection
