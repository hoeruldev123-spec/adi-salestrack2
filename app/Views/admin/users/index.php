<!-- app/Views/admin/users/index.php -->
<?php $content = ob_start() ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0"><?= $title ?></h6>
                        <p class="text-sm mb-0">Daftar semua user dalam sistem</p>
                    </div>
                    <div>
                        <a href="<?= base_url('admin/users/add') ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Tambah User
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show text-white m-4" role="alert">
                        <span class="alert-icon"><i class="bi bi-check-circle"></i></span>
                        <span class="alert-text"><?= session()->getFlashdata('success') ?></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Dibuat</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-people text-secondary" style="font-size: 3rem;"></i>
                                        <h6 class="mt-2 text-secondary">Tidak ada data user</h6>
                                        <p class="text-sm">Mulai dengan menambahkan user baru</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; ?>


                                <?php foreach ($users as $user): ?>


                                    <tr>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0"><?= $no++ ?></p>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="<?= base_url('assets/img/team-2.jpg') ?>"
                                                        class="avatar avatar-sm me-3"
                                                        alt="user image">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm"><?= esc($user['name']) ?></h6>
                                                    <p class="text-xs text-secondary mb-0"><?= esc($user['email']) ?></p>
                                                    <p class="text-xs text-secondary mb-0">@<?= esc($user['username']) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <!-- PERBAIKAN: Tampilkan role dengan benar -->
                                            <?php
                                            $roleColor = '';
                                            $roleIcon = '';

                                            switch ($user['role']) {
                                                case 'admin':
                                                    $roleColor = 'danger';
                                                    $roleIcon = 'bi-shield-check';
                                                    break;
                                                case 'manager':
                                                    $roleColor = 'warning';
                                                    $roleIcon = 'bi-person-badge';
                                                    break;
                                                case 'BOD':
                                                    $roleColor = 'info';
                                                    $roleIcon = 'bi-person-square';
                                                    break;
                                                case 'sales':
                                                    $roleColor = 'primary';
                                                    $roleIcon = 'bi-person';
                                                    break;
                                                default:
                                                    $roleColor = 'secondary';
                                                    $roleIcon = 'bi-person';
                                            }
                                            ?>

                                            <div class="d-flex align-items-center">
                                                <div class="icon icon-shape icon-sm me-2 bg-gradient-<?= $roleColor ?> text-white rounded-circle">
                                                    <i class="bi <?= $roleIcon ?>"></i>
                                                </div>
                                                <span class="text-xs font-weight-bold">
                                                    <?= strtoupper($user['role'] ?? 'N/A') ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm bg-gradient-<?= $user['is_active'] ? 'success' : 'secondary' ?>">
                                                <?= $user['is_active'] ? 'AKTIF' : 'NONAKTIF' ?>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                <?= date('d M Y', strtotime($user['created_at'])) ?>
                                            </span>
                                        </td>
                                        <!-- Untuk testing, tampilkan semua tombol -->
                                        <td class="align-middle text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <!-- EDIT BUTTON -->
                                                <?php if (in_array($currentRole, ['admin', 'manager'])): ?>
                                                    <?php
                                                    $canEdit = false;

                                                    if ($currentRole === 'admin') {
                                                        // Admin bisa edit semua
                                                        $canEdit = true;
                                                    } elseif ($currentRole === 'manager') {
                                                        // Manager hanya bisa edit sales
                                                        $canEdit = ($user['role'] === 'sales');
                                                    }
                                                    ?>

                                                    <?php if ($canEdit): ?>
                                                        <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>"
                                                            class="btn btn-sm btn-outline-info"
                                                            title="Edit User">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-secondary"
                                                            disabled
                                                            title="Tidak dapat mengedit user ini">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <!-- DELETE BUTTON -->
                                                <?php
                                                $allowDelete = false;
                                                $deleteReason = "";

                                                // Admin permission
                                                if ($currentRole === 'admin') {
                                                    if ($user['id'] == $currentUserId) {
                                                        $deleteReason = "Akun sendiri";
                                                    } elseif ($user['role'] === 'admin') {
                                                        $deleteReason = "User Admin";
                                                    } else {
                                                        $allowDelete = true;
                                                    }
                                                }
                                                // Manager permission
                                                elseif ($currentRole === 'manager') {
                                                    if ($user['role'] === 'sales') {
                                                        $allowDelete = true;
                                                    } else {
                                                        $deleteReason = "Hanya bisa hapus Sales";
                                                    }
                                                }
                                                ?>

                                                <?php if ($allowDelete): ?>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDelete(<?= $user['id'] ?>, '<?= addslashes($user['name']) ?>')"
                                                        title="Hapus User">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                <?php elseif (in_array($currentRole, ['admin', 'manager']) && $deleteReason): ?>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary"
                                                        disabled
                                                        title="Tidak dapat dihapus (<?= $deleteReason ?>)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Hapus User?',
            html: `Anda yakin ingin menghapus user <strong>"${userName}"</strong>?<br>
               <small class="text-danger">Aksi ini tidak dapat dibatalkan!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/admin/users/delete/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value.status === 'success') {
                    Swal.fire({
                        title: 'Terhapus!',
                        text: result.value.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: result.value.message || 'Gagal menghapus user.',
                        icon: 'error'
                    });
                }
            }
        });
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<?php
$content = ob_get_clean();
echo view('layouts/main', [
    'content' => $content,
    'title' => $title,
    'current_user' => $current_user,
    'active_menu' => $active_menu ?? ''
]);
?>