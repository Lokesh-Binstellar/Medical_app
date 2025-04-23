@extends('layouts.app')
@section('content')
    <div class=" ">
        <div class="page-inner px-0">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Pharmacy</h4>
                            {{-- <a href="{{ route('pharmacist.create') }}" class="btn btn-primary">Create Pharmacist</a> --}}
                            @php
                                $chk = \App\Models\Permission::checkCRUDPermissionToUser('Pharmacies', 'create');
                                // dd($chk);
                                if ($chk) {
                                    echo '<div class="col-sm-12 col-md-6 col-lg-6 text-end "><a href="' .
                                        route('pharmacist.create') .
                                        '" class="btn btn-primary"><i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Pharmacy </span></a></div>';
                                }
                            @endphp
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mt-3">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">pharmacy name</th>
                                        <th scope="col">owner name</th>
                                        <th scope="col">email </th>
                                        <th scope="col">Phone</th>
                                        {{-- <th scope="col">Address</th> --}}
                                        {{-- <th scope="col">latitude</th>
                                        <th scope="col">longitude</th>
                                        <th scope="col">image</th>
                                        <th scope="col">username</th>
                                        <th scope="col">license</th> --}}
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pharmacist as $index => $pharmacy)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $pharmacy->pharmacy_name }}</td>
                                            <td>{{ $pharmacy->owner_name }}</td>
                                            <td>{{ $pharmacy->email }}</td>
                                            <td>{{ $pharmacy->phone }}</td>
                                            {{-- <td>{{ $pharmacy->address }}</td>                                           --}}
                                            {{-- <td>{{ $pharmacy->latitude}}</td>
                                            <td>{{ $pharmacy->longitude}}</td>
                                            <td><img src="{{ $pharmacy->image}}" width="150"></td>
                                            <td>{{ $pharmacy->username}}</td>
                                            <td>{{ $pharmacy->license}}</td> --}}

                                            <td class="d-flex gap-2">

                                                {{-- Show Button --}}
                                                <a href="{{ route('pharmacist.show', $pharmacy->id) }}"
                                                    class="btn btn-info btn-sm">View</a>

                                                {{-- Edit Button with Permission --}}
                                                @php
                                                    $chkEdit = \App\Models\Permission::checkCRUDPermissionToUser(
                                                        'Pharmacies',
                                                        'Update',
                                                    );
                                                @endphp
                                                @if ($chkEdit)
                                                    <a href="{{ route('pharmacist.edit', $pharmacy->id) }}"
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
                                                    <form action="{{ route('pharmacist.destroy', $pharmacy->id) }}"
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

                            @if ($pharmacist->isEmpty())
                                <div class="text-center mt-3 text-muted">No pharmacist records found.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
