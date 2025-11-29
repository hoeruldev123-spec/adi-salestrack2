<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Principal Performance Ranking</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bi bi-funnel"></i> Filters
            </button>
        </div>
    </div>
</div>

<!-- Top Principal Spotlight -->
<?php if ($metrics['top_principal']): ?>
    <div class="alert alert-info">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="alert-heading">
                    <i class="bi bi-shield-check"></i> Top Principal: <?= esc($metrics['top_principal']['principal_name']) ?>
                </h5>
                <p class="mb-0">
                    Generated <strong>Rp <?= number_format($metrics['top_principal']['won_revenue'], 0, ',', '.') ?></strong>
                    across <?= $metrics['top_principal']['unique_accounts'] ?> accounts with
                    <?= $metrics['top_principal']['win_rate'] ?>% win rate
                </p>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge bg-warning fs-6">#1 Principal</span>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Principal Ranking Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Principal Performance Ranking</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($principal_ranking)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">Rank</th>
                            <th>Principal</th>
                            <th>Revenue</th>
                            <th>Deals</th>
                            <th>Win Rate</th>
                            <th>Avg Deal Size</th>
                            <th>Unique Accounts</th>
                            <th>Account Penetration</th>
                            <th>Growth</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($principal_ranking as $index => $principal): ?>
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
                                <td><strong><?= esc($principal['principal_name']) ?></strong></td>
                                <td>
                                    <strong class="text-success">Rp <?= number_format($principal['won_revenue'], 0, ',', '.') ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-success"><?= $principal['won_deals'] ?> won</span>
                                    <span class="badge bg-danger"><?= $principal['lost_deals'] ?> lost</span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-<?=
                                                                    $principal['win_rate'] >= 50 ? 'success' : ($principal['win_rate'] >= 30 ? 'warning' : 'danger')
                                                                    ?>" style="width: <?= $principal['win_rate'] ?>%"></div>
                                    </div>
                                    <small><?= number_format($principal['win_rate'], 1) ?>%</small>
                                </td>
                                <td>
                                    <strong>Rp <?= number_format($principal['avg_deal_size'], 0, ',', '.') ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?= $principal['unique_accounts'] ?> accounts</span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-info" style="width: <?= $principal['account_penetration'] ?>%"></div>
                                    </div>
                                    <small><?= number_format($principal['account_penetration'], 1) ?>%</small>
                                </td>
                                <td>
                                    <?php if (isset($principal_growth[$principal['id']])): ?>
                                        <?php $growth = $principal_growth[$principal['id']]; ?>
                                        <span class="text-<?= $growth['momentum'] == 'positive' ? 'success' : 'danger' ?>">
                                            <i class="bi bi-arrow-<?= $growth['momentum'] == 'positive' ? 'up' : 'down' ?>-circle"></i>
                                            <?= number_format($growth['growth'], 1) ?>%
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
                <i class="bi bi-shield-check display-4 text-muted"></i>
                <h3 class="text-muted mt-3">No Principal Performance Data</h3>
                <p class="text-muted">No principal activities recorded for the selected period</p>
            </div>
        <?php endif; ?>
    </div>
</div>