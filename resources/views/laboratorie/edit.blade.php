@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection
@section('content')
    <div class="container">

        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Laboratory Update Form</h5>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="row g-3" id="labCreateForm"
                        action="{{ route('laboratorie.update', $laboratorie->id) }}"method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- Laboratory Name --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="lab_name" id="lab_name" class="form-control"
                                    placeholder="Laboratory Name" onblur="trimFieldValue('laboratory_name')"
                                    value="{{ $laboratorie->lab_name }}" />
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
                                    placeholder="Owner Name" onblur="trimFieldValue('owner_name')"
                                    value="{{ $laboratorie->owner_name }}" />
                                <label for="owner_name">Owner Name</label>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email"
                                    value="{{ $laboratorie->email }}" />
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
                                    pattern="^\d{7,12}$" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)"
                                    value="{{ $laboratorie->phone }}" />
                                <label for="phone">Phone</label>
                            </div>
                        </div>

                        {{-- Username --}}
                        {{-- <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username"
                                value="{{ $laboratorie->username }}" />
                            <label for="username">Username</label>
                        </div>
                    </div> --}}

                        {{-- Password --}}
                        {{-- <div class="col-md-6">
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
                    </div> --}}

                        {{-- City --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="city" id="city" class="form-control" placeholder="City"
                                    onblur="trimFieldValue('city')" value="{{ $laboratorie->city }}" />
                                <label for="city">City</label>
                            </div>
                        </div>

                        {{-- Pincode --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="pincode" id="pincode" class="form-control"
                                    placeholder="Pincode" pattern="^\d{6}$"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)"
                                    value="{{ $laboratorie->pincode }}" />
                                <label for="pincode">Pincode</label>
                            </div>
                        </div>

                        {{-- State --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="state" id="state" class="form-control" placeholder="State"
                                    value="{{ $laboratorie->state }}" />
                                <label for="state">State</label>
                            </div>
                        </div>

                        {{-- Latitude --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="latitude" id="latitude" class="form-control"
                                    placeholder="Latitude"
                                    oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)"
                                    value="{{ $laboratorie->latitude }}" />
                                <label for="latitude">Latitude</label>
                            </div>
                        </div>

                        {{-- Longitude --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="longitude" id="longitude" class="form-control"
                                    placeholder="Longitude"
                                    oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)"
                                    value="{{ $laboratorie->longitude }}" />
                                <label for="longitude">Longitude</label>
                            </div>
                        </div>

                        {{-- License --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="license" id="license" class="form-control"
                                    placeholder="Drug License No." value="{{ $laboratorie->license }}" />
                                <label for="license">Drug License No.</label>
                            </div>
                        </div>

                        {{-- GST  --}}

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="gstno" id="gstno" class="form-control"
                                    placeholder="Gst No." value="{{ $laboratorie->gstno }}" />
                                <label for="gstno">GST No.</label>
                            </div>
                        </div>

                        {{-- NABL ISO Certified  --}}



                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="nabl_iso_certified" name="nabl_iso_certified" class="form-select select2"
                                    data-allow-clear="true">
                                    <option value="" disabled
                                        {{ old('nabl_iso_certified') === null && isset($laboratory) && $laboratory->nabl_iso_certified === null ? 'selected' : '' }}>
                                        Select an option
                                    </option>
                                    <option value="1"
                                        {{ old('nabl_iso_certified', $laboratory->nabl_iso_certified ?? '') == '1' ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                    <option value="0"
                                        {{ old('nabl_iso_certified', $laboratory->nabl_iso_certified ?? '') == '0' ? 'selected' : '' }}>
                                        No
                                    </option>
                                </select>

                                <label for="nabl_iso_certified">NABL ISO Certified (Yes/No)</label>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Pickup Available</label>
                            <div class="form-check custom mb-2">
                                <input class="form-check-input" type="radio" name="pickup" id="pickup_yes"
                                    value="1" @if ($laboratorie->pickup == '1') checked @endif>
                                <label class="form-check-label" for="pickup_yes">Yes</label>
                            </div>
                            <div class="form-check custom">
                                <input class="form-check-input" type="radio" name="pickup" id="pickup_no"
                                    value="0" @if ($laboratorie->pickup == '0') checked @endif>
                                <label class="form-check-label" for="pickup_no">No</label>
                            </div>
                        </div>
                        {{-- Image --}}
                        <div class="col-md-6">
                            @if ($laboratorie->image)
                                <div class="mb-2">
                                    <img id="image" src="{{ asset('assets/image/' . $laboratorie->image) }}"
                                        alt="Labrotry Image" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <label class="fw-semibold mb-1 " for="image">Upload Image</label>
                            <input type="file" name="image" id="image" class="form-control "
                                value="{{ $laboratorie->image }}" />
                        </div>

                        {{-- Address --}}
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <textarea name="address" id="address" class="form-control" placeholder="Address" style="height: 100px;">{{ $laboratorie->address }}</textarea>
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
                                                Update Test Details
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
                                                    <div id="formRepeater">
                                                        @foreach ($labTests as $index => $item)
                                                            <div class="row g-3 align-items-end mb-3 repeater-group">
                                                                <div class="col-md-2">
                                                                    <label class="form-label fw-semibold">Test</label>
                                                                    <select name="test[]" class="form-select" required>
                                                                        <option value="">Select Test</option>
                                                                        @foreach ($allTests as $test)
                                                                            <option value="{{ $test->id }}"
                                                                                {{ $test->id == $item['test'] ? 'selected' : '' }}>
                                                                                {{ $test->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label fw-semibold">Visiting
                                                                        Price</label>
                                                                    <input type="number" class="form-control"
                                                                        name="price[]" min="0"
                                                                        value="{{ $item['price'] ?? '' }}"
                                                                        placeholder="e.g. 10 Rs" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label fw-semibold">Home
                                                                        Price</label>
                                                                    <input type="number" class="form-control"
                                                                        name="homeprice[]" min="0"
                                                                        value="{{ $item['homeprice'] ?? '' }}"
                                                                        placeholder="e.g. 15 Rs" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label fw-semibold">Report
                                                                        Time</label>
                                                                    <input type="text" class="form-control"
                                                                        name="report[]" min="0"
                                                                        value="{{ $item['report'] ?? '' }}"
                                                                        placeholder="Home Price" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label fw-semibold">Offer Visiting
                                                                        Price</label>
                                                                    <input type="text" class="form-control"
                                                                        name="offer_visiting_price[]" min="0"
                                                                        value="{{ $item['offer_visiting_price'] ?? '' }}"
                                                                        placeholder="e.g. 5 Rs" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label fw-semibold">Offer Home
                                                                        Price</label>
                                                                    <input type="text" class="form-control"
                                                                        name="offer_home_price[]" min="0"
                                                                        value="{{ $item['offer_home_price'] ?? '' }}"
                                                                        placeholder="e.g. 10 Rs" required>
                                                                </div>
                                                                <div class="col-md-2 d-flex align-items-end gap-2">
                                                                    <button type="button" onclick="removeField(this)"
                                                                        class="btn btn-danger waves-effect waves-light">
                                                                        Remove
                                                                    </button>

                                                                    <!-- Add Button -->
                                                                    <button type="button" onclick="addField()"
                                                                        class="btn btn-success waves-effect waves-light">
                                                                        Add
                                                                    </button>

                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card accordion-item mt-3">
                                        <h2 class="accordion-header" id="headingPaymentMethod">
                                            <button type="button" class="accordion-button collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#collapsePaymentMethod"
                                                aria-expanded="false" aria-controls="collapsePaymentMethod"
                                                style="background-color:#033a62;color:white;">
                                                Add Package Details
                                                <span class="ms-2 icon-toggle">
                                                    <!-- Default Down Arrow -->
                                                    <i class="fa-solid fa-chevron-down"></i>
                                                </span>
                                            </button>
                                        </h2>
                                        <div id="collapsePaymentMethod" class="accordion-collapse collapse"
                                            aria-labelledby="headingPaymentMethod" data-bs-parent="#collapsibleSection">
                                            <div class="accordion-body rounded-bottom" style="background-color: #e9ebee;">
                                                <div class="row g-4" id="packageRepeater">
                                                    @php
                                                        $packages = $laboratorie->package_details
                                                            ? json_decode($laboratorie->package_details, true)
                                                            : [];
                                                        $cat = [];
                                                        foreach ($packages as $i => $package) {
                                                            $cat[$i] = $package['package_category'] ?? [];
                                                        }
                                                    @endphp

                                                    @forelse ($packages as $index => $package)
                                                        <div class="repeater-group row g-3 align-items-end mb-3"
                                                            data-index="{{ $index }}">
                                                            {{-- Package fields --}}
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Package Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="package_name[]" required
                                                                    value="{{ old('package_name.' . $index, $package['package_name'] ?? '') }}"
                                                                    placeholder="Enter Package Name">
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Visiting
                                                                    Price</label>
                                                                <input type="number" class="form-control"
                                                                    name="package_visiting_price[]" required
                                                                    value="{{ old('package_visiting_price.' . $index, $package['package_visiting_price'] ?? '') }}"
                                                                    placeholder="e.g. 10 Rs">
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Home Price</label>
                                                                <input type="number" class="form-control"
                                                                    name="package_home_price[]" required
                                                                    value="{{ old('package_home_price.' . $index, $package['package_home_price'] ?? '') }}"
                                                                    placeholder="e.g. 15 Rs">
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Report Time</label>
                                                                <input type="text" class="form-control"
                                                                    name="package_report[]" required
                                                                    value="{{ old('package_report.' . $index, $package['package_report'] ?? '') }}"
                                                                    placeholder="e.g. 15 hrs">
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Offer Visiting
                                                                    Price</label>
                                                                <input type="text" class="form-control"
                                                                    name="package_offer_visiting_price[]" required
                                                                    value="{{ old('package_offer_visiting_price.' . $index, $package['package_offer_visiting_price'] ?? '') }}"
                                                                    placeholder="e.g. 5 Rs">
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Offer Home
                                                                    Price</label>
                                                                <input type="text" class="form-control"
                                                                    name="package_offer_home_price[]" required
                                                                    value="{{ old('package_offer_home_price.' . $index, $package['package_offer_home_price'] ?? '') }}"
                                                                    placeholder="e.g. 10 Rs">
                                                            </div>

                                                            <div class="col-md-12 mt-3">
                                                                <label class="form-label fw-semibold">Description</label>
                                                                <div class="snow-editor" style="height: 200px;"></div>
                                                                <input type="hidden" name="package_description[]"
                                                                    class="description"
                                                                    value="{{ old('package_description.' . $index, $package['package_description'] ?? '') }}">
                                                            </div>

                                                            {{-- ✅ Dynamic Category --}}
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Category</label>
                                                                <div class="d-flex flex-wrap gap-3">
                                                                    @php $catIds = $cat[$index] ?? []; @endphp
                                                                    @foreach ($categories as $category)
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                name="package_category[{{ $index }}][]"
                                                                                value="{{ $category->id }}"
                                                                                id="cat{{ $index }}_{{ $category->id }}"
                                                                                {{ in_array($category->id, $catIds) ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="cat{{ $index }}_{{ $category->id }}">
                                                                                {{ $category->name }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 d-flex align-items-end gap-2">
                                                                <button type="button" onclick="removePackageField(this)"
                                                                    class="btn btn-danger">Remove</button>

                                                                <button type="button" onclick="addPackageField()"
                                                                    class="btn btn-success">Add</button>

                                                            </div>
                                                        </div>
                                                    @empty
                                                        {{-- Show one blank form if none exist --}}
                                                        <div class="repeater-group row g-3 align-items-end mb-3"
                                                            data-index="0">
                                                            <!-- You can copy same block above and clear value="..." if needed -->
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- Buttons --}}
                        <div class="card-action">
                            <button type="submit" class="btn btn-primary submit-btn">Update</button>
                            <button type="button" class="btn btn-primary"
                                onclick="window.location='{{ route('laboratorie.index') }}'">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/laboratorie/laboratorie_form.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Parsley for the form
            $('#myForm').parsley();
        });

        const testOptions = @json($allTests->map(fn($t) => ['id' => $t->id, 'name' => $t->name]));

        function addField() {
            const repeater = document.getElementById('formRepeater');
            const newGroup = document.createElement('div');
            newGroup.className = 'row g-3 align-items-end mb-3 repeater-group';

            const optionsHtml = testOptions.map(t => `<option value="${t.id}">${t.name}</option>`).join('');

            newGroup.innerHTML = `
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Test</label>
                            <select name="test[]" class="form-select" required>
                                <option value="">Select Test</option>
                                ${optionsHtml}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Visiting Price</label>
                            <input type="number" class="form-control" name="price[]" min="0" placeholder="e.g. 10 Rs" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Home Price</label>
                            <input type="number" class="form-control" name="homeprice[]" min="0" placeholder="e.g. 15 Rs" required>
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
                button.closest('.repeater-group').remove();
            }
        }


        function addPackageField() {
            const repeater = document.getElementById('packageRepeater');
            const allGroups = repeater.querySelectorAll('.repeater-group');
            const newIndex = allGroups.length;

            const firstGroup = allGroups[0];
            const newGroup = firstGroup.cloneNode(true);

            // Clear input values
            newGroup.querySelectorAll('input, textarea, select').forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });

            // Update the index
            newGroup.setAttribute('data-index', newIndex);

            // Update names and IDs for inputs and labels
            newGroup.querySelectorAll('[name]').forEach(input => {
                if (input.name.startsWith('package_category')) {
                    input.name = `package_category[${newIndex}][]`;
                } else if (input.name.includes('package_description')) {
                    input.name = 'package_description[]';
                } else {
                    const baseName = input.name.replace(/\[\]$/, '');
                    input.name = `${baseName}[]`;
                }
            });

            // Update IDs and "for" attributes of checkboxes
            newGroup.querySelectorAll('.form-check-input').forEach((checkbox) => {
                const catId = checkbox.value;
                const newId = `cat${newIndex}_${catId}`;
                const label = newGroup.querySelector(`label[for="${checkbox.id}"]`);

                checkbox.id = newId;
                if (label) {
                    label.setAttribute('for', newId);
                }
            });

            repeater.appendChild(newGroup);
        }

        function removePackageField(button) {
            const group = button.closest('.repeater-group');
            const repeater = document.getElementById('packageRepeater');
            if (repeater.querySelectorAll('.repeater-group').length > 1) {
                group.remove();
            } else {
                alert("At least one package is required.");
            }
        }
        //Snow editor Code
        let editors = [];

        // Initialize all Quill editors and prefill from hidden inputs
        $('.snow-editor').each(function(index) {
            let quill = new Quill(this, {
                bounds: this,
                theme: 'snow',
                placeholder: 'Enter description here...'
            });

            // Prefill from existing hidden input value
            let html = $('.description').eq(index).val();
            if (html && html.trim().length > 0) {
                quill.root.innerHTML = html;
            }

            editors.push(quill);
        });


        $('.submit-btn').on('click', function(event) {
            let valid = true;

            editors.forEach(function(editor, index) {
                let html = editor.root.innerHTML.trim();
                let text = editor.getText().trim();

                if (text.length === 0) {
                    alert('Description cannot be empty in entry #' + (index + 1));
                    valid = false;
                }

                $('.description').eq(index).val(html);
            });

            if (!valid) {
                event.preventDefault(); // Prevent form if any description is empty
            }
        });


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
