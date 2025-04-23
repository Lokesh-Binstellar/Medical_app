
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="page-inner px-0">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Laboratory </h4>
                        <a href="{{ route('laboratorie.create') }}" class="btn btn-primary">Add Laboratory</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mt-3">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laboratorie as $index =>$lab)
                            
                      
                            <tr class="">
                                <td>{{ $index + 1 }}</td>
                                <td>{{$lab->lab_name }}</td>
                                <td>{{$lab->email }}</td>
                                <td>{{$lab->address }}</td>
                                <td>{{$lab->phone }}</td>
                                {{-- <td class="d-flex gap-2">
                                    <a href="{{ route('laboratorie.edit',$lab->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('laboratorie.destroy',$lab->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form> 
                                    
                                  
                                </td> --}}


                                <td class="d-flex gap-2">

                                    {{-- Show Button --}}
                                    <a href="{{ route('laboratorie.show', $lab->id) }}"
                                        class="btn btn-info btn-sm">View</a>

                                    {{-- Edit Button with Permission --}}
                                    @php
                                        $chkEdit = \App\Models\Permission::checkCRUDPermissionToUser(
                                            'Pharmacies',
                                            'Update',
                                        );
                                    @endphp
                                    @if ($chkEdit)
                                        <a href="{{ route('laboratorie.edit',$lab->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                    @endif

                                    {{-- Delete Button with Permission --}}
                                    @php
                                        $chkDelete = \App\Models\Permission::checkCRUDPermissionToUser(
                                            'Pharmacies',
                                            'Delete',
                                        );
                                    @endphp
                                    @if ($chkDelete)
                                        <form action="{{ route('laboratorie.destroy',$lab->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    @endif

                                </td>
                            </tr>
                        
                            @endforeach
                            </tbody>
                        </table>
                        </div>

                        @if ($laboratorie->isEmpty())
                            <div class="text-center mt-3 text-muted">No pharmacist records found.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection