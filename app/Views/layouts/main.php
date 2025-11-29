<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?= $title ?? 'SalesTrack' ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('assets/img/brand/favicon.png') ?>" type="image/png">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('css/nucleo-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/nucleo-svg.css') ?>">

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= base_url('css/argon-dashboard.css') ?>">

    <!-- Custom CSS -->
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
</head>

<body class="g-sidenav-show bg-gray-100">
    <!-- Sidebar -->
    <?= view('layouts/sidebar') ?>

    <!-- Main Content -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Header -->
        <?= view('layouts/header') ?>

        <!-- Content -->
        <div class="container-fluid py-4">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                    <span class="alert-icon"><i class="bi bi-check-circle"></i></span>
                    <span class="alert-text"><?= session()->getFlashdata('success') ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                    <span class="alert-icon"><i class="bi bi-exclamation-triangle"></i></span>
                    <span class="alert-text"><?= session()->getFlashdata('error') ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <?= $content ?>
        </div>

        <!-- Footer -->
        <?= view('layouts/footer') ?>
    </main>

    <!-- HANYA Bootstrap 5 JS - Hapus semua Argon JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS untuk sidebar toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('iconNavbarSidenav');
            const sidebar = document.getElementById('sidenav-main');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('d-none');
                });
            }

            // Fix untuk dropdown
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>

    <!-- Scripts - URUTAN PENTING! -->
    <!-- Core JS -->
    <script src="<?= base_url('js/core/popper.min.js') ?>"></script>


    <!-- Chart.js HARUS di-load sebelum argon-dashboard.js -->
    <script src="<?= base_url('js/plugins/chartjs.min.js') ?>"></script>

    <!-- Argon Plugins -->
    <link rel="stylesheet" href="<?= base_url('css/nucleo-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/nucleo-svg.css') ?>">

    <!-- Chart Extension untuk Argon -->
    <script src="<?= base_url('js/plugins/Chart.extension.js') ?>"></script>


    <!-- Custom JS -->
    <script src="<?= base_url('js/script.js') ?>"></script>
</body>

</html>