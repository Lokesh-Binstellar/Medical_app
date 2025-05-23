@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
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
                                        <label class="form-label fw-semibold">Visiting Price</label>
                                        <input type="number" class="form-control" name="price[]" min="0"
                                            value="{{ $item['price'] ?? '' }}" placeholder="e.g. 10 Rs" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Home Price</label>
                                        <input type="number" class="form-control" name="homeprice[]" min="0"
                                            value="{{ $item['homeprice'] ?? '' }}" placeholder="e.g. 15 Rs" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Report Time</label>
                                        <input type="text" class="form-control" name="report[]" min="0"
                                            value="{{ $item['report'] ?? '' }}" placeholder="Home Price" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Offer Visiting Price</label>
                                        <input type="text" class="form-control" name="offer_visiting_price[]"
                                            min="0" value="{{ $item['offer_visiting_price'] ?? '' }}"
                                            placeholder="e.g. 5 Rs" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Offer Home Price</label>
                                        <input type="text" class="form-control" name="offer_home_price[]"
                                            min="0" value="{{ $item['offer_home_price'] ?? '' }}"
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
                        {{-- Buttons --}}
                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Update</button>
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
    </script>
@endsection
