 {{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}




<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login - gomeds</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .parsley-required,
        .parsley-errors-list li,
        .red-text {
            color: red !important;
            list-style: none;
            padding-left: 0px !important;
        }

        .parsley-required {
            color: red;
            padding-left: 0px !important;
        }

        .parsley-errors-list {
            padding-left: 0px;
        }
        input.form-control {
    padding-right: 2.5rem; /* space for the icon */
}
.eye-toggle {
    top: 14px;
    right: 15px;
    position: absolute !important;
    cursor: pointer;
    z-index: 2;
    line-height: 1;
}

    </style>

    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.css">
</head>

<body>
    <div class="wrapper d-flex justify-content-center align-items-center">
        <div class="main-panel">
            <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; margin: 0 auto;">
                <div class="card shadow" style="width: 400px;">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Login</h4>

                        <form id="loginForm" method="POST" action="{{ route('login') }}" data-parsley-validate>
                            @csrf
                            <div class="form-group mb-3">
                                <label for="email2">Email Address</label>
                                <input type="email" name="email" class="form-control" id="email2"
                                    data-parsley-type="email"
                                    data-parsley-pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$"
                                    data-parsley-pattern-message="Please enter a valid email address"
                                    placeholder="Enter your email" required
                                    data-parsley-required-message="The email field is required." />
                                    @error('email')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="password">Password</label>
                            
                                <!-- 👇 relative container just for input & icon -->
                                {{-- <div class="position-relative">
                                    <!-- Input -->
                                    <input type="password" name="password" class="form-control pe-5" id="password"
                                        placeholder="Password" required
                                        data-parsley-required-message="The password field is required."
                                        data-parsley-minlength="6"
                                        data-parsley-minlength-message="Password must be at least 6 characters long" />
                            
                                    <!-- Icon inside input -->
                                    <span class="position-absolute eye-toggle" onclick="togglePassword()">
                                        <i class="fa fa-eye" id="toggleIcon"></i>
                                    </span>
                                </div> --}}

                                <div class="position-relative">
                                    <!-- Input -->
                                    <input type="password" name="password" class="form-control pe-5" id="password"
                                        placeholder="Password" required
                                        data-parsley-required-message="The password field is required."
                                        data-parsley-minlength="6"
                                        data-parsley-minlength-message="Password must be at least 6 characters long" />
                                
                                    <!-- Icon inside input -->
                                    <span class="position-absolute eye-toggle" onclick="togglePassword()">
                                        <i class="fa fa-eye" id="toggleIcon"></i>
                                    </span>
                                </div>
                            
                                @error('password')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            
                            
                            

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="../assets/js/kaiadmin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>

    <!-- Parsley Init -->
    <script>
         $(document).ready(function () {
             $('#loginForm').parsley();
         });
     </script>

    <!-- JS for custom validation -->
  
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const toggleIcon = document.getElementById("toggleIcon");
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
            toggleIcon.classList.toggle("fa-eye");
            toggleIcon.classList.toggle("fa-eye-slash");
        }
    </script>
    
</body>

</html>
