@extends('layouts.app')
@section('content')
    <div class="">
        <div class=" page-inner px-0">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div id="loader" class="text-center mt-3" style="display:none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Importing...</span>
                                </div>
                                <p class="mt-2">Please wait, importing...</p>
                            </div>
                            <h4 class="card-title mb-0">Lab Test</h4>
                            {{-- <a href="{{ route('user.create') }}" class="btn btn-primary">Add user</a>  --}}
                            <form action="{{ route('labtest.import') }}" id="importForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file" required>
                                <button type="submit" class="btn btn-primary">Import Lab Test</button>
                            </form>
                        </div>
                        <div class="mt-2 " style="padding-left: 17px;padding-right: 17px;">
                            <form id="searchForm" method="GET" action="{{ route('labtest.index') }}"
                                class="d-flex gap-2 mb-3">
                                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                    placeholder="Search by name or id" class="form-control " style="max-width: 300px;" />

                                <button type="submit" class="btn btn-primary">Search by name or id</button>

                                <button type="button" class="btn btn-secondary"
                                    onclick="window.location.href='{{ route('labtest.index') }}'">Reset</button>
                            </form>

                        </div>
                        <div class="card-body">
                            @if (session('success'))
                            <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
                        @endif
                            <div class="table-responsive">

                            <table class="table table-bordered  mt-3">
                                <thead class="thead-dark">
                                    <tr>
                                      
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tests as $index => $test)
                                        <tr>
                                            <td>{{ $test->id}}</td>
                                            <td>{{ $test->name }}</td>
                                            <td>{{ $test->description}}</td>
                                            <td><a href="{{ route('labtest.show', $test->id) }}"
                                                    class="btn btn-info btn-sm">View</a></td>


                                            {{-- <td class="d-flex gap-2">
                                            <a href="{{ route('user.edit', $users->id) }}"><button type="button"
                                                    class="btn btn-sm btn-warning">Edit</button></a>
                                            <form action="{{ route('user.destroy', $users->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>

                                          
                                        </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            </div>
                            {{-- <div class="d-flex justify-content-center mt-4">
                                {{ $tests->links() }}
                            </div> --}}
                            {{-- @if ($user->isEmpty())
                                <div class="text-center mt-3 text-muted">No pharmacist records found.</div>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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
      
        document.getElementById('importForm').addEventListener('submit', function () {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('loader').style.display = 'block';
        });
        document.addEventListener('DOMContentLoaded', function () {
            const alertBox = document.getElementById('success-alert');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 3000); // 3000ms = 3 seconds
            }
        });
  
    </script>
@endsection
