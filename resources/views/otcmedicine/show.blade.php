@extends('layouts.app')
@section('content')
   
        <div class="card shadow-lg rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Otc Details</h5>
                <a href="{{ route('otcmedicine.index') }}" class="btn btn-light addButton">← Back to List</a>
            </div>
            <div class="card p-4 shadow">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>OTC ID</th>
                            <td>{{ $otcmedicine->otc_id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $otcmedicine->name }}</td>
                        </tr>
                        <tr>
                            <th>Breadcrumbs</th>
                            <td>{{ $otcmedicine->breadcrumbs }}</td>
                        </tr>
                        <tr>
                            <th>Manufacturers</th>
                            <td>{{ $otcmedicine->manufacturers }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ $otcmedicine->type }}</td>
                        </tr>
                        <tr>
                            <th>Packaging</th>
                            <td>{{ $otcmedicine->packaging }}</td>
                        </tr>
                        <tr>
                            <th>Package</th>
                            <td>{{ $otcmedicine->package }}</td>
                        </tr>
                        <tr>
                            <th>Qty</th>
                            <td>{{ $otcmedicine->qty }}</td>
                        </tr>
                        <tr>
                            <th>Product Form</th>
                            <td>{{ $otcmedicine->product_form }}</td>
                        </tr>
                        <tr>
                            <th>Product Highlights</th>
                            <td>{{ $otcmedicine->product_highlights }}</td>
                        </tr>
                        <tr>
                            <th>Information</th>
                            <td>{{ $otcmedicine->information }}</td>
                        </tr>
                        <tr>
                            <th>Key Ingredients</th>
                            <td>{{ $otcmedicine->key_ingredients }}</td>
                        </tr>
                        <tr>
                            <th>Key Benefits</th>
                            <td>{{ $otcmedicine->key_benefits }}</td>
                        </tr>
                        <tr>
                            <th>Directions for Use</th>
                            <td>{{ $otcmedicine->directions_for_use }}</td>
                        </tr>
                        <tr>
                            <th>Safety Information</th>
                            <td>{{ $otcmedicine->safety_information }}</td>
                        </tr>
                        <tr>
                            <th>Manufacturer Address</th>
                            <td>{{ $otcmedicine->manufacturer_address }}</td>
                        </tr>
                        <tr>
                            <th>Country of Origin</th>
                            <td>{{ $otcmedicine->country_of_origin }}</td>
                        </tr>
                        <tr>
                            <th>Manufacturer Details</th>
                            <td>{{ $otcmedicine->manufacturer_details }}</td>
                        </tr>
                        <tr>
                            <th>Marketer Details</th>
                            <td>{{ $otcmedicine->marketer_details }}</td>
                        </tr>
                        <tr>
                            <th>Image(s)</th>
                            <td>
                                @php
                                    $images = array_filter(
                                        array_map('trim', explode(',', $otcmedicine->image_url ?? '')),
                                    );
                                @endphp

                                @foreach ($images as $img)
                                    @if (!empty($img) && file_exists(public_path($img)))
                                        <img src="{{ asset($img) }}" alt="OTC image"
                                            style="max-height: 120px; margin: 5px;">
                                    @else
                                        <small style="color: red;">Missing: {{ $img }}</small>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
 
@endsection
