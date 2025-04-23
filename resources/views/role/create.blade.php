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
        <div class="page-inner">
            <form class="max-w-sm mx-auto" action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="email2">Role Name</label>
                        <input type="text" class="form-control" id="email2" name="name" placeholder="Enter Role" />
                    </div>
                    {{-- <table>
                        <tr>
                            <td>Module</td>
                            <td>create</td>
                            <td>read</td>
                            <td>update</td>
                            <td>delete</td>
                        </tr>
                        <tbody>
                            @foreach ($fillArr as $item)
                                <tr>

                                    <td>{{ $item }}</td>
                                    <td>
                                        <input type="checkbox" name="permission[{{$item}}][create]" id="">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="permission[{{$item}}][read]" id="">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="permission[{{$item}}][update]" id="">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="permission[{{$item}}][delete]" id="">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                    <div class="card-action">
                        <button class="btn btn-success">Save</button>
                    </div>

            </form>
        </div>
    </div>
    </div>
@endsection
