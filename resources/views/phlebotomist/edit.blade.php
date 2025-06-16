@extends('layouts.app')

@section('content')
    <div class="card">
        <h5 class="card-header">Update Phlebotomist</h5>
        <div class="card-body">
           <form class="row g-3" action="{{ route('phlebotomist.update', $phlebotomist->id) }}" method="POST" data-parsley-validate>
    @csrf
    @method('PUT')

    {{-- Full Name --}}
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input value="{{ $phlebotomist->phlebotomists_name }}" type="text" name="phlebotomists_name"
                id="phlebotomists_name" class="form-control" placeholder="Phlebotomist Person Name" required
                data-parsley-required-message="Phlebotomist name field is required" />
            <label for="phlebotomists_name">Phlebotomist Person Name</label>
        </div>
    </div>

    {{-- Contact Number --}}
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input value="{{ $phlebotomist->contact_number }}" type="tel" name="contact_number" id="contact_number"
                class="form-control" placeholder="Contact Number" required
                pattern="^\d{7,12}$"
                data-parsley-required-message="Contact number field is required"
                data-parsley-pattern-message="Contact number must be 7 to 12 digits only"
                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)" />
            <label for="contact_number">Contact Number</label>
        </div>
    </div>

    {{-- Username --}}
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input value="{{ $phlebotomist->username }}" type="text" name="username" id="username"
                class="form-control" placeholder="Username" required
                data-parsley-required-message="Username field is required" />
            <label for="username">Username</label>
        </div>
    </div>

    {{-- Password (Optional during update) --}}
    <div class="col-md-6">
        <div class="form-password-toggle">
            <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Password"
                        data-parsley-minlength="6"
                        data-parsley-minlength-message="Password must be at least 6 characters" />
                    <label for="password">Password</label>
                </div>
                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
            </div>
        </div>
    </div>

    {{-- Email --}}
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input value="{{ $phlebotomist->email }}" type="email" name="email" id="email"
                class="form-control" placeholder="Email" required
                data-parsley-required-message="Email field is required"
                data-parsley-type-message="Please enter a valid email address" />
            <label for="email">Email</label>
        </div>
        @error('email')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    {{-- City --}}
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input value="{{ $phlebotomist->city }}" type="text" name="city" id="city"
                class="form-control" placeholder="City" required
                data-parsley-required-message="City field is required" />
            <label for="city">City</label>
        </div>
    </div>

    {{-- Pincode --}}
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input value="{{ $phlebotomist->pincode }}" type="text" name="pincode" id="pincode"
                class="form-control" placeholder="Pincode" pattern="^\d{6}$"
                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)" required
                data-parsley-required-message="Pincode field is required"
                data-parsley-pattern-message="Pincode must be exactly 6 digits" />
            <label for="pincode">Pincode</label>
        </div>
    </div>

    {{-- State --}}
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input value="{{ $phlebotomist->state }}" type="text" name="state" id="state"
                class="form-control" placeholder="State" required
                data-parsley-required-message="State field is required" />
            <label for="state">State</label>
        </div>
    </div>

    {{-- Address --}}
    <div class="col-md-12">
        <div class="form-floating form-floating-outline">
            <textarea name="address" id="address" class="form-control" placeholder="Address" style="height: 100px;" required
                data-parsley-required-message="Address field is required">{{ $phlebotomist->address }}</textarea>
            <label for="address">Address</label>
        </div>
    </div>

    {{-- Buttons --}}
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
