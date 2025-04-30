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
                                <form action="{{ route('packageCategory.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                    @csrf
                                    <div class="col-md-4">
                                        <label for="name" class="form-label">Package Category Name</label>
                                        <input type="text" name="name" class="form-control" id="name" required placeholder="Enter category name">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="logo" class="form-label">Category Logo (Optional)</label>
                                        <input type="file" name="logo" class="form-control" id="logo">
                                    </div>
                                    
                                    <div class="col-md-4 mt-4">
                                        <button type="submit" class="btn btn-primary w-100">Add Package</button>
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
        $(document).ready(function() {
            // Initialize Select2 (if needed)
            $('.select2').select2();
        });
    </script>
@endsection
