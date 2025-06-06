@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Notifications</h4>
    </div>

    <div class="card-body">
        @auth
            @php
                $user = Auth::user();
                $role = 'User';

                if ($user->role === 'admin') {
                    $role = 'Admin';
                } elseif ($user->pharmacies) {
                    $role = 'Pharmacy';
                } elseif ($user->laboratories) {
                    $role = 'Laboratory';
                }
            @endphp

            <h5 class="text-black">Unread {{ $role }} Notifications</h5>
            @forelse ($formattedUnread as $not)
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 font-bold" style="color: black">
                                {{ $role === 'Pharmacy' ? 'New Quote Request' : ($not['title'] ?? 'Notification') }}
                            </h6>
                            <small class="text-muted">{{ $not['message'] }}</small>
                        </div>
                        <small class="text-muted text-end">{{ $not['datetime'] }}</small>
                    </div>
                </div>
            @empty
                <p class="text-muted">No unread notifications.</p>
            @endforelse

            <hr>

            <h5 class="text-black">Read {{ $role }} Notifications</h5>
            @forelse ($formattedRead as $not)
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-start bg-light">
                        <div>
                            <h6 class="mb-1 font-bold" style="color: black">
                                {{ $role === 'Pharmacy' ? 'New Quote Request' : ($not['title'] ?? 'Notification') }}
                            </h6>
                            <small class="text-muted">{{ $not['message'] }}</small>
                        </div>
                        <small class="text-muted text-end">{{ $not['datetime'] }}</small>
                    </div>
                </div>
            @empty
                <p class="text-muted">No read notifications.</p>
            @endforelse
        @endauth
    </div>
</div>



           
@endsection
