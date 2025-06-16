@extends('layouts.app')
@section('content')
   
        <div class="card shadow-lg rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Janaushadhi Details</h5>
                <a href="{{ route('janaushadhi.index') }}" class="btn btn-light addButton">← Back to List</a>
            </div>
            <div class="card p-4 shadow">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>drug code</th>
                            <td>{{ $janaushadhies->drug_code }}</td>
                        </tr>
                        <tr>
                            <th>generic name </th>
                            <td>{{ $janaushadhies->generic_name }}</td>
                        </tr>
                        <tr>
                            <th>unit size</th>
                            <td>{{ $janaushadhies->unit_size }}</td>
                        </tr>
                        <tr>
                            <th>mrp</th>
                            <td>{{ $janaushadhies->mrp }}</td>
                        </tr>
                        <tr>
                            <th>group name</th>
                            <td>{{ $janaushadhies->group_name }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    
@endsection
