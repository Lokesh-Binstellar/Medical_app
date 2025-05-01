
@extends('layouts.app')
@section('style')
<style>

</style>

@endsection
@section('content')
<div class="container">
    <div class="page-inner px-0">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header rounded-top" style="background-color:#5ecbd8">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title text-white">Package </h4>
                            <a href="{{route('labPackage.create')}}"  class="btn btn-primary text-white  fw-bold ">Add Package</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Laboratory</th>
                                        <th>Package Category</th>
                                        <th>Package Name</th>
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
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
$(function() {

var table = $('.data-table').DataTable({
    processing: true,
    serverSide: true,
    addIndex:true,
    ajax: "{{ route('labPackage.index') }}",
    columns: [ 
        {
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
      
        {
            data: 'id',
            name: 'id'
        },
        {
            data: 'lab_id',
            name: 'lab_id'
        },
        {
            data: 'package_category_id',
            name: 'package_category_id'
        },
        {
            data: 'package_name',
            name: 'package_name'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ]
});
$('body').on('click', '.deletePackage', function() {
                var package_id = $(this).data("id");

                if (!confirm("Are you sure you want to delete?")) {
                    return;
                }

                $.ajax({
                    type: "DELETE",
                    url: "/labPackage/" + package_id,
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (typeof table !== 'undefined') {
                            table.draw();
                        }
                        alert('Category deleted successfully');
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr.responseText);
                        alert('Failed to delete category.');
                    }
                });


            });
});
</script>
@endsection

