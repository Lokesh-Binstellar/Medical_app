{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Gomeds</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../assets/img/kaiadmin/favicon.ico" type="image/x-icon" />
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
                        @if ($errors->any())
                        <div class="alert alert-danger" id="form-error-box">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                        <h4 class="card-title text-center mb-4">Login</h4>

                        <form id="loginForm" method="POST" action="{{ route('login') }}" data-parsley-validate>
                            @csrf
                            <div class="form-group mb-3">
                                <label for="email2">Email Address</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    data-parsley-type="email"
                                    data-parsley-pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                    data-parsley-pattern-message="Please enter a valid email address"
                                    placeholder="Enter your email" required />
                            </div>

                            <div class="form-group mb-4">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="Password" required data-parsley-minlength="6"
                                    data-parsley-minlength-message="Password must be at least 6 characters long" />
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
        $(document).ready(function() {
            $('#loginForm').parsley();
        });
        document.addEventListener('DOMContentLoaded', function () {
        const formErrorBox = document.getElementById('form-error-box');
        const email = document.getElementById('email');  // email field id
        const password = document.getElementById('password');

        function hideErrors() {
            if (formErrorBox) {
                formErrorBox.style.display = 'none';
            }
        }

        email.addEventListener('input', hideErrors);
        password.addEventListener('input', hideErrors);
    });
    </script>
</body>

</html> --}}
