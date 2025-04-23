@extends('layouts.app')
@section('content')
   
           
          
                  {{-- <table class="table-auto">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Role_Name</th>                        
                    </thead>
                    <tbody>
                        @foreach ($role as $index => $roles)
                           
                     
                            <tr class="">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $roles->name }}</td>                            
                                <td class="flex gap-2">
                                    <a href="{{ route('pharmacist.edit',$pharmacy->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
                                    <form action="{{ route('pharmacist.destroy', $pharmacy->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                                    </form>
                                   
                                   
                                </td>
                            </tr>
                       
                            @endforeach
                    </tbody>
                </table> --}}
                <div class="container">
                  <div class="page-inner">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Role Table</div>
                            <button class="btn btn-primary "> <a href="{{ route('roles.create') }}" class="text-white" >Create role</a></button>
                          </div>
                          <div class="card-body">
                            <table class="table table-bordered  mt-3">
                              <thead class="thead-dark">
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Role name</th>                                  
                                  <th scope="col">Action</th>                                  
                                </tr>
                              </thead>
                              <tbody>
                                  @foreach ($role as $index => $roles)
                                <tr>
                                  <td>{{ $index + 1 }}</td>
                                  <td>{{ $roles->name }}</td>
                                  <td class="d-flex gap-2">
                                    <a href="{{route('roles.edit',$roles->id)}}"><button type="button" class="btn btn-warning ms-auto">Update</button></a>
                                    <form action="{{ route('roles.destroy', $roles->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="btn btn-danger ms-auto">Delete</button>
                                  </form>
                                  </td>
                                 
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                        </div>
                       
                     
                       
                      </div>
                    </div>
                  </div>
                </div>
         
      
    @endsection

    
