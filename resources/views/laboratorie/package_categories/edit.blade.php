@extends('layouts.app')

@section('styles')
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
@endsection
@section('content')
    <div class="container mt-5">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-12">



                    <div class="card">
                        <h5 class="card-header">Edit Organ</h5>
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('packageCategory.update', $packageCategory->id) }}"
                                method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf
                                @method('PUT')
                                {{-- Pharmacy Name --}}
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <input value="{{ $packageCategory->name }}" type="text" name="name"
                                            id="name" class="form-control" placeholder="Organ Name" />
                                        <label for="name">Organ Name</label>
                                    </div>
                                </div>



                                {{-- Image --}}
                                <div class="form-group d-flex justify-content-center flex-column col-md-12">
                                    @if ($packageCategory->package_image)
                                        <div class="mb-2">
                                            <img id="logo"
                                                src={{ asset('assets/package_image/' . $packageCategory->package_image) }}
                                                alt="category Image" class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    @endif
                                    <input type="file" name="package_image" class="form-control"
                                        data-parsley-required="{{ $packageCategory->package_image ? 'false' : 'true' }}"
                                        data-parsley-required-message="The image field is required.">
                                    <small class="text-muted">Leave blank to keep existing image</small>
                                </div>

                                {{-- Buttons --}}
                                <div class="card-action">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="window.location='{{ route('packageCategory.index') }}'">Cancel</button>
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
    <script src="{{ asset('js/packagecategory/packagecategory_form.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection
