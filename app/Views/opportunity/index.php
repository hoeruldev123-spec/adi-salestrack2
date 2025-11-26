<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Opportunities</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('sales/opportunities/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create Opportunity
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="introduction">Introduction</option>
                    <option value="qualification">Qualification</option>
                    <option value="proposal">Proposal</option>
                    <option value="negotiation">Negotiation</option>
                    <option value="closed_won">Closed Won</option>
                    <option value="closed_lost">Closed Lost</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="account" class="form-label">Account</label>
                <select name="account" id="account" class="form-select">
                    <option value="">All Accounts</option>
                    <!-- Accounts akan diisi via JavaScript -->
                </select>
            </div>
            <div class="col-md-3">
                <label for="principal" class="form-label">Principal</label>
                <select name="principal" id="principal" class="form-select">
                    <option value="">All Principals</option>
                    <!-- Principals akan diisi via JavaScript -->
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-outline-primary">Filter</button>
                    <a href="<?= base_url('sales/opportunities') ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Opportunities Table -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($opportunities) && count($opportunities) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="opportunitiesTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Project Name</th>
                            <th>Account</th>
                            <th>Principal</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Closing Date</th>
                            <th>Value</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($opportunities as $opp): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($opp['project_name']) ?></strong>
                                    <?php if ($opp['deal_reg']): ?>
                                        <br><small class="text-muted">Deal: <?= esc($opp['deal_reg']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($opp['account_name'] ?? 'N/A') ?></td>
                                <td><?= esc($opp['principal_name'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge bg-<?=
                                                            $opp['opportunity_status'] == 'closed_won' ? 'success' : ($opp['opportunity_status'] == 'closed_lost' ? 'danger' : ($opp['opportunity_status'] == 'negotiation' ? 'primary' : ($opp['opportunity_status'] == 'proposal' ? 'info' : 'warning')))
                                                            ?>">
                                        <?= ucfirst(str_replace('_', ' ', $opp['opportunity_status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-<?=
                                                                    $opp['progress_percentage'] >= 80 ? 'success' : ($opp['progress_percentage'] >= 50 ? 'info' : 'warning')
                                                                    ?>" style="width: <?= $opp['progress_percentage'] ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?= $opp['progress_percentage'] ?>%</small>
                                </td>
                                <td>
                                    <?php if ($opp['closing_date']): ?>
                                        <?= date('d M Y', strtotime($opp['closing_date'])) ?>
                                        <?php if (strtotime($opp['closing_date']) < time()): ?>
                                            <br><small class="text-danger">Overdue</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not set</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong>Rp <?= number_format($opp['estimated_value'], 0, ',', '.') ?></strong>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" title="Edit"
                                            onclick="editOpportunity(<?= $opp['id'] ?>)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-info" title="View Details"
                                            onclick="viewOpportunity(<?= $opp['id'] ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" title="Delete"
                                            onclick="confirmDelete(<?= $opp['id'] ?>, '<?= esc($opp['project_name']) ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination (jika diperlukan) -->
            <nav aria-label="Opportunities pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>

        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-briefcase display-1 text-muted"></i>
                <h3 class="text-muted mt-3">No Opportunities Found</h3>
                <p class="text-muted">Get started by creating your first opportunity</p>
                <a href="<?= base_url('sales/opportunities/create') ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Create First Opportunity
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Value</h6>
                        <h4>Rp <?= number_format(array_sum(array_column($opportunities, 'estimated_value')), 0, ',', '.') ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-dollar fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Won Deals</h6>
                        <h4><?= count(array_filter($opportunities, fn($opp) => $opp['opportunity_status'] === 'closed_won')) ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">In Progress</h6>
                        <h4><?= count(array_filter($opportunities, fn($opp) => !in_array($opp['opportunity_status'], ['closed_won', 'closed_lost']))) ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Opportunities</h6>
                        <h4><?= count($opportunities) ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-briefcase fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function viewOpportunity(id) {
        window.location.href = '<?= base_url('sales/opportunities/') ?>' + id;
    }

    function editOpportunity(id) {
        window.location.href = '<?= base_url('sales/opportunities/edit/') ?>' + id;
    }

    function confirmDelete(id, projectName) {
        if (confirm(`Are you sure you want to delete "${projectName}"?`)) {
            window.location.href = '<?= base_url('sales/opportunities/delete/') ?>' + id;
        }
    }

    // Filter form handling
    document.addEventListener('DOMContentLoaded', function() {
        // Set filter values from URL params
        const urlParams = new URLSearchParams(window.location.search);
        document.getElementById('status').value = urlParams.get('status') || '';
        document.getElementById('account').value = urlParams.get('account') || '';
        document.getElementById('principal').value = urlParams.get('principal') || '';
    });
</script>