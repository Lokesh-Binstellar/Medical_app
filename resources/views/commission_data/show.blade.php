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
                                <th>commonAmount</th>
                                <td>{{ $commission_data->commonAmount }}</td>
                            </tr>
                          <tr>
                                <th>GST Rate</th>
                                <td>{{ $commission_data->gstRate }}</td>
                            </tr>
                            <tr>
                                <th>Commission Below Amount</th>
                                <td>{{ $commission_data->commissionBelowAmount }}</td>
                            </tr>
                            <tr>
                                <th>Commission Above Amount</th>
                                <td>{{ $commission_data->commissionAboveAmount }}</td>
                            </tr>

 
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    
@endsection
