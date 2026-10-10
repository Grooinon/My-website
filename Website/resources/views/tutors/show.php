<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="<?= url('/') ?>">Beranda</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/tutors') ?>">Cari Tutor</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= e($tutor['full_name']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Main Profile Info -->
        <div class="col-lg-8">
            <!-- Header Card -->
            <div class="tb-card p-4 p-md-5 bg-white mb-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-4 mb-4">
                    <?php if (!empty($tutor['avatar']) && file_exists(dirname(__DIR__, 3) . '/public/uploads/avatars/' . $tutor['avatar'])): ?>
                        <img src="<?= url('uploads/avatars/' . e($tutor['avatar'])) ?>" alt="<?= e($tutor['full_name']) ?>" class="avatar-img shadow-sm" width="100" height="100">
                    <?php else: ?>
                        <div class="avatar-placeholder shadow-sm" style="width: 100px; height: 100px; font-size: 2.5rem;">
                            <?= strtoupper(substr($tutor['full_name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>

                    <div class="flex-grow-1">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h3 class="fw-bold text-dark mb-0"><?= e($tutor['full_name']) ?></h3>
                            <span class="badge badge-verified">
                                <i class="bi bi-patch-check-fill"></i> Tutor Terverifikasi
                            </span>
                            <?php if (!empty($tutor['is_available'])): ?>
                                <span class="badge bg-success-subtle text-success fw-semibold">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.55rem;"></i> Siap Mengajar
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary fw-semibold">Jadwal Penuh</span>
                            <?php endif; ?>
                        </div>

                        <p class="text-secondary small mb-2">
                            <?= e($tutor['program_name']) ?> • Angkatan <?= e($tutor['graduation_year']) ?> (NIM: <?= e($tutor['nim']) ?>)
                        </p>

                        <!-- Rating & Review Count -->
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="star-rating">
                                <i class="bi bi-star-fill text-warning"></i>
                            </span>
                            <span class="fw-bold text-dark"><?= number_format($avgRating, 1) ?></span>
                            <span class="text-muted">• <?= $reviewCount ?> ulasan mahasiswa</span>
                        </div>
                    </div>
                </div>

                <!-- Headline -->
                <div class="p-3 bg-light rounded-3 mb-4 border">
                    <div class="fw-bold text-dark mb-1 small text-uppercase tracking-wider">Motto & Fokus Bimbingan</div>
                    <p class="mb-0 text-secondary fst-italic">"<?= e($tutor['headline'] ?: 'Belajar bersama secara kolaboratif untuk pemahaman optimal.') ?>"</p>
                </div>

                <!-- Bio / Tentang Tutor -->
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-2">Tentang Tutor & Pendekatan Mengajar</h5>
                    <p class="text-secondary" style="white-space: pre-line;"><?= e($tutor['bio']) ?></p>
                </div>

                <!-- Prestasi & Pengalaman -->
                <?php if (!empty($tutor['academic_achievements']) || !empty($tutor['teaching_experience'])): ?>
                    <div class="mb-4 pt-3 border-top">
                        <h5 class="fw-bold text-dark mb-3">Kredensial & Pengalaman Akademik</h5>
                        <div class="row g-3">
                            <?php if (!empty($tutor['academic_achievements'])): ?>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <h6 class="fw-bold text-dark mb-2 small"><i class="bi bi-award text-primary me-1"></i> Prestasi Akademik</h6>
                                        <p class="small text-secondary mb-0" style="white-space: pre-line;"><?= e($tutor['academic_achievements']) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($tutor['teaching_experience'])): ?>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <h6 class="fw-bold text-dark mb-2 small"><i class="bi bi-briefcase text-primary me-1"></i> Pengalaman Mengajar</h6>
                                        <p class="small text-secondary mb-0" style="white-space: pre-line;"><?= e($tutor['teaching_experience']) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Mata Kuliah yang Diampu -->
                <div class="mb-4 pt-3 border-top">
                    <h5 class="fw-bold text-dark mb-3">Mata Kuliah yang Dikuasai</h5>
                    <div class="row g-3">
                        <?php if (empty($subjects)): ?>
                            <div class="col-12 text-muted small">Tutor belum mendaftarkan mata kuliah khusus.</div>
                        <?php else: ?>
                            <?php foreach ($subjects as $s): ?>
                                <div class="col-md-6">
                                    <div class="tb-card p-3 bg-light border h-100">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-primary text-white small"><?= e($s['subject_code']) ?></span>
                                            <span class="badge bg-success text-white small">Nilai Akademik: <?= e($s['grade']) ?></span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1 mt-2"><?= e($s['subject_name']) ?></h6>
                                        <small class="text-secondary d-block mb-1"><?= e($s['program_name']) ?> • Smt <?= $s['semester'] ?></small>
                                        <?php if (!empty($s['notes'])): ?>
                                            <p class="small text-muted mb-0 fst-italic">"<?= e($s['notes']) ?>"</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Jadwal Ketersediaan Rutin -->
                <div class="mb-4 pt-3 border-top">
                    <h5 class="fw-bold text-dark mb-3">Slot Ketersediaan Jadwal</h5>
                    <?php if (empty($availability)): ?>
                        <p class="text-muted small">Tutor belum mengatur slot jam rutin khusus. Anda tetap dapat mengajukan jadwal yang sesuai.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle small bg-light">
                                <thead class="table-light">
                                    <tr>
                                        <th>Hari</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Status Slot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($availability as $slot): ?>
                                        <tr>
                                            <td class="fw-semibold"><?= e($slot['day_of_week']) ?></td>
                                            <td><?= substr($slot['start_time'], 0, 5) ?> WIB</td>
                                            <td><?= substr($slot['end_time'], 0, 5) ?> WIB</td>
                                            <td><span class="badge bg-success-subtle text-success">Tersedia</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Reviews & Testimonials -->
                <div class="pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Ulasan dari Mahasiswa (<?= $reviewCount ?>)</h5>
                    </div>

                    <?php if (empty($reviews)): ?>
                        <div class="p-4 bg-light text-center rounded-3 text-muted small">
                            Belum ada ulasan untuk tutor ini. Jadilah mahasiswa pertama yang belajar bersama Kak <?= e($tutor['full_name']) ?>!
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($reviews as $rev): ?>
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-placeholder" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                                <?= strtoupper(substr($rev['student_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark small"><?= e($rev['student_name']) ?></div>
                                                <small class="text-muted">Mata kuliah: <?= e($rev['subject_name']) ?></small>
                                            </div>
                                        </div>
                                        <div class="text-warning small">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star-<?= $i <= $rev['rating'] ? 'fill' : 'light' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="small text-secondary mb-1">"<?= e($rev['comment']) ?>"</p>
                                    <small class="text-muted" style="font-size: 0.75rem;"><?= format_date_id($rev['created_at']) ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Booking Widget -->
        <div class="col-lg-4">
            <div class="tb-card p-4 bg-white sticky-top shadow-sm" style="top: 80px;">
                <!-- Price Header -->
                <div class="text-center pb-3 mb-3 border-bottom">
                    <div class="small text-muted mb-1">Tarif Bimbingan Belajar</div>
                    <?php if (!empty($tutor['is_free'])): ?>
                        <h3 class="fw-bold text-success mb-1">Gratis</h3>
                        <p class="small text-muted mb-0">Tutor bersedia mengajar secara sukarela demi rekan mahasiswa.</p>
                    <?php else: ?>
                        <h3 class="fw-bold text-primary mb-1"><?= format_rupiah($tutor['hourly_rate']) ?></h3>
                        <p class="small text-muted mb-0">per sesi bimbingan (pembayaran langsung di luar platform)</p>
                    <?php endif; ?>
                </div>

                <!-- Info summary -->
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-4">
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="bi bi-laptop me-1"></i> Metode:</span>
                        <strong class="text-dark">
                            <?php 
                                if ($tutor['preferred_method'] === 'online') echo 'Hanya Online';
                                elseif ($tutor['preferred_method'] === 'offline') echo 'Tatap Muka Saja';
                                else echo 'Online & Tatap Muka';
                            ?>
                        </strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="bi bi-clock me-1"></i> Durasi:</span>
                        <strong class="text-dark">60 - 120 Menit / sesi</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="bi bi-shield-check me-1"></i> Konfirmasi:</span>
                        <strong class="text-success">Menunggu persetujuan tutor</strong>
                    </li>
                </ul>

                <!-- Booking Action -->
                <?php if ($isSelf): ?>
                    <div class="alert alert-info small mb-0">
                        <i class="bi bi-info-circle me-1"></i> Ini adalah tampilan profil publik Anda sendiri.
                    </div>
                <?php elseif (!auth()): ?>
                    <div class="text-center">
                        <p class="small text-secondary mb-3">Silakan masuk dengan akun mahasiswa Anda untuk mengajukan sesi bimbingan dengan tutor ini.</p>
                        <a href="<?= url('/login') ?>" class="btn btn-tb-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk untuk Memesan
                        </a>
                    </div>
                <?php else: ?>
                    <button type="button" class="btn btn-tb-primary w-100 py-2 fw-semibold mb-2" data-bs-toggle="modal" data-bs-target="#bookingModal">
                        <i class="bi bi-calendar-plus me-1"></i> Ajukan Sesi Belajar
                    </button>
                    <div class="text-center small text-muted">
                        Jadwal otomatis divalidasi dari bentrok waktu
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- BOOKING MODAL -->
<?php if (auth() && !$isSelf): ?>
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark" id="bookingModalLabel">
                    <i class="bi bi-calendar-check text-primary me-2"></i>Ajukan Sesi Belajar Bersama <?= e($tutor['full_name']) ?>
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= url('/sessions/book') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="tutor_id" value="<?= (int) $tutor['user_id'] ?>">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Select Subject -->
                        <div class="col-md-12">
                            <label for="study_subject_id" class="form-label small fw-semibold text-secondary">Pilih Mata Kuliah <span class="text-danger">*</span></label>
                            <select class="form-select" id="study_subject_id" name="study_subject_id" required>
                                <option value="">-- Pilih Mata Kuliah yang Diajarkan Tutor --</option>
                                <?php foreach ($subjects as $sub): ?>
                                    <option value="<?= $sub['study_subject_id'] ?>">
                                        <?= e($sub['subject_name']) ?> (<?= e($sub['subject_code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <label for="session_date" class="form-label small fw-semibold text-secondary">Tanggal Belajar <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="session_date" name="session_date" min="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Start Time -->
                        <div class="col-md-6">
                            <label for="booking_start_time" class="form-label small fw-semibold text-secondary">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="booking_start_time" name="start_time" value="16:00" required>
                        </div>

                        <!-- Duration -->
                        <div class="col-md-6">
                            <label for="booking_duration" class="form-label small fw-semibold text-secondary">Durasi Sesi <span class="text-danger">*</span></label>
                            <select class="form-select" id="booking_duration" name="duration_minutes" required>
                                <option value="60">60 Menit (1 Jam)</option>
                                <option value="90" selected>90 Menit (1.5 Jam)</option>
                                <option value="120">120 Menit (2 Jam)</option>
                            </select>
                            <div class="form-text small">Perkiraan selesai: <strong id="booking_end_time_display" class="text-primary">-</strong></div>
                        </div>

                        <!-- Method -->
                        <div class="col-md-6">
                            <label for="learning_method" class="form-label small fw-semibold text-secondary">Metode Belajar <span class="text-danger">*</span></label>
                            <select class="form-select" id="learning_method" name="learning_method" required>
                                <?php if ($tutor['preferred_method'] === 'both' || $tutor['preferred_method'] === 'online'): ?>
                                    <option value="online">Online (Google Meet / Zoom)</option>
                                <?php endif; ?>
                                <?php if ($tutor['preferred_method'] === 'both' || $tutor['preferred_method'] === 'offline'): ?>
                                    <option value="offline">Tatap Muka (Kampus / Perpustakaan)</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Location or link -->
                        <div class="col-md-12">
                            <label for="location_or_link" class="form-label small fw-semibold text-secondary">Usulan Tempat / Tautan Meeting</label>
                            <input type="text" class="form-control" id="location_or_link" name="location_or_link" placeholder="Misal: Gazebo Gedung B / Tautan Google Meet akan disusulkan">
                        </div>

                        <!-- Notes / Topics to discuss -->
                        <div class="col-md-12">
                            <label for="notes" class="form-label small fw-semibold text-secondary">Topik yang Ingin Dipelajari / Catatan Tambahan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tuliskan materi, bab tugas, atau kesulitan spesifik yang ingin dibahas..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tb-primary px-4 fw-semibold">
                        <i class="bi bi-send-check me-1"></i> Kirim Permintaan Sesi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
