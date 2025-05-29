@extends('layouts.app')

@section('content')

    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lab Test Details</h5>
            <a href="{{ route('labtest.index') }}" class="btn addButton btn-sm">← Back to List</a>
        </div>

        <div class="card-body">
            <div class="row ">
                {{-- Info --}}
                <div class="col-md-12">
                    <table class="table  table-striped">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $tests->id }}</td>
                            </tr>
                            <tr>
                                <th>Test Name</th>
                                <td>{{ $tests->name }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $tests->description }}</td>
                            </tr>
                            <tr>
                                <th>Organ</th>
                                <td>{{ $tests->organ }}</td>
                            </tr>
                            <tr>
                                <th>Contains</th>
                                <td>{{ $tests->contains }}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{ ucfirst($tests->gender) }}</td>
                            </tr>
                            {{-- <tr>
                                <th>Reports In</th>
                                <td>{{ $tests->reports_in }}</td>
                            </tr> --}}
                            <tr>
                                <th>Sample Required</th>
                                <td>{{ $tests->sample_required ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Preparation</th>
                                <td>{{ $tests->preparation }}</td>
                            </tr>
                            <tr>
                                <th>How Does It Work</th>
                                <td>{{ $tests->how_does_it_work }}</td>
                            </tr>
                            <tr>
                                <th>Sub Reports</th>
                                <td>
                                    {{ is_array($tests->sub_reports) ? implode(', ', $tests->sub_reports) : $tests->sub_reports }}
                                </td>
                            </tr>
                            <tr>
                                <th>Sub Report Details</th>
                                <td>{{ $tests->sub_report_details }}</td>
                            </tr>
                            <tr>
                                <th>FAQ</th>
                                <td>{{ $tests->faq }}</td>
                            </tr>
                            <tr>
                                <th>References</th>
                                <td>{{ $tests->references }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
