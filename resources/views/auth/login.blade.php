<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="viho admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, viho admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('assets') }}/images/logo/icon.png" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/logo/icon.png" type="image/x-icon">
    <title>Smart Pos - Halaman Login</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/fontawesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/feather-icon.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/style.css">
    <link id="color" rel="stylesheet" href="{{ asset('assets') }}/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/responsive.css">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/sweetalert2.css">
</head>

<body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div>
    <!-- Loader ends-->
    <section>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="login-card">
                        <form class="theme-form login-form" action="{{ route('do_login') }}" method="POST"
                            onsubmit="submitPostLogin(event, $(this))">
                            @csrf
                            <h4>Login</h4>
                            <h6>Selamat datang kembali! Masuk ke akun anda.</h6>

                            <div class="form-group">
                                <label>Username</label>
                                <div class="input-group"><span class="input-group-text"><i
                                            class="fa fa-user"></i></span>
                                    <input class="form-control" type="text" id="username" name="username"
                                        placeholder="Masukkan username">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input id="password" name="password" class="form-control" type="password"
                                        name="login[password]" placeholder="*********">
                                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                        <i class="fa fa-eye" id="eyeIcon"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" id="btn_login" type="submit">Log in</button>
                            </div>
                            <p>Login bermasalah?<a class="ms-2" href="javascript:void(0)">Hubungi Admin</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- latest jquery-->
    <script src="{{ asset('assets') }}/js/jquery-3.5.1.min.js"></script>
    <!-- feather icon js-->
    <script src="{{ asset('assets') }}/js/icons/feather-icon/feather.min.js"></script>
    <script src="{{ asset('assets') }}/js/icons/feather-icon/feather-icon.js"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('assets') }}/js/sidebar-menu.js"></script>
    <script src="{{ asset('assets') }}/js/config.js"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('assets') }}/js/bootstrap/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/bootstrap/bootstrap.min.js"></script>
    <!-- Theme js-->
    <script src="{{ asset('assets') }}/js/script.js"></script>
    <!-- login js-->

    <script src="{{ asset('assets') }}/js/sweet-alert/sweetalert.min.js"></script>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        function submitPostLogin(event, this_) {
            event.preventDefault();
            const username = $('#username').val().trim();
            const password = $('#password').val().trim();

            if (!username || !password) {
                swal("Gagal!", "Username dan password wajib diisi!", "error");
                return;
            }

            $.ajax({
                url: "{{ route('do_login') }}",
                type: this_.attr('method'),
                beforeSend: () => {
                    $("#btn_login").prop("disabled", true).html(
                        "<div class='spinner-border spinner-border-sm text-light' role='status'></div> Loading..."
                    );
                },
                data: this_.serialize(),
                success: (response) => {
                    swal("Berhasil!", "Silahkan masuk!", "success");
                    window.location.href = response['route'];
                },
                error: ({
                    responseText
                }) => {
                    swal("Gagal!", responseText, "error");
                },
                complete: () => {
                    $("#btn_login").prop("disabled", false).html('<i class="mdi mdi-login"></i> Log In');
                }
            });
        }
    </script>
</body>

</html>
