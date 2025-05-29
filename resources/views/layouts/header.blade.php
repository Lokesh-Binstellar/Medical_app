@section('styles')
    <style>
        .filter {
            justify-content: space-evenly;
            margin-bottom: 8px;
        }

        .filter button {
            font-size: x-small;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
@endsection

<nav class="layout-navbar navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme " id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
        </a>
    </div>
    {{-- @livewire('notifications') --}}
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        {{-- @if (Route::current()->getName() == 'dashboard') --}}
        <!-- Welcome Text -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0 ">
                @auth
                    <h3 class="fw-bold text-primary mb-0">Welcome @if (Auth::user()->laboratories)
                            {{ Auth::user()->laboratories->lab_name }}
                        @elseif(Auth::user()->pharmacies)
                            {{ Auth::user()->pharmacies->pharmacy_name }}
                        @else
                            {{ auth()->user()->name }}
                        @endif !</h3>
                @else
                    <span class="fw-bold text-primary">
                        Welcome, Guest!
                    </span>
                @endauth

                @auth
                <span class="fw-bold text-primary">
                    
                    @if (Auth::user()->pharmacies)
                            Address :
                            {{ Auth::user()->pharmacies->address }}
 
                            @elseif (Auth::user()->laboratories)
                            Address :
                            {{ Auth::user()->laboratories->lab_name }}
                            @endif
                        </span>
                @endauth

            </div>


        </div>
        <!-- /Welcome Text -->
        {{-- @endif --}}

        </li>
        <!--/ Notification -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">


            <!-- Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
                @auth
                    @php

                        $notificationCount = 0;

                        if (Auth::check() && Auth::user()->pharmacies) {
                            $pharmacyUserId = Auth::user()->pharmacies->user_id;

                            $notificationCount = DB::table('notifications')
                                ->where('notifiable_id', $pharmacyUserId)
                                ->where('notifiable_type', 'App\\Models\\User') // Adjust if needed
                                ->whereNull('read_at') // Only unread notifications
                                ->count();
                        }
                    @endphp

                    <a class="nav-link btn btn-text-secondary btn-icon dropdown-toggle hide-arrow"
                        href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-expanded="false">
                        <i class="mdi mdi-bell-outline mdi-24px"></i>

                        @if ($notificationCount > 0)
                            <span id="notification-dot"
                                class="position-absolute top-0 start-50 translate-middle-y badge rounded-pill bg-danger mt-2 border"
                                style="font-size: 10px; padding: 4px 6px;transform: translateY(-30%) !important;">
                                {{ $notificationCount }}
                            </span>
                        @endif
                    </a>
                @endauth

                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="fw-normal mb-0 me-auto" style="color: black">Notification</h6>
                            @auth
                                @if (Auth::user()->pharmacies)
                                    @php
                                        $pharmacyUserId = Auth::user()->pharmacies->user_id;
                                        $notificationCount = \DB::table('notifications')
                                            ->where('notifiable_id', $pharmacyUserId)
                                            ->where('notifiable_type', 'App\\Models\\User') // Adjust if your model namespace is different
                                            ->count();
                                    @endphp
                                @endif
                            @endauth

                            <span class="badge rounded-pill bg-label-primary">{{ $notificationCount }} New</span>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container notification-item">
                        <ul class="list-group list-group-flush">
                            @auth
                                @if (Auth::user()->pharmacies)
                                    @php
                                        $pharmacyUserId = Auth::user()->pharmacies->user_id;

                                        $notifications = \DB::table('notifications')
                                            ->where('notifiable_id', $pharmacyUserId)
                                            ->where('notifiable_type', 'App\\Models\\User')
                                            ->whereNull('read_at')
                                            ->orderBy('created_at', 'desc')
                                            ->take(10)
                                            ->get();
                                    @endphp

                                    @foreach ($notifications as $notification)
                                        @php
                                            $data = json_decode($notification->data, true);
                                        @endphp

                                        <li class="list-group-item list-group-item-action dropdown-notifications-item"
                                            id="notification-{{ $notification->id }}">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar me-1">
                                                        <img src="{{ asset('assets/img/quote.png') }}" alt="avatar"
                                                            class="w-px-40 h-auto rounded-circle">
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                                                    <h6 class="mb-1 text-truncate text-dark">New Quote Request 🎉</h6>
                                                    <small class="text-truncate text-body">
                                                        {{ $data['message'] ?? 'No message available' }}
                                                    </small>
                                                    <small class="text-muted mt-1">
                                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                    </small>
                                                    <button class="btn btn-sm btn-outline-primary mt-1 mark-read-btn"
                                                        data-id="{{ $notification->id }}">Mark as read</button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            @endauth
                        </ul>
                    </li>

                    <li class="dropdown-menu-footer border-top p-2">
                        <a href="{{ route('notification.index') }}"
                            class="btn btn-primary d-flex justify-content-center">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
        <li class="nav-item navbar-dropdown dropdown-user dropdown profile">

            <a class="nav-link dropdown-toggle hide-arrow pr" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar">
                    <img src="{{ asset('assets/img/profile.jpg') }}" alt class="w-px-40 h-px-40 rounded-circle" />
                    {{-- <img src="{{ asset('assets/img/branding/main-logo.png') }}"
                            class="w-px-40 h-auto rounded-circle"> --}}

                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <!-- <li>
                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar ">
                                    <img src="{{ asset('upload/user-profile/' . Auth::user()->picture) }}"
                                        class="w-px-40 h-auto rounded-circle" />
                                    {{-- <img src="{{ asset('assets/img/branding/main-logo.png') }}"
                                            class="w-px-40 h-auto rounded-circle"> --}}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-medium d-block">
                                    @if (Auth::user()->laboratories)
                                        {{ Auth::user()->laboratories->owner_name }}
                                    @elseif(Auth::user()->pharmacies)
                                        {{ Auth::user()->pharmacies->owner_name }}
                                    @else
                                        {{ Auth::user()->name }}
                                    @endif
                                </span>
                                <small
                                    class="text-muted">{{ Auth::user()->role ? Auth::user()->role->name : '' }}</small>
                            </div>
                        </div>
                    </a>
                </li> -->
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('profile.custom') }}">
                        <i class="mdi mdi-account-outline me-2"></i>
                        <span class="align-middle">My Profile</span>
                    </a>
                </li>

                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout me-2"></i>
                        <span class="align-middle">Log Out</span>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>

                </li>
            </ul>
        </li>
        <!--/ User -->
        </ul>
    </div>

    
    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input autocomplete="off" type="text" class="form-control search-input container-xxl border-0"
            placeholder="Search..." aria-label="Search..." />
        <i class="mdi mdi-close search-toggler cursor-pointer"></i>
    </div>
</nav>
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenMeta) {
        console.error("CSRF token meta tag not found!");
    } else {
        const csrfToken = csrfTokenMeta.getAttribute('content');

        document.querySelectorAll('.mark-read-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;

                fetch(`/notifications/read/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('.notification-item').remove();

                        }
                    });
            });
        });
    }
</script>
<script>
    // Enable Pusher logging - disable in production!
    Pusher.logToConsole = true;



    var pusher = new Pusher('7ba4a23b60749764133c', {
        cluster: 'ap1'
    });

    var channel = pusher.subscribe('my-channel');

    channel.bind('my-event', function() {
        console.log('Pusher callback called');

        // Optional: prevent multiple reloads in a short time
        location.reload(true);
    });
</script>
