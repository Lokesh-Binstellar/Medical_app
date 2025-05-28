@extends('layouts.app')
@section('styles')
{{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}
 
@endsection
@section('content')

    <div class="container">
        <div class="col-xl">
            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Role Name</h5>
              </div>
              <div class="card-body">
                <form action="{{ route('roles.store') }}" method="POST" id="roleCreateForm">
                    @csrf
                  <div class="form-floating form-floating-outline mb-4 col-md-6">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Role" />
                    <label for="name">Role Name</label>
                  </div>
                  <button type="submit" class="btn btn-primary">Save</button>
                </form>
              </div>
            </div>
          </div>


        
    </div>
    </div>
@endsection



@section('scripts')
<script src="{{ asset('js/role/role_form.js') }}"></script>
{{-- <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script> --}}
@endsection
