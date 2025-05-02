@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
 
@endsection
@section('content')

    <div class="col-12">
        <div class="card">
            <h5 class="card-header">User From</h5>
            <div class="card-body">
                <form class="row g-3" action="{{ route('user.store') }}" method="POST"
                    id="userCreateForm">
                    @csrf
   
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="name" class="form-control" placeholder="John Doe"
                                name="name" />
                            <label for="name">Full Name</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="email" id="email" name="email"
                                placeholder="john.doe" />
                            <label for="email">Email</label>
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" class="form-control" type="password" id="password" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="multicol-password2" />
                                    <label for="password">Password</label>
                                </div>
                                <span class="input-group-text cursor-pointer" id="multicol-password2"><i
                                        class="mdi mdi-eye-off-outline"></i></span>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select id="role_id" name="role_id" class="form-select select2" data-allow-clear="true">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <label for="role_id">Role</label>
                        </div>
                    </div>


                    <div class="card-action">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-primary" onclick="window.location='{{ route('user.index') }}'">Cancel</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
<script src="{{ asset('js/user/user_form.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection
