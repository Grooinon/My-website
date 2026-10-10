<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kelola Pengguna</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Manajemen Pengguna Platform</h3>
            <p class="text-secondary small mb-0">Kelola akun mahasiswa, status aktivasi, dan peranan tutor.</p>
        </div>
        <div>
            <span class="badge bg-primary px-3 py-2">Total: <?= (int) $totalUsers ?> Pengguna Terdaftar</span>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="tb-card p-3 bg-white mb-4">
        <form action="<?= url('/admin/users') ?>" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau NIM..." value="<?= e($filters['search']) ?>">
                </div>
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Semua Role</option>
                    <option value="student" <?= ($filters['role'] === 'student') ? 'selected' : '' ?>>Mahasiswa</option>
                    <option value="admin" <?= ($filters['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_tutor" class="form-select form-select-sm">
                    <option value="">Semua Tutor</option>
                    <option value="1" <?= ($filters['is_tutor'] === '1') ? 'selected' : '' ?>>Hanya Tutor</option>
                    <option value="0" <?= ($filters['is_tutor'] === '0') ? 'selected' : '' ?>>Non-Tutor</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_active" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="1" <?= ($filters['is_active'] === '1') ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= ($filters['is_active'] === '0') ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-tb-primary btn-sm w-100">Filter</button>
                <a href="<?= url('/admin/users') ?>" class="btn btn-light border btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="tb-card p-4 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light small">
                    <tr>
                        <th>Identitas Pengguna</th>
                        <th>NIM & Angkatan</th>
                        <th>Program Studi</th>
                        <th>Role & Tutor</th>
                        <th>Status Akun</th>
                        <th class="text-end">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-placeholder" style="width: 36px; height: 36px; font-size: 0.9rem;">
                                        <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small"><?= e($u['full_name']) ?></div>
                                        <small class="text-muted"><?= e($u['email']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark small"><?= e($u['nim']) ?></span>
                                <small class="text-muted d-block">Angkatan <?= e($u['graduation_year']) ?></small>
                            </td>
                            <td>
                                <small class="text-secondary"><?= e($u['program_name'] ?? '-') ?></small>
                            </td>
                            <td>
                                <span class="badge <?= ($u['role'] === 'admin') ? 'bg-danger' : 'bg-primary' ?> me-1">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                                <?php if (!empty($u['is_tutor'])): ?>
                                    <span class="badge bg-success-subtle text-success border">Tutor</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($u['is_active'])): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Dinonaktifkan</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <form action="<?= url('/admin/users/' . $u['id'] . '/toggle') ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status aktivasi akun pengguna ini?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm <?= !empty($u['is_active']) ? 'btn-outline-danger' : 'btn-outline-success' ?> py-1 px-2">
                                        <?= !empty($u['is_active']) ? 'Nonaktifkan' : 'Aktifkan' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4 pt-2">
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item <?= ($p === $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= url('/admin/users?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>">
                                <?= $p ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
