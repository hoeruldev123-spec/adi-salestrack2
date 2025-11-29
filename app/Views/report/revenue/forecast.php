<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Revenue Forecast Report</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bi bi-funnel"></i> Filters
            </button>
        </div>
    </div>
</div>

<!-- Forecast Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Pipeline</h6>
                        <h3>Rp <?= number_format($total_forecast['potential'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-graph-up fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Weighted Forecast</h6>
                        <h3>Rp <?= number_format($total_forecast['weighted'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calculator fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Forecast Accuracy</h6>
                        <h3>~70%</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-speedometer2 fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Forecast by Status -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pipeline by Opportunity Status</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($forecast_by_status)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Deals</th>
                                    <th>Potential Value</th>
                                    <th>Win Probability</th>
                                    <th>Weighted Forecast</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $statusWeights = [
                                    'introduction' => 10,
                                    'qualification' => 30,
                                    'proposal' => 60,
                                    'negotiation' => 80
                                ];
                                ?>
                                <?php foreach ($forecast_by_status as $forecast): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-<?=
                                                                    $forecast['opportunity_status'] == 'negotiation' ? 'primary' : ($forecast['opportunity_status'] == 'proposal' ? 'info' : 'warning')
                                                                    ?>">
                                                <?= ucfirst(str_replace('_', ' ', $forecast['opportunity_status'])) ?>
                                            </span>
                                        </td>
                                        <td><?= $forecast['deal_count'] ?></td>
                                        <td><strong>Rp <?= number_format($forecast['potential_value'], 0, ',', '.') ?></strong></td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-<?=
                                                                            $statusWeights[$forecast['opportunity_status']] >= 60 ? 'success' : ($statusWeights[$forecast['opportunity_status']] >= 30 ? 'warning' : 'secondary')
                                                                            ?>" style="width: <?= $statusWeights[$forecast['opportunity_status']] ?? 0 ?>%"></div>
                                            </div>
                                            <small><?= $statusWeights[$forecast['opportunity_status']] ?? 0 ?>%</small>
                                        </td>
                                        <td><strong>Rp <?= number_format($forecast['potential_value'] * ($statusWeights[$forecast['opportunity_status']] ?? 0) / 100, 0, ',', '.') ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3">No forecast data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Monthly Forecast -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">6-Month Revenue Forecast</h5>
            </div>
            <div class="card-body">
                <canvas id="forecastChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Win Probability Analysis -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Win Probability Analysis</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($win_probability)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Stage</th>
                                    <th>Historical Win Rate</th>
                                    <th>Current Opportunities</th>
                                    <th>Pipeline Value</th>
                                    <th>Weighted Forecast</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($win_probability as $status => $data): ?>
                                    <?php if ($data['count'] > 0): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-<?=
                                                                        $status == 'negotiation' ? 'primary' : ($status == 'proposal' ? 'info' : ($status == 'closed_won' ? 'success' : 'warning'))
                                                                        ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                                                </span>
                                            </td>
                                            <td><?= $data['win_rate'] ?>%</td>
                                            <td><?= $data['count'] ?></td>
                                            <td>Rp <?= number_format($data['potential_value'], 0, ',', '.') ?></td>
                                            <td><strong>Rp <?= number_format($data['weighted_forecast'], 0, ',', '.') ?></strong></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3">No win probability data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Forecast Chart
        const forecastCtx = document.getElementById('forecastChart').getContext('2d');
        const forecastChart = new Chart(forecastCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_map(function ($item) {
                            return date('M Y', strtotime($item['month'] . '-01'));
                        }, $forecast_months)) ?>,
                datasets: [{
                    label: 'Forecasted Revenue',
                    data: <?= json_encode(array_column($forecast_months, 'forecast')) ?>,
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
                                return 'Forecast: Rp ' + context.raw.toLocaleString('id-ID');
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