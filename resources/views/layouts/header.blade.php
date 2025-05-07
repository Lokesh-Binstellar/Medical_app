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

<nav class="layout-navbar navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        @if (Route::current()->getName() == 'dashboard')
            <!-- Welcome Text -->
            <div class="navbar-nav align-items-center">
                <div class="nav-item navbar-search-wrapper mb-0 mt-4 ">
                    @auth
                        <h3 class="fw-bold text-primary">Welcome @if (Auth::user()->laboratories)
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
                        @if (Auth::user()->pharmacies)
                            <span class="fw-bold text-primary">
                                Address :
                                {{ Auth::user()->pharmacies->address }}

                            </span>
                        @endif
                    @endauth

                </div>


            </div>
            <!-- /Welcome Text -->
        @endif
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Notification -->
            {{-- <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                    href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                    aria-expanded="false">
                    <i class="mdi mdi-bell-outline mdi-24px"></i>
                    <span
                        class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border unread-notification-count"> --}}
            {{-- {{ auth()->user()->unreadNotifications->count() }} --}}
            {{-- </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0" id="notificationDropdown" style="height: 538px">
                    <li class="dropdown-menu-header border-bottom py-50">
                        <div class="dropdown-header d-flex align-items-center py-2">
                            <h6 class="mb-0 me-auto">Notifications</h6>
                            <div class="d-flex align-items-center">
                                <span class="badge rounded-pill bg-label-primary fs-xsmall me-2" id="unread-count">0
                                    New</span>
                                <button type="button" aria-label="Mark all as read" data-bs-toggle="tooltip" data-bs-original-title="Mark all as read" id="markAllRead"
                                    class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all">
                                    <i class="ri-mail-open-line text-heading ri-20px"></i>
                                </button>
                            </div>
                        </div>
                        <form id="filter-form">
                            <div class="form-floating w-auto d-flex filter">
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="fetchNotifications('all')">
                                    All
                                  </button>
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="fetchNotifications('read')">
                                    Read
                                  </button>
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="fetchNotifications('unread')">
                                    Unread
                                  </button>

                            </div>
                        </form>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush" id="notificationList"></ul>
                    </li>
                </ul>
            </li>
        </ul> --}}

            <!--/ Notification -->
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown profile">
                <a class="nav-link dropdown-toggle hide-arrow pr" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar">
                        <img src="{{ asset('assets/img/profile.jpg') }}" alt class="w-px-40 h-px-40 rounded-circle" />
                        {{-- <img src="{{ asset('assets/img/branding/main-logo.png') }}"
                            class="w-px-40 h-auto rounded-circle"> --}}

                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
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
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.custom') }}">
                            <i class="mdi mdi-account-outline me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    {{-- <li>
                    <a class="dropdown-item" href="{{ route('profile.updatePassword') }}">
                        <i class="mdi mdi-key-outline me-2"></i>
                        <span class="align-middle">Change Password</span>
                    </a>
                </li> --}}
                    {{-- <li>
                        <a class="dropdown-item" href="pages-account-settings-billing.html">
                            <span class="d-flex align-items-center align-middle">
                                <i class="flex-shrink-0 mdi mdi-credit-card-outline me-2"></i>
                                <span class="flex-grow-1 align-middle">Billing</span>
                                <span
                                    class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-faq.html">
                            <i class="mdi mdi-help-circle-outline me-2"></i>
                            <span class="align-middle">FAQ</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-pricing.html">
                            <i class="mdi mdi-currency-usd me-2"></i>
                            <span class="align-middle">Pricing</span>
                        </a>
                    </li> --}}
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

                        {{-- <a class="dropdown-item" href="auth-login-cover.html" target="_blank">
                            <i class="mdi mdi-logout me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a> --}}
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

{{-- @section('script') --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- 
<script>
    $(document).ready(function() {
        fetchNotifications('all'); --}}

{{-- // Fetch notifications when filter changes
        // $("#notificationFilter").on("change", function() {
        //     let filter = $(this).val();
        //     fetchNotifications(filter);
        // });

        // Mark notification as read
        $(document).on("click", ".mark-as-read", function() {
            let notificationId = $(this).data("id");
            $.ajax({
                url: "{{ route('notifications.markAsRead','') }}/" + notificationId,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    fetchNotifications($("#notificationFilter").val());
                }
            });
        });

        // Mark all notifications as read
        $("#markAllRead").on("click", function() {
            $.ajax({
                url: "{{ route('notifications.markAllRead') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    fetchNotifications($("#notificationFilter").val());
                }
            });
        });

        fetchNotifications();
    });

    function fetchNotifications(filter = 'all') {
        $.ajax({
            url: "{{ route('notifications.filter') }}",
            type: "GET",
            data: {
                filter: filter
            },
            success: function(response) {

                let notifications = response.notifications;
                let unreadCount = response.unread_count;

                let notificationList = $("#notificationList");
                let unreadCountBadge = $("#unread-count");
                let unread = $(".unread-notification-count");

                notificationList.empty();
                unreadCountBadge.text(unreadCount + " New");
                if (unreadCount === 0) {
                    unread.addClass('d-none');
                } else {
                    unread.removeClass('d-none');
                }

                if (notifications.length === 0) {
                    notificationList.append(
                        '<li class="list-group-item text-center">No notifications</li>');
                    return;
                }

                notifications.forEach(notification => {
                    let isUnread = notification.read_at === null;
                    let badge = isUnread ?
                        '<span class="badge" style="position: absolute; bottom: 42px; left: 16px; background-color: red; color: white; padding: 2px 8px; font-size: 12px; border-radius: 12px;">New</span>' :
                        '';
                    let readButton = isUnread ?
                        `<a href="javascript:void(0);" class="mark-as-read" data-id="${notification.id}"><i class="fa-solid fa-xmark"></i></a>` :
                        '';

                    notificationList.append(`
        <li class="list-group-item list-group-item-action dropdown-notifications-item">
            <div class="d-flex gap-2">
                <div class="flex-shrink-0" style="margin-top: 25px;">
                    <div class="avatar me-1">
                        <img src="../../assets/img/avatars/2.png" class="w-px-40 h-auto rounded-circle" />
                        </div>
                        <div>
                            ${badge}
                            </div>
                </div>
                <div class="d-flex flex-column flex-grow-1 overflow-hidden" style="padding: 12px;">
                    <h6 class="mb-1 text-truncate">${notification.data.module ?? 'Notification'} - ${notification.data.header ?? 'Low Stock'}</h6>
                    <small class="text-truncate text-body">${notification.data.message}</small>
                </div>
                <div class="flex-shrink-0" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%);">${readButton}</div>
            </div>
        </li>
    `);
                });
            }
        });
    }
</script> --}}
{{-- @endsection --}}
