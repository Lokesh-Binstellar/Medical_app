@extends('layouts.app')
@section('styles')
    {{-- <link href="https://cdn.jsdelivr.net/npm/parsleyjs/src/parsley.css" rel="stylesheet" /> --}}
@endsection

@section('content')
    <div class="container">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Add Phlebotomist</h5>
                <div class="card-body">
                    <form class="row g-3" action="{{ route('phlebotomist.store') }}" method="POST" data-parsley-validate>
                        @csrf

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="name" class="form-control" required
                                    data-parsley-required-message="Name is required" placeholder="Name" />
                                <label for="name">Name</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="tel" name="contact_number" class="form-control" required
                                    placeholder="Phone" pattern="^\d{7,12}$" data-parsley-pattern="^\d{7,12}$"
                                    data-parsley-pattern-message="Enter a valid contact number (7 to 12 digits)"
                                    data-parsley-required-message="Contact number is required"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)" />
                                <label for="phone">Contact Number</label>
                            </div>
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-primary"
                                onclick="window.location='{{ route('phlebotomist.index') }}'">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection



@section('scripts')
    <script>
        $(document).ready(function() {
            $('form').parsley({
                errorClass: 'is-invalid',
                successClass: 'is-valid',
                errorsWrapper: '<span class="invalid-feedback d-block"></span>',
                errorTemplate: '<span></span>',
                trigger: 'change'
            });
        });
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script> --}}
    <script src="{{ asset('assets/js/parsley.min.js') }}"></script>
@endsection
