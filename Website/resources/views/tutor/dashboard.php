<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary text-white">Mode Tutor Sebaya</span>
                <span class="badge badge-verified"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
            </div>
            <h3 class="fw-bold text-dark mb-0">Dashboard Tutor Sebaya</h3>
            <p class="text-secondary small mb-0">Kelola permintaan bimbingan, jadwal mengajar, dan materi akademik yang Anda ampu.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= url('/tutors/' . $tutorProfile['id']) ?>" class="btn btn-outline-primary btn-sm px-3" target="_blank">
                <i class="bi bi-eye me-1"></i> Lihat Profil Publik Saya
            </a>
            <a href="<?= url('/tutor/profile/edit') ?>" class="btn btn-light border btn-sm px-3">
                <i class="bi bi-gear me-1"></i> Pengaturan Tutor
            </a>
        </div>
    </div>

    <!-- Quick Navigation Pills -->
    <div class="tb-card p-2 bg-white mb-4 d-flex flex-wrap gap-1">
        <a href="<?= url('/tutor/dashboard') ?>" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
            <i class="bi bi-speedometer2 me-1"></i> Ringkasan
        </a>
        <a href="<?= url('/tutor/sessions') ?>" class="btn btn-sm btn-light text-secondary rounded-pill px-3 fw-medium">
            <i class="bi bi-calendar-check me-1"></i> Kelola Sesi Belajar
        </a>
        <a href="<?= url('/tutor/subjects') ?>" class="btn btn-sm btn-light text-secondary rounded-pill px-3 fw-medium">
            <i class="bi bi-book me-1"></i> Mata Kuliah Bimbingan (<?= count($subjects) ?>)
        </a>
        <a href="<?= url('/tutor/availability') ?>" class="btn btn-sm btn-light text-secondary rounded-pill px-3 fw-medium">
            <i class="bi bi-clock-history me-1"></i> Jadwal Ketersediaan
        </a>
        <a href="<?= url('/tutor/profile/edit') ?>" class="btn btn-sm btn-light text-secondary rounded-pill px-3 fw-medium">
            <i class="bi bi-person-lines-fill me-1"></i> Edit Profil & Tarif
        </a>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="tb-card p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small text-muted fw-semibold">Permintaan Masuk</span>
                    <span class="badge bg-warning-subtle text-warning p-2 rounded-3"><i class="bi bi-inbox fs-6"></i></span>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $stats['pending_requests'] ?></h3>
                <small class="text-muted">Perlu konfirmasi Anda</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="tb-card p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small text-muted fw-semibold">Jadwal Mendatang</span>
                    <span class="badge bg-primary-subtle text-primary p-2 rounded-3"><i class="bi bi-calendar-event fs-6"></i></span>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $stats['upcoming_sessions'] ?></h3>
                <small class="text-muted">Sesi aktif terjadwal</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="tb-card p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small text-muted fw-semibold">Sesi Diselesaikan</span>
                    <span class="badge bg-success-subtle text-success p-2 rounded-3"><i class="bi bi-check2-circle fs-6"></i></span>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $stats['completed_sessions'] ?></h3>
                <small class="text-muted">Total tuntas mengajar</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="tb-card p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small text-muted fw-semibold">Rata-Rata Rating</span>
                    <span class="badge bg-warning-subtle text-warning p-2 rounded-3"><i class="bi bi-star-fill fs-6"></i></span>
                </div>
                <h3 class="fw-bold text-dark mb-0">⭐ <?= number_format($stats['avg_rating'], 1) ?></h3>
                <small class="text-muted"><?= $stats['total_reviews'] ?> ulasan mahasiswa</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Column: Requests & Upcoming Sessions -->
        <div class="col-lg-8">
            <!-- New Incoming Requests -->
            <div class="tb-card p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-bell-fill text-warning me-2"></i>Permintaan Sesi Belajar Masuk
                    </h5>
                    <a href="<?= url('/tutor/sessions?status=pending') ?>" class="small text-primary text-decoration-none">Kelola Semua</a>
                </div>

                <?php if (empty($pendingRequests)): ?>
                    <div class="p-4 bg-light rounded-3 text-center text-muted small">
                        <i class="bi bi-check2-circle fs-2 d-block mb-1 text-success"></i>
                        Tidak ada permintaan bimbingan baru yang menunggu konfirmasi saat ini.
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($pendingRequests as $req): ?>
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-placeholder" style="width: 44px; height: 44px; font-size: 1.1rem;">
                                            <?= strtoupper(substr($req['student_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fw-bold text-dark mb-0"><?= e($req['student_name']) ?></h6>
                                                <span class="badge bg-primary-subtle text-primary small">NIM: <?= e($req['student_nim']) ?></span>
                                            </div>
                                            <small class="text-secondary d-block mt-1">
                                                Mata Kuliah: <strong><?= e($req['subject_name']) ?></strong> (<?= e($req['subject_code']) ?>)
                                            </small>
                                            <small class="text-muted d-block">
                                                <i class="bi bi-calendar-event me-1"></i><?= format_date_id($req['session_date']) ?> • 
                                                <i class="bi bi-clock me-1"></i><?= substr($req['start_time'], 0, 5) ?> - <?= substr($req['end_time'], 0, 5) ?> WIB (<?= $req['duration_minutes'] ?> mnt, <?= ucfirst($req['learning_method']) ?>)
                                            </small>
                                            <?php if (!empty($req['notes'])): ?>
                                                <div class="small bg-white p-2 rounded-2 mt-2 border text-secondary fst-italic">
                                                    "<?= e($req['notes']) ?>"
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <!-- Reject Button Modal -->
                                        <button type="button" class="btn btn-outline-danger btn-sm px-3" data-bs-toggle="modal" data-bs-target="#rejectModal_<?= $req['id'] ?>">
                                            Tolak
                                        </button>

                                        <!-- Accept Button -->
                                        <form action="<?= url('/tutor/sessions/' . $req['id'] . '/accept') ?>" method="POST" class="m-0">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-success btn-sm px-3 fw-semibold">
                                                <i class="bi bi-check-lg me-1"></i> Setujui
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Reason Modal -->
                            <div class="modal fade" id="rejectModal_<?= $req['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-bottom py-3">
                                            <h6 class="modal-title fw-bold text-danger">Tolak Permintaan Sesi</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="<?= url('/tutor/sessions/' . $req['id'] . '/reject') ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <div class="modal-body p-4">
                                                <p class="small text-secondary mb-3">Tuliskan alasan penolakan agar mahasiswa dapat memahami dan menyesuaikan jadwal lain:</p>
                                                <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Contoh: Ada jadwal kuliah praktikum pada jam tersebut..." required></textarea>
                                            </div>
                                            <div class="modal-footer border-top p-3">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger fw-semibold px-4">Tolak Permintaan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Active Upcoming Confirmed Sessions -->
            <div class="tb-card p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-calendar2-check-fill text-primary me-2"></i>Jadwal Bimbingan Mendatang (Disetujui)
                    </h5>
                    <a href="<?= url('/tutor/sessions?status=accepted') ?>" class="small text-primary text-decoration-none">Lihat Semua</a>
                </div>

                <?php if (empty($upcomingSessions)): ?>
                    <div class="p-3 bg-light rounded-3 text-center text-muted small">
                        Belum ada jadwal bimbingan aktif yang disetujui dalam waktu dekat.
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($upcomingSessions as $uSes): ?>
                            <div class="p-3 bg-light rounded-3 border d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success-subtle text-success small">Terkonfirmasi</span>
                                        <h6 class="fw-bold text-dark mb-0"><?= e($uSes['subject_name']) ?></h6>
                                    </div>
                                    <small class="text-secondary d-block mt-1">
                                        Mahasiswa: <strong><?= e($uSes['student_name']) ?></strong> (<?= e($uSes['student_phone'] ?? '-') ?>)
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-calendar3 me-1"></i><?= format_date_id($uSes['session_date']) ?> • 
                                        <?= substr($uSes['start_time'], 0, 5) ?> - <?= substr($uSes['end_time'], 0, 5) ?> WIB (<?= ucfirst($uSes['learning_method']) ?>)
                                    </small>
                                </div>
                                <div>
                                    <form action="<?= url('/tutor/sessions/' . $uSes['id'] . '/complete') ?>" method="POST" onsubmit="return confirm('Apakah sesi bimbingan ini telah selesai dilaksanakan?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-success btn-sm px-3 fw-semibold">
                                            <i class="bi bi-check-circle me-1"></i> Tandai Selesai
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar: Reviews & Quick Subject overview -->
        <div class="col-lg-4">
            <!-- Recent Reviews -->
            <div class="tb-card p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-star-fill text-warning me-1"></i> Ulasan Terbaru
                    </h6>
                    <span class="badge bg-light text-dark border"><?= $stats['total_reviews'] ?> total</span>
                </div>

                <?php if (empty($recentReviews)): ?>
                    <p class="text-muted small mb-0">Belum ada ulasan yang masuk dari mahasiswa.</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($recentReviews as $r): ?>
                            <div class="p-2 bg-light rounded-2 border small">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark"><?= e($r['student_name']) ?></strong>
                                    <span class="text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star-<?= $i <= $r['rating'] ? 'fill' : 'light' ?>"></i>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <p class="text-secondary mb-1 fst-italic">"<?= e($r['comment']) ?>"</p>
                                <small class="text-muted" style="font-size: 0.72rem;"><?= format_date_id($r['created_at']) ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Subjects Handled -->
            <div class="tb-card p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-journal-check text-primary me-1"></i> Mata Kuliah Anda
                    </h6>
                    <a href="<?= url('/tutor/subjects') ?>" class="small text-primary text-decoration-none">+ Tambah</a>
                </div>

                <?php if (empty($subjects)): ?>
                    <p class="text-muted small mb-0">Anda belum mendaftarkan mata kuliah yang diampu.</p>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-1">
                        <?php foreach ($subjects as $s): ?>
                            <span class="badge bg-light text-dark border p-2 small">
                                <?= e($s['subject_name']) ?> (Nilai: <strong><?= e($s['grade']) ?></strong>)
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
