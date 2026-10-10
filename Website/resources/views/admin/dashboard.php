<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-danger text-white">Administrator Panel</span>
                <span class="badge bg-light text-secondary border">Hak Akses Penuh</span>
            </div>
            <h3 class="fw-bold text-dark mb-0">Dashboard Administrator TemanBelajar</h3>
            <p class="text-secondary small mb-0">Pantau operasional platform tutor sebaya mahasiswa, audit trail, dan tata kelola data kurikulum.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/admin/logs') ?>" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-journal-text me-1"></i> Log Aktivitas
            </a>
            <a href="<?= url('/student/dashboard') ?>" class="btn btn-light border btn-sm px-3">
                <i class="bi bi-box-arrow-left me-1"></i> Mode Mahasiswa
            </a>
        </div>
    </div>

    <!-- Admin Navigation Tabs -->
    <div class="tb-card p-2 bg-white mb-4 d-flex flex-wrap gap-1">
        <a href="<?= url('/admin/dashboard') ?>" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
            <i class="bi bi-speedometer2 me-1"></i> Ringkasan
        </a>
        <a href="<?= url('/admin/users') ?>" class="btn btn-sm btn-light text-secondary rounded-pill px-3 fw-medium">
            <i class="bi bi-people me-1"></i> Kelola Pengguna
        </a>
        <a href="<?= url('/admin/programs') ?>" class="btn btn-sm btn-light text-secondary rounded-pill px-3 fw-medium">
            <i class="bi bi-building me-1"></i> Program Studi
        </a>
        <a href="<?= url('/admin/subjects') ?>" class="btn btn-sm btn-light text-secondary rounded-pill px-3 fw-medium">
            <i class="bi bi-book me-1"></i> Mata Kuliah
        </a>
        <a href="<?= url('/admin/sessions') ?>" class="btn btn-sm btn-light text-secondary rounded-pill px-3 fw-medium">
            <i class="bi bi-calendar-event me-1"></i> Monitoring Sesi
        </a>
        <a href="<?= url('/admin/reviews') ?>" class="btn btn-sm btn-light text-secondary rounded-pill px-3 fw-medium">
            <i class="bi bi-star me-1"></i> Moderasi Ulasan
        </a>
        <a href="<?= url('/admin/logs') ?>" class="btn btn-sm btn-light text-secondary rounded-pill px-3 fw-medium">
            <i class="bi bi-shield-check me-1"></i> Audit Trail
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="tb-card p-3 bg-white h-100">
                <span class="small text-muted fw-semibold d-block mb-1">Total Pengguna</span>
                <h4 class="fw-bold text-dark mb-0"><?= $stats['total_users'] ?></h4>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="tb-card p-3 bg-white h-100">
                <span class="small text-muted fw-semibold d-block mb-1">Mahasiswa</span>
                <h4 class="fw-bold text-primary mb-0"><?= $stats['total_students'] ?></h4>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="tb-card p-3 bg-white h-100">
                <span class="small text-muted fw-semibold d-block mb-1">Tutor Aktif</span>
                <h4 class="fw-bold text-success mb-0"><?= $stats['total_tutors'] ?></h4>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="tb-card p-3 bg-white h-100">
                <span class="small text-muted fw-semibold d-block mb-1">Mata Kuliah</span>
                <h4 class="fw-bold text-info mb-0"><?= $stats['total_subjects'] ?></h4>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="tb-card p-3 bg-white h-100">
                <span class="small text-muted fw-semibold d-block mb-1">Total Sesi</span>
                <h4 class="fw-bold text-warning mb-0"><?= $stats['total_sessions_all'] ?></h4>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="tb-card p-3 bg-white h-100">
                <span class="small text-muted fw-semibold d-block mb-1">Ulasan Masuk</span>
                <h4 class="fw-bold text-dark mb-0"><?= $stats['total_reviews'] ?></h4>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Monitoring Sesi Terbaru -->
        <div class="col-lg-7">
            <div class="tb-card p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-clock-history text-primary me-2"></i>Aktivitas Sesi Belajar Terbaru
                    </h5>
                    <a href="<?= url('/admin/sessions') ?>" class="small text-primary text-decoration-none">Lihat Semua</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light small">
                            <tr>
                                <th>Kode</th>
                                <th>Mahasiswa</th>
                                <th>Tutor</th>
                                <th>Mata Kuliah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <?php foreach ($recentSessions as $rs): ?>
                                <tr>
                                    <td class="font-monospace fw-semibold"><?= e($rs['session_code']) ?></td>
                                    <td><?= e($rs['student_name']) ?></td>
                                    <td><?= e($rs['tutor_name']) ?></td>
                                    <td><?= e($rs['subject_name']) ?></td>
                                    <td>
                                        <?php if ($rs['status'] === 'pending'): ?>
                                            <span class="badge badge-status-pending">Pending</span>
                                        <?php elseif ($rs['status'] === 'accepted'): ?>
                                            <span class="badge badge-status-accepted">Accepted</span>
                                        <?php elseif ($rs['status'] === 'completed'): ?>
                                            <span class="badge badge-status-completed">Completed</span>
                                        <?php elseif ($rs['status'] === 'rejected'): ?>
                                            <span class="badge badge-status-rejected">Rejected</span>
                                        <?php elseif ($rs['status'] === 'cancelled'): ?>
                                            <span class="badge badge-status-cancelled">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Audit Trail Ringkas -->
        <div class="col-lg-5">
            <div class="tb-card p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-shield-check text-success me-2"></i>Audit Trail Log
                    </h5>
                    <a href="<?= url('/admin/logs') ?>" class="small text-primary text-decoration-none">Semua Log</a>
                </div>

                <div class="d-flex flex-column gap-2">
                    <?php foreach ($latestLogs as $log): ?>
                        <div class="p-2 bg-light rounded-2 border small">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark border font-monospace"><?= e($log['action']) ?></span>
                                <small class="text-muted"><?= format_date_id($log['created_at']) ?></small>
                            </div>
                            <div class="text-secondary mt-1">
                                <strong><?= e($log['full_name'] ?? 'Sistem / Anonim') ?>:</strong> <?= e($log['details']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
