<?php
// Prepare chart data for Manager
$pipelineLabels = array_map(function ($item) {
    return ucfirst(str_replace('_', ' ', $item['opportunity_status']));
}, $pipeline_status);

$pipelineData = array_column($pipeline_status, 'value');

$monthlyLabels = array_map(function ($item) {
    $date = DateTime::createFromFormat('Y-m', $item['month']);
    return $date ? $date->format('M Y') : $item['month'];
}, $monthly_performance);

$monthlyRevenue = array_map(function ($item) {
    return $item['revenue'] / 1000000; // Convert to millions
}, $monthly_performance);

$monthlyOpportunities = array_column($monthly_performance, 'opportunities');

$teamLabels = array_column($team_performance, 'name');
$teamRevenue = array_map(function ($item) {
    return $item['revenue'] / 1000000;
}, $team_performance);

$principalLabels = array_column($principal_performance, 'principal_name');
$principalRevenue = array_map(function ($item) {
    return $item['revenue'] / 1000000;
}, $principal_performance);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Manager</h1>
    <small class="text-muted">Company-wide Overview</small>
</div>

<!-- Company Metrics -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Pipeline</h5>
                        <span class="h2 font-weight-bold mb-0"><?= $total_opportunities ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                            <i class="bi bi-briefcase"></i>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="text-nowrap"><?= $active_sales ?> active sales</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Revenue</h5>
                        <span class="h2 font-weight-bold mb-0">Rp <?= number_format($total_revenue / 1000000, 1) ?>M</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2">
                        <i class="bi bi-arrow-up"></i> 12.5%
                    </span>
                    <span class="text-nowrap">Since last month</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Company Win Rate</h5>
                        <span class="h2 font-weight-bold mb-0"><?= number_format($win_rate, 1) ?>%</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow">
                            <i class="bi bi-trophy"></i>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2">
                        <i class="bi bi-arrow-up"></i> 3.8%
                    </span>
                    <span class="text-nowrap">Since last quarter</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Active Sales</h5>
                        <span class="h2 font-weight-bold mb-0"><?= $active_sales ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="text-nowrap">Sales team members</span>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- Company Overview Charts -->
<div class="row mt-4">
    <!-- Pipeline Status -->
    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Company Pipeline by</h6>
                        <h2 class="mb-0">Status</h2>
                    </div>
                </div>
            </div>
            <div class="card-body p-0"> <!-- Remove padding untuk full width -->
                <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                    <canvas id="pipelineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Principals -->
    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Top</h6>
                        <h2 class="mb-0">Principal</h2>
                    </div>
                </div>
            </div>
            <div class="card-body p-0"> <!-- Remove padding untuk full width -->
                <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                    <canvas id="principalChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Performance -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                        <h2 class="mb-0">Monthly Opportunities</h2>

                    </div>
                </div>
            </div>
            <div class="card-body p-0"> <!-- Remove padding untuk full width -->
                <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team & Principal Performance -->
