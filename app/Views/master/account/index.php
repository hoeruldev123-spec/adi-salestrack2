<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Accounts Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('master/accounts/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Account
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($items) && count($items) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="accountsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Account Name</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $item): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($item['account_name']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $item['is_active'] ? 'success' : 'secondary' ?>">
                                        <?= $item['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('master/accounts/edit/' . $item['id']) ?>"
                                            class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('master/accounts/toggle/' . $item['id']) ?>"
                                            class="btn btn-outline-<?= $item['is_active'] ? 'warning' : 'success' ?>"
                                            title="<?= $item['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                            <i class="bi bi-power"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Delete"
                                            onclick="confirmDelete(<?= $item['id'] ?>, '<?= esc($item['account_name']) ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-building display-1 text-muted"></i>
                <h3 class="text-muted mt-3">No Accounts Found</h3>
                <p class="text-muted">Get started by creating your first account</p>
                <a href="<?= base_url('master/accounts/create') ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Create First Account
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"?`)) {
            window.location.href = '<?= base_url('master/accounts/delete/') ?>' + id;
        }
    }
</script>