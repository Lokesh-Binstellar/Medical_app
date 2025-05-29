@extends('layouts.app')

@section('content')

    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Delivery Person Details</h5>
            <a href="{{ route('delivery_person.index') }}" class="btn btn-light btn-sm addButton">← Back to List</a>
        </div>

        <div class="card-body">
            <div class="row">


                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>Delivery Person Name</th>
                                <td>{{ $delivery->delivery_person_name }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $delivery->phone }}</td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>{{ $delivery->username }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $delivery->email }}</td>
                            </tr>
                           
                            <tr>
                                <th>City</th>
                                <td>{{ $delivery->city }}</td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>{{ $delivery->state }}</td>
                            </tr>
                            <tr>
                                <th>Pincode</th>
                                <td>{{ $delivery->pincode }}</td>
                            </tr>
                           
                            {{-- <tr>
                                <th>Latitude</th>
                                <td>{{ $delivery->latitude }}</td>
                            </tr>
                            <tr>
                                <th>Longitude</th>
                                <td>{{ $delivery->longitude }}</td>
                            </tr> --}}

                         <tr>
                                <th>Address</th>
                                <td>{{ $delivery->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

@endsection
