{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-4">Join Us Requests</h4>

        <div class="table-responsive">
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('joinus.index') }}",
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: 'first_name',
                        render: function(data, type, row) {
                            return row.first_name + ' ' + row.last_name;
                        }
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'phone_number'
                    },
                    {
                        data: 'message'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        function deleteJoinUs(id) {
            if (confirm('Are you sure you want to delete?')) {
                $.ajax({
                    url: '/admin/join-us/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('.data-table').DataTable().ajax.reload();
                    }
                });
            }
        }
    </script>
@endsection --}}


@extends('layouts.app')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
    <style>
        .select2 {
            width: 300px !important;
        }

        body>span.select2-container.select2-container--default.select2-container--open {
            width: auto !important;
        }
    </style>
@endsection
@section('content')
    <div class="container ">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0 ">Join Us Request </h4>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif


                            <div class="container mt-5">
                                <h4 class="text-black">Admin Settings</h4>

                                @if (session('success'))
                                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger mt-2">{{ session('error') }}</div>
                                @endif

                                @php
                                    // This should be an array of email addresses
                                    $storedEmails = array_filter(explode(',', $settings->notification_emails ?? ''));
                                @endphp

                                <form method="POST" action="{{ route('joinus.updateEmails') }}">
                                    @csrf

                                    @php
                                        $existingEmails = array_filter(
                                            explode(',', $settings->notification_emails ?? ''),
                                        );
                                    @endphp

                                    <div class="form-group mb-3">
                                        <label for="email-input">Enter Email and press comma or enter:</label>
                                        <input type="text" id="email-input" class="form-control"
                                            placeholder="Type email and press Enter or Comma">
                                    </div>

                                    <input type="hidden" name="notification_emails" id="notificationEmails">
                                    <button id="save-emails" class="btn btn-primary mt-2">Save Settings</button>
                                </form>
                            </div>


                            <hr>
                            <div class="table-responsive">
                                <table id="add-row"
                                    class="display table table-striped table-hover data-table sortingclose">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Type</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Message</th>
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
            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('joinus.index') }}",
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'message',
                        name: 'message'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            window.deleteBanner = function(id) {
                if (confirm("Delete this banner?")) {
                    $.ajax({
                        url: "/join-us/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            table.ajax.reload();
                            alert("Deleted");
                        }
                    });
                }
            }

            window.editBanner = function(id) {
                $.get('/join-us/' + id + '/edit', function(data) {
                    // You can open a modal and populate image for editing
                    console.log(data);
                });
            }
        });







        $(document).ready(function() {
            const $input = $("#email-input");
            const $hiddenEmailInput = $("#notificationEmails");

            // Load stored emails from Laravel blade (ensure it's a clean array)
            const storedEmails = @json($existingEmails);

            // Initialize Tagify and preload existing tags
            const tagify = new Tagify($input[0]);
            tagify.addTags(storedEmails);

            // On tagify change: update hidden input
            tagify.on("change", function() {
                const currentEmails = tagify.value.map(tag => tag.value.trim());
                $hiddenEmailInput.val(currentEmails.join(","));
            });

            // Save updated emails
            $("#save-emails").on("click", function(e) {
                e.preventDefault();

                const currentEmails = tagify.value.map(tag => tag.value.trim());

                $.ajax({
                    url: "{{ route('joinus.updateEmails') }}",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    contentType: "application/json",
                    data: JSON.stringify({
                        emails: currentEmails // only current (after removal/addition)
                    }),
                    success: function(data) {
                        alert(data.message || "Emails updated successfully!");
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert("Something went wrong!");
                    }
                });
            });
        });
        

        function submitJoinUsForm(event) {
    event.preventDefault();

    var formData = {
        type: $('#type').val(),
        first_name: $('#first_name').val(),
        last_name: $('#last_name').val(),
        email: $('#email').val(),
        phone_number: $('#phone_number').val(),
        message: $('#message').val(),
    };

    $.ajax({
        url: '/api/join-us',
        type: 'POST',
        data: JSON.stringify(formData),
        contentType: 'application/json',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function (response) {
            alert(response.message); // You can use toast here too
        },
        error: function (xhr) {
            if (xhr.responseJSON && xhr.responseJSON.message) {
                alert(xhr.responseJSON.message);
            } else {
                alert('Something went wrong. Please try again later.');
            }
        }
    });
}
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
@endsection
