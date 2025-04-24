@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">OTC Product Details</h1>
    <a href="{{ route('otcmedicine.index') }}" class="btn btn-primary mt-3 mb-3  text-right" width='200'>← Back to List</a>
    <div class="card p-4 shadow">
        <table class="table table-bordered">
            <tbody>
                <tr><th>OTC ID</th><td>{{ $otcmedicine->otc_id }}</td></tr>
                <tr><th>Name</th><td>{{ $otcmedicine->name }}</td></tr>
                <tr><th>Breadcrumbs</th><td>{{ $otcmedicine->breadcrumbs }}</td></tr>
                <tr><th>Manufacturers</th><td>{{ $otcmedicine->manufacturers }}</td></tr>
                <tr><th>Type</th><td>{{ $otcmedicine->type }}</td></tr>
                <tr><th>Packaging</th><td>{{ $otcmedicine->packaging }}</td></tr>
                <tr><th>Package</th><td>{{ $otcmedicine->package }}</td></tr>
                <tr><th>Qty</th><td>{{ $otcmedicine->qty }}</td></tr>
                <tr><th>Product Form</th><td>{{ $otcmedicine->product_form }}</td></tr>
                <tr><th>Product Highlights</th><td>{{ $otcmedicine->product_highlights }}</td></tr>
                <tr><th>Information</th><td>{{ $otcmedicine->information }}</td></tr>
                <tr><th>Key Ingredients</th><td>{{ $otcmedicine->key_ingredients }}</td></tr>
                <tr><th>Key Benefits</th><td>{{ $otcmedicine->key_benefits }}</td></tr>
                <tr><th>Directions for Use</th><td>{{ $otcmedicine->directions_for_use }}</td></tr>
                <tr><th>Safety Information</th><td>{{ $otcmedicine->safety_information }}</td></tr>
                <tr><th>Manufacturer Address</th><td>{{ $otcmedicine->manufacturer_address }}</td></tr>
                <tr><th>Country of Origin</th><td>{{ $otcmedicine->country_of_origin }}</td></tr>
                <tr><th>Manufacturer Details</th><td>{{ $otcmedicine->manufacturer_details }}</td></tr>
                <tr><th>Marketer Details</th><td>{{ $otcmedicine->marketer_details }}</td></tr>
                <tr>
                    <th>Image(s)</th>
                    <td>
                        @foreach(explode(',', $otcmedicine->image_url) as $img)
                        <img src="{{ asset('storage/' . trim($img)) }}" alt="OTC image" style="max-height: 120px; margin: 5px;">
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
