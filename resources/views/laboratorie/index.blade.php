
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
                            <h4 class="card-title text-white">Laboratory </h4>
                            <a href="{{ route('laboratorie.create') }}"  class="btn btn-primary text-white  fw-bold ">Add Laboratory</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Phone</th>
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
        ajax: "{{ route('laboratorie.index') }}",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'lab_name',
                name: 'lab_name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'address',
                name: 'address'
            },
            {
                data: 'phone',
                name: 'phone'
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
</script>
@endsection


