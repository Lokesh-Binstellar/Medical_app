@extends('layouts.app')
@section('styles')
<style>
    
</style>
@endsection
@section('content')
    <div class="container mt-5">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">                    
                        <div class="card-header d-flex justify-content-between flex-column ">
                            <h4 class="card-title pb-3 ">Popular Category </h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <form action="{{ route('popular_category.store') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                                    @csrf
                                    <select name="name" class="form-control select2" id="brand-select" required style="width: 250px;">
                                        <option value="">Select Brand</option>
                                        @foreach ($popularCategory as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <input type="file" name="logo" class="form-control-file" >
                                    <button type="submit" class="btn btn-primary  ">Add Category</button>
                                </form>
                            </div>
                            
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <table class="table table-bordered mt-3">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Category Name</th>
                                        <th>Logo</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($AddedCategory as $index => $category)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                @if ($category->logo)
                                                    <img src="{{url('storage/category/' .$category->logo)}}" alt="logo" width="50">
                                                @else
                                                    <span class="text-muted">No Logo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Edit Button -->
                                                <a href="{{ route('popular_category.edit', $category->id) }}" class="btn btn-sm btn-warning">
                                                    Edit
                                                </a>

                                                <!-- Delete Button -->
                                                <form action="{{ route('popular_category.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this brand?')">
                                                        Delete
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize Select2 with AJAX
            $('.select2').select2()
        });
    </script>
@endsection
