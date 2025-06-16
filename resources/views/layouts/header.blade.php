@section('styles')
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
        <div class="navbar-nav align-items-center d-flex justify-content-between w-100">

            {{-- Left Side: Welcome + Address --}}
            <div class="nav-item navbar-search-wrapper mb-0">
                @auth
                    <h3 class="fw-bold text-primary mb-0">
                        Welcome
                        @if (Auth::user()->laboratories)
                            {{ Auth::user()->laboratories->lab_name }}
                        @elseif(Auth::user()->pharmacies)
                            {{ Auth::user()->pharmacies->pharmacy_name }}
                        @elseif(Auth::user()->deliveryProfile)
                            {{ Auth::user()->deliveryProfile->delivery_person_name }}
                        @elseif(Auth::user()->phlebotomists)
                            {{ Auth::user()->phlebotomists->phlebotomists_name }}
                        @else
                            {{ auth()->user()->name }}
                        @endif!
                    </h3>
                @else
                    <span class="fw-bold text-primary">
                        Welcome, Guest!
                    </span>
                @endauth


                @auth
                    <span class="fw-bold text-primary d-block mt-1">
                        @if (Auth::user()->pharmacies)
                            Address: {{ Auth::user()->pharmacies->address }}
                        @elseif (Auth::user()->laboratories)
                            Address: {{ Auth::user()->laboratories->lab_name }}
                        @endif
                    </span>
                @endauth
            </div>

            {{-- Right Side: ON/OFF Switch for Pharmacy --}}
            @if (Auth::check() && Auth::user()->role->name === 'pharmacy')
                <div class="me-3 d-flex align-items-center">
                    <label class="form-switch-custom mb-0">
                        <input type="checkbox" id="pharmacyStatusToggle"
                            {{ Auth::user()->pharmacies->status ? 'checked' : '' }}>
                        <span class="slider-custom"></span>
                    </label>
                    <span id="pharmacyStatusText"
                        class="toggle-text ms-2 fw-semibold {{ Auth::user()->pharmacies->status ? 'text-success' : 'text-danger' }}">
                        Status: {{ Auth::user()->pharmacies->status ? 'On' : 'Off' }}
                    </span>

                </div>
            @endif




        </div>

        <!-- /Welcome Text -->
        {{-- @endif --}}

        </li>
        <!--/ Notification -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <div id="pusher-message"
                style="display:none;padding:10px; background:#dff0d8; color:#3c763d; border:1px solid #d6e9c6; margin:10px 0;">
                messahge</div>
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
                                style="font-size: 10px; padding: 5px 5px; transform: translateY(-30%) !important;">
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
                                            // ->take(20)
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
                            // Remove notification
                            this.closest('.dropdown-notifications-item').remove();

                            // Update count
                            const badge = document.getElementById('notification-dot');
                            let count = parseInt(badge?.innerText || 0);

                            if (!isNaN(count)) {
                                count--;
                                if (count > 0) {
                                    badge.innerText = count;
                                    document.querySelector('.dropdown-header .badge').innerText =
                                        `${count} New`;
                                } else {
                                    badge.remove();
                                    document.querySelector('.dropdown-header .badge').remove();
                                }
                            }
                        }
                    });
            });
        });

    }


    // Initialize Pusher
    Pusher.logToConsole = true;

    var pusher = new Pusher('7ba4a23b60749764133c', {
        cluster: 'ap1'
    });

    var userRole = @json(auth()->user()->role->name ?? 'guest');
    var userId = @json(auth()->user()->id ?? 0);
    console.log('User Role:', userRole);
    console.log('User ID:', userId);

    // 🔒 Subscribe to user-specific channel
    if (userRole === 'admin') {
        var adminChannel = pusher.subscribe('admin-channel');
        adminChannel.bind('my-event', function(data) {
            console.log('Admin event:', data);
            showPusherMessage(data.message);
        });
    } else {
        var channel = pusher.subscribe('my-channel.' + userRole + '.user.' + userId);
        channel.bind('my-event', function(data) {
            localStorage.setItem('pusher_message', data.message || 'You have received a new quote.');
            location.reload(true);
        });
    }

    function showPusherMessage(msg) {
        var messageDiv = document.getElementById('pusher-message');
        messageDiv.textContent = msg || 'Notification received!';
        messageDiv.style.display = 'block';
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 20000);
    }


    window.onload = function() {
        var message = localStorage.getItem('pusher_message');
        if (message) {
            var messageDiv = document.getElementById('pusher-message');
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';

            setTimeout(function() {
                messageDiv.style.display = 'none';
                localStorage.removeItem('pusher_message');
            }, 20000);
        }
    };

    window.onload = function() {
        var message = localStorage.getItem('pusher_message');
        if (message) {
            var messageDiv = document.getElementById('pusher-message');
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';

            setTimeout(function() {
                messageDiv.style.display = 'none';
                localStorage.removeItem('pusher_message');
            }, 20000);
        }
    };
</script>
<script>
    const toggle = document.getElementById('pharmacyStatusToggle');
    const statusText = document.getElementById('pharmacyStatusText');

    toggle?.addEventListener('change', function() {
        fetch("{{ route('pharmacy.toggleStatus') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    const newStatus = data.new_status;

                    statusText.textContent = 'Status: ' + newStatus;

                    // Remove both possible classes first
                    statusText.classList.remove('text-success', 'text-danger');

                    // Add appropriate class
                    if (newStatus === 'On') {
                        statusText.classList.add('text-success');
                    } else {
                        statusText.classList.add('text-danger');
                    }
                } else {
                    alert('Failed to update status');
                    toggle.checked = !toggle.checked; // revert toggle
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Error updating status.");
                toggle.checked = !toggle.checked; // revert toggle
            });
    });
</script>
