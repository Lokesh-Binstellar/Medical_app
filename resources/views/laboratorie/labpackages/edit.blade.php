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

        <form id="myForm" class="form-horizontal" action="{{ route('labPackage.update', $labPackage->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white rounded-top">
                    <h4 class="card-title text-white">Edit Package</h4>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="lab_id" class="form-label fw-semibold">Select Laboratory</label>
                            <select name="lab_id" id="lab_id" class="form-select select2" required>
                                <option value="">Select Laboratory</option>
                                @foreach ($labData as $lab)
                                    <option value="{{ $lab->id }}" {{ $labPackage->lab_id == $lab->id ? 'selected' : '' }}>
                                        {{ $lab->lab_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="col-md-4">
                            <label for="package_category_id" class="form-label fw-semibold">Select Package Category</label>
                            <select name="package_category_id" id="package_category_id" class="form-select select2" required>
                                <option value="">Select Package Category</option>
                                @foreach ($packageCategory as $pack)
                                    <option value="{{ $pack->id }}" {{ $labPackage->package_category_id == $pack->id ? 'selected' : '' }}>
                                        {{ $pack->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="package_name" class="fw-semibold">Package Name</label>
                            <input type="text" name="package_name" class="form-control" id="package_name" value="{{ $labPackage->package_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="fw-semibold">Price</label>
                            <input type="text" name="price" class="form-control" id="price" value="{{ $labPackage->price }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="home_price" class="fw-semibold">Home Price</label>
                            <input type="text" name="home_price" class="form-control" id="home_price" value="{{ $labPackage->home_price }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="fw-semibold">Description</label>
                            <input type="text" name="description" class="form-control" id="description" value="{{ $labPackage->description }}">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Update Package</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
