@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection
@section('content')

    <div class="card">
        <h5 class="card-header">Laboratory Update Form</h5>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="row g-3" id="labCreateForm" action="{{ route('commission_data.update', $commission_data->id) }}"method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                  <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="commonAmount" id="commonAmount" class="form-control"
                            placeholder="Common Amount" value="{{ $commission_data->commonAmount }}" />
                        <label for="commonAmount">Common Amount</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="gstRate" id="gstRate" class="form-control" placeholder="GST Rate"
                            value="{{ $commission_data->gstRate }}"/>
                        <label for="gstRate">GST Rate</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="commissionBelowAmount" id="commissionBelowAmount" class="form-control" placeholder="Commission Below Amount"
                           value="{{ $commission_data->commissionBelowAmount }}" />
                        <label for="commissionBelowAmount">Commission Below Amount(with gst)</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="commissionAboveAmount" id="commissionAboveAmount" class="form-control" placeholder="Commission Above Amount"
                        value="{{ $commission_data->commissionAboveAmount }}"/>
                        <label for="commissionAboveAmount">Commission Above Amount(with gst)</label>
                    </div>
                </div>
                {{-- Buttons --}}
                <div class="card-action">
                    <button type="submit" class="btn btn-primary submit-btn">Update</button>
                    <button type="button" class="btn btn-primary"
                        onclick="window.location='{{ route('commission_data.index') }}'">Cancel</button>
                </div>
            </form>
        </div>
    @endsection
    @section('scripts')
    @endsection
