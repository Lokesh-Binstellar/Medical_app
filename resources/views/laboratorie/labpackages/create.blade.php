@extends('layouts.app')

@section('styles')
    <style>
        .cust-icon {
            padding-bottom: 20px !important;
        }

        .p-8-4 {
            padding: 8.4px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="myForm" class="form-horizontal" action="{{ route('labPackage.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white rounded-top">
                        <h4 class="card-title text-white">Package </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="lab_id" class="form-label fw-semibold">Select Laboratory</label>
                                <select name="lab_id" id="lab_id" class="form-select select2" required>
                                    <option value="">Select Laboratory</option>
                                    @foreach ($labData as $lab)
                                        <option value="{{ $lab->id }}"
                                            {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                            {{ $lab->lab_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="package_category_id" class="form-label fw-semibold">Select Package
                                    Category</label>
                                <select name="package_category_id" id="package_category_id" class="form-select select2"
                                    required>
                                    <option value="">Select Package Category</option>
                                    @foreach ($packageCategory as $pack)
                                        <option value="{{ $pack->id }}"
                                            {{ old('package_category_id') == $pack->id ? 'selected' : '' }}>
                                            {{ $pack->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="package_name" class="fw-semibold">Package Name</label>
                                <input type="text" name="package_name" class="form-control" id="package_name" required
                                    data-parsley-required-message="The package name field is required.">
                                @error('package_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="fw-semibold">Price</label>
                                <input type="text" name="price" class="form-control" id="price" required
                                    data-parsley-required-message="The price field is required.">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="home_price" class="fw-semibold">Home Price</label>
                                <input type="text" name="home_price" class="form-control" id="home_price" required
                                    data-parsley-required-message="The home price field is required.">
                                @error('home_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="description" class="fw-semibold">Description</label>
                                <textarea name="description" id="description" class="form-control tinymce-editor" rows="6">
                            {{ old('description', $labPackage->description ?? '') }}
                        </textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save Package</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> --}}
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
        tinymce.init({
            selector: '.tinymce-editor',
            height: 300,
            menubar: false,
            plugins: 'lists link image preview code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link image | preview code',
            branding: false
        });
    </script>
@endsection
