@extends('layouts.app')
@section('styles')
    <style>
        .cust-icon {
            padding-bottom: 20px !important;
        }

        .p-8-4 {
            padding: 8.4px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form id="myForm" class="form-horizontal" action="{{ route('laboratorie.update', $laboratorie->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white rounded-top">
                        <h4 class="card-title text-white">Update Laboratory</h4>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Laboratory Name -->
                            <div class="col-md-6">
                                <label for="lab_name" class="fw-semibold">Laboratory Name</label>
                                <input type="text" name="lab_name" class="form-control" id="lab_name"
                                    value="{{ old('lab_name', $laboratorie->lab_name) }}" required
                                    data-parsley-required-message="The laboratory name field is required."
                                    onblur="trimFieldValue('lab_name')">
                                @error('lab_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Owner Name -->
                            <div class="col-md-6">
                                <label for="owner_name" class="fw-semibold">Owner Name</label>
                                <input type="text" name="owner_name" class="form-control" id="owner_name"
                                    value="{{ old('owner_name', $laboratorie->owner_name) }}" required
                                    data-parsley-required-message="The owner name field is required."
                                    onblur="trimFieldValue('owner_name')">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{ old('email', $laboratorie->email) }}" required
                                    data-parsley-pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$"
                                    data-parsley-pattern-message="Email must be in the format like name@domain.com"
                                    data-parsley-required-message="The email field is required.">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="phone" class="fw-semibold">Phone</label>
                                <input type="tel" name="phone" class="form-control" id="phone"
                                    value="{{ old('phone', $laboratorie->phone) }}" required pattern="^\d{7,12}$"
                                    data-parsley-pattern="^\d{7,12}$"
                                    data-parsley-pattern-message="Please enter a valid phone number with 7 to 12 digits."
                                    data-parsley-required-message="The phone field is required."
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)">
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="fw-semibold">Username</label>
                                <input type="text" name="username" class="form-control" id="username"    value="{{ old('username', $laboratorie->username) }}"required
                                    data-parsley-required-message="The username field is required.">
                            </div>
                            <!-- City -->
                            <div class="col-md-6">
                                <label for="city" class="fw-semibold">City</label>
                                <input type="text" name="city" class="form-control" id="city"
                                    value="{{ old('city', $laboratorie->city) }}" required
                                    data-parsley-required-message="The city field is required."
                                    onblur="trimFieldValue('city')">
                            </div>

                            <!-- Pincode -->
                            <div class="col-md-6">
                                <label for="pincode" class="fw-semibold">Pincode</label>
                                <input type="text" name="pincode" class="form-control" id="pincode"
                                    value="{{ old('pincode', $laboratorie->pincode) }}" required
                                    data-parsley-pattern="^\d{6}$"
                                    data-parsley-pattern-message="Pincode must be exactly 6 digits."
                                    data-parsley-required-message="The pincode field is required."
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">
                            </div>

                            <!-- State -->
                            <div class="col-md-6">
                                <label for="state" class="fw-semibold">State</label>
                                <input type="text" name="state" class="form-control" id="state"
                                    value="{{ old('state', $laboratorie->state) }}" required
                                    data-parsley-required-message="The state field is required."
                                    onblur="trimFieldValue('state')">
                            </div>

                            <!-- Latitude -->
                            <div class="col-md-6">
                                <label for="latitude" class="fw-semibold">Latitude</label>
                                <input type="text" name="latitude" class="form-control" id="latitude"
                                    value="{{ old('latitude', $laboratorie->latitude) }}" required
                                    data-parsley-required-message="The latitude field is required."
                                    data-parsley-pattern="^-?(90(\.0+)?|[1-8]?\d(\.\d+)?|0(\.\d+)?)$"
                                    data-parsley-pattern-message="Latitude must be a number between -90 and 90 with up to 10 decimal places."
                                    oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)">
                            </div>

                            <!-- Longitude -->
                            <div class="col-md-6">
                                <label for="longitude" class="fw-semibold">Longitude</label>
                                <input type="text" name="longitude" class="form-control" id="longitude"
                                    value="{{ old('longitude', $laboratorie->longitude) }}" required
                                    data-parsley-required-message="The longitude field is required."
                                    data-parsley-pattern="^-?(180(\.0+)?|1[0-7]\d(\.\d+)?|[1-9]?\d(\.\d+)?)$"
                                    data-parsley-pattern-message="Longitude must be a number between -180 and 180 with up to 10 decimal places."
                                    oninput="this.value=this.value.replace(/[^0-9.-]/g,'').slice(0,10)">
                            </div>

                            <!-- License -->
                            <div class="col-md-6">
                                <label for="license" class="fw-semibold">License No.</label>
                                <input type="text" name="license" class="form-control" id="license"
                                    value="{{ old('license', $laboratorie->license) }}" required
                                    data-parsley-required-message="The drug license no. field is required."
                                    data-parsley-pattern="^[A-Za-z0-9/-]+$"
                                    data-parsley-pattern-message="The drug license number can only contain letters, numbers, hyphens, and slashes.">
                            </div>
                            <div class="col-md-6">
                                <label for="nabl_iso_certified" class="form-label fw-semibold">NABL ISO Certified (Yes/No)</label>
                                <select class="form-select" id="nabl_iso_certified" name="nabl_iso_certified" required>
                                    <option value="" disabled {{ old('nabl_iso_certified', $laboratorie->nabl_iso_certified) === null ? 'selected' : '' }}>Select an option</option>
                                    <option value="1" {{ old('nabl_iso_certified', $laboratorie->nabl_iso_certified) == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('nabl_iso_certified', $laboratorie->nabl_iso_certified) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            

                            @php
                                $pickupValue = old('pickup', isset($laboratorie) ? $laboratorie->pickup : '');
                            @endphp

                            <div class="form-group col-md-6">
                                <label>Pickup Available</label><br>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pickup" id="pickup_yes"
                                        value="1" {{ $pickupValue == '1' ? 'checked' : '' }} required
                                        data-parsley-required-message="Please select if pickup is available."
                                        data-parsley-errors-container="#pickup-error">
                                    <label class="form-check-label" for="pickup_yes">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pickup" id="pickup_no"
                                        value="0" {{ $pickupValue == '0' ? 'checked' : '' }} required
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
                                        <img src="{{ asset('assets/image/' . $laboratorie->image) }}" alt="Labrotry Image" class="img-thumbnail"
                                            style="max-height: 150px;">
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control"
                                    data-parsley-required="{{ $laboratorie->image ? 'false' : 'true' }}"
                                    data-parsley-required-message="The image field is required.">
                                <small class="text-muted">Leave blank to keep existing image</small>
                            </div>

                            <div class="col-md-6">
                                <label for="gst" class="fw-semibold">GST No.</label>
                                <input type="text" name="gstno" class="form-control" id="gst"
                                    value="{{ old('gstno', $laboratorie->gstno) }}" required
                                    data-parsley-required-message="The GST no. field is required."
                                    data-parsley-pattern="^[A-Za-z0-9/-]+$"
                                    data-parsley-pattern-message="The drug license number can only contain letters, numbers, hyphens, and slashes.">
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                <label for="address" class="fw-semibold">Address</label>
                                <textarea name="address" id="address" rows="3" class="form-control" required data-parsley-minlength="10"
                                    data-parsley-required-message="The address field is required." onblur="trimFieldValue('address')">{{ old('address', $laboratorie->address) }}</textarea>
                            </div>

                            <!-- Test Details -->
                            <div id="formRepeater">
                                @foreach ($labTests as $index => $item)
                                    <div class="row g-3 align-items-end mb-3 repeater-group">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Test</label>
                                            <select name="test[]" class="form-select" required>
                                                <option value="">Select Test</option>
                                                @foreach ($allTests as $test)
                                                    <option value="{{ $test->id }}" {{ $test->id == $item['test'] ? 'selected' : '' }}>
                                                        {{ $test->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Price</label>
                                            <input type="number" class="form-control" name="price[]" min="0" value="{{ $item['price'] ?? '' }}" placeholder="Price" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Home Price</label>
                                            <input type="number" class="form-control" name="homeprice[]" min="0" value="{{ $item['homeprice'] ?? '' }}" placeholder="Home Price" required>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end gap-2">
                                            <button type="button" onclick="removeField(this)" class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                <i class="bi bi-dash-lg"></i>
                                            </button>
                                            <button type="button" onclick="addField()" class="btn btn-outline-success btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                           
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            

                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Update Laboratory</button>
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
            <div class="col-md-4">
                <label class="form-label fw-semibold">Test</label>
                <select name="test[]" class="form-select" required>
                    <option value="">Select Test</option>
                    ${optionsHtml}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Price</label>
                <input type="number" class="form-control" name="price[]" min="0" placeholder="Price" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Home Price</label>
                <input type="number" class="form-control" name="homeprice[]" min="0" placeholder="Home Price" required>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="button" onclick="removeField(this)" class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-dash-lg"></i>
                </button>
                <button type="button" onclick="addField()" class="btn btn-outline-success btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-plus-lg"></i>
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


