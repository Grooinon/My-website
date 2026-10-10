<div class="container py-4">
    <!-- Header & Breadcrumb -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/student/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sesi Belajar Saya</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Daftar Sesi Belajar Mahasiswa</h3>
        </div>
        <div>
            <a href="<?= url('/tutors') ?>" class="btn btn-tb-primary btn-sm px-3 fw-semibold">
                <i class="bi bi-search me-1"></i> Ajukan Sesi Belajar Baru
            </a>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="tb-card p-2 bg-white mb-4 d-flex flex-wrap gap-1">
        <?php
        $tabs = [
            'all' => 'Semua Sesi',
            'pending' => 'Menunggu (Pending)',
            'accepted' => 'Disetujui (Accepted)',
            'completed' => 'Selesai (Completed)',
            'rejected' => 'Ditolak (Rejected)',
            'cancelled' => 'Dibatalkan (Cancelled)',
        ];
        ?>
        <?php foreach ($tabs as $key => $label): ?>
            <a href="<?= url('/student/sessions?status=' . $key) ?>" class="btn btn-sm rounded-pill px-3 fw-medium <?= ($currentStatus === $key) ? 'btn-primary' : 'btn-light text-secondary' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Sessions List -->
    <div class="tb-card p-4 bg-white">
        <?php if (empty($sessions)): ?>
            <div class="tb-empty-state py-5">
                <div class="tb-empty-state-icon mx-auto">
                    <i class="bi bi-calendar2-x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Tidak Ada Sesi Belajar</h5>
                <p class="text-secondary small mb-4">Tidak ada sesi belajar dengan filter status ini.</p>
                <a href="<?= url('/tutors') ?>" class="btn btn-tb-primary">
                    <i class="bi bi-search me-1"></i> Telusuri Tutor Sebaya
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light small text-secondary">
                        <tr>
                            <th>Kode Sesi</th>
                            <th>Mata Kuliah & Tutor</th>
                            <th>Waktu & Metode</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessions as $ses): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace"><?= e($ses['session_code']) ?></span>
                                    <small class="d-block text-muted" style="font-size: 0.75rem;"><?= format_date_id($ses['created_at']) ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-placeholder" style="width: 38px; height: 38px; font-size: 0.95rem;">
                                            <?= strtoupper(substr($ses['tutor_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= e($ses['subject_name']) ?></div>
                                            <small class="text-muted">Tutor: <strong class="text-secondary"><?= e($ses['tutor_name']) ?></strong></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">
                                        <i class="bi bi-calendar3 me-1 text-primary"></i><?= format_date_id($ses['session_date']) ?>
                                    </div>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-clock me-1"></i><?= substr($ses['start_time'], 0, 5) ?> - <?= substr($ses['end_time'], 0, 5) ?> WIB (<?= $ses['duration_minutes'] ?> menit)
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
                                        <!-- Cancel action for pending / accepted -->
                                        <?php if (in_array($ses['status'], ['pending', 'accepted'])): ?>
                                            <form action="<?= url('/sessions/' . $ses['id'] . '/cancel') ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan sesi belajar ini?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-x-circle me-1"></i> Batalkan
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <!-- Review action for completed session -->
                                        <?php if ($ses['status'] === 'completed'): ?>
                                            <?php if (empty($ses['review_id'])): ?>
                                                <button type="button" class="btn btn-warning btn-sm fw-semibold text-dark" data-bs-toggle="modal" data-bs-target="#reviewModal_<?= $ses['id'] ?>">
                                                    <i class="bi bi-star-fill me-1"></i> Beri Ulasan
                                                </button>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-dark border px-2 py-1">
                                                    <i class="bi bi-star-fill text-warning me-1"></i> Telah Diulas (<?= $ses['review_rating'] ?>⭐)
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <!-- REVIEW MODAL FOR THIS SESSION -->
                                    <?php if ($ses['status'] === 'completed' && empty($ses['review_id'])): ?>
                                        <div class="modal fade text-start" id="reviewModal_<?= $ses['id'] ?>" tabindex="-1" aria-labelledby="reviewModalLabel_<?= $ses['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                                    <div class="modal-header border-bottom py-3">
                                                        <h5 class="modal-title fw-bold text-dark" id="reviewModalLabel_<?= $ses['id'] ?>">
                                                            <i class="bi bi-star-half text-warning me-2"></i>Ulasan Sesi: <?= e($ses['subject_name']) ?>
                                                        </h5>
                                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?= url('/reviews') ?>" method="POST">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="study_session_id" value="<?= $ses['id'] ?>">

                                                        <div class="modal-body p-4">
                                                            <div class="mb-3 text-center">
                                                                <label class="form-label small fw-semibold text-secondary d-block mb-2">Berapa bintang untuk bimbingan dari Kak <?= e($ses['tutor_name']) ?>?</label>
                                                                
                                                                <!-- Interactive Star Picker -->
                                                                <div class="star-input-group justify-content-center mb-2">
                                                                    <input type="radio" id="star5_<?= $ses['id'] ?>" name="rating" value="5" checked>
                                                                    <label for="star5_<?= $ses['id'] ?>" title="5 Bintang (Luar Biasa)"><i class="bi bi-star-fill"></i></label>
                                                                    
                                                                    <input type="radio" id="star4_<?= $ses['id'] ?>" name="rating" value="4">
                                                                    <label for="star4_<?= $ses['id'] ?>" title="4 Bintang (Sangat Baik)"><i class="bi bi-star-fill"></i></label>
                                                                    
                                                                    <input type="radio" id="star3_<?= $ses['id'] ?>" name="rating" value="3">
                                                                    <label for="star3_<?= $ses['id'] ?>" title="3 Bintang (Baik)"><i class="bi bi-star-fill"></i></label>
                                                                    
                                                                    <input type="radio" id="star2_<?= $ses['id'] ?>" name="rating" value="2">
                                                                    <label for="star2_<?= $ses['id'] ?>" title="2 Bintang (Cukup)"><i class="bi bi-star-fill"></i></label>
                                                                    
                                                                    <input type="radio" id="star1_<?= $ses['id'] ?>" name="rating" value="1">
                                                                    <label for="star1_<?= $ses['id'] ?>" title="1 Bintang (Kurang)"><i class="bi bi-star-fill"></i></label>
                                                                </div>
                                                                <small class="text-muted" id="star_rating_text">Pilih 1 sampai 5 bintang</small>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="comment_<?= $ses['id'] ?>" class="form-label small fw-semibold text-secondary">Tuliskan Pengalaman Belajar Anda <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" id="comment_<?= $ses['id'] ?>" name="comment" rows="4" placeholder="Bagaimana penjelasan materi oleh tutor? Apakah materi mudah dipahami? Tuliskan ulasan jujur Anda..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top p-3">
                                                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-warning fw-semibold px-4 text-dark">
                                                                <i class="bi bi-send-check me-1"></i> Kirim Ulasan
                                                            </button>
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
                                <a class="page-link" href="<?= url('/student/sessions?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>">
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
