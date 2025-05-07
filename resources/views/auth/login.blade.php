@extends('layouts.guest')
@section('styles')
@endsection
@section('content')
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic ">
        <div class="authentication-inner">
            <!-- Login -->
            <div class="card p-2">
                <!-- Logo -->
                <div class="app-brand justify-content-center mt-5">
                    <img src="{{ asset('assets/img/gomeds.png') }}" class="main-logo" alt="">
                </div>
                <!-- /Logo -->

                <div class="card-body mt-2">
                    

                    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email Error --}}
                        @error('email')
                            <p class="text-danger mb-1 text-start" role="alert">
                                {{ $message }}
                            </p>
                        @enderror

                        <div class="form-floating form-floating-outline mb-3 text-start">
                            <input type="text" autocomplete="off" class="form-control" id="email" name="email"
                                placeholder="Enter your email" autofocus
                                value="{{ old('email', request()->cookie('email')) }}" />
                            <label for="email">Email</label>
                        </div>

                        {{-- Password Field --}}
                        <div class="mb-3 text-start">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" autocomplete="off" id="password" class="form-control"
                                            name="password"
                                            placeholder="••••••••••"
                                            aria-describedby="password"
                                            value="{{ request()->cookie('password') }}" />
                                        <label for="password">Password</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                                @error('password')
                                    <p class="text-danger mt-2 mb-0">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100 signButton" type="submit">Sign in</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection









