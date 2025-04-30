@extends('layouts.app')
@section('styles')
    <style>
        .parsley-required,
        .parsley-errors-list li,
        .red-text {
            color: red !important;
        }

        .parsley-required {
            color: red;
        }
    </style>
@endsection
@section('content')
    <!-- End Sidebar -->

    {{-- <div class="main-panel"> --}}
    <div class="container">
        <div class="page-inner">
            <form id="myForm" class="form-horizontal" action="{{ route('pharmacist.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header rounded-top" style="background-color:#5ecbd8">
                        <h4 class="card-title  text-white">Pharmacy Registration Form</h4>
                    </div>

                    <div class="card-body">
                        <div class="form row">
                            <!-- Pharmacy Name -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="pharmacy_name">Pharmacy Name</label>
                                <input type="text" name="pharmacy_name" class="form-control" id="pharmacy_name" required
                                    data-parsley-required-message="The pharmacy name field is required."
                                    onblur="trimFieldValue('pharmacy_name')">
                            </div>

                            <!-- Owner Name -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="owner_name">Owner Name</label>
                                <input type="text" name="owner_name" class="form-control" id="owner_name" required
                                    data-parsley-required-message="The owner name field is required."
                                    onblur="trimFieldValue('owner_name')">
                            </div>

                            <!-- Email -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email" required
                                    data-parsley-pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$"
                                    data-parsley-pattern-message="Email must be in the format like name@domain.com"
                                    data-parsley-required-message="The email field is required.">

                                {{-- Laravel backend error --}}
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="phone">Phone</label>
                                <input type="tel" name="phone" class="form-control" id="phone" required
                                    pattern="^\d{7,12}$" data-parsley-pattern="^\d{7,12}$"
                                    data-parsley-pattern-message="Please enter a valid phone number with 7 to 12 digits."
                                    data-parsley-required-message="The phone field is required."
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)">
                            </div>




                            <!-- Username -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username" required
                                    data-parsley-required-message="The username field is required.">
                            </div>

                            <!-- Password -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" required
                                    data-parsley-minlength="8"
                                    data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).+$"
                                    data-parsley-pattern-message="Password must contain uppercase, lowercase, number, and special character."
                                    data-parsley-required-message="The password field is required.">
                            </div>

                            <!-- City -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="city">City</label>
                                <input type="text" name="city" class="form-control" id="city" required
                                    data-parsley-required-message="The city field is required."
                                    onblur="trimFieldValue('city')">
                            </div>

                            <!-- Pincode -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="pincode">Pincode</label>
                                <input type="text" name="pincode" class="form-control" id="pincode" required
                                    data-parsley-pattern="^\d{6}$"
                                    data-parsley-pattern-message="Pincode must be exactly 6 digits."
                                    data-parsley-required-message="The pincode field is required."
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">
                            </div>

                            <!-- State -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="state">State</label>
                                <input type="text" name="state" class="form-control" id="state" required
                                    data-parsley-required-message="The state field is required."
                                    onblur="trimFieldValue('state')">
                            </div>

                            <!-- Latitude -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="latitude">Latitude</label>
                                <input type="text" name="latitude" class="form-control" id="latitude" required
                                    data-parsley-required-message="The latitude field is required."
                                    data-parsley-pattern="^-?(90(\.0+)?|[1-8]?\d(\.\d+)?|0(\.\d+)?)$"
                                    data-parsley-pattern-message="Latitude must be a number between -90 and 90 with up to 10 decimal places."
                                    oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)">
                            </div>


                            <!-- Longitude -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="longitude">Longitude</label>
                                <input type="text" name="longitude" class="form-control" id="longitude" required
                                    data-parsley-required-message="The longitude field is required."
                                    data-parsley-pattern="^-?(180(\.0+)?|1[0-7]\d(\.\d+)?|[1-9]?\d(\.\d+)?)$"
                                    data-parsley-pattern-message="Longitude must be a number between -180 and 180 with up to 10 decimal places."
                                    oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)">
                            </div>


                            <!-- License -->
                            <div class="form-group col-md-6">
                                <label class="fw-semibold" for="license">Drug License No.</label>
                                <input type="text" name="license" class="form-control" id="license" required
                                    data-parsley-required-message="The drug license no. field is required."
                                    data-parsley-pattern="^[A-Za-z0-9/-]+$"
                                    data-parsley-pattern-message="The drug license number can only contain letters, numbers, hyphens, and slashes.">
                            </div>


                            <!-- Image -->
                            <div class="form-group col-md-6 d-flex justify-content-center flex-column">
                                <label class="fw-semibold" for="image">Image</label>
                                <input type="file" name="image" class="form-control-file" id="image" required
                                    data-parsley-required-message="The image field is required.">
                            </div>

                            {{-- <div class="form-group input-group  d-flex flex-column" style="width:100px">      
                            <label class="fw-semibold" class="" for="inputGroupSelect01">Status:</label>
                            <select class="custom-select " id="inputGroupSelect01" name="role_id" class="form-control">
                              <option value="1" {{ (isset($pharmacies) && $pharmacies->status == '1') ? 'selected' : '' }}>Active</option>
                              <option value="0" {{ (isset($pharmacies) && $pharmacies->status == '0') ? 'selected' : '' }}>Inactive</option>
                            </select>
                          </div> --}}

                            <!-- Address -->
                            <div class="form-group col-md-12">
                                <label class="fw-semibold" for="address">Address</label>
                                <textarea name="address" id="address" rows="3" class="form-control" required data-parsley-minlength="10"
                                    data-parsley-required-message="The address field is required." onblur="trimFieldValue('address')"></textarea>
                            </div>
                        </div>
                    </div>



                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
    {{-- </div> --}}


    <script>
        // Wait for the DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Parsley for the form
            $('#myForm').parsley();
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fields to auto-trim on blur
            const trimFields = ['pharmacy_name', 'owner_name', 'city', 'state', 'address'];

            trimFields.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('blur', function() {
                        input.value = input.value.trim();
                    });
                }
            });

            // Email field - block space entirely, cursor should not move
            const emailInput = document.getElementById('email');
            if (emailInput) {
                // Prevent spacebar press (keydown)
                emailInput.addEventListener('keydown', function(e) {
                    if (e.key === ' ' || e.keyCode === 32) {
                        e.preventDefault(); // completely stop the space
                    }
                });

                // Remove spaces if pasted or autofilled
                emailInput.addEventListener('input', function() {
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
