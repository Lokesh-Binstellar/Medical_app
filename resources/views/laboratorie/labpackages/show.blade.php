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
                

                {{-- Laboratory Information --}}
                <div class="col-md-8">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                            <tr>
                                <th>Lab Id </th>
                                <td>{{ $labPackages->lab_id }}</td>
                            </tr>
                            <tr>
                                <th>Package Category Id</th>
                                <td>{{ $labPackages->package_category_id }}</td>
                            </tr>
                            <tr>
                                <th>Package Name</th>
                                <td>{{ $labPackages->package_name }}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>{{ $labPackages->price }}</td>
                            </tr>
                            <tr>
                                <th>Home price</th>
                                <td>{{ $labPackages->home_price }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $labPackages->description }}</td>
                            </tr>
                            

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
