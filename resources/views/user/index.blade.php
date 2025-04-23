

@extends('layouts.app')
@section('content')
   
    <div class="container ">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Users</h4>
                            <a href="{{ route('user.create') }}" class="btn btn-primary">Add user</a>
                           
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered  mt-3">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user as $index => $users)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $users->name }}</td>
                                        <td>{{ $users->email }}</td>
                                        <td>
                                            <span class="badge badge-success">{{ $users->role->name ?? '' }}</span>
                                        </td>

                                        <td class="d-flex gap-2">
                                            <a href="{{ route('user.edit', $users->id) }}"><button type="button"
                                                    class="btn btn-sm btn-warning">Edit</button></a>
                                            <form action="{{ route('user.destroy', $users->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>

                                          
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </div>
                            @if ($user->isEmpty())
                                <div class="text-center mt-3 text-muted">No pharmacist records found.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
