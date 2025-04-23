<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">
        <!-- 👇 This is the new added part -->
        @auth
    <span class="fw-bold text-primary">
        Welcome,
        @if(Auth::user()->laboratories)
            {{ Auth::user()->laboratories->lab_name }}
        @elseif(Auth::user()->pharmacies)
            {{ Auth::user()->pharmacies->pharmacy_name }}
        @else
            {{ Auth::user()->name }}
        @endif
    </span>
@else
    <span class="fw-bold text-primary">
        Welcome, Guest!
    </span>
@endauth

    
        

        {{-- <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                        <i class="fa fa-search search-icon"></i>
                    </button>
                </div>
                <input type="text" placeholder="Search ..." class="form-control" />
            </div>
        </nav> --}}

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-expanded="false" aria-haspopup="true">
                    <i class="fa fa-search"></i>
                </a>
                {{-- <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                        <div class="input-group">
                            <input type="text" placeholder="Search ..." class="form-control" />
                        </div>
                    </form>
                </ul> --}}
            </li>
            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-envelope"></i>
                </a>
             
            </li>
           

            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                    aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="{{asset('assets/img/profile.jpg')}}" alt="..." class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                        <span class="op-7">Hi,</span>
                        {{-- <span class="fw-bold">{{ Auth::user()->name }}</span> --}}
                        <span class="fw-bold">@if(Auth::user()->laboratories)
                            {{ Auth::user()->laboratories->owner_name }}
                        @elseif(Auth::user()->pharmacies)
                            {{ Auth::user()->pharmacies->owner_name }}
                        @else
                            {{ Auth::user()->name }}
                        @endif</span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <img src="{{asset('assets/img/profile.jpg')}}" alt="image profile"
                                        class="avatar-img rounded" />
                                </div>
                                <div class="u-text">
                                    {{-- <h4>{{ Auth::user()->name }}</h4> --}}
                                    <h4>@if(Auth::user()->laboratories)
                                        {{ Auth::user()->laboratories->owner_name }}
                                    @elseif(Auth::user()->pharmacies)
                                        {{ Auth::user()->pharmacies->owner_name }}
                                    @else
                                        {{ Auth::user()->name }}
                                    @endif</h4>
                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                    <a href="{{ route('profile.custom') }}" class="btn btn-xs btn-secondary btn-sm">View Profile</a>

                                </div>
                            </div>
                        </li>
                        <li>
                            {{-- <div class="dropdown-divider"></div> --}}
                            {{-- <a class="dropdown-item" href="#">My Profile</a>
                            <a class="dropdown-item" href="#">My Balance</a>
                            <a class="dropdown-item" href="#">Inbox</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Account Setting</a> --}}
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                {{-- <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link> --}}
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    this.closest('form').submit();">Logout</a>
                            </form>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
