<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/img/apple-icon.png') ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <title>Login - SalesTrack</title>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="<?= base_url('css/nucleo-icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('css/nucleo-svg.css') ?>" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- CSS Files -->
    <link id="pagestyle" href="<?= base_url('css/argon-dashboard.css') ?>" rel="stylesheet" />
</head>

<body class="">
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Sign In</h4>
                                    <p class="mb-0">Enter your username and password to sign in</p>
                                </div>
                                <div class="card-body">
                                    <!-- Error Messages -->
                                    <?php if (session()->getFlashdata('error')): ?>
                                        <div class="alert alert-danger text-white">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (session()->getFlashdata('errors')): ?>
                                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                            <div class="alert alert-danger text-white">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                <?= $error ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <!-- Success Messages -->
                                    <?php if (session()->getFlashdata('success')): ?>
                                        <div class="alert alert-success text-white">
                                            <i class="bi bi-check-circle me-2"></i>
                                            <?= session()->getFlashdata('success') ?>
                                        </div>
                                    <?php endif; ?>

                                    <form action="<?= base_url('login') ?>" method="post" role="form">
                                        <?= csrf_field() ?>

                                        <div class="mb-3">
                                            <input type="text"
                                                class="form-control form-control-lg"
                                                id="username"
                                                name="username"
                                                placeholder="Username"
                                                aria-label="Username"
                                                value="<?= old('username') ?>"
                                                required>
                                        </div>

                                        <div class="mb-3">
                                            <input type="password"
                                                class="form-control form-control-lg"
                                                id="password"
                                                name="password"
                                                placeholder="Password"
                                                aria-label="Password"
                                                required>
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">
                                                Sign in
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        Need help accessing your account?
                                        <a href="#" class="text-primary text-gradient font-weight-bold">Contact Admin</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                style="background-image: url('<?= base_url('images/loby all data international.jpeg') ?>');
                                        background-size: cover; background-position: center center;
       background-repeat: no-repeat;">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Track Your Success"</h4>
                                <p class="text-white position-relative">Manage your sales opportunities and drive business growth with SalesTrack.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts -->
    <script src="<?= base_url('js/core/popper.min.js') ?>"></script>
    <script src="<?= base_url('js/core/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('js/plugins/perfect-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('js/plugins/smooth-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('js/argon-dashboard.js') ?>"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
</body>

</html>