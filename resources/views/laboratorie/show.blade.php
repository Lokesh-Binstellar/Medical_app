@extends('layouts.app')

@section('styles')
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.85rem;
        }

        .lab-img {
            max-height: 250px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .card-header h5 {
            font-weight: 600;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table th {
            width: 25%;
            background-color: #f8f9fa;
        }

        .card-body {
            animation: fadeIn 0.4s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('content')
    <div class="container my-5">
        <div class="card shadow-lg rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Laboratory Details</h5>
                <a href="{{ route('laboratorie.index') }}" class="btn btn-light btn-sm">← Back to List</a>
            </div>

            <div class="card-body">
                @if ($lab->image)
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <img src="{{ asset('assets/image/' . $lab->image) }}" alt="Laboratory Image"
                                class="img-fluid lab-img">
                            <p class="mt-3 text-muted"><strong>Laboratory Image</strong></p>
                        </div>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
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
                                    <span class="badge bg-{{ $lab->nabl_iso_certified ? 'success' : 'danger' }} p-2">
                                        {{ $lab->nabl_iso_certified ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Home Sample Collection</th>
                                <td>
                                    <span class="badge bg-{{ $lab->pickup ? 'success' : 'danger' }} p-2">
                                        {{ $lab->pickup ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            </tr>

                            {{-- Test Details --}}
                            @if ($lab->test && count($labTests))
                                <tr>
                                    <th>Test Details</th>
                                    <td>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover text-center">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Test</th>
                                                        <th>Price</th>
                                                        <th>Home Price</th>
                                                        <th>Report Time</th>
                                                        <th>Offer Visiting Price</th>
                                                        <th>Offer Home Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($labTests as $test)
                                                        <tr>
                                                            <td>{{ $test['test_name'] }}</td>
                                                            <td>₹{{ number_format($test['price'], 2) }}</td>
                                                            <td>₹{{ $test['homeprice'] }}</td>
                                                            <td>{{ $test['report'] }}</td>
                                                            <td>₹{{ $test['offer_visiting_price'] }}</td>
                                                            <td>₹{{ $test['offer_home_price'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            {{-- Package Details --}}
                            <tr>
                                <th>Package Details</th>
                                <td>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-center">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Package Name</th>
                                                    <th>Description</th>
                                                    <th>Visiting Price</th>
                                                    <th>Home Price</th>
                                                    <th>Report</th>
                                                    <th>Offer Visiting Price</th>
                                                    <th>Offer Home Price</th>
                                                    <th>Categories</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($packageDetails as $package)
                                                    <tr>
                                                        <td>{{ $package['package_name'] }}</td>
                                                        <td>{!! $package['package_description'] !!}</td>
                                                        <td>₹{{ $package['package_visiting_price'] }}</td>
                                                        <td>₹{{ $package['package_home_price'] }}</td>
                                                        <td>{{ $package['package_report'] }}</td>
                                                        <td>₹{{ $package['package_offer_visiting_price'] }}</td>
                                                        <td>₹{{ $package['package_offer_home_price'] }}</td>
                                                        <td>
                                                            @if (!empty($package['package_category']) && is_array($package['package_category']))
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($package['package_category'] as $cat)
                                                                        <span
                                                                            class="badge bg-info text-dark">{{ $cat }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
