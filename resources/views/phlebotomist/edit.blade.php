@extends('layouts.app')

@section('content')
    
            <div class="card">
                <h5 class="card-header">Update Phlebotomist</h5>
                <div class="card-body">
                    <form class="row g-3" action="{{ route('phlebotomist.update', $phlebotomist->id) }}" method="POST" data-parsley-validate>
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="name" value="{{ $phlebotomist->name }}" class="form-control"
                                    required
                                    data-parsley-required-message="Name is required" />
                                <label for="name">Name</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="contact_number"
                                    value="{{ $phlebotomist->contact_number }}"
                                    class="form-control" required
                                    pattern="^\d{7,12}$"
                                    data-parsley-pattern="^\d{7,12}$"
                                    data-parsley-pattern-message="Enter a valid contact number (7 to 12 digits)"
                                    data-parsley-required-message="Contact number is required"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)" />
                                <label for="phone">Contact Number</label>
                            </div>
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('phlebotomist.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        
@endsection

@section('scripts')

    <script src="{{ asset('assets/js/parsley.min.js') }}"></script>
    <script>
        $(document).ready(function () {
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
