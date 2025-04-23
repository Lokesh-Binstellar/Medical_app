{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
<form class="max-w-sm mx-auto" action="{{ route('user.store') }}" method="POST">
    @csrf
    <div class="mb-5">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label>
        <input type="text" name="name" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter Name" required />
      </div>
  <div class="mb-5">
    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your email</label>
    <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required />
  </div>
  <div class="mb-5">
    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">password</label>
    <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter password" required />
  </div>
  
  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
</form>
</x-app-layout> --}}





@extends('layouts.app')
{{-- @section('content')
    <div class="container">
        <div class="page-inner">
            <form class="max-w-sm mx-auto" action="{{ route('user.store') }}" method="POST">
                @csrf

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
                                            <input type="text" name="name" class="form-control" id="name"
                                                required />
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                required />
                                        </div>
                                        <div class="form-group">
                                            <label for="password">password</label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                required />
                                        </div>
                                        <div class="form-group input-group mb-3">
                                          
                                    
                                          <label class="" for="inputGroupSelect01">Role:</label>
                                          <select class="custom-select " id="inputGroupSelect01" name="role_id" class="form-control">
                                              @foreach ($roles as $role)
                                                  <option value="{{ $role->id }}">{{ $role->name }}</option>
                                              @endforeach
                                          </select>
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
    </div>
@endsection --}}

@section('content')
    <div class="container">
        <div class="page-inner">
            {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li> 
                    @endforeach
                </ul>
            </div>
        @endif --}}
            <form class="max-w-sm mx-auto" action="{{ route('user.store') }}" method="POST" id="userCreateForm">
                @csrf

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
                                            <input type="text" name="name" class="form-control" id="name" required
                                                
                                                data-parsley-required-message="The name field is required." />
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                required
                                                data-parsley-pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$"
                                                data-parsley-pattern-message="Email must be in the format like name@domain.com"
                                                data-parsley-required-message="The email field is required." />
                                        
                                            {{-- Laravel backend error --}}
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        

                                        <!-- Password -->
                                        <div class="form-group">
                                            <label for="password">password</label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                required data-parsley-minlength="8"
                                                data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).+$"
                                                data-parsley-pattern-message="Password must contain uppercase, lowercase, number, and special character."
                                                data-parsley-required-message="The password field is required." />

                                        </div>

                                        
                                        <div class="form-group mb-3">
                                            <label for="inputGroupSelect01">Role</label>
                                            <select class="form-control select2" id="inputGroupSelect01" name="role_id"
                                                style="appearance: auto;" placeholder="Select Role" required data-parsley-required-message="The role field is required." >
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div> 
                                       
                                    </div>
                                </div>
                            </div>


                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Save</button>
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
            $('#userCreateForm').parsley();
        });
    </script>
    <!-- Script to trim on input blur -->
    <script>
        document.getElementById('name').addEventListener('blur', function() {
            this.value = this.value.trim().replace(/\s+/g, ' ');
        });
    </script>
@endsection