<div class="row mt-4">
    <!-- Team Performance -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Sales Team</h6>
                        <h2 class="mb-0">Performance</h2>

                    </div>
                </div>
            </div>
            <div class="card-body p-0"> <!-- Remove padding untuk full width -->
                <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                    <canvas id="teamChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Biodata Sales Terbaik -->
    <div class="col-xl-6">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Team Leadership</h6>
                        <h2 class="mb-0">Top Performing Sales</h2>
                    </div>
                    <div class="col-auto">
                        <?php if (isset($top_sales) && $top_sales): ?>
                            <span class="badge bg-gradient-success">
                                <i class="bi bi-trophy me-1"></i>Top Performer
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($top_sales) && $top_sales): ?>
                    <!-- Sales Performance Overview -->
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow" style="width: 60px; height: 60px;">
                                <i class="bi bi-person-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="mb-1"><?= esc($top_sales['sales_name'] ?? 'N/A') ?></h5>
                            <p class="text-muted mb-1 small">
                                <i class="bi bi-award-fill text-warning me-1"></i>
                                <?= esc($top_sales['specialization'] ?? 'Sales Executive') ?>
                            </p>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">
                                    Win Rate: <?= number_format($top_sales['win_rate'] ?? 0, 1) ?>%
                                </span>
                                <small class="text-muted">
                                    Rank #<?= $top_sales['performance_rank'] ?? '1' ?>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Key Performance Metrics -->
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <div class="border-end">
                                <h6 class="text-primary mb-1"><?= $top_sales['total_deals'] ?? '0' ?></h6>
                                <small class="text-muted">Deals</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h6 class="text-success mb-1"><?= number_format($top_sales['win_rate'] ?? 0, 1) ?>%</h6>
                                <small class="text-muted">Win Rate</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h6 class="text-info mb-1">Rp <?= number_format(($top_sales['total_revenue'] ?? 0) / 1000000, 1) ?>M</h6>
                            <small class="text-muted">Revenue</small>
                        </div>
                    </div>

                    <!-- Manager's View - Performance Analysis -->
                    <div class="mb-3">
                        <h6 class="text-uppercase text-muted ls-1 mb-2">Performance Analysis</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Team Ranking</small>
                            <strong class="text-warning">#<?= $top_sales['performance_rank'] ?? '1' ?> of <?= $active_sales ?? '0' ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Contribution to Team Revenue</small>
                            <strong class="text-success">
                                <?php
                                $contribution = $total_revenue > 0 ? (($top_sales['total_revenue'] ?? 0) / $total_revenue) * 100 : 0;
                                echo number_format($contribution, 1) . '%';
                                ?>
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Deal Success Ratio</small>
                            <strong class="text-info"><?= number_format($top_sales['win_rate'] ?? 0, 1) ?>%</strong>
                        </div>
                    </div>

                    <!-- Contact & Management Actions -->
                    <div class="mb-3">
                        <h6 class="text-uppercase text-muted ls-1 mb-2">Management</h6>
                        <?php if (!empty($top_sales['sales_email'])): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope text-primary me-2"></i>
                                    <small><?= esc($top_sales['sales_email']) ?></small>
                                </div>
                                <a href="mailto:<?= esc($top_sales['sales_email']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-send"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($top_sales['location'])): ?>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <small><?= esc($top_sales['location']) ?></small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Manager Actions -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-success" onclick="viewSalesDetails(<?= $top_sales['sales_id'] ?? 0 ?>)">
                            <i class="bi bi-graph-up me-1"></i>View Performance Details
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="sendRecognition(<?= $top_sales['sales_id'] ?? 0 ?>)">
                            <i class="bi bi-star me-1"></i>Send Recognition
                        </button>
                    </div>

                <?php else: ?>
                    <!-- No Data State for Manager -->
                    <div class="text-center py-4">
                        <i class="bi bi-people display-4 text-muted"></i>
                        <h5 class="text-muted mt-3">No Team Performance Data</h5>
                        <p class="text-muted mb-3">Sales team performance data is not available.</p>

                        <div class="alert alert-warning">
                            <h6>Manager Action Required:</h6>
                            <ul class="text-start small">
                                <li>Ensure sales profiles are created in database</li>
                                <li>Run sales performance seeder</li>
                                <li>Verify sales team members are active</li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        .sales-stats {
            border-left: 3px solid #5e72e4;
            padding-left: 1rem;
        }

        .performance-metric {
            font-size: 0.875rem;
        }

        .required-field {
            font-family: 'Courier New', monospace;
            font-size: 0.75rem;
        }
    </style>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <a href="<?= base_url('manager/opportunities') ?>" class="btn btn-outline-primary w-100 mb-2">
                            <i class="bi bi-eye"></i><br>View Opportunities
                        </a>
                    </div>
                    <div class="col-md-3 text-center">
                        <a href="<?= base_url('reports/sales/performance') ?>" class="btn btn-outline-success w-100 mb-2">
                            <i class="bi bi-graph-up"></i><br>Sales Reports
                        </a>
                    </div>
                    <div class="col-md-3 text-center">
                        <a href="<?= base_url('master/accounts') ?>" class="btn btn-outline-warning w-100 mb-2">
                            <i class="bi bi-gear"></i><br>Master Data
                        </a>
                    </div>
                    <div class="col-md-3 text-center">
                        <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-info w-100 mb-2">
                            <i class="bi bi-people"></i><br>User Management
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Loading Manager Dashboard charts...');

        // Pipeline Status Chart
        const pipelineCtx = document.getElementById('pipelineChart');
        if (pipelineCtx && <?= !empty($pipelineLabels) ? 'true' : 'false' ?>) {
            new Chart(pipelineCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($pipelineLabels) ?>,
                    datasets: [{
                        data: <?= json_encode($pipelineData) ?>,
                        backgroundColor: [
                            '#6c757d', '#fd7e14', '#0dcaf0',
                            '#0d6efd', '#198754'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        // Monthly Performance Chart
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx && <?= !empty($monthlyLabels) ? 'true' : 'false' ?>) {
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($monthlyLabels) ?>,
                    datasets: [{
                        label: 'Revenue (Millions)',
                        data: <?= json_encode($monthlyRevenue) ?>,
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: '#28a745',
                        borderWidth: 1,
                        yAxisID: 'y'
                    }, {
                        label: 'Opportunities',
                        data: <?= json_encode($monthlyOpportunities) ?>,
                        backgroundColor: 'rgba(13, 110, 253, 0.6)',
                        borderColor: '#0d6efd',
                        borderWidth: 1,
                        type: 'line',
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Revenue (Millions IDR)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Opportunities'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        }

        // Team Performance Chart
        const teamCtx = document.getElementById('teamChart');
        if (teamCtx && <?= !empty($teamLabels) ? 'true' : 'false' ?>) {
            new Chart(teamCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($teamLabels) ?>,
                    datasets: [{
                        label: 'Revenue (Millions)',
                        data: <?= json_encode($teamRevenue) ?>,
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: '#28a745',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Revenue (Millions IDR)'
                            }
                        }
                    }
                }
            });
        }

        // Principal Performance Chart
        const principalCtx = document.getElementById('principalChart');
        if (principalCtx && <?= !empty($principalLabels) ? 'true' : 'false' ?>) {
            new Chart(principalCtx, {
                type: 'pie',
                data: {
                    labels: <?= json_encode($principalLabels) ?>,
                    datasets: [{
                        data: <?= json_encode($principalRevenue) ?>,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });

    // Biodata sales terbaik

    // Manager-specific functions
    function viewSalesDetails(salesId) {
        // Redirect to sales performance detail page
        window.location.href = '/manager/sales/' + salesId + '/performance';
    }

    function sendRecognition(salesId) {
        // Open recognition modal or form
        alert('Send recognition to sales ID: ' + salesId);
        // Implement recognition functionality
    }
</script>