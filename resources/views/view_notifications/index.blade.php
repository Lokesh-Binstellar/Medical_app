@extends('layouts.app')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Notifications</h4>
                </div>
                <div class="card-body">
                    @auth
                        @foreach ($formattedNotifications as $not)
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1" style="color: black;">{{ $not['title'] }}</h6>
                                        <small class="text-muted">{{ $not['message'] }}</small>
                                    </div>
                                    <small class="text-muted text-end">{{ $not['datetime'] }}</small>
                                </div>
                            </div>
                        @endforeach
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
