<!-- Page content -->
<div class="container-fluid mt--7">
    <!-- Stats Cards - Argon Style -->
    <div class="row">
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Opportunities</h5>
                            <span class="h2 font-weight-bold mb-0"><?= $total_opportunities ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-nowrap">All your opportunities</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Won Deals</h5>
                            <span class="h2 font-weight-bold mb-0"><?= $won_deals ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                <i class="bi bi-trophy"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-success mr-2">
                            <?= $total_opportunities > 0 ? number_format(($won_deals / $total_opportunities) * 100, 1) : 0 ?>%
                        </span>
                        <span class="text-nowrap">Win Rate</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Revenue</h5>
                            <span class="h2 font-weight-bold mb-0">Rp <?= number_format($total_revenue / 1000000, 1) ?>M</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-nowrap">Total revenue won</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Avg Deal Size</h5>
                            <span class="h2 font-weight-bold mb-0">
                                Rp <?= number_format(($avg_deal_size ?? 0) / 1000000, 1) ?>M
                            </span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                <i class="bi bi-graph-up"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-nowrap">Average won deal value</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-5">
        <!-- Annual Chart -->
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                            <h2 class="mb-0">Annual Opportunities</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                        <canvas id="annualChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Distribution</h6>
                            <h2 class="mb-0">Opportunity Status</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue & Quick Stats -->
    <div class="row mt-5">
        <!-- Revenue by Principal -->
        <div class="col-xl-8">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Revenue by Principal</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Quick Stats</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted ls-1 mb-2">Active Opportunities</h6>
                        <h2 class="text-primary mb-0"><?= $total_opportunities - $won_deals ?></h2>
                        <span class="text-sm">Currently in pipeline</span>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted ls-1 mb-2">Win Rate</h6>
                        <h2 class="text-success mb-0">
                            <?= $total_opportunities > 0 ? number_format(($won_deals / $total_opportunities) * 100, 1) : 0 ?>%
                        </h2>
                        <span class="text-sm">Conversion rate</span>
                    </div>

                    <div>
                        <h6 class="text-uppercase text-muted ls-1 mb-2">Avg Progress</h6>
                        <h2 class="text-info mb-0">
                            <?php
                            $avgProgress = 0;
                            if (isset($recent_opportunities) && !empty($recent_opportunities)) {
                                $totalProgress = array_sum(array_column($recent_opportunities, 'progress_percentage'));
                                $avgProgress = $totalProgress / count($recent_opportunities);
                            }
                            echo number_format($avgProgress, 1) . '%';
                            ?>
                        </h2>
                        <span class="text-sm">Average deal progress</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Opportunities -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Recent Opportunities</h3>
                        </div>
                        <div class="col text-right">
                            <a href="<?= base_url('sales/opportunities') ?>" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <?php if (isset($recent_opportunities) && count($recent_opportunities) > 0): ?>
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Project Name</th>
                                    <th scope="col">Account</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Progress</th>
                                    <th scope="col">Value</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_opportunities as $opp): ?>
                                    <tr>
                                        <th scope="row">
                                            <span class="mb-0 text-sm"><?= esc($opp['project_name']) ?></span>
                                        </th>
                                        <td><?= esc($opp['account_name'] ?? 'N/A') ?></td>
                                        <td>
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-<?= $opp['opportunity_status'] == 'closed_won' ? 'success' : ($opp['opportunity_status'] == 'closed_lost' ? 'danger' : 'warning') ?>"></i>
                                                <?= ucfirst(str_replace('_', ' ', $opp['opportunity_status'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2"><?= $opp['progress_percentage'] ?>%</span>
                                                <div class="w-100">
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-<?= $opp['progress_percentage'] >= 80 ? 'success' : ($opp['progress_percentage'] >= 50 ? 'info' : 'warning') ?>"
                                                            role="progressbar"
                                                            style="width: <?= $opp['progress_percentage'] ?>%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-sm font-weight-bold">Rp <?= number_format($opp['estimated_value'] / 1000000, 1) ?>M</span>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">View details</a></li>
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h3 class="text-muted mt-4">No opportunities found</h3>
                            <p class="text-muted mb-4">Start by creating your first sales opportunity</p>
                            <a href="<?= base_url('sales/opportunities/create') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create First Opportunity
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing charts...');

        // Data dari PHP dengan fallback
        const statusData = {
            labels: <?= isset($statusLabels) ? json_encode($statusLabels) : '[]' ?>,
            data: <?= isset($statusData) ? json_encode($statusData) : '[]' ?>
        };

        const monthlyData = {
            labels: <?= isset($monthlyLabels) ? json_encode($monthlyLabels) : '[]' ?>,
            data: <?= isset($monthlyData) ? json_encode($monthlyData) : '[]' ?>
        };

        const revenueData = {
            labels: <?= isset($revenueLabels) ? json_encode($revenueLabels) : '[]' ?>,
            data: <?= isset($revenueData) ? json_encode($revenueData) : '[]' ?>
        };

        console.log('Chart Data:', {
            statusData,
            monthlyData,
            revenueData
        });

        // Pastikan Chart.js sudah terload
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded!');
            return;
        }

        // 1. Status Distribution Chart (Doughnut)
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            console.log('Initializing status chart...');
            try {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statusData.labels,
                        datasets: [{
                            data: statusData.data,
                            backgroundColor: [
                                '#5e72e4', '#fb6340', '#ffd600',
                                '#2dce89', '#f5365c', '#11cdef'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });
                console.log('Status chart initialized successfully');
            } catch (error) {
                console.error('Error initializing status chart:', error);
            }
        } else {
            console.error('Status chart canvas not found');
        }

        // 2. Annual Progress Chart (Line)
        const annualCtx = document.getElementById('annualChart');
        if (annualCtx) {
            console.log('Initializing annual chart...');
            try {
                // Data dari PHP
                const annualData = {
                    labels: <?= json_encode($annualLabels ?? []) ?>,
                    data: <?= json_encode($annualData ?? []) ?>
                };

                // Jika tidak ada data, gunakan sample data
                if (annualData.labels.length === 0) {
                    annualData.labels = ['2022', '2023', '2024', '2025'];
                    annualData.data = [12, 18, 24, 15];
                }

                new Chart(annualCtx, {
                    type: 'line',
                    data: {
                        labels: annualData.labels,
                        datasets: [{
                            label: 'My Opportunities per Year',
                            data: annualData.data,
                            borderColor: '#5e72e4',
                            backgroundColor: 'rgba(94, 114, 228, 0.15)',
                            borderWidth: 3,
                            pointBackgroundColor: '#5e72e4',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 9,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 5
                                },
                                title: {
                                    display: true,
                                    text: 'Number of Opportunities'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Year'
                                }
                            }
                        }
                    }
                });
                console.log('Annual chart initialized successfully');
            } catch (error) {
                console.error('Error initializing annual chart:', error);
            }
        }

        // 3. Revenue Chart (Bar)
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            console.log('Initializing revenue chart...');
            try {
                new Chart(revenueCtx, {
                    type: 'bar',
                    data: {
                        labels: revenueData.labels,
                        datasets: [{
                            label: 'Revenue (Millions IDR)',
                            data: revenueData.data,
                            backgroundColor: '#2dce89',
                            borderColor: '#2dce89',
                            borderWidth: 0,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    drawBorder: false
                                },
                                title: {
                                    display: true,
                                    text: 'Revenue (in Millions IDR)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                console.log('Revenue chart initialized successfully');
            } catch (error) {
                console.error('Error initializing revenue chart:', error);
            }
        }

        // Initialize Argon Dashboard plugins
        if (typeof Scrollbar !== 'undefined') {
            var scrollbar = document.querySelector('.scrollbar');
            if (scrollbar) {
                new Scrollbar(scrollbar, {
                    continuousScrolling: false
                });
            }
        }
    });
</script>