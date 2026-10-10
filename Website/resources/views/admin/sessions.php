<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Monitoring Sesi Belajar</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Monitoring Seluruh Sesi Belajar</h3>
            <p class="text-secondary small mb-0">Pantau seluruh permintaan, kesepakatan jadwal, dan penyelesaian bimbingan.</p>
        </div>
        <div>
            <span class="badge bg-primary px-3 py-2">Total Sesi: <?= (int) $totalSessions ?></span>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="tb-card p-3 bg-white mb-4">
        <form action="<?= url('/admin/sessions') ?>" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari kode sesi, nama mahasiswa, tutor, atau mata kuliah..." value="<?= e($filters['search']) ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="all">Semua Status Sesi</option>
                    <option value="pending" <?= ($filters['status'] === 'pending') ? 'selected' : '' ?>>Pending (Menunggu)</option>
                    <option value="accepted" <?= ($filters['status'] === 'accepted') ? 'selected' : '' ?>>Accepted (Disetujui)</option>
                    <option value="completed" <?= ($filters['status'] === 'completed') ? 'selected' : '' ?>>Completed (Selesai)</option>
                    <option value="rejected" <?= ($filters['status'] === 'rejected') ? 'selected' : '' ?>>Rejected (Ditolak)</option>
                    <option value="cancelled" <?= ($filters['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled (Dibatalkan)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-tb-primary btn-sm w-100">Filter Data</button>
                <a href="<?= url('/admin/sessions') ?>" class="btn btn-light border btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="tb-card p-4 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover small">
                <thead class="table-light">
                    <tr>
                        <th>Kode Sesi</th>
                        <th>Mahasiswa Pemohon</th>
                        <th>Tutor Pembimbing</th>
                        <th>Mata Kuliah</th>
                        <th>Jadwal Sesi</th>
                        <th>Metode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sessions)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data sesi bimbingan yang ditemukan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sessions as $s): ?>
                            <tr>
                                <td class="font-monospace fw-bold text-dark"><?= e($s['session_code']) ?></td>
                                <td>
                                    <strong><?= e($s['student_name']) ?></strong>
                                    <small class="text-muted d-block"><?= e($s['student_nim']) ?></small>
                                </td>
                                <td>
                                    <strong><?= e($s['tutor_name']) ?></strong>
                                    <small class="text-muted d-block"><?= e($s['tutor_nim']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= e($s['subject_code']) ?></span>
                                    <span class="fw-semibold d-block mt-1"><?= e($s['subject_name']) ?></span>
                                </td>
                                <td>
                                    <div><?= format_date_id($s['session_date']) ?></div>
                                    <small class="text-muted"><?= substr($s['start_time'], 0, 5) ?> - <?= substr($s['end_time'], 0, 5) ?> WIB</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= ucfirst($s['learning_method']) ?></span>
                                </td>
                                <td>
                                    <?php if ($s['status'] === 'pending'): ?>
                                        <span class="badge badge-status-pending px-2 py-1">Pending</span>
                                    <?php elseif ($s['status'] === 'accepted'): ?>
                                        <span class="badge badge-status-accepted px-2 py-1">Accepted</span>
                                    <?php elseif ($s['status'] === 'completed'): ?>
                                        <span class="badge badge-status-completed px-2 py-1">Completed</span>
                                    <?php elseif ($s['status'] === 'rejected'): ?>
                                        <span class="badge badge-status-rejected px-2 py-1">Rejected</span>
                                    <?php elseif ($s['status'] === 'cancelled'): ?>
                                        <span class="badge badge-status-cancelled px-2 py-1">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4 pt-2">
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item <?= ($p === $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= url('/admin/sessions?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>">
                                <?= $p ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
