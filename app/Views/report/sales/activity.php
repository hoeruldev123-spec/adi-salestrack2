<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sales Activity Report</h1>
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

<!-- Metrics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Opportunities</h6>
                        <h2><?= $metrics['total_opportunities'] ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-briefcase fs-1"></i>
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
                        <h6 class="card-title">Total Value</h6>
                        <h4>Rp <?= number_format($metrics['total_value'], 0, ',', '.') ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-dollar fs-1"></i>
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
                        <h6 class="card-title">Won Deals</h6>
                        <h2><?= $metrics['won_deals'] ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-trophy fs-1"></i>
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
                        <h6 class="card-title">Win Rate</h6>
                        <h2><?= $metrics['win_rate'] ?>%</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-graph-up fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Opportunities Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Opportunities</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($opportunities)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Account</th>
                            <th>Principal</th>
                            <th>Status</th>
                            <th>Sales Person</th>
                            <th>Created Date</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($opportunities as $opp): ?>
                            <tr>
                                <td><?= esc($opp['project_name']) ?></td>
                                <td><?= esc($opp['account_name'] ?? 'N/A') ?></td>
                                <td><?= esc($opp['principal_name'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge bg-<?=
                                                            $opp['opportunity_status'] == 'closed_won' ? 'success' : ($opp['opportunity_status'] == 'closed_lost' ? 'danger' : 'warning')
                                                            ?>">
                                        <?= ucfirst(str_replace('_', ' ', $opp['opportunity_status'])) ?>
                                    </span>
                                </td>
                                <td><?= esc($opp['sales_name'] ?? 'N/A') ?></td>
                                <td><?= date('d M Y', strtotime($opp['created_at'])) ?></td>
                                <td><strong>Rp <?= number_format($opp['estimated_value'], 0, ',', '.') ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-2">No opportunities found for the selected period</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="get">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Report</h5>
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
                        <label for="sales_id" class="form-label">Sales Person</label>
                        <select class="form-select" id="sales_id" name="sales_id">
                            <option value="">All Sales People</option>
                            <?php foreach ($sales_people as $sales): ?>
                                <option value="<?= $sales['id'] ?>"
                                    <?= ($filters['sales_id'] ?? '') == $sales['id'] ? 'selected' : '' ?>>
                                    <?= esc($sales['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="introduction" <?= ($filters['status'] ?? '') == 'introduction' ? 'selected' : '' ?>>Introduction</option>
                            <option value="qualification" <?= ($filters['status'] ?? '') == 'qualification' ? 'selected' : '' ?>>Qualification</option>
                            <option value="proposal" <?= ($filters['status'] ?? '') == 'proposal' ? 'selected' : '' ?>>Proposal</option>
                            <option value="negotiation" <?= ($filters['status'] ?? '') == 'negotiation' ? 'selected' : '' ?>>Negotiation</option>
                            <option value="closed_won" <?= ($filters['status'] ?? '') == 'closed_won' ? 'selected' : '' ?>>Closed Won</option>
                            <option value="closed_lost" <?= ($filters['status'] ?? '') == 'closed_lost' ? 'selected' : '' ?>>Closed Lost</option>
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