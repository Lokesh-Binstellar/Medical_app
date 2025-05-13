@extends('layouts.app')
@section('styles')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                            <h4 class="card-title mb-0 ">Home Screen Banner </h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <form action="{{ route('homebanner.store') }}" method="POST" enctype="multipart/form-data"
                                    class="d-flex gap-2 " id="importForm">
                                    @csrf
                                    
                                    <div class="error-msg">
                                        <input type="file" name="image" class="form-control" required >
                                    </div>
                                    <div class="error-msg">
                                        <input type="number" name="priority" class="form-control" placeholder="Priority" min="0" required>
                                    </div>
                                
                                    <div>

                                        <button type="submit" class="btn btn-primary addButton text-nowrap px-5">+ Add Banner</button>
                                    </div>
                                    
                                </form>
                            </div>

                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                           

                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover data-table sortingclose">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Banner</th>
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
$(function () {
    let table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('homebanner.index') }}",
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'image', name: 'image'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    window.deleteBanner = function(id) {
        if (confirm("Delete this banner?")) {
            $.ajax({
                url: "/home-banners/" + id,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function () {
                    table.ajax.reload();
                    alert("Deleted");
                }
            });
        }
    }

    window.editBanner = function(id) {
        $.get('/home-banners/' + id + '/edit', function (data) {
            // You can open a modal and populate image for editing
            console.log(data);
        });
    }
});


// $('.sortingclose').DataTable({
//     processing: true,
//     serverSide: true,
//     ajax: "{{ route('homebanner.index') }}",
//     columnDefs: [
//         {
//             targets: [1], // index of the "Banner" column (0-based)
//             orderable: false
//         }
//     ]
// });

</script>

@endsection
