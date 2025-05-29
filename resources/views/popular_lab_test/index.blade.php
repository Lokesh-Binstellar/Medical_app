@extends('layouts.app')
@section('styles')
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}
    <style>
        /* .select2 {
            width: 300px !important;
        }

        body>span.select2-container.select2-container--default.select2-container--open {
            width: auto !important;
        } */

        .fv-plugins-message-container.fv-plugins-message-container--enabled.invalid-feedback {
            min-height: 1.5rem;
        }
    </style>
@endsection


@section('content')

                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center ">
                            <h4 class="card-title mb-0 ">Popular Lab Test </h4>

                        </div>
                        <div class="card-body">
                            <div class="">
                                <form action="{{ route('popular_lab_test.store') }}" method="POST"
                                    enctype="multipart/form-data" class="row g-3 align-items-center" id="importForm">
                                    @csrf
                                    <div class="error-msg col-md-4">
                                        <option value="">Select Lab test</option>
                                        <select name="name" class="form-control select2" id="lab-test-select" >
                                            <option value="">Select Lab Test</option>
                                            @foreach ($labTests as $item)
                                                <option value="{{ $item->id }}" data-contains="{{ $item->contains }}">
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-end col-md-4">
                                        <button type="submit" class="btn btn-primary  text-nowrap px-5">+ Add
                                            Lab Test</button>
                                    </div>
                                </form>
                            </div>




                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <div class="card-body">
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

            // Initialize Select2
            $('#lab-test-select').select2({
                placeholder: "Select Lab Test",
                allowClear: true
            });

            // Auto-show contains when a lab test is selected (optional)
            $('#lab-test-select').on('change', function() {
                const selectedOption = $(this).find(':selected');
                const contains = selectedOption.data('contains');

                // Optional: display contains value somewhere on the form
                // Example: set value in a hidden or readonly input
                $('#contains-field').val(contains);
            });
        });
    </script>
    <script src="{{ asset('js/popularlabtest/popularlabtest_form.js') }}"></script>
@endsection
