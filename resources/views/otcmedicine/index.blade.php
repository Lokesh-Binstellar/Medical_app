@extends('layouts.app')
@section('styles')
{{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}
 
@endsection
@section('content')

                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center rounded-top">
                            <h4 class="card-title mb-0 text-white">Otcmedicine</h4>
                            <div class="d-flex justify-content-between align-items-center">
                            <form action="{{ route('otcmedicine.import') }}" id="importForm" method="POST"
                                enctype="multipart/form-data" class="d-flex gap-2">
                                @csrf
                                <div class="error-msg">

                                    <input type="file" name="file" class="form-control" id="file">
                                </div>
                                <div>

                                    <button type="submit" class="btn btn-primary addButton text-nowrap px-5">+ Import Medicine</button>
                                </div>
                            </form>
                            </div>
                        </div>
                        {{-- <div class="card-header d-flex justify-content-between align-items-center rounded-top" style="background-color:#5ecbd8">
                            <h4 class="card-title mb-0 text-white">OTC</h4>
                            <form action="{{ route('otcmedicine.import') }}" id="importForm" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file" required>
                                <button type="submit" class="btn btn-primary">Import Medicine</button>
                            </form>
                        </div> --}}



                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Breadcrumbs</th>
                                            <th>Manufacturer</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                
@endsection
@section('scripts')
    <script>
        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('otcmedicine.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'otc_id',
                        name: 'otc_id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'breadcrumbs',
                        name: 'breadcrumbs'
                    },
                    {
                        data: 'manufacturers',
                        name: 'manufacturers'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });



        });
    </script>
<script src="{{ asset('js/otc/otc_form.js') }}"></script>
{{-- <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script> --}}
@endsection

