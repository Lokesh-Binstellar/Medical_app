@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Laboratory Details</h5>
            <a href="{{ route('laboratorie.index') }}" class="btn btn-light btn-sm">← Back to List</a>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Image Section --}}
                @if($lab->image)
                <div class="col-md-4 text-center mb-4">
                    <img src="{{ asset('assets/image/' . $lab->image) }}" alt="Laboratory Image" class="img-fluid rounded-circle shadow-lg" style="max-height: 250px;">
                    <p class="mt-3 text-muted"><strong>Laboratory Image</strong></p>
                </div>
                @endif

                {{-- Laboratory Information --}}
                <div class="col-md-8">
                    <table class="table table-bordered table-striped table-hover">
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
                                <th>GST No.</th>
                                <td>{{ $lab->gstno }}</td>
                            </tr>
                            <tr>
                                <th>NABL ISO Certified</th>
                                <td>
                                    <span class="badge bg-{{ $lab->nabl_iso_certified == 1 ? 'success' : 'danger' }} p-2 text-uppercase">
                                        {{ $lab->nabl_iso_certified == 1 ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Home Sample Collection</th>
                                <td>
                                    <span class="badge bg-{{ $lab->pickup == 1 ? 'success' : 'danger' }} p-2 text-uppercase">
                                        {{ $lab->pickup == 1 ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            </tr>
                            
                            {{-- Test Details --}}
                            @if($lab->test)
                            <tr>
                                <th>Test Details</th>
                                <td>
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Test</th>
                                                <th>Price</th>
                                                <th>Home Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($labTests as $test)
                                            <tr>
                                                <td>{{ $test['test_name'] }}</td>
                                                <td>{{ number_format($test['price'], 2) }}</td>
                                                <td>{{ number_format($test['homeprice'], 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
