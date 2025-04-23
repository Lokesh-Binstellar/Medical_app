@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Laboratory Details</h5>
            <a href="{{ route('pharmacist.index') }}" class="btn btn-light btn-sm">← Back to List</a>
        </div>

        <div class="card-body">
            <div class="row">

                {{-- Image --}}
                @if($lab->image)
                    <div class="col-md-4 text-center mb-4">
                        <img src="{{ $lab->image }}" alt="Pharmacy Image" class="img-fluid rounded shadow" style="max-height: 250px;">
                        <p class="mt-2"><strong>Laboratory Image</strong></p>
                    </div>
                @endif

                {{-- Info --}}
                <div class="col-md-8">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>Laboratory Name</th>
                                <td>{{ $lab->lab_name }}</td>
                            </tr>
                            <tr>
                                <th>Owner Name</th>
                                <td>{{ $lab->owner_name }}</td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>{{ $lab->username }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $lab->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $lab->phone }}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{ $lab->city }}</td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>{{ $lab->state }}</td>
                            </tr>
                            <tr>
                                <th>Pincode</th>
                                <td>{{ $lab->pincode }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $lab->address }}</td>
                            </tr>
                            <tr>
                                <th>Latitude</th>
                                <td>{{ $lab->latitude }}</td>
                            </tr>
                            <tr>
                                <th>Longitude</th>
                                <td>{{ $lab->longitude }}</td>
                            </tr>
                            <tr>
                                <th>Drug License No.</th>
                                <td>{{ $lab->license }}</td>
                            </tr>
                            <tr>
                                <th>Home sample collection</th>
                                <td>{{ $lab->pickup == 1 ? 'Yes' : 'No' }}</td>
                            </tr>
                           
                            {{-- <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $lab->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $lab->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                
                            </tr> --}}
                        
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
