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
                <div class="col-12">



                    <div class="card">
                        <h5 class="card-header">Edit Popular Category</h5>
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('popular_category.update', $category->id) }}"
                                method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf
                                @method('PUT')
                                {{-- Pharmacy Name --}}
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input value="{{ $category->name }}"  type="text" name="name"
                                            id="name" class="form-control" placeholder="category Name" />
                                        <label for="name">Category Name</label>
                                    </div>
                                </div>
            
                               
                                
                                {{-- Image --}}
                                <div class="form-group col-md-6 d-flex justify-content-center flex-column">
                                    @if ($category->logo)
                                        <div class="mb-2">
                                            <img id="logo" src="{{url('popular/category/' .$category->logo)}}" alt="category Image" class="img-thumbnail"
                                                style="max-height: 150px;">
                                        </div>
                                    @endif
                                    <input type="file" name="logo" class="form-control"
                                        data-parsley-required="{{ $category->logo ? 'false' : 'true' }}"
                                        data-parsley-required-message="The image field is required.">
                                    <small class="text-muted">Leave blank to keep existing image</small>
                                </div>
            
                                {{-- Buttons --}}
                                <div class="card-action">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="window.location='{{ route('popular_category.index') }}'">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/popularcategory/popularcategory_form.js') }}"></script>
{{-- <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
@endsection