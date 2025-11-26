<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Opportunity Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('sales/opportunities') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
        <?php if (session()->get('role') === 'sales' && $opportunity['sales_id'] == session()->get('user_id')): ?>
            <a href="<?= base_url('sales/opportunities/edit/' . $opportunity['id']) ?>" class="btn btn-primary ms-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Opportunity Summary -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-briefcase"></i> <?= esc($opportunity['project_name']) ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Deal Registration:</th>
                                <td>
                                    <?php if ($opportunity['deal_reg']): ?>
                                        <span class="badge bg-info"><?= esc($opportunity['deal_reg']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Not set</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Account:</th>
                                <td><strong><?= esc($opportunity['account_name'] ?? 'N/A') ?></strong></td>
                            </tr>
                            <tr>
                                <th>Principal:</th>
                                <td><strong><?= esc($opportunity['principal_name'] ?? 'N/A') ?></strong></td>
                            </tr>
                            <tr>
                                <th>PIC:</th>
                                <td><?= esc($opportunity['pic'] ?? 'Not specified') ?></td>
                            </tr>
                            <tr>
                                <th>Sales Person:</th>
                                <td><?= esc($opportunity['sales_name'] ?? 'N/A') ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Status:</th>
                                <td>
                                    <span class="badge bg-<?=
                                                            $opportunity['opportunity_status'] == 'closed_won' ? 'success' : ($opportunity['opportunity_status'] == 'closed_lost' ? 'danger' : ($opportunity['opportunity_status'] == 'negotiation' ? 'primary' : ($opportunity['opportunity_status'] == 'proposal' ? 'info' : 'warning')))
                                                            ?> fs-6">
                                        <?= ucfirst(str_replace('_', ' ', $opportunity['opportunity_status'])) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Progress:</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                            <div class="progress-bar bg-<?=
                                                                        $opportunity['progress_percentage'] >= 80 ? 'success' : ($opportunity['progress_percentage'] >= 50 ? 'info' : 'warning')
                                                                        ?>" style="width: <?= $opportunity['progress_percentage'] ?>%"></div>
                                        </div>
                                        <span class="fw-bold"><?= $opportunity['progress_percentage'] ?>%</span>
                                    </div>
                                    <?php if ($opportunity['progress']): ?>
                                        <small class="text-muted"><?= esc($opportunity['progress']) ?></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Closing Date:</th>
                                <td>
                                    <?php if ($opportunity['closing_date']): ?>
                                        <strong><?= date('d M Y', strtotime($opportunity['closing_date'])) ?></strong>
                                        <?php
                                        $closingDate = strtotime($opportunity['closing_date']);
                                        $today = time();
                                        $daysLeft = round(($closingDate - $today) / (60 * 60 * 24));

                                        if ($daysLeft < 0): ?>
                                            <br><small class="text-danger">Overdue by <?= abs($daysLeft) ?> days</small>
                                        <?php elseif ($daysLeft <= 7): ?>
                                            <br><small class="text-warning"><?= $daysLeft ?> days left</small>
                                        <?php else: ?>
                                            <br><small class="text-success"><?= $daysLeft ?> days left</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not set</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Estimated Value:</th>
                                <td>
                                    <h5 class="text-success mb-0">Rp <?= number_format($opportunity['estimated_value'], 0, ',', '.') ?></h5>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if (session()->get('role') === 'sales' && $opportunity['sales_id'] == session()->get('user_id')): ?>
                        <a href="<?= base_url('sales/opportunities/edit/' . $opportunity['id']) ?>" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit Opportunity
                        </a>
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(<?= $opportunity['id'] ?>, '<?= esc($opportunity['project_name']) ?>')">
                            <i class="bi bi-trash"></i> Delete Opportunity
                        </button>
                    <?php endif; ?>

                    <?php if ($opportunity['opportunity_status'] !== 'closed_won' && $opportunity['opportunity_status'] !== 'closed_lost'): ?>
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                            <i class="bi bi-arrow-up-circle"></i> Update Status
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Technical Details -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Technical Details</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Solution Architect:</th>
                        <td><?= esc($opportunity['sa_name'] ?? 'Not assigned') ?></td>
                    </tr>
                    <tr>
                        <th>License Type:</th>
                        <td><?= esc($opportunity['license_type'] ?? 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td><?= date('d M Y H:i', strtotime($opportunity['created_at'])) ?></td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td><?= date('d M Y H:i', strtotime($opportunity['updated_at'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Services & Remarks -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Services & License Details</h6>
            </div>
            <div class="card-body">
                <?php if ($opportunity['services_license']): ?>
                    <p><?= nl2br(esc($opportunity['services_license'])) ?></p>
                <?php else: ?>
                    <p class="text-muted">No services and license details provided.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Remarks & Notes</h6>
            </div>
            <div class="card-body">
                <?php if ($opportunity['remarks']): ?>
                    <p><?= nl2br(esc($opportunity['remarks'])) ?></p>
                <?php else: ?>
                    <p class="text-muted">No remarks or additional notes.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Activity Log -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title mb-0">Activity Log</h6>
        <small class="text-muted">Recent activities for this opportunity</small>
    </div>
    <div class="card-body">
        <?php if (!empty($logs)): ?>
            <div class="timeline">
                <?php foreach ($logs as $log): ?>
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-<?=
                                                        $log['action'] == 'created' ? 'success' : ($log['action'] == 'updated' ? 'primary' : 'danger')
                                                        ?>"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <strong><?= esc($log['user_name']) ?> (<?= strtoupper($log['user_role']) ?>)</strong>
                                <small class="text-muted"><?= date('d M Y H:i', strtotime($log['created_at'])) ?></small>
                            </div>
                            <p class="mb-1">
                                <span class="text-<?=
                                                    $log['action'] == 'created' ? 'success' : ($log['action'] == 'updated' ? 'primary' : 'danger')
                                                    ?> fw-bold"><?= ucfirst($log['action']) ?></span>
                                <?php if ($log['action'] == 'updated' && $log['new_value']): ?>
                                    - <?= esc($log['new_value']) ?>
                                <?php endif; ?>
                            </p>
                            <?php if ($log['action'] == 'updated' && $log['old_value'] && $log['new_value']): ?>
                                <div class="bg-light p-2 rounded small">
                                    <div class="text-danger"><s><?= esc($log['old_value']) ?></s></div>
                                    <div class="text-success"><?= esc($log['new_value']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-clock-history display-4 text-muted"></i>
                <p class="text-muted mt-2">No activity recorded yet</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('sales/opportunities/update-status/' . $opportunity['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Update Opportunity Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_status" class="form-label">New Status</label>
                        <select class="form-select" id="new_status" name="opportunity_status" required>
                            <option value="introduction" <?= $opportunity['opportunity_status'] == 'introduction' ? 'selected' : '' ?>>Introduction</option>
                            <option value="qualification" <?= $opportunity['opportunity_status'] == 'qualification' ? 'selected' : '' ?>>Qualification</option>
                            <option value="proposal" <?= $opportunity['opportunity_status'] == 'proposal' ? 'selected' : '' ?>>Proposal</option>
                            <option value="negotiation" <?= $opportunity['opportunity_status'] == 'negotiation' ? 'selected' : '' ?>>Negotiation</option>
                            <option value="closed_won" <?= $opportunity['opportunity_status'] == 'closed_won' ? 'selected' : '' ?>>Closed Won</option>
                            <option value="closed_lost" <?= $opportunity['opportunity_status'] == 'closed_lost' ? 'selected' : '' ?>>Closed Lost</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="progress_percentage" class="form-label">Progress Percentage</label>
                        <input type="range" class="form-range" id="progress_percentage" name="progress_percentage"
                            min="0" max="100" value="<?= $opportunity['progress_percentage'] ?>"
                            oninput="updateModalProgressValue(this.value)">
                        <div class="d-flex justify-content-between">
                            <small>0%</small>
                            <small id="modalProgressValue"><?= $opportunity['progress_percentage'] ?>%</small>
                            <small>100%</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="progress_notes" class="form-label">Progress Notes</label>
                        <textarea class="form-control" id="progress_notes" name="progress" rows="3"
                            placeholder="Add notes about the progress..."><?= $opportunity['progress'] ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .timeline-content {
        padding-bottom: 10px;
        border-left: 2px solid #dee2e6;
        padding-left: 20px;
    }
</style>

<script>
    function confirmDelete(id, projectName) {
        if (confirm(`Are you sure you want to delete "${projectName}"?`)) {
            window.location.href = '<?= base_url('sales/opportunities/delete/') ?>' + id;
        }
    }

    function updateModalProgressValue(value) {
        document.getElementById('modalProgressValue').textContent = value + '%';
    }

    // Auto-update progress based on status selection
    document.getElementById('new_status').addEventListener('change', function(e) {
        const progressMap = {
            'introduction': 20,
            'qualification': 40,
            'proposal': 60,
            'negotiation': 80,
            'closed_won': 100,
            'closed_lost': 0
        };

        const progress = progressMap[e.target.value] || 0;
        document.getElementById('progress_percentage').value = progress;
        document.getElementById('modalProgressValue').textContent = progress + '%';
    });
</script>