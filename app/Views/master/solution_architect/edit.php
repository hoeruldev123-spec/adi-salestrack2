<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Account: <?= esc($item['account_name']) ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('master/accounts') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('master/accounts/update/' . $item['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="account_name" class="form-label">Account Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="account_name" name="account_name"
                            value="<?= old('account_name', $item['account_name']) ?>" required
                            placeholder="Enter account name">
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                <?= $item['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Form Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('master/accounts') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Account</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>