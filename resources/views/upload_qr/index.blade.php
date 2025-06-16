@extends('layouts.app')

@section('styles')
    <style>
        /* .fv-plugins-message-container.fv-plugins-message-container--enabled.invalid-feedback {
                        min-height: 1.5rem;
                    } */
    </style>
@endsection

@section('content')

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0 ">Gomeds QR Code</h4>
        </div>

        <div class="card-body">
            <div class="">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="{{ route('upload_qr.store') }}" method="POST" enctype="multipart/form-data"
                    class="row g-3 align-items-end" id="qrForm" data-parsley-validate>
                    @csrf

                    <div class="error-msg col-md-4">
                        <label for="qr" class="form-label">QR Code (jpeg, png, jpg)</label>
                        <input type="file" name="qr_image" class="form-control" data-parsley-required="true"
                            data-parsley-required-message="Please select a QR code image"
                            data-parsley-fileextension="jpeg,jpg,png" accept=".jpeg,.jpg,.png">
                    </div>


                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">+ Upload QR Code</button>
                    </div>
                </form>



            </div>



            <div class="table-responsive">
                <table id="qr-table" class="display table table-striped table-hover data-table sortingclose">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>QR Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // ✅ Add custom file extension validator
            window.Parsley.addValidator('fileextension', {
                validateString: function(value, requirement) {
                    var fileExtension = value.split('.').pop().toLowerCase();
                    var allowedExtensions = requirement.split(',');
                    return allowedExtensions.indexOf(fileExtension) !== -1;
                },
                messages: {
                    en: 'Allowed file types: jpeg, jpg, png'
                }
            });

            // ✅ Initialize Parsley
            $('#qrForm').parsley({
                errorClass: 'is-invalid',
                successClass: 'is-valid',
                errorsWrapper: '<span class="invalid-feedback d-block"></span>',
                errorTemplate: '<span></span>',
                trigger: 'change'
            });
        });

        $(function() {
            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('upload_qr.index') }}",
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'qr_image',
                        name: 'qr_image'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            window.deleteQR = function(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false,
                    reverseButtons: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/upload-qr/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'The QR code has been deleted.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                $('.data-table').DataTable().ajax.reload();
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Failed!',
                                    text: 'Something went wrong.',
                                    icon: 'error',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            };
        });
    </script>
@endsection
