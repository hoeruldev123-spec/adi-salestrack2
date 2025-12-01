<?php if (isset($current_user)): ?>
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="<?= base_url($current_user['role'] . '/dashboard') ?>">
                <span class="ms-1 font-weight-bold text-white">
                    <i class="bi bi-graph-up me-2"></i>SalesTrack
                </span>
            </a>
        </div>
        <hr class="horizontal light mt-0 mb-2">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link text-white <?= ($active_menu ?? '') == 'dashboard' ? 'active bg-gradient-primary' : '' ?>"
                        href="<?=
                                ($current_user['role'] == 'admin') ?
                                    base_url('admin/users') :  // Admin ke user management
                                    base_url($current_user['role'] . '/dashboard')  // Role lain ke dashboard mereka
                                ?>">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-speedometer2 opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>


                <!-- Opportunities dengan routing berdasarkan role -->
                <?php if (in_array($current_user['role'], ['sales', 'manager', 'bod'])): ?>
                    <li class="nav-item">
                        <?php
                        // Tentukan URL berdasarkan role
                        $opportunitiesUrl = '';
                        if ($current_user['role'] == 'sales') {
                            $opportunitiesUrl = base_url('sales/opportunities');
                        } elseif ($current_user['role'] == 'manager') {
                            $opportunitiesUrl = base_url('manager/opportunities');
                        } elseif ($current_user['role'] == 'bod') {
                            $opportunitiesUrl = base_url('bod/opportunities');
                        }
                        ?>
                        <a class="nav-link text-white <?= ($active_menu ?? '') == 'opportunities' ? 'active bg-gradient-primary' : '' ?>"
                            href="<?= $opportunitiesUrl ?>">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-briefcase opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Opportunities</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Reports -->
                <?php if (in_array($current_user['role'], ['manager', 'bod'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= ($active_menu ?? '') == 'reports' ? 'active bg-gradient-primary' : '' ?>"
                            href="<?= base_url('manager/reports/sales') ?>">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-bar-chart opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Reports</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Master Data -->
                <?php if (in_array($current_user['role'], ['manager', 'bod'])): ?>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Master Data</h6>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white <?= ($active_submenu ?? '') == 'accounts' ? 'active bg-gradient-primary' : '' ?>"
                            href="<?= base_url('master/accounts') ?>">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-building opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Accounts</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white <?= ($active_submenu ?? '') == 'principals' ? 'active bg-gradient-primary' : '' ?>"
                            href="<?= base_url('master/principals') ?>">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-shield-check opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Principals</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white <?= ($active_submenu ?? '') == 'solution-architects' ? 'active bg-gradient-primary' : '' ?>"
                            href="<?= base_url('master/solution-architects') ?>">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-badge opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Solution Architects</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white <?= ($active_submenu ?? '') == 'license-types' ? 'active bg-gradient-primary' : '' ?>"
                            href="<?= base_url('master/license-types') ?>">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-key opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">License Types</span>
                        </a>
                    </li>


                <?php endif; ?>


                <!-- Menu User Management -->
                <?php if (in_array($current_user['role'], ['admin', 'manager',])): ?>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">User Management</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= ($active_menu ?? '') == 'users' ? 'active bg-gradient-primary' : '' ?>"
                            href="<?= base_url('admin/users') ?>">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-people opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Manage Users</span>
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>
    </aside>
<?php endif; ?>