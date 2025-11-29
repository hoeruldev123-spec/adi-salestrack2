<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sales Performance Ranking</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bi bi-funnel"></i> Filters
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-download"></i> Export
            </button>
        </div>
    </div>
</div>

<!-- Top Performer Spotlight -->
<?php if ($metrics['top_performer']): ?>
    <div class="alert alert-success">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="alert-heading">
                    <i class="bi bi-trophy-fill"></i> Top Performer: <?= esc($metrics['top_performer']['name']) ?>
                </h5>
                <p class="mb-0">
                    Generated <strong>Rp <?= number_format($metrics['top_performer']['won_revenue'], 0, ',', '.') ?></strong>
                    from <?= $metrics['top_performer']['won_deals'] ?> deals with
                    <?= $metrics['top_performer']['win_rate'] ?>% win rate
                </p>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge bg-warning fs-6">#1 Rank</span>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Performance Metrics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Sales People</h6>
                        <h2><?= $metrics['total_sales'] ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Revenue</h6>
                        <h4>Rp <?= number_format($metrics['total_revenue'], 0, ',', '.') ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-dollar fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Avg Win Rate</h6>
                        <h2><?= number_format($metrics['avg_win_rate'], 1) ?>%</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-graph-up fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Active Opportunities</h6>
                        <h2><?= array_sum(array_column($sales_ranking, 'total_opportunities')) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-briefcase fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Ranking Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Sales Performance Ranking</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($sales_ranking)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">Rank</th>
                            <th>Sales Person</th>
                            <th>Revenue</th>
                            <th>Deals</th>
                            <th>Win Rate</th>
                            <th>Avg Deal Size</th>
                            <th>Pipeline</th>
                            <th>Progress</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales_ranking as $index => $sales): ?>
                            <tr>
                                <td>
                                    <?php if ($index < 3): ?>
                                        <span class="badge bg-<?=
                                                                $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'danger')
                                                                ?> fs-6">#<?= $index + 1 ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">#<?= $index + 1 ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= esc($sales['name']) ?></strong>
                                    <br><small class="text-muted"><?= esc($sales['email']) ?></small>
                                </td>
                                <td>
                                    <strong class="text-success">Rp <?= number_format($sales['won_revenue'], 0, ',', '.') ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-success"><?= $sales['won_deals'] ?> won</span>
                                    <span class="badge bg-danger"><?= $sales['lost_deals'] ?> lost</span>
                                    <br><small class="text-muted"><?= $sales['total_opportunities'] ?> total</small>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-<?=
                                                                    $sales['win_rate'] >= 50 ? 'success' : ($sales['win_rate'] >= 30 ? 'warning' : 'danger')
                                                                    ?>" style="width: <?= $sales['win_rate'] ?>%"></div>
                                    </div>
                                    <small><?= number_format($sales['win_rate'], 1) ?>%</small>
                                </td>
                                <td>
                                    <strong>Rp <?= number_format($sales['avg_deal_size'], 0, ',', '.') ?></strong>
                                </td>
                                <td>
                                    <strong>Rp <?= number_format($sales['total_pipeline'], 0, ',', '.') ?></strong>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-info" style="width: <?= $sales['avg_progress'] ?>%"></div>
                                    </div>
                                    <small><?= number_format($sales['avg_progress'], 1) ?>%</small>
                                </td>
                                <td>
                                    <?php if (isset($performance_trends[$sales['id']])): ?>
                                        <?php $trend = $performance_trends[$sales['id']]; ?>
                                        <span class="text-<?=
                                                            $trend['trend'] == 'up' ? 'success' : ($trend['trend'] == 'down' ? 'danger' : 'secondary')
                                                            ?>">
                                            <i class="bi bi-arrow-<?= $trend['trend'] ?>-circle"></i>
                                            <?= number_format($trend['growth'], 1) ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-trophy display-4 text-muted"></i>
                <h3 class="text-muted mt-3">No Sales Performance Data</h3>
                <p class="text-muted">No sales activities recorded for the selected period</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Performance Distribution -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Revenue Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueDistributionChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Win Rate Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="winRateChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="get">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Ranking Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="<?= $start_date ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="<?= $end_date ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="period" class="form-label">Comparison Period</label>
                        <select class="form-select" id="period" name="period">
                            <option value="monthly" <?= $period == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                            <option value="quarterly" <?= $period == 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                            <option value="yearly" <?= $period == 'yearly' ? 'selected' : '' ?>>Yearly</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="<?= current_url() ?>" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Distribution Chart
        const revenueCtx = document.getElementById('revenueDistributionChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_slice(array_column($sales_ranking, 'name'), 0, 10)) ?>,
                datasets: [{
                    label: 'Revenue (in Millions)',
                    data: <?= json_encode(array_slice(array_map(function ($sales) {
                                return $sales['won_revenue'] / 1000000;
                            }, $sales_ranking), 0, 10)) ?>,
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: '#28a745',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: Rp ' + (context.raw * 1000000).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Revenue (in Millions IDR)'
                        }
                    }
                }
            }
        });

        // Win Rate Distribution Chart
        const winRateCtx = document.getElementById('winRateChart').getContext('2d');
        const winRateChart = new Chart(winRateCtx, {
            type: 'doughnut',
            data: {
                labels: ['High (>50%)', 'Medium (30-50%)', 'Low (<30%)'],
                datasets: [{
                    data: [
                        <?= count(array_filter($sales_ranking, function ($sales) {
                            return $sales['win_rate'] > 50;
                        })) ?>,
                        <?= count(array_filter($sales_ranking, function ($sales) {
                            return $sales['win_rate'] >= 30 && $sales['win_rate'] <= 50;
                        })) ?>,
                        <?= count(array_filter($sales_ranking, function ($sales) {
                            return $sales['win_rate'] < 30;
                        })) ?>
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#dc3545'
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
    });
</script>