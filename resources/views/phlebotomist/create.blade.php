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

                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="phlebotomists_name" id="phlebotomists_name"
                            class="form-control" placeholder="Full Name" />
                        <label for="phlebotomists_name">Full Name</label>
                    </div>
                    @error('phlebotomists_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                {{-- contact_number --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="tel" name="contact_number" id="contact_number" class="form-control"
                            placeholder="contact_number" pattern="^\d{7,12}$"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)"
                            value="{{ old('contact_number') }}" />
                        <label for="contact_number">Contact number</label>
                    </div>
                    @error('contact_number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username"
                            value="{{ old('username') }}" />
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
                                    placeholder="Password" />
                                <label for="password">Password</label>
                            </div>
                            <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
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
                            value="{{ old('email') }}" />
                        <label for="email">Email</label>
                    </div>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                {{-- City --}}
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="city" id="city" class="form-control" placeholder="City"
                            value="{{ old('city') }}" />
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
                            value="{{ old('state') }}" />
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
                            value="{{ old('pincode') }}" />
                        <label for="pincode">Pincode</label>
                    </div>
                    @error('pincode')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>



                {{-- Address --}}
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline">
                        <textarea name="address" id="address" class="form-control" placeholder="Address" style="height: 100px;">{{ old('address') }}</textarea>
                        <label for="address">Address</label>
                    </div>
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

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
