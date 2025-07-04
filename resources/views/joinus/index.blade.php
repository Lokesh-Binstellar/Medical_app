

@extends('layouts.app')
@section('styles')

@endsection
@section('content')

                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0 ">Join Us Request </h4>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif


                            <div class=" ">
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

            window.deleteJoinUs = function(id) {
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
                            url: "/join-us/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.success,
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
                success: function(response) {
                    alert(response.message); // You can use toast here too
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message);
                    } else {
                        alert('Something went wrong. Please try again later.');
                    }
                }
            });
        }
    </script>

@endsection
