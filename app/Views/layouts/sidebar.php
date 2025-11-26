<?php if (isset($current_user)): ?>
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= ($active_menu ?? '') == 'dashboard' ? 'active' : '' ?>"
                        href="<?= base_url($current_user['role'] . '/dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                <?php if (in_array($current_user['role'], ['sales', 'manager', 'bod'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($active_menu ?? '') == 'opportunities' ? 'active' : '' ?>"
                            href="<?= base_url('sales/opportunities') ?>">
                            <i class="bi bi-briefcase"></i> Opportunities
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (in_array($current_user['role'], ['manager', 'bod'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($active_menu ?? '') == 'reports' ? 'active' : '' ?>"
                            href="<?= base_url('manager/reports/sales') ?>">
                            <i class="bi bi-bar-chart"></i> Reports
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (in_array($current_user['role'], ['manager', 'bod'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($active_menu ?? '') == 'master' ? 'active' : '' ?>"
                            href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear"></i> Master Data
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item <?= ($active_submenu ?? '') == 'accounts' ? 'active' : '' ?>"
                                    href="<?= base_url('master/accounts') ?>">
                                    <i class="bi bi-building"></i> Accounts
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= ($active_submenu ?? '') == 'principals' ? 'active' : '' ?>"
                                    href="<?= base_url('master/principals') ?>">
                                    <i class="bi bi-shield-check"></i> Principals
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= ($active_submenu ?? '') == 'solution-architects' ? 'active' : '' ?>"
                                    href="<?= base_url('master/solution-architects') ?>">
                                    <i class="bi bi-person-badge"></i> Solution Architects
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= ($active_submenu ?? '') == 'license-types' ? 'active' : '' ?>"
                                    href="<?= base_url('master/license-types') ?>">
                                    <i class="bi bi-key"></i> License Types
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
<?php endif; ?>