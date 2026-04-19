<!doctype html>
<html lang="en">


<!-- Mirrored from preview.pichforest.com/dashonic/layouts/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 27 Apr 2024 09:53:52 GMT -->

<head>

    <meta charset="utf-8" />
    <title>Sign In | Dashonic - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Pichforest" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.0/css/line.css">


</head>


<body>

    <!-- <body data-layout="horizontal"> -->

    <div class="authentication-bg min-vh-100">
        <div class="bg-overlay bg-white"></div>
        <div class="container">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-md-8 col-lg-6 col-xl-4">

                        <div class="text-center  py-5">
                            <div class="mb-4 mb-md-5">
                                <a href="index.html" class="d-block auth-logo">
                                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="22" class="auth-logo-dark">
                                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="22" class="auth-logo-light">
                                </a>
                            </div>
                            <div class="mb-4">
                                <h5>Welcome Back !</h5>
                                <p>Sign in to continue to Dashonic.</p>
                            </div>
                            <form class="form" action="{{ route('login') }}" method="POST">
                                @csrf
                                @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                    <i class="mdi mdi-alert-circle me-1"></i>
                                    {{ $errors->first('email') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                @endif
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="email" class="form-control" id="input-email" placeholder="Enter Email" name="email" value=" {{ old('email') }}">
                                    <label for="input-email">Email</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-users-alt"></i>
                                    </div>
                                </div>

                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password">
                                    <label for="password">Password</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-padlock"></i>
                                    </div>
                                </div>

                                <div class="form-check form-check-info font-size-16">
                                    <input class="form-check-input" type="checkbox" id="remember-check">
                                    <label class="form-check-label font-size-14" for="remember-check">
                                        Remember me
                                    </label>
                                </div>


                                <div class="mt-3">
                                    <button type="submit" class="btn btn-info w-100">Log In</button>
                                </div>

                                <div class="mt-4">
                                    <a href="auth-resetpassword-basic.html" class="text-muted text-decoration-underline">Forgot your password?</a>
                                </div>
                            </form><!-- end form -->

                            <div class="mt-5 text-center text-muted">
                                <p>Don't have an account ? <a href="{{ route('register') }}" class="fw-medium text-decoration-underline"> Signup </a></p>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="text-center text-muted p-4">
                            <p class="mb-0">&copy; <script>
                                    document.write(new Date().getFullYear())
                                </script> Dashonic. Crafted with <i class="mdi mdi-heart text-danger"></i> by Pichforest</p>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->

            </div>
        </div><!-- end container -->
    </div>
    <!-- end authentication section -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenujs/metismenujs.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

</body>

<!-- Mirrored from preview.pichforest.com/dashonic/layouts/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 27 Apr 2024 09:53:52 GMT -->

</html>