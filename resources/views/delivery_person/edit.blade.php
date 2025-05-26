@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
@endsection
@section('content')
<div class="container">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Delivery Person Update Form</h5>
            <div class="card-body">
                <form class="row g-3" id="deliveryCreateForm" action="{{ route('delivery_person.update', $delivery->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- Pharmacy Name --}}
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input value="{{ $delivery->delivery_person_name }}" type="text" name="delivery_person_name"
                                id="delivery_person_name" class="form-control" placeholder="Delivery Person Name" />
                            <label for="delivery_person_name">Delivery Person Name</label>
                        </div>
                    </div>


                    {{-- Phone --}}
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input value="{{ $delivery->phone }}" type="tel" name="phone" id="phone"
                                class="form-control" placeholder="Phone" pattern="^\d{7,12}$"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)" />
                            <label for="phone">Phone</label>
                        </div>
                    </div>

                    {{-- Username --}}
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input value="{{ $delivery->username }}" type="text" name="username" id="username"
                                class="form-control" placeholder="Username" />
                            <label for="username">Username</label>
                        </div>
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
                    </div>

                    
                    {{-- Email --}}
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input value="{{ $delivery->email }}" type="email" name="email" id="email"
                                class="form-control" placeholder="Email" />
                            <label for="email">Email</label>
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- City --}}
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input value="{{ $delivery->city }}" type="text" name="city" id="city"
                                class="form-control" placeholder="City" />
                            <label for="city">City</label>
                        </div>
                    </div>

                    {{-- Pincode --}}
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input value="{{ $delivery->pincode }}" type="text" name="pincode" id="pincode"
                                class="form-control" placeholder="Pincode" pattern="^\d{6}$"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)" />
                            <label for="pincode">Pincode</label>
                        </div>
                    </div>

                    {{-- State --}}
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input value="{{ $delivery->state }}" type="text" name="state" id="state"
                                class="form-control" placeholder="State" />
                            <label for="state">State</label>
                        </div>
                    </div>

                    {{-- Latitude --}}
                    {{-- <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input value="{{ $delivery->latitude }}" type="text" name="latitude" id="latitude"
                                class="form-control" placeholder="Latitude"
                                oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)" />
                            <label for="latitude">Latitude</label>
                        </div>
                    </div> --}}

                    {{-- Longitude --}}
                    {{-- <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input value="{{ $delivery->longitude }}" type="text" name="longitude" id="longitude"
                                class="form-control" placeholder="Longitude"
                                oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)" />
                            <label for="longitude">Longitude</label>
                        </div>
                    </div> --}}

 


                    {{-- Address --}}
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <textarea name="address" id="address" class="form-control" placeholder="Address" style="height: 100px;">{{ $delivery->address }}</textarea>
                            <label for="address">Address</label>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="card-action">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-primary"
                            onclick="window.location='{{ route('delivery_person.index') }}'">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{ asset('js/delivery_person/delivery_person_form.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection
