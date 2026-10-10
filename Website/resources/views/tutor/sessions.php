<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/tutor/dashboard') ?>">Dashboard Tutor</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kelola Sesi Belajar</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Kelola Permintaan & Sesi Belajar</h3>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/tutor/availability') ?>" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-clock me-1"></i> Atur Jadwal
            </a>
            <a href="<?= url('/tutor/dashboard') ?>" class="btn btn-light border btn-sm px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="tb-card p-2 bg-white mb-4 d-flex flex-wrap gap-1">
        <?php
        $tabs = [
            'all' => 'Semua Sesi',
            'pending' => 'Permintaan Masuk (Pending)',
            'accepted' => 'Disetujui (Accepted)',
            'completed' => 'Selesai (Completed)',
            'rejected' => 'Ditolak (Rejected)',
            'cancelled' => 'Dibatalkan (Cancelled)',
        ];
        ?>
        <?php foreach ($tabs as $key => $label): ?>
            <a href="<?= url('/tutor/sessions?status=' . $key) ?>" class="btn btn-sm rounded-pill px-3 fw-medium <?= ($currentStatus === $key) ? 'btn-primary' : 'btn-light text-secondary' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Sessions Table -->
    <div class="tb-card p-4 bg-white">
        <?php if (empty($sessions)): ?>
            <div class="tb-empty-state py-5">
                <div class="tb-empty-state-icon mx-auto">
                    <i class="bi bi-inbox"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Tidak Ada Sesi Belajar</h5>
                <p class="text-secondary small mb-0">Tidak ada permintaan atau jadwal bimbingan dengan status ini.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light small text-secondary">
                        <tr>
                            <th>Kode & Mahasiswa</th>
                            <th>Mata Kuliah & Topik</th>
                            <th>Jadwal & Metode</th>
                            <th>Status</th>
                            <th class="text-end">Aksi Pengelolaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessions as $ses): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace"><?= e($ses['session_code']) ?></span>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <div class="avatar-placeholder" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                            <?= strtoupper(substr($ses['student_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small"><?= e($ses['student_name']) ?></div>
                                            <small class="text-muted">NIM: <?= e($ses['student_nim']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary text-white small"><?= e($ses['subject_code']) ?></span>
                                    <div class="fw-semibold text-dark mt-1"><?= e($ses['subject_name']) ?></div>
                                    <?php if (!empty($ses['notes'])): ?>
                                        <small class="text-muted d-block mt-1 fst-italic">"<?= e($ses['notes']) ?>"</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">
                                        <i class="bi bi-calendar3 me-1 text-primary"></i><?= format_date_id($ses['session_date']) ?>
                                    </div>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-clock me-1"></i><?= substr($ses['start_time'], 0, 5) ?> - <?= substr($ses['end_time'], 0, 5) ?> WIB (<?= $ses['duration_minutes'] ?> mnt)
                                    </small>
                                    <small class="badge bg-light text-secondary border mt-1">
                                        <?= ucfirst($ses['learning_method']) ?>
                                        <?php if (!empty($ses['location_or_link'])): ?>
                                            : <?= e($ses['location_or_link']) ?>
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($ses['status'] === 'pending'): ?>
                                        <span class="badge badge-status-pending px-3 py-1">Menunggu</span>
                                    <?php elseif ($ses['status'] === 'accepted'): ?>
                                        <span class="badge badge-status-accepted px-3 py-1">Disetujui</span>
                                    <?php elseif ($ses['status'] === 'completed'): ?>
                                        <span class="badge badge-status-completed px-3 py-1">Selesai</span>
                                    <?php elseif ($ses['status'] === 'rejected'): ?>
                                        <span class="badge badge-status-rejected px-3 py-1">Ditolak</span>
                                        <?php if (!empty($ses['rejection_reason'])): ?>
                                            <small class="d-block text-danger mt-1" style="font-size: 0.72rem;">"<?= e($ses['rejection_reason']) ?>"</small>
                                        <?php endif; ?>
                                    <?php elseif ($ses['status'] === 'cancelled'): ?>
                                        <span class="badge badge-status-cancelled px-3 py-1">Dibatalkan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <?php if ($ses['status'] === 'pending'): ?>
                                            <!-- Accept -->
                                            <form action="<?= url('/tutor/sessions/' . $ses['id'] . '/accept') ?>" method="POST" class="m-0">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-success btn-sm fw-semibold">
                                                    <i class="bi bi-check-lg me-1"></i> Setujui
                                                </button>
                                            </form>
                                            <!-- Reject Modal Trigger -->
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal_<?= $ses['id'] ?>">
                                                Tolak
                                            </button>
                                        <?php elseif ($ses['status'] === 'accepted'): ?>
                                            <!-- Complete -->
                                            <form action="<?= url('/tutor/sessions/' . $ses['id'] . '/complete') ?>" method="POST" class="m-0" onsubmit="return confirm('Apakah sesi bimbingan telah selesai dilaksanakan?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                                                    <i class="bi bi-check-circle me-1"></i> Tandai Selesai
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Reject Reason Modal -->
                                    <?php if ($ses['status'] === 'pending'): ?>
                                        <div class="modal fade text-start" id="rejectModal_<?= $ses['id'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-bottom py-3">
                                                        <h6 class="modal-title fw-bold text-danger">Tolak Permintaan Sesi</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?= url('/tutor/sessions/' . $ses['id'] . '/reject') ?>" method="POST">
                                                        <?= csrf_field() ?>
                                                        <div class="modal-body p-4">
                                                            <p class="small text-secondary mb-3">Tuliskan alasan penolakan bimbingan agar mahasiswa memahami:</p>
                                                            <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Contoh: Jadwal bentrok dengan kegiatan praktikum..." required></textarea>
                                                        </div>
                                                        <div class="modal-footer border-top p-3">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger fw-semibold px-4">Tolak Permintaan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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
                                <a class="page-link" href="<?= url('/tutor/sessions?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>">
                                    <?= $p ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
