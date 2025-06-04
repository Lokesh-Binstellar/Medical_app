@extends('layouts.app')
@section('content')
    <!-- Customers List Table -->
    <div class="card">
        <div class="card-header rounded-top">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0  text-white">All Customers</h4>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-customers table" id="customer_table">
                <thead class="table-light">
                    <tr>
                        <th>#</th> <!-- DT_RowIndex -->
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer as $index => $cust)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-capitalize">
                                <a href="{{ route('customer.show', $cust->id) }}">
                                    {{ $cust->firstName ?? 'NA' }}
                                </a>
                            </td>
                            <td>{{ $cust->lastName ?? 'NA' }}</td>
                            <td>{{ $cust->email ?? 'NA' }}</td>
                            <td>{{ $cust->mobile_no ?? 'NA' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
