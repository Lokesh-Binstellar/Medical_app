@extends('layouts.app')
@section('title', 'Update Janaushadhi')
@section('styles')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
@section('content')
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h5 class="card-title m-0 me-2 text-secondary">Update Janaushadhi</h5>
        </div>
        <form action="{{ route('janaushadhi.update', $janaushadhies->id) }}" method="POST" id="janaushadhi_form"
            data-parsley-validate>
            @csrf
            @method('PUT')

            <div class="card-body pb-0">
                <div class="row">

                    {{-- Drug Code --}}
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="drug_code" id="drug_code"
                                value="{{ old('drug_code', $janaushadhies->drug_code) }}" placeholder="Enter Drug Code"
                                required data-parsley-required-message="The drug code is required." />
                            <label for="drug_code">Drug Code</label>
                            @error('drug_code')
                                <small class="red-text ml-10" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Generic Name --}}
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="generic_name" id="generic_name"
                                value="{{ old('generic_name', $janaushadhies->generic_name) }}"
                                placeholder="Enter Generic Name" required
                                data-parsley-required-message="The generic name is required." />
                            <label for="generic_name">Generic Name</label>
                            @error('generic_name')
                                <small class="red-text ml-10" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Unit Size --}}
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="unit_size" id="unit_size"
                                value="{{ old('unit_size', $janaushadhies->unit_size) }}" placeholder="Enter Unit Size"
                                required data-parsley-required-message="The unit size is required." />
                            <label for="unit_size">Unit Size</label>
                            @error('unit_size')
                                <small class="red-text ml-10" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- MRP --}}
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="form-floating form-floating-outline">
                            <input type="number" step="0.01" class="form-control" name="mrp" id="mrp"
                                value="{{ old('mrp', $janaushadhies->mrp) }}" placeholder="Enter MRP" required
                                data-parsley-required-message="The MRP field is required." data-parsley-type="number"
                                data-parsley-type-message="MRP must be a number." />
                            <label for="mrp">MRP</label>
                            @error('mrp')
                                <small class="red-text ml-10" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Group Name --}}
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="group_name" id="group_name"
                                value="{{ old('group_name', $janaushadhies->group_name) }}" placeholder="Enter Group Name"
                                required data-parsley-required-message="The group name is required." />
                            <label for="group_name">Group Name</label>
                            @error('group_name')
                                <small class="red-text ml-10" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer text-end pt-0">
                <a href="{{ route('janaushadhi.index') }}" class="btn btn-outline-secondary waves-effect me-1">Cancel</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>

    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/parsley.min.js') }}"></script>
@endsection
