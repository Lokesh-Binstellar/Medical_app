@extends('layouts.app')
@section('styles')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}
@endsection
@section('content')
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center rounded-top">
            <h4 class="card-title mb-0 text-white">Medicine</h4>
            <div class="d-flex justify-content-between align-items-center">
                <form action="{{ route('medicine.import') }}" id="importForm" method="POST" enctype="multipart/form-data"
                    class="d-flex gap-2">

                    @csrf

                        <input type="file" name="file" class="form-control" id="file">
                        @error('file')
                            <small class="red-text ml-10" role="alert">{{ $message }}</small>
                        @enderror
                    <div>

                        <button type="submit" class="btn btn-primary addButton text-nowrap px-5">
                            + Import Medicine
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="add-row" class="display table table-striped table-hover data-table">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>product id</th>
                            <th>Name</th>
                            <th>Salt composition</th>
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
                ajax: "{{ route('medicine.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'product_id',
                        name: 'product_id'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'salt_composition',
                        name: 'salt_composition'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // $(document).ready(function() {
            //     $('#importForm').parsley();
            // });

        });
    </script>


    <script src="{{ asset('js/medicine/medicine_form.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script> --}}
@endsection
