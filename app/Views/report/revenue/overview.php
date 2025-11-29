<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Revenue Overview Report</h1>
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

<!-- Date Range Info -->
<div class="alert alert-info">
    <i class="bi bi-calendar-range"></i>
    Showing data from <strong><?= date('d M Y', strtotime($start_date)) ?></strong>
    to <strong><?= date('d M Y', strtotime($end_date)) ?></strong>
</div>

<!-- Revenue Metrics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Revenue</h6>
                        <h3>Rp <?= number_format($metrics['total_revenue'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-dollar fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small>
                        <?php $growth = $metrics['growth_rate']; ?>
                        <i class="bi bi-arrow-<?= $growth >= 0 ? 'up' : 'down' ?>"></i>
                        <?= number_format(abs($growth), 1) ?>% from previous month
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Deals</h6>
                        <h2><?= $metrics['total_deals'] ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-trophy fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small>Closed won deals</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Avg Deal Size</h6>
                        <h4>Rp <?= number_format($metrics['average_deal_size'], 0, ',', '.') ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-graph-up fs-1"></i>
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
                        <h6 class="card-title">Current Month</h6>
                        <h4>Rp <?= number_format($metrics['current_month_revenue'], 0, ',', '.') ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-month fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue by Month Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Revenue Trend (Last 12 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Principals -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Principals by Revenue</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($revenue_by_principal)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($revenue_by_principal as $index => $principal): ?>
                            <?php if ($index < 5 && $principal['revenue'] > 0): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= esc($principal['principal_name']) ?></h6>
                                        <small class="text-muted"><?= $principal['deals_count'] ?> deals</small>
                                    </div>
                                    <span class="badge bg-success rounded-pill">
                                        Rp <?= number_format($principal['revenue'] / 1000000, 1) ?>M
                                    </span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3">No revenue data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Revenue by Principal Table -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Revenue by Principal</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($revenue_by_principal)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Principal</th>
                                    <th>Revenue</th>
                                    <th>Deals Count</th>
                                    <th>Avg Deal Size</th>
                                    <th>% of Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($revenue_by_principal as $principal): ?>
                                    <?php if ($principal['revenue'] > 0): ?>
                                        <tr>
                                            <td><strong><?= esc($principal['principal_name']) ?></strong></td>
                                            <td><strong>Rp <?= number_format($principal['revenue'], 0, ',', '.') ?></strong></td>
                                            <td><?= $principal['deals_count'] ?></td>
                                            <td>Rp <?= number_format($principal['deals_count'] > 0 ? $principal['revenue'] / $principal['deals_count'] : 0, 0, ',', '.') ?></td>
                                            <td>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success"
                                                        style="width: <?= $metrics['total_revenue'] > 0 ? ($principal['revenue'] / $metrics['total_revenue']) * 100 : 0 ?>%">
                                                    </div>
                                                </div>
                                                <small><?= $metrics['total_revenue'] > 0 ? number_format(($principal['revenue'] / $metrics['total_revenue']) * 100, 1) : 0 ?>%</small>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-pie-chart display-4 text-muted"></i>
                        <p class="text-muted mt-2">No revenue data available for principals</p>
                    </div>
                <?php endif; ?>
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
                    <h5 class="modal-title">Filter Revenue Report</h5>
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
                </div>
                <div class="modal-footer">
                    <a href="<?= current_url() ?>" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_map(function ($item) {
                            return date('M Y', strtotime($item['month'] . '-01'));
                        }, $revenue_by_month)) ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?= json_encode(array_column($revenue_by_month, 'revenue')) ?>,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Pipeline',
                    data: <?= json_encode(array_column($revenue_by_month, 'total_pipeline')) ?>,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderDash: [5, 5],
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000000) {
                                    return 'Rp ' + (value / 1000000000).toFixed(1) + 'B';
                                } else if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>