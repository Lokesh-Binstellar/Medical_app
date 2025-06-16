@extends('layouts.app')
@section('styles')
    {{-- <link href="https://cdn.jsdelivr.net/npm/parsleyjs/src/parsley.css" rel="stylesheet" /> --}}
@endsection

@section('content')
    <div class="card">
        <h5 class="card-header">Add Phlebotomist</h5>
        <div class="card-body">
            <form class="row g-3" action="{{ route('phlebotomist.store') }}" method="POST" data-parsley-validate>
                @csrf

                {{-- Full Name --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="phlebotomists_name" id="phlebotomists_name" class="form-control"
                            placeholder="Full Name" required data-parsley-required-message="Phlebotomist name field is required"
                            value="{{ old('phlebotomists_name') }}" />
                        <label for="phlebotomists_name">Full Name</label>
                    </div>
                    @error('phlebotomists_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Contact Number --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="tel" name="contact_number" id="contact_number" class="form-control"
                            placeholder="Contact Number" pattern="^\d{7,12}$"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)" required
                            data-parsley-pattern="^\d{7,12}$"
                            data-parsley-pattern-message="Contact number must be 7 to 12 digits."
                            data-parsley-required-message="Contact number field is required"
                            value="{{ old('contact_number') }}" />
                        <label for="contact_number">Contact Number</label>
                    </div>
                    @error('contact_number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username"
                            required data-parsley-required-message="Username field is required" value="{{ old('username') }}" />
                        <label for="username">Username</label>
                    </div>
                    @error('username')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="col-md-6">
                    <div class="form-password-toggle">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Password" required data-parsley-minlength="6"
                                    data-parsley-minlength-message="Password must be at least 6 characters long."
                                    data-parsley-required-message="Password field is required." />
                                <label for="password">Password</label>
                            </div>
                            <span class="input-group-text cursor-pointer">
                                <i class="mdi mdi-eye-off-outline"></i>
                            </span>
                        </div>
                    </div>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email"
                            required data-parsley-type="email"
                            data-parsley-type-message="Please enter a valid email address."
                            data-parsley-required-message="Email field is required." value="{{ old('email') }}" />
                        <label for="email">Email</label>
                    </div>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- City --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="city" id="city" class="form-control" placeholder="City" required
                            data-parsley-required-message="City field is required" value="{{ old('city') }}" />
                        <label for="city">City</label>
                    </div>
                    @error('city')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- State --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="state" id="state" class="form-control" placeholder="State"
                            required data-parsley-required-message="State field is required" value="{{ old('state') }}" />
                        <label for="state">State</label>
                    </div>
                    @error('state')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Pincode --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="pincode" id="pincode" class="form-control" placeholder="Pincode"
                            pattern="^\d{4,10}$" required data-parsley-pattern="^\d{4,10}$"
                            data-parsley-pattern-message="Pincode must be 4 to 10 digits."
                            data-parsley-required-message="Pincode field is required" value="{{ old('pincode') }}" />
                        <label for="pincode">Pincode</label>
                    </div>
                    @error('pincode')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline">
                        <textarea name="address" id="address" class="form-control" placeholder="Address" style="height: 100px;" required
                            data-parsley-required-message="Address field is required">{{ old('address') }}</textarea>
                        <label for="address">Address</label>
                    </div>
                    {{-- @error('address')
            <span class="text-danger">{{ $message }}</span>
        @enderror --}}
                </div>

                {{-- Buttons --}}
                <div class="card-action">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-primary"
                        onclick="window.location='{{ route('phlebotomist.index') }}'">Cancel</button>
                </div>
            </form>



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
