@extends('layouts.app')
@section('content')
    <div class="">
        <div class=" page-inner px-0">
            <div class="row justify-content-center ">


                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header rounded-top" style="background-color:#000000">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">Lab Test</h4>
                                <form action="{{ route('labtest.import') }}" id="importForm" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="file" required>
                                    <button type="submit" class="btn btn-primary text-white  fw-bold ">Import Lab Test</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
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
                ajax: "{{ route('labtest.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });


        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchInput");

            // Trigger search on change
            searchInput.addEventListener("change", function() {
                document.getElementById("searchForm").submit();
            });

            // Clear input on page refresh (if needed)
            if (performance.navigation.type === 1) { // 1 means reload
                searchInput.value = "";
            }
        });

        document.getElementById('importForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('loader').style.display = 'block';
        });
        document.addEventListener('DOMContentLoaded', function() {
            const alertBox = document.getElementById('success-alert');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 3000); // 3000ms = 3 seconds
            }
        });
    </script>
@endsection
