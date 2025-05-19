@extends('layouts.app')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <style>
        .select2 {
            width: 300px !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center ">
                            <h4 class="card-title mb-0 ">Popular Lab Test </h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <form action="{{ route('popular_lab_test.store') }}" method="POST"
                                    enctype="multipart/form-data" class="d-flex gap-2 align-items-center" id="importForm">
                                    @csrf
                                    <select name="name" class="form-control select2" id="lab-test-select">
                                        <option value="">Select Lab Test</option>
                                        @foreach ($labTests as $item)
                                            <option value="{{ $item->id }}" data-contains="{{ $item->contains }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>

                                    {{-- <input type="file" name="logo" class="form-control" id="logo"> --}}
                                    <button type="submit" class="btn btn-primary addButton text-nowrap px-5">+ Add
                                        Lab Test</button>
                                </form>
                            </div>

                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif



                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover data-table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Lab Test Name</th>
                                            <th>Contains</th>
                                            <th>Action</th>
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
                ajax: "{{ route('popular_lab_test.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'contains',
                        name: 'contains'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Your delete function with SweetAlert2
            window.deleteTest = function(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This lab test will be deleted permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2', // Red 'Yes' button
                        cancelButton: 'btn btn-secondary' // Grey 'Cancel' button
                    },
                    buttonsStyling: false,
                    reverseButtons: false // ✅ Confirm ("Yes") on left, Cancel on right
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('popular_lab_test.destroy', '') }}/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Popular Lab Test deleted successfully.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                table.ajax.reload();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }


        });
    </script>
@endsection
