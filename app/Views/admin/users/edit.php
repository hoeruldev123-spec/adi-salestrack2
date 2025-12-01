<!-- app/Views/admin/users/edit.php -->
<?php $content = ob_start() ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0"><?= $title ?></h6>
                        <p class="text-sm mb-0">Edit data user</p>
                    </div>
                    <div>
                        <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="form-control-label">Username</label>
                                <input type="text"
                                    class="form-control <?= ($validation && $validation->hasError('username')) ? 'is-invalid' : '' ?>"
                                    id="username"
                                    name="username"
                                    value="<?= old('username', $user['username']) ?>">
                                <?php if ($validation && $validation->hasError('username')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('username') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Nama Lengkap</label>
                                <input type="text"
                                    class="form-control <?= ($validation && $validation->hasError('name')) ? 'is-invalid' : '' ?>"
                                    id="name"
                                    name="name"
                                    value="<?= old('name', $user['name']) ?>">
                                <?php if ($validation && $validation->hasError('name')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('name') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-control-label">Email</label>
                                <input type="email"
                                    class="form-control <?= ($validation && $validation->hasError('email')) ? 'is-invalid' : '' ?>"
                                    id="email"
                                    name="email"
                                    value="<?= old('email', $user['email']) ?>">
                                <?php if ($validation && $validation->hasError('email')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('email') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-control-label">Password</label>
                                <input type="password"
                                    class="form-control <?= ($validation && $validation->hasError('password')) ? 'is-invalid' : '' ?>"
                                    id="password"
                                    name="password"
                                    placeholder="Kosongkan jika tidak ingin mengubah">
                                <?php if ($validation && $validation->hasError('password')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('password') ?>
                                    </div>
                                <?php endif; ?>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="form-control-label">Role</label>

                                <?php if ($currentRole === 'manager'): ?>
                                    <!-- Manager hanya bisa edit sales, role tidak bisa diubah -->
                                    <input type="text"
                                        class="form-control bg-light"
                                        value="<?= strtoupper($user['role']) ?>"
                                        readonly>
                                    <input type="hidden" name="role" value="<?= $user['role'] ?>">
                                    <small class="text-info">
                                        <i class="bi bi-info-circle"></i> Manager tidak dapat mengubah role user
                                    </small>
                                <?php else: ?>
                                    <!-- Admin bisa ubah role -->
                                    <select class="form-control <?= ($validation && $validation->hasError('role')) ? 'is-invalid' : '' ?>"
                                        id="role"
                                        name="role">
                                        <option value="">Pilih Role</option>
                                        <?php foreach ($allowedRoles as $role): ?>
                                            <option value="<?= $role ?>" <?= old('role', $user['role']) == $role ? 'selected' : '' ?>>
                                                <?= strtoupper($role) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>

                                <?php if ($validation && $validation->hasError('role')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('role') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Status Akun</label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="is_active"
                                        name="is_active"
                                        value="1"
                                        <?= old('is_active', $user['is_active']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Update User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('layouts/main', [
    'content' => $content,
    'title' => $title,
    'current_user' => $current_user,
    'active_menu' => $active_menu ?? ''
]);
?>