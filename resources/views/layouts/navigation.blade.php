@php
    use App\Models\Role;
    use App\Models\Permission;
    use App\Models\User;

    $loggedInUser = Auth::user();
    $permissions = [];
    $roleId = User::where('id', Auth::user()->id)->value('role_id');
    $data = Permission::where('role_id', $roleId)->pluck('module', 'id')->toArray();
    $permissions = array_unique($data);

    $isSuperAdmin = 0;
    if ($loggedInUser->role_id == 1) {
        $isSuperAdmin = 1;
    }
    // dd($permissions);
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <div class="mdi mdi-close close-menu "></div>
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <img src="{{ asset('assets/img/gomeds.png') }}" height="130" width="250" alt="">
        </a>
    </div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ in_array(Route::current()->getName(), ['dashboard']) ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        @if (in_array('Roles', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['roles.index']) ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-shield-account"></i>
                    <div data-i18n="Role">Role</div>
                </a>
            </li>
        @endif

        @if (in_array('Users', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['user.index']) ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                    <div data-i18n="User">User</div>
                </a>
            </li>
        @endif

        @if (in_array('Pharmacies', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['pharmacist.index']) ? 'active' : '' }}">
                <a href="{{ route('pharmacist.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-medical-bag"></i>
                    <div data-i18n="Pharmacies">Pharmacies</div>
                </a>
            </li>
        @endif

        @if (in_array('Pharmacies', $permissions))
            <li class="menu-item {{ in_array(Route::current()->getName(), ['add.medicine']) ? 'active' : '' }}">
                <a href="{{ route('add.medicine') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-pill"></i>
                    <div data-i18n="Add Medicine ">Add Medicine </div>
                </a>
            </li>
        @endif

        @if (in_array('Laboratories', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['laboratorie.index']) ? 'active' : '' }}">
                <a href="{{ route('laboratorie.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-flask"></i>
                    <div data-i18n="Laboratories">Laboratories</div>
                </a>
            </li>
        @endif


        @if (in_array('Medicines', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['medicine.index']) ? 'active' : '' }}">
                <a href="{{ route('medicine.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-pill"></i>
                    <div data-i18n="Medicine">Medicine</div>
                </a>
            </li>
        @endif

        @if (in_array('Otcmedicines', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['otcmedicine.index']) ? 'active' : '' }}">
                <a href="{{ route('otcmedicine.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-pill"></i>
                    <div data-i18n="OTC">OTC</div>
                </a>
            </li>
        @endif


        @if (in_array('Laboratories', $permissions) || $isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['popular_lab_test.index']) ? 'active' : '' }}">
                <a href="{{ route('popular_lab_test.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-flask"></i>
                    <div data-i18n="Popular Lab Test">Popular Lab Test</div>
                </a>
            </li>
        @endif



        @if (in_array('PopularBrand', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['popular.index']) ? 'active' : '' }}">
                <a href="{{ route('popular.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-star"></i>
                    <div data-i18n="Popular Brands">Popular Brands</div>
                </a>
            </li>
        @endif


        @if (in_array('PopularCategory', $permissions) || $isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['popular_category.index']) ? 'active' : '' }}">
                <a href="{{ route('popular_category.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-view-grid"></i>
                    <div data-i18n="Popular Categories">Popular Categories</div>
                </a>
            </li>
        @endif



        @if (in_array('Laboratories', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['labtest.index']) ? 'active' : '' }}">
                <a href="{{ route('labtest.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-flask"></i>
                    <div data-i18n="Lab Test">Lab Test</div>
                </a>
            </li>
        @endif

        @if (in_array('Laboratories', $permissions) || $isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['packageCategory.index']) ? 'active' : '' }}">
                <a href="{{ route('packageCategory.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant"></i>
                    <div data-i18n="Package Category">Package Category</div>
                </a>
            </li>
        @endif

        @if (in_array('Carts', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['addMedicine.index']) ? 'active' : '' }}">
                <a href="{{ route('addMedicine.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-pill"></i>
                    <div data-i18n="Add Medicine ">Add Medicine </div>
                </a>
            </li>
        @endif

        @if (in_array('Carts', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['addMedicine.index']) ? 'active' : '' }}">
                <a href="{{ route('addMedicine.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-document-outline"></i>
                    <div data-i18n="Additional Charges">Additional Charges</div>
                </a>
            </li>
        @endif

        @if (in_array('Prescriptions', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['prescriptions.index']) ? 'active' : '' }}">
                <a href="{{ route('prescriptions.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-prescription "></i>
                    <div data-i18n="Prescriptions">Prescriptions</div>
                </a>
            </li>
        @endif
        @if (in_array('Home Banners', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::currentRouteName(), ['homebanner.index']) ? 'active' : '' }}">
                <a href="{{ route('homebanner.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-image-area"></i>
                    <div data-i18n="Home Banners">Add HomeScreen Banner</div>
                </a>
            </li>
        @endif

        @if (in_array('Medicine Banners', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::currentRouteName(), ['medicinebanner.index']) ? 'active' : '' }}">
                <a href="{{ route('medicinebanner.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-image-area"></i>
                    <div data-i18n="Medicine Banners">Add MedicineScreen Banner</div>
                </a>
            </li>
        @endif


        @if (in_array('Join', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::currentRouteName(), ['joinus.index']) ? 'active' : '' }}">
                <a href="{{ route('joinus.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-plus"></i>
                    <div data-i18n="Join Us">Join Us</div>
                </a>
            </li>
        @endif


        @if (in_array('App Rating', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ request()->routeIs('app_ratings.index') ? 'active' : '' }}">
                <a href="{{ route('app_ratings.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-star"></i>
                    <div data-i18n="App Rating">App Rating</div>
                </a>
            </li>
        @endif

        @if ($isSuperAdmin)
            <li class="menu-item {{ request()->routeIs('zip_code_vise_delivery.index') ? 'active' : '' }}">
                <a href="{{ route('zip_code_vise_delivery.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-map-marker"></i>
                    <div data-i18n="Zip Code Vice Delivery">Zip Code Vice Delivery</div>
                </a>
            </li>
        @endif

        @if (in_array('Laboratories', $permissions))
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['phlebotomist.index']) ? 'active' : '' }}">
                <a href="{{ route('phlebotomist.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-plus"></i>
                    <div data-i18n="Add phlebotomist">Add phlebotomist</div>
                </a>
            </li>
        @endif



        <li class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="menu-icon tf-icons mdi mdi-logout"></i>
                <div data-i18n="Logout">Logout</div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </a>
        </li>
    </ul>
</aside>
