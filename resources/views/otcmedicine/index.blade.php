@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">OTC</h4>
                            {{-- <a href="{{ route('user.create') }}" class="btn btn-primary">Add user</a>  --}}
                            <form action="{{ route('otcmedicine.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file" required>
                                <button type="submit" class="btn btn-primary">Import OTC</button>
                            </form>
                        </div>
                       
                        <div class="card-body">
                            <table class="table table-bordered  mt-3">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">breadcrumbs</th>
                                        <th scope="col">Manufacturers</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($otc as $index => $ot)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $ot->otc_id }}</td>
                                            <td>{{ $ot->name }}</td>
                                            <td>{{ $ot->breadcrumbs }}</td>
                                            <td>{{ $ot->manufacturers }}</td>
                                            <td><a href="{{ route('otcmedicine.show', $ot->id) }}"
                                                    class="btn btn-info btn-sm">View</a></td>


                                            {{-- <td class="d-flex gap-2">
                                            <a href="{{ route('user.edit', $users->id) }}"><button type="button"
                                                    class="btn btn-sm btn-warning">Edit</button></a>
                                            <form action="{{ route('user.destroy', $users->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>

                                          
                                        </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            {{-- <div class="d-flex justify-content-center mt-4">
                                {{ $medicines->links() }}
                            </div> --}}
                            {{-- @if ($user->isEmpty())
                                <div class="text-center mt-3 text-muted">No pharmacist records found.</div>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
@endsection
