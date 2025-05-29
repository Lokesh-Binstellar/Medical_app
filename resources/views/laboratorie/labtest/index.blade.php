@extends('layouts.app')
@section('styles')



@endsection
@section('content')

                    <div class="card">
                        <div class="card-header rounded-top">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title mb-0">Lab Test</h4>
                                <div class="d-flex justify-content-between align-items-center">
                                <form action="{{ route('labtest.import') }}" id="importForm" method="POST"
                                    enctype="multipart/form-data" class="d-flex  gap-2">
                                    @csrf
                                    <div class="error-msg">

                                        <input type="file" name="file" class="form-control" id="file">
                                    </div>
                                    <div>

                                        <button type="submit" class="btn btn-primary addButton text-nowrap px-5">+ Import Lab Test</button>
                                    </div>
                                </form>
                                </div>
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
    <script src="{{ asset('js/labtest/labtest_from.js') }}"></script>

@endsection
