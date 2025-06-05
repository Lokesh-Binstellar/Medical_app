<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gomeds - Your Digital Healthcare Partner')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #2d3748;
            background-color: #f8fafc;
        }

        /* Header with Logo and Menu in same line */
        .header {
            background: #f7f7f9;
            color: #033a62;
            /* padding: 1rem 0; */
            /* box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); */
            position: relative;
           
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }



        /* Navigation Menu */
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            align-items: center;
            margin-bottom: 0
        }

        .nav-menu li a {
            color: #f7f7f9;
            text-decoration: none;
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgb(0, 50, 92);
            backdrop-filter: blur(10px);
        }

        .nav-menu li a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            /* margin: 20px 0 */
            border: none;
            color: rgb(0, 50, 92);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            position: absolute;
            margin: 20px 0;
            top: 100%;
            left: 0;
            right: 0;
            background: #00325c;
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .mobile-menu.active {
            display: block;
        }

        .mobile-menu ul {
            list-style: none;
            padding: 1rem;
        }

        .mobile-menu li {
            margin-bottom: 0.5rem;
        }

        .mobile-menu a {
            color: white;
            text-decoration: none;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
        }

        .mobile-menu a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            /* min-height: 70vh; */
        }

        .hero-section {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
        }

        .hero-section h2 {
            font-size: 3rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 1rem;
            background: #00325c;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #4a5568;
            font-weight: 500;
            margin-bottom: 2rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .service-card {
            background: white;
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #00325c;
        }

        .service-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .service-icon {
            width: 90px;
            height: 90px;
            background: #00325c;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2.2rem;
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .service-card h3 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .service-card p {
            color: #718096;
            margin-bottom: 1.5rem;
            line-height: 1.7;
            font-size: 1.05rem;
        }

        .service-card ul {
            list-style: none;
            padding: 0;
        }

        .service-card li {
            color: #4a5568;
            margin-bottom: 0.75rem;
            padding-left: 1.5rem;
            position: relative;
            line-height: 1.6;
        }

        .service-card li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #48bb78;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .stats-section {
            background: #00325c;
            color: white;
            padding: 4rem 2rem;
            border-radius: 24px;
            margin: 3rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.95;
            font-weight: 500;
        }

        .content-section {
            background: white;
            border-radius: 24px;
            padding: 2.5rem;
            margin: 2rem 0;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }

        .content-section h3 {
            color: #2d3748;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .content-section h3 i {
            color: #00325c;
            font-size: 1.5rem;
        }

        .content-section h4 {
            color: #4a5568;
            font-size: 1.3rem;
            font-weight: 600;
            margin: 1.5rem 0 0.75rem 0;
        }

        .content-section p {
            color: #718096;
            line-height: 1.8;
            margin-bottom: 1.2rem;
            font-size: 1.05rem;
        }

        .content-section ul {
            padding-left: 1.5rem;
        }

        .content-section li {
            color: #4a5568;
            margin-bottom: 0.75rem;
            line-height: 1.7;
        }

        /* Contact Form Styles */
        .contact-form {
            background: white;
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
            border: 1px solid #e2e8f0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #2d3748;
            font-size: 1rem;
        }

        .form-group label i {
            color: #667eea;
            margin-right: 0.5rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 1.2rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
            background: #f8fafc;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .btn {
            background: #00325c;
            color: white;
            border: none;
            padding: 1.2rem 2.5rem;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .contact-info {
            background: linear-gradient(135deg, #e6fffa 0%, #f0fff4 100%);
            padding: 2.5rem;
            border-radius: 24px;
            margin: 2rem 0;
            border-left: 6px solid #48bb78;
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.1);
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1.25rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 16px;
            transition: transform 0.3s ease;
        }

        .contact-item:hover {
            transform: translateX(5px);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: #48bb78;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }

        .footer {
            background: #00325c;
            color: white;
            text-align: center;
            padding: 4rem 2rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #e2e8f0;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .footer-links a:hover {
            color: #e2e8f0 !important;
            background: rgb(44, 96, 138);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                padding: 0 1rem;
            }



            .nav-menu {
                display: none;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .hero-section h2 {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .container {
                padding: 1rem;
            }

            .service-card {
                padding: 2rem;
            }

            .stats-section {
                padding: 3rem 1.5rem;
            }

            .content-section {
                padding: 2rem;
            }

            .contact-form {
                padding: 2rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {


            .hero-section h2 {
                font-size: 1.8rem;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="app-brand demo">
                <a href="{{ url('/webpage/home') }}" class="app-brand-link">
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
                    <div
                        style="display: inline-flex; flex-direction: column; align-items: flex-end; margin-left: 10px;">
                        <span style="font-size: 32px; font-weight: bold; ">Gomeds</span>
                        <span
                            style="background-color: #00325c; color: white; padding: 2px 8px; border-radius: 6px; font-weight: bold; font-size: 16px;">24|7</span>
                    </div>

                </a>


            </div>


            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ url('/webpage/home') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="{{ url('/webpage/about') }}"><i class="fas fa-info-circle"></i> About Us</a></li>
                    <li><a href="{{ url('/webpage/contact') }}"><i class="fas fa-phone"></i> Contact Us</a></li>
                    <li><a href="{{ Route('webpage.privacy-policy') }}"><i class="fas fa-shield-alt"></i> Privacy Policy</a>
                    </li>
                    <li><a href="{{ Route('webpage.terms') }}"><i class="fas fa-file-contract"></i> Terms & Conditions </a></li>
                </ul>

                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="mobile-menu" id="mobileMenu">
                    <ul>
                        <li><a href="{{ url('/webpage/home') }}"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="{{ url('/webpage/about') }}"><i class="fas fa-info-circle"></i> About Us</a></li>
                        <li><a href="{{ url('/webpage/contact') }}"><i class="fas fa-phone"></i> Contact Us</a></li>
                        <li><a href="{{ Route('webpage.privacy-policy') }}"><i class="fas fa-shield-alt"></i>
                                Privacy Policy</a></li>
                        <li><a href="{{ Route('webpage.terms') }}"><i class="fas fa-file-contract"></i> Terms & Conditions </a></li>
                    </ul>
                </div>
            </nav>
        </header>
    </div>

    <div class="container">
        @yield('content')
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="{{ url('/webpage/home') }}">Home</a>
                <a href="{{ url('/webpage/about') }}">About Us</a>
                <a href="{{ url('/webpage/contact') }}">Contact Us</a>
                <a href="{{ Route('webpage.privacy-policy') }}">Privacy Policy</a>
                <a href="{{ Route('webpage.terms') }}">Terms & Conditions</a>
            </div>
            <p>&copy; {{ date('Y') }} Gomeds - Digital Healthcare Platform. All rights reserved.</p>
            <p><i class="fas fa-heartbeat"></i> Connecting You to Better Health</p>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('active');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const toggleButton = document.querySelector('.mobile-menu-toggle');

            if (!mobileMenu.contains(event.target) && !toggleButton.contains(event.target)) {
                mobileMenu.classList.remove('active');
            }
        });
    </script>
</body>

</html>
