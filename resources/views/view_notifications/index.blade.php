@extends('layouts.app')
@section('content')

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Notification</h4>

                    </div>

                    <div class="card-body">
                        @auth
       
                            @if (Auth::user()->role === 'admin')
                                <h5>Admin Notifications</h5>
                                @foreach ($formattedNotifications as $not)
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 font-bold" style="color: black">{{ $not['title'] }}</h6>
                                                <small class="text-muted">{{ $not['message'] }}</small>
                                            </div>
                                            <small class="text-muted text-end">{{ $not['datetime'] }}</small>
                                        </div>
                                    </div>
                                @endforeach

                   
                            @elseif (Auth::user()->pharmacies)
                                <h5>Pharmacy Notifications</h5>
                                @foreach ($formattedNotifications as $not)
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 font-bold" style="color: black">New Quoate Request</h6>
                                                <small class="text-muted">{{ $not['message'] }}</small>
                                            </div>
                                            <small class="text-muted text-end">{{ $not['datetime'] }}</small>
                                        </div>
                                    </div>
                                @endforeach

                
                            @elseif (Auth::user()->laboratories)
                                <h5>Laboratory Notifications</h5>
                                @foreach ($formattedNotifications as $not)
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 font-bold" style="color: black">{{ $not['title'] }}</h6>
                                                <small class="text-muted">{{ $not['message'] }}</small>
                                            </div>
                                            <small class="text-muted text-end">{{ $not['datetime'] }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endauth
                    </div>
                </div>

           
@endsection
