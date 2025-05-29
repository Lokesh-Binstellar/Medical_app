@extends('layouts.app')

@section('styles')
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
@endsection

@section('content')


                    <div class="card">
                        <div class="card-header rounded-top">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h4 class="card-title mb-0">Zip Code Vice Delivery</h4>
                                <div class="d-flex gap-2 flex-wrap">
                                    <form action="{{ route('zip_code_vise_delivery.upload') }}" id="importForm"
                                        method="POST" enctype="multipart/form-data"
                                        class="d-flex align-items-center gap-2">
                                        @csrf
                                        <input type="file" name="file" class="form-control" required>
                                        <button type="submit" class="btn btn-primary addButton text-nowrap px-5">+ Import
                                            Zip Code File</button>
                                    </form>

                                    <!-- Delete All Button -->
                                    <form id="deleteAllForm" action="{{ route('zip_code_vise_delivery.deleteAll') }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-primary addButton text-nowrap " id="deleteAllBtn">Delete
                                            All</button>
                                    </form>

                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            @if (session('success'))
                                <div id="success-alert" class="alert alert-success alert-dismissible fade show"
                                    role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped data-table w-100">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Zipcode</th>
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
        $(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('zip_code_vise_delivery.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'zipcode',
                        name: 'zipcode'
                    }
                ]
            });

            // Optional success alert fade out
            const alertBox = document.getElementById('success-alert');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 3000);
            }
        });


        document.getElementById('deleteAllBtn').addEventListener('click', function(e) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete all zip codes!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteAllForm').submit();
                }
            });
        });
    </script>
@endsection
