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
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span style="color: var(--bs-primary)">
                    <svg width="195" height="238" viewBox="0 0 195 238" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M89.3936 0.261983C91.3688 0.132246 93.3484 0.0494934 95.3324 0.013773C117.343 -0.321274 135.406 5.47333 151.082 15.5625C181.707 35.2749 194.338 65.6327 194.832 93.3856C195.644 138.915 170.433 168.3 135.082 205.858C126.395 215.087 114.872 228.881 103.684 236.525C102.293 237.142 100.525 237.671 98.8635 237.839C95.9963 238.13 93.2218 237.375 90.9586 236.246C83.9969 232.779 73.2899 220.275 68.183 214.964C38.963 184.576 8.50621 152.814 1.77599 116.225C-3.97021 84.984 3.61344 48.2246 33.5627 22.9973C47.796 11.0077 66.2387 2.07345 89.3936 0.261983Z"
                            fill="#5ECBD8" />
                        <path
                            d="M90.4228 26.1648C101.462 25.0303 113.413 27.2655 122.972 30.8941C145.635 39.4964 159.051 56.2727 165.495 72.5759C165.796 73.3504 166.083 74.1279 166.354 74.9084C166.628 75.6869 166.886 76.4683 167.13 77.2528C167.373 78.0373 167.603 78.8227 167.819 79.6092C168.034 80.3977 168.236 81.1871 168.423 81.9776C168.609 82.7681 168.782 83.5595 168.942 84.3519C169.099 85.1464 169.243 85.9418 169.373 86.7383C169.503 87.5328 169.617 88.3292 169.717 89.1277C169.817 89.9261 169.903 90.7246 169.975 91.523C170.047 92.3214 170.103 93.1209 170.145 93.9213C170.189 94.7218 170.217 95.5222 170.229 96.3226C170.243 97.123 170.242 97.9235 170.226 98.7239C170.212 99.5244 170.182 100.325 170.136 101.125C170.092 101.924 170.034 102.723 169.96 103.524C169.886 104.322 169.798 105.119 169.696 105.916C169.594 106.714 169.478 107.512 169.346 108.308C169.214 109.103 169.068 109.897 168.909 110.692C168.749 111.484 168.574 112.276 168.385 113.066C168.195 113.856 167.991 114.646 167.774 115.434C167.556 116.221 167.324 117.005 167.079 117.788C166.833 118.572 166.573 119.354 166.297 120.132C166.024 120.913 165.735 121.69 165.432 122.465C165.13 123.239 164.814 124.011 164.483 124.779C164.153 125.55 163.809 126.317 163.45 127.082C163.092 127.846 162.72 128.607 162.333 129.363C161.947 130.122 161.548 130.876 161.135 131.627C160.722 132.379 160.294 133.128 159.853 133.872C159.412 134.617 158.958 135.359 158.491 136.097C158.023 136.834 157.543 137.566 157.05 138.295C156.555 139.025 156.047 139.751 155.526 140.472C155.007 141.194 154.474 141.912 153.927 142.624C153.38 143.337 152.821 144.046 152.25 144.75C151.677 145.453 151.092 146.152 150.495 146.846C139.649 159.194 125.847 168.506 104.665 172.15C91.5568 172.88 80.5067 171.458 69.4967 166.449C45.9887 155.757 32.2932 134.72 27.1609 116.686C20.4463 93.0919 25.7421 66.6415 46.3436 46.5506C57.3592 35.8075 71.0652 28.5793 90.4228 26.1648Z"
                            fill="#5ECBD8" />
                        <path
                            d="M112.364 48.1406C117.64 47.7543 122.373 48.2304 127.087 49.9311C137.037 53.5211 143.353 61.2311 146.524 68.2224C151.453 79.0973 150.294 90.6907 142.074 100.7C136.157 107.901 128.41 114.782 121.573 121.62C114.609 128.591 103.665 141.298 95.1361 146.622C91.1561 149.107 86.9045 150.37 81.7754 151.373C75.8277 151.646 70.3959 151.26 65.1115 149.236C55.6119 145.595 49.4309 138.019 46.6744 131.166C46.5315 130.816 46.395 130.466 46.2651 130.115C46.1351 129.762 46.0116 129.408 45.8946 129.055C45.7777 128.702 45.6674 128.347 45.5638 127.992C45.4599 127.635 45.3627 127.277 45.2721 126.92C45.1815 126.563 45.0975 126.205 45.0202 125.848C44.943 125.489 44.8724 125.13 44.8085 124.77C44.7444 124.409 44.6871 124.049 44.6364 123.689C44.5858 123.328 44.5419 122.967 44.5046 122.605C44.4673 122.244 44.4367 121.882 44.413 121.519C44.3892 121.157 44.372 120.795 44.3614 120.432C44.3511 120.07 44.3474 119.708 44.3504 119.345C44.3534 118.984 44.3631 118.621 44.3794 118.258C44.3958 117.897 44.4189 117.535 44.4489 117.174C44.4786 116.811 44.5152 116.448 44.5585 116.087C44.6016 115.726 44.6515 115.366 44.7082 115.006C44.7647 114.645 44.8279 114.285 44.8978 113.925C44.9677 113.566 45.0442 113.208 45.1274 112.851C45.2107 112.491 45.3006 112.134 45.397 111.779C45.4934 111.421 45.5964 111.065 45.706 110.71C45.8158 110.354 45.9321 110.001 46.0549 109.65C46.1774 109.296 46.3067 108.944 46.4427 108.593C46.5784 108.242 46.7207 107.892 46.8694 107.545C47.0181 107.196 47.1732 106.848 47.3347 106.503C47.4964 106.155 47.6644 105.81 47.8387 105.467C48.0132 105.124 48.194 104.782 48.381 104.443C48.5681 104.104 48.7613 103.765 48.9608 103.428C49.1604 103.091 49.3662 102.755 49.5782 102.422C49.7902 102.086 50.0084 101.754 50.2325 101.425C50.4569 101.095 50.6873 100.767 50.9237 100.44C51.1602 100.114 51.4028 99.7899 51.6513 99.4666C51.8997 99.1452 52.154 98.8258 52.4144 98.5084C52.6747 98.1891 52.9409 97.8727 53.213 97.5593C60.3759 89.3044 69.1289 81.4297 77.1594 73.5371C83.3964 67.405 92.156 57.2728 100.138 52.4043C103.803 50.1706 107.6 48.976 112.364 48.1406Z"
                            fill="#FEFEFE" />
                        <path
                            d="M79.0102 82.5586C82.0814 83.9299 110.384 113.578 114.747 117.887C106.461 125.043 98.0093 137.618 86.7418 142.313C85.1116 142.87 83.4883 143.271 81.7062 143.571C81.39 143.623 81.072 143.67 80.7521 143.711C80.4323 143.751 80.1111 143.787 79.7885 143.819C79.4659 143.849 79.1423 143.873 78.8177 143.891C78.4931 143.911 78.168 143.925 77.8424 143.933C77.517 143.941 77.1913 143.943 76.8655 143.939C76.5397 143.935 76.2144 143.926 75.8893 143.912C75.5643 143.896 75.2401 143.875 74.9167 143.849C74.5931 143.825 74.2708 143.794 73.9498 143.756C73.6288 143.72 73.3095 143.678 72.9918 143.631C72.674 143.583 72.3583 143.529 72.0446 143.469C71.731 143.411 71.4199 143.347 71.1112 143.277C70.8026 143.207 70.4969 143.133 70.194 143.053C69.8912 142.973 69.5916 142.887 69.2954 142.795C68.9993 142.705 68.7069 142.611 68.418 142.511C68.1291 142.411 67.8443 142.305 67.5636 142.193C67.283 142.084 67.0067 141.969 66.7351 141.849C59.1134 138.49 54.7967 132.142 53.0309 126.339C49.0853 113.375 55.304 105.937 65.849 95.6281C70.31 91.3046 74.6971 86.948 79.0102 82.5586Z"
                            fill="#5ECBD8" />
                    </svg>

                </span>
            </span>
            <div style="display: inline-flex; flex-direction: column; align-items: flex-end; margin-left: 10px;">
                <span style="font-size: 32px; font-weight: bold; ">Gomeds</span>
                <span
                    style="background-color: #00325c; color: white; padding: 2px 8px; border-radius: 6px; font-weight: bold; font-size: 16px;">24|7</span>
            </div>

        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                    fill="currentColor" fill-opacity="0.6" />
                <path
                    d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                    fill="currentColor" fill-opacity="0.38" />
            </svg>
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



        {{-- User Management --}}
        @if (in_array('Users', $permissions) || $isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['user.index', 'pharmacist.index', 'laboratorie.index', 'delivery_person.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-multiple-outline"></i>
                    <div data-i18n="User Management">User Management</div>
                </a>
                <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    @if (in_array('Users', $permissions) || $isSuperAdmin == 1)
                        <li class="menu-item {{ Route::current()->getName() == 'user.index' ? 'active' : '' }}">
                            <a href="{{ route('user.index') }}" class="menu-link">
                                <div data-i18n="User">User</div>
                            </a>
                        </li>
                    @endif

                    @if ($isSuperAdmin == 1)
                        <li class="menu-item {{ Route::current()->getName() == 'pharmacist.index' ? 'active' : '' }}">
                            <a href="{{ route('pharmacist.index') }}" class="menu-link">
                                <div data-i18n="Pharmacies">Pharmacies</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('Laboratories', $permissions) || $isSuperAdmin == 1)
                        <li class="menu-item {{ Route::current()->getName() == 'laboratorie.index' ? 'active' : '' }}">
                            <a href="{{ route('laboratorie.index') }}" class="menu-link">
                                <div data-i18n="Laboratories">Laboratories</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('DeliveryPerson', $permissions) || $isSuperAdmin == 1)
                        <li
                            class="menu-item {{ Route::current()->getName() == 'delivery_person.index' ? 'active' : '' }}">
                            <a href="{{ route('delivery_person.index') }}" class="menu-link">
                                <div data-i18n="Delivery Person">Delivery Person</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif




        {{-- customer --}}
        @if ($isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::currentRouteName(), ['customer.list']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                    <div data-i18n="Customer Details">Customer Details</div>
                </a>
                <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    <li class="menu-item {{ Route::currentRouteName() == 'labtest.index' ? 'active' : '' }}">
                        <a href="{{ route('customer.list') }}" class="menu-link">
                            <div data-i18n="Customers">Customers</div>
                        </a>
                    </li>
                    {{-- <li class="menu-item {{ Route::currentRouteName() == 'addLabTest.index' ? 'active' : '' }}">
                        <a href="{{ route('addLabTest.index') }}" class="menu-link">
                            <div data-i18n="Add Lab Test">Add Lab Test</div>
                        </a>
                    </li> --}}
                </ul>
            </li>
        @endif

        {{-- Pharmacy Management --}}
        @if (in_array('Medicines', $permissions) || in_array('Otcmedicines', $permissions) || $isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['popular.index', 'popular_category.index', 'medicine.index', 'otcmedicine.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-pill"></i>
                    <div data-i18n="Pharmacy Management">Pharmacy Management</div>
                </a>
                <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    @if (in_array('Medicines', $permissions) || $isSuperAdmin == 1)
                        <li class="menu-item {{ Route::current()->getName() == 'medicine.index' ? 'active' : '' }}">
                            <a href="{{ route('medicine.index') }}" class="menu-link">
                                <div data-i18n="Medicine">Medicine</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('Otcmedicines', $permissions) || $isSuperAdmin == 1)
                        <li class="menu-item {{ Route::current()->getName() == 'otcmedicine.index' ? 'active' : '' }}">
                            <a href="{{ route('otcmedicine.index') }}" class="menu-link">
                                <div data-i18n="OTC">OTC</div>
                            </a>
                        </li>
                    @endif
                    @if ($isSuperAdmin == 1)
                        <li class="menu-item {{ Route::current()->getName() == 'popular.index' ? 'active' : '' }}">
                            <a href="{{ route('popular.index') }}" class="menu-link">
                                <div data-i18n="Popular Brands">Popular Brands</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Route::current()->getName() == 'popular_category.index' ? 'active' : '' }}">
                            <a href="{{ route('popular_category.index') }}" class="menu-link">
                                <div data-i18n="Popular Categories">Popular Categories</div>
                            </a>
                        </li>
                    @endif


                </ul>
            </li>
        @endif

        {{-- @if (in_array('Pharmacies', $permissions)) --}}
            <li class="menu-item {{ in_array(Route::current()->getName(), ['janaushadhi.index']) ? 'active' : '' }}">
                <a href="{{ route('janaushadhi.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-hospital-box"></i>
                    <div data-i18n="Janaushadhi Management">Janaushadhi Management</div>
                </a>
            </li>
        {{-- @endif --}}

        {{-- Laboratory Management --}}
        @if ($isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['popular_lab_test.index', 'packageCategory.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-flask"></i>
                    <div data-i18n="Laboratory Management">Laboratory Management</div>
                </a>
                <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    @if ($isSuperAdmin == 1)
                        <li
                            class="menu-item {{ Route::current()->getName() == 'popular_lab_test.index' ? 'active' : '' }}">
                            <a href="{{ route('popular_lab_test.index') }}" class="menu-link">
                                <div data-i18n="Popular Lab Test">Popular Lab Test</div>
                            </a>
                        </li>

                        <li
                            class="menu-item {{ Route::current()->getName() == 'packageCategory.index' ? 'active' : '' }}">
                            <a href="{{ route('packageCategory.index') }}" class="menu-link">
                                <div data-i18n="Filter By Organs">Filter By Organs</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif


        {{-- Prescription & Cart --}}
        @if (in_array('Carts', $permissions) || in_array('Prescriptions', $permissions) || $isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['addMedicine.index', 'prescriptions.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-cart-outline"></i>
                    <div data-i18n="Prescription & Cart">Prescription & Cart</div>
                </a>
                <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    @if (in_array('Prescriptions', $permissions) || $isSuperAdmin == 1)
                        <li
                            class="menu-item {{ Route::current()->getName() == 'prescriptions.index' ? 'active' : '' }}">
                            <a href="{{ route('prescriptions.index') }}" class="menu-link">
                                <div data-i18n="Prescriptions">Prescriptions</div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('Carts', $permissions) || $isSuperAdmin == 1)
                        <li
                            class="menu-item {{ Route::current()->getName() == 'addMedicine.index' ? 'active' : '' }}">
                            <a href="{{ route('addMedicine.index') }}" class="menu-link">
                                <div data-i18n="Add Medicine">Add Medicine</div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('Prescriptions', $permissions) || $isSuperAdmin == 1)
                        <li
                            class="menu-item {{ Route::current()->getName() == 'prescriptions.upload' ? 'active' : '' }}">
                            <a href="{{ route('prescriptions.upload') }}" class="menu-link">
                                <div data-i18n="Upload Prescription">Upload Prescription</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif



        {{-- Lab Manage --}}
        @if ($isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::currentRouteName(), ['labtest.index', 'addLabTest.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-flask"></i>
                    <div data-i18n="Lab Manage">Lab Manage</div>
                </a>
                <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    <li class="menu-item {{ Route::currentRouteName() == 'labtest.index' ? 'active' : '' }}">
                        <a href="{{ route('labtest.index') }}" class="menu-link">
                            <div data-i18n="Import Lab Test">Import Lab Test</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::currentRouteName() == 'addLabTest.index' ? 'active' : '' }}">
                        <a href="{{ route('addLabTest.index') }}" class="menu-link">
                            <div data-i18n="Add Lab Test">Add Lab Test</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- Banners --}}
        @if (in_array('Home Banners', $permissions) || in_array('Medicine Banners', $permissions) || $isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::currentRouteName(), ['homebanner.index', 'medicinebanner.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-image-area"></i>
                    <div data-i18n="Banners">Banners</div>
                </a>
                <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    @if (in_array('Home Banners', $permissions) || $isSuperAdmin == 1)
                        <li class="menu-item {{ Route::currentRouteName() == 'homebanner.index' ? 'active' : '' }}">
                            <a href="{{ route('homebanner.index') }}" class="menu-link">
                                <div data-i18n="Home Banners">Home Screen Banner</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('Medicine Banners', $permissions) || $isSuperAdmin == 1)
                        <li
                            class="menu-item {{ Route::currentRouteName() == 'medicinebanner.index' ? 'active' : '' }}">
                            <a href="{{ route('medicinebanner.index') }}" class="menu-link">
                                <div data-i18n="Medicine Banners">Medicine Screen Banner</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif



        {{-- Admin Utilities --}}
        @if (in_array('Carts', $permissions) ||
                in_array('Join', $permissions) ||
                in_array('App Rating', $permissions) ||
                in_array('Upload QR', $permissions) ||
                $isSuperAdmin == 1)
            <li
                class="menu-item 
    {{ in_array(Route::currentRouteName(), [
        'additionalcharges',
        'joinus.index',
        'app_ratings.index',
        'upload_qr.index',
        'zip_code_vise_delivery.index',
    ])
        ? 'active open'
        : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-tools"></i>
                    <div data-i18n="Admin Utilities">Admin Utilities</div>
                </a>

                <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    @if (in_array('Carts', $permissions) || $isSuperAdmin == 1 || auth()->user()->role === 'Delivery Boy')
                        <li class="menu-item {{ Route::currentRouteName() == 'additionalcharges' ? 'active' : '' }}">
                            <a href="{{ route('additionalcharges') }}" class="menu-link">
                                <div data-i18n="Additional Charges">Additional Charges</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('Join', $permissions) || $isSuperAdmin == 1 || auth()->user()->role === 'Delivery Boy')
                        <li class="menu-item {{ Route::currentRouteName() == 'joinus.index' ? 'active' : '' }}">
                            <a href="{{ route('joinus.index') }}" class="menu-link">
                                <div data-i18n="Join Us">Join Us</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('App Rating', $permissions) || $isSuperAdmin == 1 || auth()->user()->role === 'Delivery Boy')
                        <li class="menu-item {{ Route::currentRouteName() == 'app_ratings.index' ? 'active' : '' }}">
                            <a href="{{ route('app_ratings.index') }}" class="menu-link">
                                <div data-i18n="App Rating">App Rating</div>
                            </a>
                        </li>
                    @endif

                    {{-- ✅ Upload QR Code option --}}
                    @if (in_array('Upload QR', $permissions) || $isSuperAdmin == 1)
                        <li class="menu-item {{ Route::currentRouteName() == 'upload_qr.index' ? 'active' : '' }}">
                            <a href="{{ route('upload_qr.index') }}" class="menu-link">
                                <div data-i18n="QR Code">QR Code</div>
                            </a>
                        </li>
                    @endif

                    @if ($isSuperAdmin == 1)
                        <li
                            class="menu-item {{ Route::currentRouteName() == 'zip_code_vise_delivery.index' ? 'active' : '' }}">
                            <a href="{{ route('zip_code_vise_delivery.index') }}" class="menu-link">
                                <div data-i18n="Zip Code Vice Delivery">Zip Code Vice Delivery</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
   @if (in_array('About', $permissions) || $isSuperAdmin == 1)
            @php
                $cmsRoutes = [
                    'cms.about-us.index',
                    'cms.contact-us.index',
                    'cms.faqs.index',
                    'cms.terms-and-conditions.index',
                    'cms.return-policies.index',
                    'cms.privacy-policies.index',
                    // 'cms.shipping-policies.index',
                ];
            @endphp
 
            <li class="menu-item {{ in_array(Route::currentRouteName(), $cmsRoutes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-information-outline"></i>
                    <div data-i18n="About Gomeds 24/7">About Gomeds 24/7</div>
                </a>
                <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    <li class="menu-item {{ Route::currentRouteName() == 'cms.about-us.index' ? 'active' : '' }}">
                        <a href="{{ route('cms.about-us.index') }}" class="menu-link">
                            <div data-i18n="About Us">About Us</div>
                        </a>
                    </li>
 
                    <li class="menu-item {{ Route::currentRouteName() == 'cms.contact-us.index' ? 'active' : '' }}">
                        <a href="{{ route('cms.contact-us.index') }}" class="menu-link">
                            <div data-i18n="Contact Us">Contact Us</div>
                        </a>
                    </li>
 
                    <li class="menu-item {{ Route::currentRouteName() == 'cms.faqs.index' ? 'active' : '' }}">
                        <a href="{{ route('cms.faqs.index') }}" class="menu-link">
                            <div data-i18n="FAQs">FAQ's</div>
                        </a>
                    </li>
 
                    <li
                        class="menu-item {{ Route::currentRouteName() == 'cms.terms-and-conditions.index' ? 'active' : '' }}">
                        <a href="{{ route('cms.terms-and-conditions.index') }}" class="menu-link">
                            <div data-i18n="Terms and Conditions">Terms and Conditions</div>
                        </a>
                    </li>
 
                    <li
                        class="menu-item {{ Route::currentRouteName() == 'cms.return-policies.index' ? 'active' : '' }}">
                        <a href="{{ route('cms.return-policies.index') }}" class="menu-link">
                            <div data-i18n="Return Policy">Return Policy</div>
                        </a>
                    </li>
 
                    <li
                        class="menu-item {{ Route::currentRouteName() == 'cms.privacy-policies.index' ? 'active' : '' }}">
                        <a href="{{ route('cms.privacy-policies.index') }}" class="menu-link">
                            <div data-i18n="Privacy Policy">Privacy Policy</div>
                        </a>
                    </li>
 
 
                </ul>
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
        



        @if ($isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::currentRouteName(), ['filtered.orders']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-filter-outline"></i>
                    <div data-i18n="Ledger Orders">Ledger Orders</div>
                </a>
                <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    <li class="menu-item {{ Route::currentRouteName() == 'filtered.orders' ? 'active' : '' }}">
                        <a href="{{ route('filtered.orders') }}" class="menu-link">
                            <div data-i18n="Pharmacy Ledger Report">Pharmacy Ledger Report</div>
                        </a>
                    </li>

                    {{-- Future Use: Laboratory Ledger Report --}}
                    {{--
            <li class="menu-item {{ Route::currentRouteName() == 'lab.ledger.report' ? 'active' : '' }}">
                <a href="{{ route('lab.ledger.report') }}" class="menu-link">
                    <div data-i18n="Laboratory Ledger Report">Laboratory Ledger Report</div>
                </a>
            </li>
            --}}
                </ul>
            </li>
        @endif



        @if (in_array('Pharmacies', $permissions) ||
                $isSuperAdmin == 1 ||
                in_array('Orders', $permissions) ||
                in_array('Laboratories', $permissions))
            <li class="menu-item {{ in_array(Route::current()->getName(), ['orderdetails']) ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-view-list"></i>
                    <div data-i18n="Order Details ">Order Details </div>
                </a>
                 <ul class="menu-sub" style="list-style: none; padding-left: 0; margin: 0;">
                    <li class="menu-item {{ Route::currentRouteName() == 'orderdetails' ? 'active' : '' }}">
                        <a href="{{ route('orderdetails') }}" class="menu-link">
                            <div data-i18n="Order Details">Order Details</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::currentRouteName() == 'returnorderdetails' ? 'active' : '' }}">
                        <a href="{{ route('returnorderdetails') }}" class="menu-link">
                            <div data-i18n="Return Order Details">Return Order Details</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if ($isSuperAdmin == 1)
            <li class="menu-item {{ request()->is('commission_data*') ? 'active' : '' }}">
                <a href="{{ route('commission_data.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-cash-multiple"></i>
                    <div data-i18n="Commission Data">Commission Data</div>
                </a>
            </li>
        @endif

        @if (in_array('Laboratories', $permissions))
            <li class="menu-item {{ in_array(Route::current()->getName(), ['phlebotomist.index']) ? 'active' : '' }}">
                <a href="{{ route('phlebotomist.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-plus"></i>
                    <div data-i18n="Add phlebotomist">Add phlebotomist</div>
                </a>
            </li>
        @endif
        @if (in_array('Laboratories', $permissions) || in_array('Pharmacies', $permissions))
            <li class="menu-item {{ Route::currentRouteName() == 'free_delivery_charge' ? 'active' : '' }}">
                <a href="{{ route('free_delivery_charge') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-truck-delivery-outline"></i>
                    <div data-i18n="Free Delivery Charge">Free Delivery Charge</div>
                </a>
            </li>
        @endif

        {{-- @if (in_array('Laboratories', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['laboratorie.index']) ? 'active' : '' }}">
                <a href="{{ route('laboratorie.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-flask"></i>
                    <div data-i18n="Laboratories">Laboratories</div>
                </a>
            </li>
        @endif --}}



        {{-- @if (in_array('Medicines', $permissions) || $isSuperAdmin == 1)
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
        @endif --}}


        {{-- @if ($isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['popular_lab_test.index']) ? 'active' : '' }}">
                <a href="{{ route('popular_lab_test.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-flask"></i>
                    <div data-i18n="Popular Lab Test">Popular Lab Test</div>
                </a>
            </li>
        @endif --}}



        {{-- @if ($isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['popular.index']) ? 'active' : '' }}">
                <a href="{{ route('popular.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-star"></i>
                    <div data-i18n="Popular Brands">Popular Brands</div>
                </a>
            </li>
        @endif


        @if ($isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['popular_category.index']) ? 'active' : '' }}">
                <a href="{{ route('popular_category.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-view-grid"></i>
                    <div data-i18n="Popular Categories">Popular Categories</div>
                </a>
            </li>
        @endif --}}





        {{-- @if (in_array('Laboratories', $permissions) || $isSuperAdmin == 1)
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['packageCategory.index']) ? 'active' : '' }}">
                <a href="{{ route('packageCategory.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-filter-variant"></i>

                    <div data-i18n="Filter By Organs">Filter By Organs</div>
                </a>
            </li>
        @endif --}}

        {{-- @if (in_array('Carts', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['addMedicine.index']) ? 'active' : '' }}">
                <a href="{{ route('addMedicine.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-pill"></i>
                    <div data-i18n="Add Medicine ">Add Medicine </div>
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
        @endif --}}



        {{-- @if (in_array('Carts', $permissions) || $isSuperAdmin == 1)
            <li class="menu-item {{ in_array(Route::current()->getName(), ['additionalcharges']) ? 'active' : '' }}">
                <a href="{{ route('additionalcharges') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-document-outline"></i>
                    <div data-i18n="Additional Charges">Additional Charges</div>
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
        @endif --}}




        @if (in_array('Laboratories', $permissions))
            <li class="menu-item {{ in_array(Route::current()->getName(), ['calendar.index']) ? 'active' : '' }}">
                <a href="{{ route('calendar.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-calendar-blank-outline"></i>
                    <div data-i18n="Slot Booking">Slot Booking</div>
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



<style>
    /* Sidebar base styles */
    .layout-menu {
        transition: width 0.25s ease;
        overflow: visible;
    }

    /* Logo container */
    .app-brand-link {
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
    }


    /* Menu text styles */
    .menu-text {
        transition: all 0.25s ease;
        white-space: nowrap;
        overflow: hidden;
    }
</style>
