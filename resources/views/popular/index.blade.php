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
    <div class="container ">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0 ">Popular Brands </h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <form action="{{ route('popular.store') }}" method="POST" enctype="multipart/form-data"
                                    class="d-flex gap-2 align-items-center" id="importForm">
                                    @csrf
                                    <select name="name" class="form-control select2" id="brand-select"  >
                                        <option value="">Select Brand</option>
                                        @foreach ($popularBrands as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <input type="file" name="logo" class="form-control" id="logo">
                                    <button type="submit" class="btn btn-primary addButton text-nowrap px-5">+ Add Brand</button>
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
                                            <th>Brand Name</th>
                                            <th>Logo</th>
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
        // DataTable already here...
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('popular.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'logo', name: 'logo' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // ✅ Initialize Select2 with correct placeholder
        $('#brand-select').select2({
            placeholder: "Select Brand",
            allowClear: true
        });

        // ✅ Delete function (already fine)
        window.deleteUser = function(id) {
            if (confirm('Are you sure you want to delete this Brand?')) {
                $.ajax({
                    url: '{{ route('popular.destroy', '') }}/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        alert('Popular deleted successfully!');
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            }
        }
    });
</script>

<script src="{{ asset('js/popularbrands/popularbrands_form.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
