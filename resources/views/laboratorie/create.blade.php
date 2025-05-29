@extends('layouts.app')

@section('styles')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection
@section('content')
  
            <div class="card">
                <h5 class="card-header">Laboratory Registration Form</h5>
                <div class="card-body">
                    <form class="row g-3" id="labCreateForm" action="{{ route('laboratorie.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="lab_name" id="lab_name" class="form-control"
                                    placeholder="Laboratory Name" onblur="trimFieldValue('laboratory_name')" />
                                <label for="lab_name">Laboratory Name</label>
                            </div>
                            @error('lab_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        {{-- Owner Name --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="owner_name" id="owner_name" class="form-control"
                                    placeholder="Owner Name" onblur="trimFieldValue('owner_name')" />
                                <label for="owner_name">Owner Name</label>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Email" />
                                <label for="email">Email</label>
                            </div>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone"
                                    pattern="^\d{7,12}$"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)" />
                                <label for="phone">Phone</label>
                            </div>
                        </div>

                        {{-- Username --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="username" id="username" class="form-control"
                                    placeholder="Username" />
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
                                    <span class="input-group-text cursor-pointer"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                        </div>

                        {{-- City --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="city" id="city" class="form-control" placeholder="City"
                                    onblur="trimFieldValue('city')" />
                                <label for="city">City</label>
                            </div>
                        </div>

                        {{-- Pincode --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="pincode" id="pincode" class="form-control"
                                    placeholder="Pincode" pattern="^\d{6}$"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)" />
                                <label for="pincode">Pincode</label>
                            </div>
                        </div>

                        {{-- State --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="state" id="state" class="form-control"
                                    placeholder="State" />
                                <label for="state">State</label>
                            </div>
                        </div>

                        {{-- Latitude --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="latitude" id="latitude" class="form-control"
                                    placeholder="Latitude"
                                    oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)" />
                                <label for="latitude">Latitude</label>
                            </div>
                        </div>

                        {{-- Longitude --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="longitude" id="longitude" class="form-control"
                                    placeholder="Longitude"
                                    oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)" />
                                <label for="longitude">Longitude</label>
                            </div>
                        </div>

                        {{-- License --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="license" id="license" class="form-control"
                                    placeholder="Drug License No." />
                                <label for="license">Drug License No.</label>
                            </div>
                        </div>

                        {{-- GST  --}}

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="gstno" id="gstno" class="form-control"
                                    placeholder="Gst No." />
                                <label for="gstno">GST No.</label>
                            </div>
                        </div>

                        {{-- NABL ISO Certified  --}}



                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="nabl_iso_certified" name="nabl_iso_certified" class="form-select select2"
                                    data-allow-clear="true">
                                    <option value="" disabled
                                        {{ old('nabl_iso_certified') === null ? 'selected' : '' }}>
                                        Select an option</option>
                                    <option value="1" {{ old('nabl_iso_certified') == '1' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="0" {{ old('nabl_iso_certified') == '0' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                                <label for="nabl_iso_certified">NABL ISO Certified (Yes/No)</label>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Pickup Available</label>
                            <div class="form-check custom mb-2">
                                <input class="form-check-input" type="radio" name="pickup" id="pickup_yes"
                                    value="1" {{ isset($laboratory) && $laboratory->pickup == 1 ? 'checked' : '' }}
                                    required data-parsley-required-message="Please select if pickup is available."
                                    data-parsley-errors-container="#pickup-error">
                                <label class="form-check-label" for="pickup_yes">Yes</label>
                            </div>
                            <div class="form-check custom">
                                <input class="form-check-input" type="radio" name="pickup" id="pickup_no"
                                    value="0" {{ isset($laboratory) && $laboratory->pickup == 0 ? 'checked' : '' }}
                                    required data-parsley-required-message="Please select if pickup is available."
                                    data-parsley-errors-container="#pickup-error">
                                <label class="form-check-label" for="pickup_no">No</label>
                            </div>
                        </div>




                        {{-- Image --}}
                        <div class="col-md-6">
                            <label class="fw-semibold mb-1 " for="image">Upload Image</label>
                            <input type="file" name="image" id="image" class="form-control " />
                        </div>

                        {{-- Address --}}
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <textarea name="address" id="address" class="form-control" placeholder="Address" style="height: 100px;"></textarea>
                                <label for="address">Address</label>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-12 ">

                                <div class="accordion " id="collapsibleSection">
                                    <div class="card accordion-item border border-dark rounded ">
                                        <h2 class="accordion-header  sticky-element bg-label-secondary rounded d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row "
                                            id="headingDeliveryAddress">
                                            <button type="button" class="accordion-button rounded-top "
                                                data-bs-toggle="collapse" data-bs-target="#collapseDeliveryAddress"
                                                aria-expanded="true" aria-controls="collapseDeliveryAddress"
                                                style="background-color:#033a62;color:white;">
                                                Add Test Details
                                                <span class="ms-2 icon-toggle">
                                                    <!-- Default Down Arrow -->
                                                    <i class="fa-solid fa-chevron-down"></i>
                                                </span>
                                            </button>
                                        </h2>
                                        <div id="collapseDeliveryAddress" class="accordion-collapse collapse show"
                                            data-bs-parent="#collapsibleSection">
                                            <div class="accordion-body rounded-bottom" style="background-color: #e9ebee;">
                                                <div class="row g-4">
                                                    <div class="col-md-12 mt-4" id="formRepeater">
                                                        <div class="row g-3 align-items-end mb-3 repeater-group">
                                                            <div class="col-md-2">
                                                                <label for="brand-select"
                                                                    class="form-label fw-semibold">Test</label>
                                                                <select name="test[]" class="form-select p-8-4  "
                                                                    required>
                                                                    <option value="">Select Test</option>
                                                                    @foreach ($tests as $test)
                                                                        <option value="{{ $test->id }}">
                                                                            {{ $test->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Visiting
                                                                    Price</label>
                                                                <input type="number" class="form-control" name="price[]"
                                                                    min="0" placeholder="e.g. 10 Rs" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Home Price</label>
                                                                <input type="number" class="form-control"
                                                                    name="homeprice[]" min="0"
                                                                    placeholder="e.g. 15 Rs" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Report Time</label>
                                                                <input type="text" class="form-control"
                                                                    name="report[]" min="0"
                                                                    placeholder="e.g. 15 hrs" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Offer Visiting
                                                                    Price</label>
                                                                <input type="text" class="form-control"
                                                                    name="offer_visiting_price[]" min="0"
                                                                    placeholder="e.g. 5 Rs" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Offer Home
                                                                    Price</label>
                                                                <input type="text" class="form-control"
                                                                    name="offer_home_price[]" min="0"
                                                                    placeholder="e.g. 10 Rs" required>
                                                            </div>
                                                            <div class="col-md-2 d-flex align-items-end gap-2">
                                                                <!-- Remove Button -->
                                                                {{-- <button type="button" onclick="removeField(this)"
                                                                    class="btn btn-danger waves-effect waves-light">
                                                                    Remove
                                                                </button> --}}

                                                                <!-- Add Button -->
                                                                <button type="button" onclick="addField()"
                                                                    class="btn btn-success waves-effect waves-light">
                                                                    Add
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card accordion-item mt-3">
                                        <h2 class="accordion-header" id="headingPackage">
                                            <button type="button" class="accordion-button collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#collapsePackage"
                                                aria-expanded="false" aria-controls="collapsePackage"
                                                style="background-color:#033a62;color:white;">
                                                Add Package Details
                                                <span class="ms-2 icon-toggle">
                                                    <i class="fa-solid fa-chevron-down"></i>
                                                </span>
                                            </button>
                                        </h2>
                                        <div id="collapsePackage" class="accordion-collapse collapse"
                                            aria-labelledby="headingPackage" data-bs-parent="#collapsibleSection">
                                            <div class="accordion-body rounded-bottom" style="background-color: #e9ebee;">
                                                <div class="row g-4">
                                                    <div class="col-md-12 mt-4" id="packageRepeater">
                                                        <div class="row g-3 align-items-end mb-3 repeater-group">
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Package Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="package_name[0]"
                                                                    placeholder="Enter Package Name" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Visiting
                                                                    Price</label>
                                                                <input type="number" class="form-control"
                                                                    name="package_visiting_price[0]"
                                                                    placeholder="e.g. 10 Rs" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Home Price</label>
                                                                <input type="number" class="form-control"
                                                                    name="package_home_price[0]" placeholder="e.g. 15 Rs"
                                                                    required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Report Time</label>
                                                                <input type="text" class="form-control"
                                                                    name="package_report[0]" placeholder="e.g. 15 hrs"
                                                                    required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Offer Visiting
                                                                    Price</label>
                                                                <input type="text" class="form-control"
                                                                    name="package_offer_visiting_price[0]"
                                                                    placeholder="e.g. 5 Rs" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Offer Home
                                                                    Price</label>
                                                                <input type="text" class="form-control"
                                                                    name="package_offer_home_price[0]"
                                                                    placeholder="e.g. 10 Rs" required>
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <label class="form-label fw-semibold">Description</label>
                                                                <div class="snow-editor" id="editor-0"></div>
                                                                <input type="hidden" name="package_description[0]"
                                                                    class="description" />
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Category</label>
                                                                <div class="d-flex flex-wrap gap-3">
                                                                    @foreach ($categories as $category)
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                name="package_category[0][]"
                                                                                value="{{ $category->id }}"
                                                                                id="cat0_{{ $category->id }}">
                                                                            <label class="form-check-label"
                                                                                for="cat0_{{ $category->id }}">
                                                                                {{ $category->name }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 d-flex align-items-end gap-2">
                                                                {{-- <button type="button" onclick="removePackageField(this)" class="btn btn-danger">Remove</button> --}}
                                                                <button type="button" onclick="addPackageField()"
                                                                    class="btn btn-success">Add</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="card-action">
                            <button type="submit" class="btn btn-primary submit_btn">Save</button>
                            <button type="button" class="btn btn-primary"
                                onclick="window.location='{{ route('laboratorie.index') }}'">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        
@endsection
@section('scripts')
    <script src="{{ asset('js/laboratorie/laboratorie_form.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/form-layouts.js') }}"></script> --}}
    <script src="{{ asset('assets/js/forms-editors.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
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

        // form repeater-group
        function addField() {
            const repeater = document.getElementById('formRepeater');
            const newGroup = document.createElement('div');
            newGroup.className = 'row g-3 align-items-end mb-3 repeater-group';

            newGroup.innerHTML = `
        <div class="col-md-2">
            <label for="brand-select" class="form-label fw-semibold">Test</label>
            <select name="test[]" class="form-select p-8-4" required>
                <option value="">Select Test</option>
                @foreach ($tests as $test)
                    <option value="{{ $test->id }}">{{ $test->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Visiting Price</label>
            <input type="number" class="form-control" name="price[]" min="0"
                placeholder="e.g. 10 Rs" required>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Home Price</label>
            <input type="number" class="form-control" name="homeprice[]" min="0"
                placeholder="e.g. 15 Rs" required>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Report Time</label>
            <input type="number" class="form-control" name="report[]" min="0"
                placeholder="e.g. 15 hrs" required>
        </div>

         <div class="col-md-2">
                                    <label class="form-label fw-semibold">Offer Visiting Price</label>
                                    <input type="text" class="form-control" name="offer_visiting_price[]" min="0"
                                        placeholder="e.g. 5 Rs" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Offer Home Price</label>
                                    <input type="text" class="form-control" name="offer_home_price[]" min="0"
                                        placeholder="e.g. 10 Rs" required>
                                </div>
        <div class="col-md-2 d-flex align-items-end gap-2">
            <!-- Remove Button -->
            <button type="button" onclick="removeField(this)"  class="btn btn-danger waves-effect waves-light">
               Remove
            </button>

            <!-- Add Button -->
            <button type="button" onclick="addField()" class="btn btn-success waves-effect waves-light">
               Add
            </button>
        </div>
    `;

            repeater.appendChild(newGroup);
        }

        function removeField(button) {
            const repeater = document.getElementById('formRepeater');
            const groups = repeater.querySelectorAll('.repeater-group');
            if (groups.length > 1) {
                const group = button.closest('.repeater-group');
                if (group) group.remove();
            }
            // else {
            //     alert("At least one test must remain.");
            // }
        }

        let packageIndex = 1;

        // Initialize editor for index 0
        const quill0 = new Quill("#editor-0", {
            theme: "snow"
        });
        quill0.on('text-change', function() {
            document.querySelector('input[name="package_description[0]"]').value = quill0.root.innerHTML;
        });

        function addPackageField() {
            const repeater = document.getElementById('packageRepeater');
            const newGroup = document.createElement('div');
            newGroup.className = 'row g-3 align-items-end mb-3 repeater-group';
            newGroup.innerHTML = `
            <div class="col-md-2">
                <label class="form-label fw-semibold">Package Name</label>
                <input type="text" class="form-control" name="package_name[${packageIndex}]" placeholder="Enter Package Name" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Visiting Price</label>
                <input type="number" class="form-control" name="package_visiting_price[${packageIndex}]" placeholder="e.g. 10 Rs" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Home Price</label>
                <input type="number" class="form-control" name="package_home_price[${packageIndex}]" placeholder="e.g. 15 Rs" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Report Time</label>
                <input type="text" class="form-control" name="package_report[${packageIndex}]" placeholder="e.g. 15 hrs" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Offer Visiting Price</label>
                <input type="text" class="form-control" name="package_offer_visiting_price[${packageIndex}]" placeholder="e.g. 5 Rs" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Offer Home Price</label>
                <input type="text" class="form-control" name="package_offer_home_price[${packageIndex}]" placeholder="e.g. 10 Rs" required>
            </div>
            <div class="col-md-12 mt-3">
                <label class="form-label fw-semibold">Description</label>
                <div class="snow-editor" id="editor-${packageIndex}"></div>
                <input type="hidden" name="package_description[${packageIndex}]" class="description" />
            </div>
            <div class="col-md-12">
                <label class="form-label fw-semibold">Category</label>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($categories as $category)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                type="checkbox"
                                name="package_category[${packageIndex}][]"
                                value="{{ $category->id }}"
                                id="cat${packageIndex}_{{ $category->id }}">
                            <label class="form-check-label" for="cat${packageIndex}_{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="button" onclick="removePackageField(this)" class="btn btn-danger">Remove</button>
                <button type="button" onclick="addPackageField()" class="btn btn-success">Add</button>
            </div>
        `;
            repeater.appendChild(newGroup);

            const quill = new Quill(`#editor-${packageIndex}`, {
                theme: 'snow'
            });
            quill.on('text-change', function() {
                newGroup.querySelector('.description').value = quill.root.innerHTML;
            });

            packageIndex++;
        }

        function removePackageField(button) {
            button.closest('.repeater-group').remove();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll(".accordion-button");

            buttons.forEach(button => {
                button.addEventListener("click", function() {
                    const icon = button.querySelector(".icon-toggle i");
                    if (button.classList.contains("collapsed")) {
                        icon.classList.remove("fa-chevron-up");
                        icon.classList.add("fa-chevron-down");
                    } else {
                        icon.classList.remove("fa-chevron-down");
                        icon.classList.add("fa-chevron-up");
                    }
                });
            });
        });
    </script>
@endsection
