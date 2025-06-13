@extends('layouts.app')

@section('styles')
@endsection
@section('content')
    <div class="card shadow-xl rounded-3">
        <div class="card-header bg-primary text-white fw-bold fs-5">
            Free Delivery Charge
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('delivery_charges.store') }}" method="POST" data-parsley-validate>
                @csrf
                <div class="mb-3">
                    <label for="free_delivery_charge" class="form-label">Free Delivery Charge (₹)</label>
                   @php
    $freeDeliveryValue = old('free_delivery_charge');

    if (!$freeDeliveryValue) {
        $freeDeliveryValue = $pharmacy->free_delivery_charge ?? $lab->free_delivery_charge ?? '';
    }
@endphp
                    <input type="number" step="0.01" required
                        data-parsley-required-message="Free Delivery Charge is required" class="form-control"
                        id="free_delivery_charge" name="free_delivery_charge" placeholder="Enter free delivery charge"
                      value="{{ $freeDeliveryValue }}">
                </div>

                <button type="submit" class="btn btn-primary">Save Free Delivery Charge</button>
            </form>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/parsley.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('form[data-parsley-validate]').parsley({
                errorClass: 'is-invalid',
                successClass: 'is-valid',
                errorsWrapper: '<span class="invalid-feedback d-block"></span>',
                errorTemplate: '<span></span>',
                trigger: 'change'
            });
        });
    </script>
@endsection
