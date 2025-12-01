<!-- app/Views/admin/users/add.php -->
<?php $content = ob_start() ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0"><?= $title ?></h6>
                        <p class="text-sm mb-0">Tambah user baru ke sistem</p>
                    </div>
                    <div>
                        <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                        <span class="alert-icon"><i class="bi bi-exclamation-triangle"></i></span>
                        <span class="alert-text"><?= session()->getFlashdata('error') ?></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/users/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="form-control-label">Username</label>
                                <input type="text"
                                    class="form-control <?= ($validation && $validation->hasError('username')) ? 'is-invalid' : '' ?>"
                                    id="username"
                                    name="username"
                                    value="<?= old('username') ?>"
                                    placeholder="Masukkan username">
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
                                    value="<?= old('name') ?>"
                                    placeholder="Masukkan nama lengkap">
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
                                    value="<?= old('email') ?>"
                                    placeholder="Masukkan email">
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
                                    placeholder="Masukkan password">
                                <?php if ($validation && $validation->hasError('password')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('password') ?>
                                    </div>
                                <?php endif; ?>
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <!-- app/Views/admin/users/add.php -->
                            <!-- Update dropdown role: -->

                            <div class="form-group">
                                <label for="role" class="form-control-label">Role</label>
                                <select class="form-control <?= ($validation && $validation->hasError('role')) ? 'is-invalid' : '' ?>"
                                    id="role"
                                    name="role"
                                    <?= ($currentRole === 'manager') ? 'disabled' : '' ?>>
                                    <option value="">Pilih Role</option>
                                    <?php foreach ($allowedRoles as $role): ?>
                                        <option value="<?= $role ?>" <?= old('role') == $role ? 'selected' : '' ?>>
                                            <?= strtoupper($role) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <?php if ($currentRole === 'manager'): ?>
                                    <input type="hidden" name="role" value="sales">
                                    <small class="text-info">
                                        <i class="bi bi-info-circle"></i> Manager hanya dapat menambahkan user dengan role <strong>SALES</strong>
                                    </small>
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
                                        <?= old('is_active', 1) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                    <small class="text-muted d-block">User dapat login jika status aktif</small>
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
                                    <i class="bi bi-save me-1"></i> Simpan User
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
echo view('layouts/main', ['content' => $content, 'title' => $title, 'current_user' => $current_user, 'active_menu' => $active_menu ?? '']);
?>