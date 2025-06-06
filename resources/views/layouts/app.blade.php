<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />


    @yield('token')
    <title>@yield('title')</title>
    {{-- <title>Dashboard - {{ config('app.name', 'Laravel') }}</title> --}}




    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/kaiadmin/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/materialdesignicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet"> --}}


    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-statistics.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-analytics.css') }}" />


    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/parsleyjs/src/parsley.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css"> --}}
    <style>
        /* ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px #033a62;
            border-radius: 10px;
            background-color: #033a62;
        }

        ::-webkit-scrollbar {
            width: 8px;
            padding-top: 10px;
            background-color: #033a62;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px #033a62;
            background-color: #f5f5f5;
            height: 20px;
        } */

        .app-brand {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .red-text {
            color: red;
        }

        .parsley-required,
        .parsley-type,
        .parsley-errors-list {
            color: red !important;
        }

        .parsley-errors-list {
            font-size: 12px !important;
        }

        .table-responsive {
            overflow: unset;
        }

        .dataTables_scrollBody {
            overflow: unset !important;
        }

        div.dataTables_processing>div:last-child>div {
            background: #033a62;
        }

        .layout-navbar-fixed:not(.layout-menu-collapsed) .layout-content-navbar:not(.layout-without-menu) .layout-navbar,
        .layout-menu-fixed.layout-navbar-fixed:not(.layout-menu-collapsed) .layout-content-navbar:not(.layout-without-menu) .layout-navbar {
            left: 17.63rem !important;
        }

        /* .layout-navbar-fixed .layout-navbar.navbar-detached {
            width: 84% !important;
        } */

        .align {
            text-align: left !important;
        }

        .text-truncate {
            white-space: wrap;
        }

        #layout-navbar {
            -webkit-backdrop-filter: blur(6px) !important;
        }

        @media screen and (max-width: 1440px) {
            /* .layout-navbar-fixed .layout-navbar.navbar-detached {
                width: 79% !important;
            } */
        }

        @media screen and (max-width: 1366px) {
            /* .layout-navbar-fixed .layout-navbar.navbar-detached {
                width: 78% !important;
            } */
        }

        @media screen and (max-width: 1024px) {
            .close-menu {
                position: absolute;
                right: 5px;
                top: 5px;
                background-color: #033a62;
                color: #fff;
                border-radius: 5px;
            }

            .layout-navbar-fixed:not(.layout-menu-collapsed) .layout-content-navbar:not(.layout-without-menu) .layout-navbar,
            .layout-menu-fixed.layout-navbar-fixed:not(.layout-menu-collapsed) .layout-content-navbar:not(.layout-without-menu) .layout-navbar {
                left: 0 !important;
            }

            /* .layout-navbar-fixed .layout-navbar.navbar-detached {
                width: 100% !important;
            } */

        }

        @media screen and (max-width: 1024px) {
            .layout-menu-toggle a {
                padding-left: 20px !important;
            }

            .close-menu.mdi:before {
                font-size: 22px;
                line-height: 22px;
            }

            .pr {
                padding-right: 24px;
            }

            .dropdown-menu-end[data-bs-popper] {
                right: 24px;
                /* left: 18px !important; */
            }
        }

        @media screen and (max-width: 768px) {

            .pr {
                padding-right: 24px;
            }

            .dropdown-menu-end[data-bs-popper] {
                right: 16px;
                /* left: 18px !important; */
            }
        }
    </style>
    @livewireStyles

    @yield('styles')

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.navigation')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('layouts.header')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <div class="container-fluid">
                        @yield('content')
                    </div>


                    <!-- Footer -->
                    @include('layouts.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.3.1/typeahead.bundle.min.js"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
  

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>


    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>


    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>/ --}}
    {{-- <script src="https://code.highcharts.com/highcharts.js"></script> --}}
    {{-- <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1mG52tiHM3duCJupl0CtEB3xpzUGiohQ&callback=initAutocomplete&libraries=places&v=weekly"
    defer
  ></script> --}}




    @yield('scripts')
    @livewireScripts
    {{-- <script>
        $(document).ready(function() {

            $(".close-menu").click(function() {

                $("#layout-menu").hide();
                $(".layout-navbar-fixed").removeClass("layout-menu-expanded");
            });


            $(".layout-menu-toggle").click(function() {
                $("#layout-menu").show();
                $(".layout-navbar-fixed").addClass("layout-menu-expanded");
            });
            // layout-menu-toggle


        });
    </script> --}}

    {{-- <script>
        function markAllAsRead() {
            fetch('/notifications/mark-all-as-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelectorAll('.dropdown-notifications-item').forEach(item => {
                            item.classList.add('marked-as-read');
                        });
                        document.querySelector('.badge-dot').innerText = '0'; // Update badge count
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`[data-id="${notificationId}"]`).classList.add('marked-as-read');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function archiveNotification(notificationId) {
            fetch(`/notifications/${notificationId}/archive`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        document.querySelector(`[data-id="${notificationId}"]`).remove();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script> --}}





</body>

</html>
