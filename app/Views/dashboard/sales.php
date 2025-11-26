<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Sales</h1>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total Opportunities</h5>
                        <h2><?= $total_opportunities ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-briefcase fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Active Deals</h5>
                        <h2><?= $recent_opportunities ? count($recent_opportunities) : 0 ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-graph-up fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">In Progress</h5>
                        <h2>5</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Closed Won</h5>
                        <h2>3</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Opportunities -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Opportunities</h5>
                <a href="<?= base_url('sales/opportunities') ?>" class="btn btn-primary btn-sm">View All</a>
            </div>
            <div class="card-body">
                <?php if ($recent_opportunities && count($recent_opportunities) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Account</th>
                                    <th>Principal</th>
                                    <th>Status</th>
                                    <th>Closing Date</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_opportunities as $opp): ?>
                                    <tr>
                                        <td><?= $opp['project_name'] ?></td>
                                        <td><?= $opp['account_name'] ?? 'N/A' ?></td>
                                        <td><?= $opp['principal_name'] ?? 'N/A' ?></td>
                                        <td>
                                            <span class="badge bg-<?=
                                                                    $opp['opportunity_status'] == 'closed_won' ? 'success' : ($opp['opportunity_status'] == 'closed_lost' ? 'danger' : 'warning')
                                                                    ?>">
                                                <?= ucfirst(str_replace('_', ' ', $opp['opportunity_status'])) ?>
                                            </span>
                                        </td>
                                        <td><?= $opp['closing_date'] ? date('d M Y', strtotime($opp['closing_date'])) : 'N/A' ?></td>
                                        <td>Rp <?= number_format($opp['estimated_value'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No opportunities found</p>
                        <a href="<?= base_url('sales/opportunities/create') ?>" class="btn btn-primary">Create First Opportunity</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>