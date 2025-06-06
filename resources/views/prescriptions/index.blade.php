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

        .swal2-deny btn btn-outline-secondary {
            display: none !important;

        }
    </style>
@endsection
@section('content')
    <div>
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

        <div class="card">
            <div class="card-header rounded-top">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0  text-white">Prescriptions</h4>
                    {{-- <a href="{{ route('laboratorie.create') }}" class="btn btn-primary text-white  addButton ">+ Add
                                    Laboratory</a> --}}

                                
                </div>
            </div>
            <div class="card-body">
                    <button onclick="triggerEvent()">event calll</button>
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
                                {{-- <th style="width: 10%">Action</th> --}}
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
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // }
                ]
            });


        });

        function updateStatus(select, id) {
            let value = select.value;
            let prevValue = select.getAttribute('data-prev');

            if (value === "1") {
                Swal.fire({
                    title: 'Reason Required',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    showLoaderOnConfirm: true,
                    customClass: {
                        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-outline-danger waves-effect'
                    },
                    onBeforeOpen: () => {
                        // Hide the submit button initially
                        const submitButton = document.querySelector('.swal2-confirm');
                        if (submitButton) {
                            showCancelButton.style.display = 'none !important';
                        }
                    },
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Reason is required!'; // If empty, show validation message
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;

                        fetch('/prescriptions/update-status/' + id, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    prescription_status: value,
                                    reason: reason
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status) {
                                    // Disable the dropdown if "No" is selected and saved
                                    select.disabled = true;

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Updated!',
                                        text: 'Prescription status updated successfully',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }
                            });
                    } else {
                        // If cancelled, revert to previous value
                        select.value = prevValue;
                    }
                });
            } else {
                // For "Yes" just update
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
                        }
                    });
            }
        }







function triggerEvent() {
    const receiverId = 5;
    fetch(`/trigger-event?receiver_id=${receiverId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.status);
    })
    .catch(error => console.error('Fetch error:', error));
}




    
    </script>
@endsection
