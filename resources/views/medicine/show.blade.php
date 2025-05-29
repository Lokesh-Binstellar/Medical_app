@extends('layouts.app')
@section('content')
   
        <div class="card shadow-lg rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Medicine Details</h5>
                <a href="{{ route('medicine.index') }}" class="btn btn-light addButton">← Back to List</a>
            </div>
            <div class="card p-4 shadow">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Product ID</th>
                            <td>{{ $medicines->product_id }}</td>
                        </tr>
                        <tr>
                            <th>Product Name</th>
                            <td>{{ $medicines->product_name }}</td>
                        </tr>
                        <tr>
                            <th>Marketer</th>
                            <td>{{ $medicines->marketer }}</td>
                        </tr>
                        <tr>
                            <th>Salt Composition</th>
                            <td>{{ $medicines->salt_composition }}</td>
                        </tr>
                        <tr>
                            <th>Medicine Type</th>
                            <td>{{ $medicines->medicine_type }}</td>
                        </tr>
                        <tr>
                            <th>Introduction</th>
                            <td>{{ $medicines->introduction }}</td>
                        </tr>
                        <tr>
                            <th>Benefits</th>
                            <td>{{ $medicines->benefits }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $medicines->description }}</td>
                        </tr>
                        <tr>
                            <th>How to Use</th>
                            <td>{{ $medicines->how_to_use }}</td>
                        </tr>
                        <tr>
                            <th>Safety Advice</th>
                            <td>{{ $medicines->safety_advise }}</td>
                        </tr>
                        <tr>
                            <th>If Miss</th>
                            <td>{{ $medicines->if_miss }}</td>
                        </tr>
                        <tr>
                            <th>Packaging Detail</th>
                            <td>{{ $medicines->packaging_detail }}</td>
                        </tr>
                        <tr>
                            <th>Package</th>
                            <td>{{ $medicines->package }}</td>
                        </tr>
                        <tr>
                            <th>Qty</th>
                            <td>{{ $medicines->qty }}</td>
                        </tr>
                        <tr>
                            <th>Product Form</th>
                            <td>{{ $medicines->product_form }}</td>
                        </tr>
                        <tr>
                            <th>Prescription Required</th>
                            <td>{{ $medicines->prescription_required }}</td>
                        </tr>
                        <tr>
                            <th>Fact Box</th>
                            <td>{{ $medicines->fact_box }}</td>
                        </tr>
                        <tr>
                            <th>Primary Use</th>
                            <td>{{ $medicines->primary_use }}</td>
                        </tr>
                        <tr>
                            <th>Storage</th>
                            <td>{{ $medicines->storage }}</td>
                        </tr>
                        <tr>
                            <th>Use Of</th>
                            <td>{{ $medicines->use_of }}</td>
                        </tr>
                        <tr>
                            <th>Common Side Effect</th>
                            <td>{{ $medicines->common_side_effect }}</td>
                        </tr>
                        <tr>
                            <th>Alcohol Interaction</th>
                            <td>{{ $medicines->alcohol_interaction }}</td>
                        </tr>
                        <tr>
                            <th>Pregnancy Interaction</th>
                            <td>{{ $medicines->pregnancy_interaction }}</td>
                        </tr>
                        <tr>
                            <th>Lactation Interaction</th>
                            <td>{{ $medicines->lactation_interaction }}</td>
                        </tr>
                        <tr>
                            <th>Driving Interaction</th>
                            <td>{{ $medicines->driving_interaction }}</td>
                        </tr>
                        <tr>
                            <th>Kidney Interaction</th>
                            <td>{{ $medicines->kidney_interaction }}</td>
                        </tr>
                        <tr>
                            <th>Liver Interaction</th>
                            <td>{{ $medicines->liver_interaction }}</td>
                        </tr>
                        <tr>
                            <th>Manufacturer Address</th>
                            <td>{{ $medicines->manufacturer_address }}</td>
                        </tr>
                        <tr>
                            <th>Country of Origin</th>
                            <td>{{ $medicines->country_of_origin }}</td>
                        </tr>
                        <tr>
                            <th>Q & A</th>
                            <td>{{ $medicines->q_a }}</td>
                        </tr>
                        <tr>
                            <th>How it Works</th>
                            <td>{{ $medicines->how_it_works }}</td>
                        </tr>
                        <tr>
                            <th>Interaction</th>
                            <td>{{ $medicines->interaction }}</td>
                        </tr>
                        <tr>
                            <th>Manufacturer Details</th>
                            <td>{{ $medicines->manufacturer_details }}</td>
                        </tr>
                        <tr>
                            <th>Marketer Details</th>
                            <td>{{ $medicines->marketer_details }}</td>
                        </tr>
                        <tr>
                            <th>Image(s)</th>
                            <td>
                                @foreach (explode(',', $medicines->image_url) as $img)
                                    <img src="{{ asset($img) }}" alt="medicine image"
                                        style="max-height: 120px; margin: 5px;">
                                @endforeach
                            </td>

                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    
@endsection
