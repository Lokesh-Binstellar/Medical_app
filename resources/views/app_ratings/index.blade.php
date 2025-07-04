

@extends('layouts.app')

@section('content')

                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0">App Ratings</h4>
                        </div>

                        <div class="card-body">

                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover data-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Reviewer</th>
                                                <th>Review</th>
                                                <th>Date</th>
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
    $(document).ready(function () {
        $('#add-row').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("app_ratings.index") }}',
            columns: [
                {
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                { data: 'reviewer', name: 'reviewer' },
                { data: 'review', name: 'review', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' }
            ]
        });
    });
</script>
@endsection
