@extends('layouts.app')
@section('styles')
@endsection
@section('content')
    
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="card">
            <div class="card-header rounded-top">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0 text-white">Upload Prescription</h4>
                </div>
            </div>
             <div class="card-body">
            <form action="{{ route('admin.prescription.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 fv-plugins-bootstrap5 fv-plugins-framework">
                @csrf
                
                    <!-- Customer Select (Left side) -->
                    <div class="col-md-6 fv-plugins-icon-container">
                        <label for="customer">Select Customer</label>
                        <select id="customer" name="customer_id"
                            class="form-control @error('customer_id') is-invalid @enderror" style="width: 100%;"></select>
                        @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File Upload (Right side) -->
                    <div class="col-md-6 fv-plugins-icon-container">
                        <label for="prescription">Upload Prescription</label>
                        <input type="file" name="prescription" id="prescription"
                            class="form-control @error('prescription') is-invalid @enderror">
                        @error('prescription')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                

                <!-- Save Button -->
                <div class="card-action">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                </div>
            </form>
            </div>
        </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#customer').select2({
                placeholder: 'Search by name or mobile number',
                ajax: {
                    url: '{{ url("/customers/search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            });
        });
    </script>
@endsection