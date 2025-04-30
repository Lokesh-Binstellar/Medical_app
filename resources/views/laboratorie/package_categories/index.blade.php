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
                            <div class="mb-4">
                                <form action="{{ route('packageCategory.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                    @csrf
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Package Category Name</label>
                                        <input type="text" name="name" class="form-control" id="name" required placeholder="Enter category name">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="logo" class="form-label">Category Logo (Optional)</label>
                                        <input type="file" name="logo" class="form-control" id="logo">
                                    </div>
                                    
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">Add Package</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Success message -->
                            @if (session('success'))
                                <div class="alert alert-success mb-3">{{ session('success') }}</div>
                            @endif

                            <!-- Table to display added categories -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Package Name</th>
                                            <th>Logo</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($AddedBrands as $index => $brand)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $brand->name }}</td>
                                                <td>
                                                    @if ($brand->logo)
                                                        <img src="{{ url('storage/brand/' . $brand->logo) }}" alt="logo" width="50" class="rounded">
                                                    @else
                                                        <span class="text-muted">No Logo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('popular.edit', $brand->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <form action="{{ route('popular.destroy', $brand->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this brand?')">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No popular brands added yet.</td>
                                            </tr>
                                        @endforelse
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

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize Select2 (if needed)
            $('.select2').select2();
        });
    </script>
@endsection
