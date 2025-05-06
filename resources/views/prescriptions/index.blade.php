@extends('layouts.app')
@section('styles')
    <style>
        .custom-dropdown {
            -webkit-appearance: none;
            /* Remove default styling in WebKit browsers */
            -moz-appearance: none;
            /* Remove default styling in Firefox */
            appearance: none;
            /* Remove default styling in modern browsers */
            padding-right: 30px;
            /* Add some space for the arrow */
            background: url('data:image/svg+xml;utf8,<svg width="10" height="10" xmlns="http://www.w3.org/2000/svg"><path d="M0 0 L10 0 L5 5 Z" fill="%23000"/></svg>') no-repeat right center;
            background-size: 10px 10px;
            background-position-x: calc(100% - 10px);
            background-position-y: center;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="page-inner px-0">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header rounded-top">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title mb-0  text-white">Prescriptions</h4>
                                {{-- <a href="{{ route('laboratorie.create') }}" class="btn btn-primary text-white  addButton ">+ Add
                                    Laboratory</a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Prescription Id</th>
                                            <th>Customer Name</th>
                                            <th>Customer Phone no.</th>
                                            <th>Prescription</th>
                                            <th>Valid</th>
                                            <th>Status</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
                ajax: "{{ route('prescriptions.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'customer_id',
                        name: 'customer.firstName'
                    }, // Customer Name
                    {
                        data: 'customer_phone',
                        name: 'customer.phone'
                    }, // Customer Phone
                    {
                        data: 'prescription_file',
                        name: 'prescription_file'
                    },
                    {
                        data: 'prescription_status',
                        name: 'prescription_status'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

        function updateStatus(select, id) {
            let value = select.value;

            fetch('/prescriptions/update-status/' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        prescription_status: value
                    })
                })
                .then(res => res.json())
                .then(data => {
            if (data.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Prescription status updated successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Failed to update status'
                });
            }
        });
        }
    </script>
@endsection
