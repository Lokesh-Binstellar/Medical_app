{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <form class="max-w-sm mx-auto" action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
        </div>

        <div class="mb-5">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
        </div>
        <div class="mb-5">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">password</label>
            <input type="password" id="password" name="password"  value="{{ $user->password }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter password" required />
          </div>

        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Update</button>
    </form>
</x-app-layout> --}}
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <form class="max-w-sm mx-auto" action="{{ route('user.update', $user->id) }}" method="POST" id="userEditForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">User Form</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <!-- Name -->
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" value="{{ $user->name }}"
                                                class="form-control" id="name" required
                                                data-parsley-required-message="The name field is required." />
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" value="{{ $user->email }}"
                                                class="form-control" id="email" required
                                                data-parsley-pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$"
                                                data-parsley-pattern-message="Email must be valid."
                                                data-parsley-required-message="The email field is required." />
                                        </div>




                                        <!-- Password (optional) -->
                                        <div class="form-group">
                                            <label for="password">New Password</label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                placeholder="Leave blank to keep current password"
                                                data-parsley-minlength="8"
                                                data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).+$"
                                                data-parsley-pattern-message="Password must contain uppercase, lowercase, number, and special character.">
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                id="password_confirmation" data-parsley-equalto="#password"
                                                data-parsley-equalto-message="Passwords do not match.">
                                        </div>


                                        <!-- Role -->


                                        <div class="form-group mb-3">
                                            <label for="inputGroupSelect01">Role</label>
                                            <select class="form-control select2" id="inputGroupSelect01" name="role_id"
                                                style="appearance: auto;" required
                                                data-parsley-required-message="The role field is required.">
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>



                                    </div>

                                </div>
                            </div>

                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Wait for the DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Parsley for the form
            $('#userEditForm').parsley();
        });
    </script>


    <script>
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');

        // 🔹 Trim name only when user leaves the field (on blur)
        nameInput.addEventListener('blur', function() {
            this.value = this.value.trim(); // only trims start & end spaces
        });

        // 🔹 Block all spaces in email while typing
        emailInput.addEventListener('keypress', function(e) {
            if (e.key === ' ') {
                e.preventDefault();
            }
        });

        // 🔹 Remove spaces if user pastes something in email
        emailInput.addEventListener('input', function() {
            this.value = this.value.replace(/\s+/g, '');
        });
    </script>
@endsection
