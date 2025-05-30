{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <form class="max-w-sm mx-auto" action="{{ route('laboratorie.update', $laboratorie->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
            <input type="text" name="name" id="name" value="{{ $laboratorie->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
        </div>

        <div class="mb-5">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
            <input type="email" id="email" name="email" value="{{ $laboratorie->email }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
        </div>

        <div class="mb-5">
            <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Address</label>
            <textarea name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>{{ $laboratorie->address }}</textarea>
        </div>

        <div class="mb-5">
            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Phone</label>
            <input type="tel" name="phone" id="phone" value="{{ $laboratorie->phone }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full" required />
        </div>

        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Update</button>
    </form>
</x-app-layout> --}}


@extends('layouts.app')
@section('content')
    {{-- <div class="container">
        <div class="page-inner">
            <form class="max-w-sm mx-auto" action="{{ route('laboratorie.update', $laboratorie->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Pharmacist Form</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <!-- Name -->
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" value="{{ $laboratorie->name }}"
                                                class="form-control" id="name" required />
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" value="{{ $laboratorie->email }}"
                                                class="form-control" id="email" required />
                                        </div>

                                        <!-- Address -->
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea class="form-control" name="address" id="address" rows="5" required>{{ $laboratorie->address }}</textarea>
                                        </div>

                                        <!-- Phone -->
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="tel" name="phone" value="{{ $laboratorie->phone }}"
                                                class="form-control" id="phone" required />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}
    <div class="container">
        <div class="page-inner">
            <form class="form-horizontal" id="myForm" action="{{ route('laboratorie.update', $laboratorie->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">laboratory Registration </h4>
                    </div>

                    <div class="card-body">
                        <div class="form row">
                            <!-- Pharmacy Name -->
                            <div class="form-group col-md-6">
                                <label for="lab_name">Laboratory Name</label>
                                <input type="text" name="lab_name" value="{{ $laboratorie->lab_name }}"
                                    class="form-control" id="laboratory_name" required
                                    data-parsley-required-message="The laboratory name field is required."
                                    onblur="trimFieldValue('laboratory_name')">
                            </div>

                            <!-- Owner Name -->
                            <div class="form-group col-md-6">
                                <label for="owner_name">Owner Name</label>
                                <input type="text" name="owner_name" value="{{ $laboratorie->owner_name }}"
                                    class="form-control" id="owner_name" required
                                    data-parsley-required-message="The owner name field is required."
                                    onblur="trimFieldValue('owner_name')">
                            </div>

                            <!-- Email -->
                            <div class="form-group col-md-6">
                                <label for="email">Email</label>
                                <input type="email" name="email" value="{{ $laboratorie->email }}" class="form-control"
                                    id="email" required
                                    data-parsley-pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$"
                                    data-parsley-pattern-message="Email must be in the format like name@domain.com"
                                    data-parsley-required-message="The email field is required.">
                            </div>
                            <!-- Phone -->
                            <div class="form-group col-md-6">
                                <label for="phone">Phone</label>
                                <input type="tel" name="phone" value="{{ $laboratorie->phone }}" class="form-control"
                                    id="phone"  required
                                    pattern="^\d{7,12}$" 
                                    data-parsley-pattern="^\d{7,12}$"
                                    data-parsley-pattern-message="Please enter a valid phone number with 7 to 12 digits."
                                    data-parsley-required-message="The phone field is required."
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)">
                            </div>
                            <!-- Username -->
                            <div class="form-group col-md-6">
                                <label for="username">Username</label>
                                <input type="text" name="username" value="{{ $laboratorie->username }}"
                                    class="form-control" id="username" required
                                    data-parsley-required-message="The username field is required.">
                            </div>

                            <!-- Password -->
                            {{-- <div class="form-group col-md-6">
                                <label for="password">Password</label>
                                <input type="password" name="password" value="{{ $laboratorie->phone }}" class="form-control" id="password" required>
                            </div> --}}
                            <!-- city -->
                            <div class="form-group col-md-6">
                                <label for="city">City</label>
                                <input type="text" name="city" value="{{ $laboratorie->city }}" class="form-control"
                                    id="city" required
                                    data-parsley-required-message="The city field is required."
                                    onblur="trimFieldValue('city')">
                            </div>
                            <!-- pincode-->
                            <div class="form-group col-md-6">
                                <label for="pincode">Pincode</label>
                                <input type="text" name="pincode" value="{{ $laboratorie->pincode }}"
                                    class="form-control" id="pincode" required
                                    data-parsley-pattern="^\d{6}$"
                                    data-parsley-pattern-message="Pincode must be exactly 6 digits."
                                    data-parsley-required-message="The pincode field is required."
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">
                            </div>
                            <!-- State -->
                            <div class="form-group col-md-6">
                                <label for="state">State</label>
                                <input type="text" name="state" value="{{ $laboratorie->state }}" class="form-control"
                                    id="state" required
                                    data-parsley-required-message="The state field is required."
                                    onblur="trimFieldValue('state')">
                            </div>
                            <!-- Latitude -->
                            <div class="form-group col-md-6">
                                <label for="latitude">Latitude</label>
                                <input type="text" name="latitude" value="{{ $laboratorie->latitude }}"
                                    class="form-control" id="latitude" required
                                    data-parsley-required-message="The latitude field is required."
                                    data-parsley-pattern="^-?(90(\.0+)?|[1-8]?\d(\.\d+)?|0(\.\d+)?)$"
                                    data-parsley-pattern-message="Latitude must be a number between -90 and 90 with up to 10 decimal places."
                                    oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)">
                            </div>

                            <!-- Longitude -->
                            <div class="form-group col-md-6">
                                <label for="longitude">Longitude</label>
                                <input type="text" name="longitude" value="{{ $laboratorie->longitude }}"
                                    class="form-control" id="longitude" required
                                    data-parsley-required-message="The longitude field is required."
                                    data-parsley-pattern="^-?(180(\.0+)?|1[0-7]\d(\.\d+)?|[1-9]?\d(\.\d+)?)$"
                                    data-parsley-pattern-message="Longitude must be a number between -180 and 180 with up to 10 decimal places."
                                    oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)">
                            </div>

                            <!-- License -->
                            <div class="form-group col-md-6">
                                <label for="license">License No.</label>
                                <input type="text" name="license" value="{{ $laboratorie->license }}"
                                    class="form-control" id="license" required
                                    data-parsley-required-message="The drug license no. field is required."
                                    data-parsley-pattern="^[A-Za-z0-9/-]+$"
                                    data-parsley-pattern-message="The drug license number can only contain letters, numbers, hyphens, and slashes.">
                            </div>
                            <!-- Pickup Available -->
                            @php
                            $pickupValue = old('pickup', isset($laboratorie) ? $laboratorie->pickup : '');
                        @endphp
                        
                        <div class="form-group col-md-6">
                            <label>Pickup Available</label><br>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pickup" id="pickup_yes"
                                    value="1"
                                    {{ $pickupValue == '1' ? 'checked' : '' }} required
                                    data-parsley-required-message="Please select if pickup is available."
                                    data-parsley-errors-container="#pickup-error">
                                <label class="form-check-label" for="pickup_yes">Yes</label>
                            </div>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pickup" id="pickup_no"
                                    value="0"
                                    {{ $pickupValue == '0' ? 'checked' : '' }} required
                                    data-parsley-required-message="Please select if pickup is available."
                                    data-parsley-errors-container="#pickup-error">
                                <label class="form-check-label" for="pickup_no">No</label>
                            </div>
                        
                            <div id="pickup-error" class="text-danger mt-1"></div>
                        </div>
                        
                            
                            <!-- Image -->
                            <div class="form-group col-md-6 d-flex justify-content-center flex-column">
                                @if ($laboratorie->image)
                                    <div class="mb-2">
                                        <img src="{{ $laboratorie->image }}" alt="Pharmacy Image" class="img-thumbnail"
                                            style="max-height: 150px;">
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control"
                                    data-parsley-required="{{ $laboratorie->image ? 'false' : 'true' }}"
                                    data-parsley-required-message="The image field is required.">
                                <small class="text-muted">Leave blank to keep existing image</small>
                            </div>
                            {{-- <div class="form-group input-group  d-flex flex-column" style="width:100px">      
                              <label class="" for="inputGroupSelect01">Status:</label>
                              <select class="custom-select " id="inputGroupSelect01" name="role_id" class="form-control">
                                <option value="1" {{ (isset($pharmacies) && $pharmacies->status == '1') ? 'selected' : '' }}>Active</option>
                                  <option value="0" {{ (isset($pharmacies) && $pharmacies->status == '0') ? 'selected' : '' }}>Inactive</option>
                              </select>
                            </div> --}}


                            <!-- Address (full width) -->
                            <div class="form-group col-md-12">
                                <label for="address">Address</label>
                                <textarea name="address" id="address" rows="3" class="form-control" required data-parsley-minlength="10"
                                data-parsley-required-message="The address field is required."
                                onblur="trimFieldValue('address')">{{ $laboratorie->address }}</textarea>
                            </div>
                        </div>
                    </div>


                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <script>
        // Wait for the DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Parsley for the form
            $('#myForm').parsley();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
          // Fields to auto-trim on blur
          const trimFields = ['pharmacy_name', 'owner_name', 'city', 'state', 'address'];
      
          trimFields.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
              input.addEventListener('blur', function () {
                input.value = input.value.trim();
              });
            }
          });
      
          // Email field - block space entirely, cursor should not move
          const emailInput = document.getElementById('email');
          if (emailInput) {
            // Prevent spacebar press (keydown)
            emailInput.addEventListener('keydown', function (e) {
              if (e.key === ' ' || e.keyCode === 32) {
                e.preventDefault(); // completely stop the space
              }
            });
      
            // Remove spaces if pasted or autofilled
            emailInput.addEventListener('input', function () {
              const cursorPos = emailInput.selectionStart;
              const cleaned = emailInput.value.replace(/\s+/g, '');
              const diff = emailInput.value.length - cleaned.length;
      
              emailInput.value = cleaned;
      
              // Keep cursor in place if space was stripped
              if (diff > 0) {
                emailInput.setSelectionRange(cursorPos - diff, cursorPos - diff);
              }
            });
          }
        });
      </script>
      
@endsection
