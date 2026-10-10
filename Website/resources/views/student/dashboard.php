<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard Mahasiswa</h3>
            <p class="text-secondary small mb-0">Selamat datang, <strong><?= e($user['full_name']) ?></strong>! Kelola jadwal belajar dan bimbingan akademik Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/tutors') ?>" class="btn btn-tb-primary btn-sm px-3 fw-semibold">
                <i class="bi bi-search me-1"></i> Cari Tutor Baru
            </a>
            <a href="<?= url('/student/sessions') ?>" class="btn btn-outline-secondary btn-sm px-3 fw-semibold">
                <i class="bi bi-journal-text me-1"></i> Semua Sesi Belajar
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="tb-card p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small text-muted fw-semibold">Jadwal Mendatang</span>
                    <span class="badge bg-primary-subtle text-primary p-2 rounded-3"><i class="bi bi-calendar-event fs-6"></i></span>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $stats['upcoming_sessions'] ?></h3>
                <small class="text-muted">Sesi terkonfirmasi</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="tb-card p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small text-muted fw-semibold">Permintaan Pending</span>
                    <span class="badge bg-warning-subtle text-warning p-2 rounded-3"><i class="bi bi-hourglass-split fs-6"></i></span>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $stats['pending_requests'] ?></h3>
                <small class="text-muted">Menunggu tutor</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="tb-card p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small text-muted fw-semibold">Sesi Selesai</span>
                    <span class="badge bg-success-subtle text-success p-2 rounded-3"><i class="bi bi-check2-all fs-6"></i></span>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $stats['completed_sessions'] ?></h3>
                <small class="text-muted">Tuntas bimbingan</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="tb-card p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small text-muted fw-semibold">Total Pengajuan</span>
                    <span class="badge bg-info-subtle text-info p-2 rounded-3"><i class="bi bi-collection fs-6"></i></span>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $stats['total_requests'] ?></h3>
                <small class="text-muted">Sepanjang waktu</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Upcoming Sessions -->
        <div class="col-lg-8">
            <div class="tb-card p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-calendar-check text-primary me-2"></i>Jadwal Belajar Mendatang
                    </h5>
                    <a href="<?= url('/student/sessions?status=accepted') ?>" class="small text-primary text-decoration-none">Lihat Semua</a>
                </div>

                <?php if (empty($upcomingSessions)): ?>
                    <div class="p-4 bg-light rounded-3 text-center text-muted small">
                        <i class="bi bi-calendar-x fs-2 d-block mb-1 text-secondary"></i>
                        Belum ada jadwal sesi belajar yang disetujui dalam waktu dekat.<br>
                        <a href="<?= url('/tutors') ?>" class="text-primary fw-semibold mt-2 d-inline-block">Cari tutor sekarang & ajukan bimbingan</a>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($upcomingSessions as $ses): ?>
                            <div class="p-3 bg-light rounded-3 border d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-placeholder" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                        <?= strtoupper(substr($ses['tutor_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary text-white small"><?= e($ses['subject_code']) ?></span>
                                            <h6 class="fw-bold text-dark mb-0"><?= e($ses['subject_name']) ?></h6>
                                        </div>
                                        <small class="text-secondary d-block mt-1">
                                            Tutor: <strong><?= e($ses['tutor_name']) ?></strong> • 
                                            <i class="bi bi-calendar-event me-1"></i><?= format_date_id($ses['session_date']) ?> (<?= substr($ses['start_time'], 0, 5) ?> - <?= substr($ses['end_time'], 0, 5) ?> WIB)
                                        </small>
                                        <small class="text-muted d-block">
                                            Metode: <strong class="text-dark"><?= ucfirst($ses['learning_method']) ?></strong>
                                            <?php if (!empty($ses['location_or_link'])): ?>
                                                • Lokasi: <?= e($ses['location_or_link']) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="text-md-end">
                                    <span class="badge badge-status-accepted mb-2 d-inline-block px-3 py-1">Disetujui</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pending Requests -->
            <div class="tb-card p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-hourglass-top text-warning me-2"></i>Permintaan Menunggu Konfirmasi Tutor
                    </h5>
                    <a href="<?= url('/student/sessions?status=pending') ?>" class="small text-primary text-decoration-none">Lihat Semua</a>
                </div>

                <?php if (empty($pendingSessions)): ?>
                    <div class="p-3 bg-light rounded-3 text-center text-muted small">
                        Tidak ada permintaan sesi yang sedang menunggu konfirmasi.
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($pendingSessions as $pSes): ?>
                            <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0"><?= e($pSes['subject_name']) ?></h6>
                                    <small class="text-muted">
                                        Tutor: <?= e($pSes['tutor_name']) ?> • Tanggal: <?= format_date_id($pSes['session_date']) ?>
                                    </small>
                                </div>
                                <div>
                                    <span class="badge badge-status-pending px-3 py-1">Menunggu</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Activity / Notifications -->
        <div class="col-lg-4">
            <!-- Notifications Card -->
            <div class="tb-card p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-bell text-primary me-1"></i> Notifikasi Terbaru
                    </h6>
                    <a href="<?= url('/notifications') ?>" class="small text-muted text-decoration-none">Lihat Semua</a>
                </div>

                <?php if (empty($notifications)): ?>
                    <p class="text-muted small mb-0">Belum ada notifikasi baru.</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($notifications as $n): ?>
                            <div class="p-2 rounded-2 <?= empty($n['is_read']) ? 'bg-primary-subtle' : 'bg-light' ?> small">
                                <div class="fw-semibold text-dark"><?= e($n['title']) ?></div>
                                <div class="text-secondary" style="font-size: 0.8rem;"><?= e($n['message']) ?></div>
                                <div class="text-muted mt-1" style="font-size: 0.72rem;"><?= format_date_id($n['created_at']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Academic Tips Card -->
            <div class="tb-card p-4 bg-light border">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-info-circle text-primary me-1"></i> Tips Belajar Efektif</h6>
                <ul class="small text-secondary ps-3 mb-0 d-flex flex-column gap-1">
                    <li>Persiapkan topik atau soal spesifik sebelum sesi belajar dimulai.</li>
                    <li>Gunakan fitur ulasan setelah sesi selesai untuk mengapresiasi tutor.</li>
                    <li>Anda juga dapat membagikan keahlian Anda dengan mengaktifkan mode tutor!</li>
                </ul>
            </div>
        </div>
    </div>
</div>
