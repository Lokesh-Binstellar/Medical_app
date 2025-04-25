@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pharmacy Details</h5>
            <a href="{{ route('pharmacist.index') }}" class="btn btn-light btn-sm">← Back to List</a>
        </div>

        <div class="card-body">
            <div class="row">

                {{-- Image --}}
                @if($pharmacy->image)
                    <div class="col-md-4 text-center mb-4">
                        <img src="{{ asset('assets/image/' . $pharmacy->image) }}" alt="Pharmacy Image" class="img-fluid rounded shadow" style="max-height: 250px;">
                        <p class="mt-2"><strong>Pharmacy Image</strong></p>
                    </div>
                @endif

                {{-- Info --}}
                <div class="col-md-8">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>Pharmacy Name</th>
                                <td>{{ $pharmacy->pharmacy_name }}</td>
                            </tr>
                            <tr>
                                <th>Owner Name</th>
                                <td>{{ $pharmacy->owner_name }}</td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>{{ $pharmacy->username }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $pharmacy->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $pharmacy->phone }}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{ $pharmacy->city }}</td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>{{ $pharmacy->state }}</td>
                            </tr>
                            <tr>
                                <th>Pincode</th>
                                <td>{{ $pharmacy->pincode }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $pharmacy->address }}</td>
                            </tr>
                            <tr>
                                <th>Latitude</th>
                                <td>{{ $pharmacy->latitude }}</td>
                            </tr>
                            <tr>
                                <th>Longitude</th>
                                <td>{{ $pharmacy->longitude }}</td>
                            </tr>
                            <tr>
                                <th>Drug License No.</th>
                                <td>{{ $pharmacy->license }}</td>
                            </tr>
                           
                            {{-- <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $pharmacy->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $pharmacy->status == 1 ? 'Active' : 'Inactive' }}
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
