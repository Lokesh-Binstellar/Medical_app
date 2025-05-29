@extends('layouts.app')

@section('content')
    
        <div class="card shadow rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    @if ($user->role->name === 'laboratory')
                        Laboratory Details
                    @elseif ($user->role->name === 'pharmacy')
                        Pharmacy Details
                    @else
                        User Details
                    @endif

                </h5>
                {{-- <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm">Edit Profile</a> --}}
            </div>

            <div class="card-body">
                <div class="row">
                    {{-- Get relevant entity --}}
                    @php
                        $user = Auth::user();
                        $entity = $user->role->name === 'laboratory' ? $user->laboratories : $user->pharmacies;

                    @endphp

                    @if ($entity)
                        {{-- Image --}}
                        @if ($entity->image)
                            {{-- {{ $entity->image }} --}}
                            <div class="col-md-4 text-center mb-4">
                                <img src="{{ $entity->image }}" alt="Image" class="img-fluid rounded shadow"
                                    style="max-height: 250px;">
                                <p class="mt-2"><strong>Image</strong></p>
                            </div>
                        @endif

                        {{-- Info --}}
                        <div class="col-md-8">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>{{ $user->role->name === 'laboratory' ? 'Laboratory Name' : 'Pharmacy Name' }}
                                        </th>
                                        <td>{{ $entity->pharmacy_name ?? ($entity->lab_name ?? '-') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Owner Name</th>
                                        <td>{{ $entity->owner_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Username</th>
                                        <td>{{ $entity->username ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $entity->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $entity->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>City</th>
                                        <td>{{ $entity->city ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>State</th>
                                        <td>{{ $entity->state ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pincode</th>
                                        <td>{{ $entity->pincode ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $entity->address ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Latitude</th>
                                        <td>{{ $entity->latitude ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Longitude</th>
                                        <td>{{ $entity->longitude ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>License No.</th>
                                        <td>{{ $entity->license ?? '-' }}</td>
                                    </tr>

                                    {{-- Only for Laboratory --}}
                                    @if ($user->role === 'laboratory')
                                        <tr>
                                            <th>Home Sample Collection</th>
                                            <td>{{ $entity->pickup == 1 ? 'Yes' : 'No' }}</td>
                                        </tr>
                                    @endif

                                    {{-- Status --}}
                                    {{-- <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $entity->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $entity->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="col-12">
                            <p class="text-danger">No details found for this entity.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    
@endsection
