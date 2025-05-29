@extends('layouts.app')

@section('styles')
@endsection

@section('content')
    
            <div class="card">
                <h5 class="card-header">Delivery Person Registration Form</h5>
                <div class="card-body">
                    <form class="row g-3" id="deliveryPersonCreateForm" action="{{ route('delivery_person.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Delivery Person Name (mapped to delivery_person_name) --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="delivery_person_name" id="delivery_person_name"
                                    class="form-control" placeholder="Full Name"
                                    value="{{ old('delivery_person_name') }}" />
                                <label for="delivery_person_name">Full Name</label>
                            </div>
                            @error('delivery_person_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        {{-- Phone --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone"
                                    pattern="^\d{7,12}$" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)"
                                    value="{{ old('phone') }}" />
                                <label for="phone">Phone</label>
                            </div>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="username" id="username" class="form-control"
                                    placeholder="Username" value="{{ old('username') }}" />
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
                                    <span class="input-group-text cursor-pointer"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
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
                                <input type="text" name="pincode" id="pincode" class="form-control"
                                    placeholder="Pincode" value="{{ old('pincode') }}" />
                                <label for="pincode">Pincode</label>
                            </div>
                            @error('pincode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>



                        {{-- Latitude --}}
                        {{-- <div class="col-md-6">


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
                        {{-- Buttons --}}
                        <div class="card-action mt-3">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary"
                                onclick="window.location='{{ route('delivery_person.index') }}'">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
      
@endsection

@section('scripts')
    <script src="{{ asset('js/delivery_person/delivery_person_form.js') }}"></script>
@endsection
