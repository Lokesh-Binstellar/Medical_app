@extends('layouts.app')

@section('styles')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection
@section('content')
    <div class="card">
        <h5 class="card-header">Commission Data Form</h5>
        <div class="card-body">
            <form class="row g-3" id="labCreateForm" action="{{ route('commission_data.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf


                {{-- Pincode --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="commonAmount" id="commonAmount" class="form-control"
                            placeholder="Common Amount"/>
                        <label for="commonAmount">Common Amount</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="gstRate" id="gstRate" class="form-control" placeholder="GST Rate"
                            />
                        <label for="gstRate">GST Rate</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="commissionBelowAmount" id="commissionBelowAmount" class="form-control" placeholder="Commission Below Amount"
                            />
                        <label for="commissionBelowAmount">Commission Below Amount(with gst)</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="commissionAboveAmount" id="commissionAboveAmount" class="form-control" placeholder="Commission Above Amount"/>
                        <label for="commissionAboveAmount">Commission Above Amount(with gst)</label>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="card-action">
                    <button type="submit" class="btn btn-primary submit_btn">Save</button>
                    <button type="button" class="btn btn-primary"
                        onclick="window.location='{{ route('commission_data.index') }}'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    
    <script>
      
    </script>
@endsection
