@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Edit Category</h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('popular_category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="name">Category Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $category->name }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <input type="file" name="logo" class="form-control-file">
                                    @if ($category->logo)
                                        <img src="{{url('storage/category/' .$category->logo)}}" alt="logo" width="50" class="mt-2">
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">Update Category</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
