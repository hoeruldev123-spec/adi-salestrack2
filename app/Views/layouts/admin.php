<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #343a40;
        }

        .sidebar .nav-link {
            color: #c2c7d0;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #495057;
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: #007bff;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('/dashboard') ?>">
                <i class="fas fa-cube"></i> Admin Panel
            </a>

            <div class="d-flex align-items-center">
                <span class="text-light me-3">
                    <?= session()->get('name') ?> (<?= ucfirst(session()->get('role')) ?>)
                </span>
                <a href="<?= site_url('/auth/logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0">
                <div class="sidebar pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= current_url() == site_url('/dashboard') ? 'active' : '' ?>"
                                href="<?= site_url('/dashboard') ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <?php if (in_array(session()->get('role'), ['admin', 'manager'])): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), '/admin/users') !== false ? 'active' : '' ?>"
                                    href="<?= site_url('/admin/users') ?>">
                                    <i class="fas fa-users"></i> Manajemen User
                                </a>
                            </li>
                        <?php endif; ?>
                        <!-- Tambahkan menu lainnya sesuai role -->
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 py-4">
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= session('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    </script>
</body>

</html>