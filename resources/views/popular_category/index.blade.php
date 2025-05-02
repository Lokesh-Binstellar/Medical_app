@extends('layouts.app')
@section('styles')
    <style>

    </style>
@endsection
@section('content')
    <div class="container mt-5">
        <div class="page-inner">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between flex-column ">
                            <h4 class="card-title pb-3 ">Popular Category </h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <form action="{{ route('popular_category.store') }}" method="POST"
                                    enctype="multipart/form-data" class="d-flex gap-2">
                                    @csrf
                                    <select name="name" class="form-control select2" id="brand-select" required
                                        style="width: 250px;">
                                        <option value="">Select Brand</option>
                                        @foreach ($popularCategory as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <input type="file" name="logo" class="form-control-file">
                                    <button type="submit" class="btn btn-primary  ">Add Category</button>
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
                                            <th>#</th>
                                            <th>Category Name</th>
                                            <th>Logo</th>
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

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize Select2 with AJAX
            $('.select2').select2()
        });


        $(function() {

var table = $('.data-table').DataTable({
    processing: true,
    serverSide: true,
    addIndex:true,
    ajax: "{{ route('popular_category.index') }}",
    columns: [ 
        {
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
        {
            data: 'name',
            name: 'name'
        },
        {
            data: 'logo',
            name: 'logo'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ]
});
// $('body').on('click', '.deletePackage', function() {
//                 var package_id = $(this).data("id");

//                 if (!confirm("Are you sure you want to delete?")) {
//                     return;
//                 }

//                 $.ajax({
//                     type: "DELETE",
//                     url: "/labPackage/" + package_id,
//                     data: {
//                         _token: "{{ csrf_token() }}"
//                     },
//                     success: function(response) {
//                         if (typeof table !== 'undefined') {
//                             table.draw();
//                         }
//                         alert('Category deleted successfully');
//                     },
//                     error: function(xhr) {
//                         console.log('Error:', xhr.responseText);
//                         alert('Failed to delete category.');
//                     }
//                 });


//             });
});
    </script>
@endsection
