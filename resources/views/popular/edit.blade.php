@extends('layouts.app')
@section('styles')

{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}

@endsection
@section('content')

                    {{-- <div class="card shadow">
                        <div class="card-header">
                            <h4 class="card-title mb-0 ">Edit Brand</h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('popular.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="name">Brand Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $brand->name }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <input type="file" name="logo" class="form-control-file">
                                    @if ($brand->logo)
                                        <img src="{{url('storage/brand/' .$brand->logo)}}" alt="logo" width="50" class="mt-2">
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">Update Brand</button>
                            </form>
                        </div>
                    </div> --}}


                    <div class="card">
                        <h5 class="card-header">Edit Brand</h5>
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('popular.update', $brand->id) }}"
                                method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf
                                @method('PUT')
                                {{-- Pharmacy Name --}}
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input value="{{ $brand->name }}"  type="text" name="name"
                                            id="name" class="form-control" placeholder="Brand Name" />
                                        <label for="name">Brand Name</label>
                                    </div>
                                </div>
            
                               
                                
                                {{-- Image --}}
                                <div class="form-group col-md-6 d-flex justify-content-center flex-column">
                                    @if ($brand->logo)
                                        <div class="mb-2">
                                            <img id="logo" src="{{url('popular/brands/' .$brand->logo)}}" alt="Brand Image" class="img-thumbnail"
                                                style="max-height: 150px;">
                                        </div>
                                    @endif
                                    <input type="file" name="logo" class="form-control">
                                        {{-- data-parsley-required="{{ $brand->logo ? 'false' : 'true' }}"
                                        data-parsley-required-message="The image field is required."> --}}
                                    <small class="text-muted">Leave blank to keep existing image</small>
                                </div>
            
                                {{-- Buttons --}}
                                <div class="card-action">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="window.location='{{ route('popular.index') }}'">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                
@endsection

@section('scripts')
{{-- <script src="{{ asset('js/popularbrands/popularbrands_form.js') }}"></script> --}}

@endsection