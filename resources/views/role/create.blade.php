@extends('layouts.app')
@section('content')
    {{-- <form class="max-w-sm mx-auto" action="{{ route('roles.store') }}" method="POST">
    @csrf
    <div class="mb-5">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Role-Name</label>
        <input type="text" name="name" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required />
      </div>
  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
</form> --}}
    <div class="container">
        <div class="col-xl">
            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Role Name</h5>
              </div>
              <div class="card-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                  <div class="form-floating form-floating-outline mb-4">
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
