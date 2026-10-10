<!-- HERO SECTION -->
<section class="hero-gradient py-5 py-lg-6">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-white border shadow-sm small text-primary fw-semibold">
                    <i class="bi bi-stars"></i> Platform Tutor Sebaya Mahasiswa Kampus
                </div>
                <h1 class="display-4 fw-extrabold text-dark mb-3 lh-sm">
                    Temukan Teman Belajar yang <span class="text-primary">Tepat untukmu.</span>
                </h1>
                <p class="lead text-secondary mb-4">
                    Tingkatkan pemahaman mata kuliah sulit melalui bimbingan santai bersama rekan mahasiswa berprestasi. Belajar lebih fleksibel, tanpa rasa canggung, dan saling mendukung kemajuan akademik.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="<?= url('/tutors') ?>" class="btn btn-tb-primary btn-lg px-4 py-3 d-flex align-items-center gap-2">
                        <i class="bi bi-search"></i> Cari Tutor Sekarang
                    </a>
                    <?php if (auth()): ?>
                        <?php if (empty(auth()['is_tutor'])): ?>
                            <a href="<?= url('/tutor/profile/edit') ?>" class="btn btn-tb-outline btn-lg px-4 py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-mortarboard"></i> Mulai Jadi Tutor
                            </a>
                        <?php else: ?>
                            <a href="<?= url('/tutor/dashboard') ?>" class="btn btn-tb-outline btn-lg px-4 py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-speedometer2"></i> Dashboard Tutor
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= url('/register') ?>" class="btn btn-tb-outline btn-lg px-4 py-3 d-flex align-items-center gap-2">
                            <i class="bi bi-mortarboard"></i> Mulai Jadi Tutor
                        </a>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center gap-4 text-muted small pt-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> 100% Mahasiswa Terverifikasi
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> Opsi Bimbingan Gratis
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> Daring atau Tatap Muka
                    </div>
                </div>
            </div>

            <!-- Hero Visual Card / Quick Search -->
            <div class="col-lg-5">
                <div class="tb-card p-4 bg-white shadow-lg border-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-compass text-primary me-2"></i>Cari Bimbingan Cepat
                        </h5>
                        <span class="badge bg-primary-subtle text-primary fw-semibold">Terhubung Langsung</span>
                    </div>
                    <form action="<?= url('/tutors') ?>" method="GET" class="d-flex flex-column gap-3">
                        <div>
                            <label class="form-label small fw-semibold text-secondary">Cari Mata Kuliah / Nama Tutor</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="q" class="form-control border-start-0 ps-0" placeholder="Misal: Web, Basis Data, Struktur Data...">
                            </div>
                        </div>
                        <div>
                            <label class="form-label small fw-semibold text-secondary">Program Studi</label>
                            <select name="program_id" class="form-select">
                                <option value="">Semua Program Studi</option>
                                <?php foreach ($studyPrograms as $prog): ?>
                                    <option value="<?= $prog['id'] ?>"><?= e($prog['name']) ?> (<?= e($prog['code']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label small fw-semibold text-secondary">Metode Belajar</label>
                            <select name="method" class="form-select">
                                <option value="all">Semua Metode (Online & Offline)</option>
                                <option value="online">Online (Google Meet / Zoom)</option>
                                <option value="offline">Tatap Muka (Kampus / Perpustakaan)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label small fw-semibold text-secondary">Biaya Sesi</label>
                            <select name="price_type" class="form-select">
                                <option value="">Semua (Gratis & Berbayar)</option>
                                <option value="free">Bimbingan Gratis</option>
                                <option value="paid">Berbayar Ringan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-tb-primary w-100 py-2 mt-2">
                            <i class="bi bi-arrow-right-circle me-1"></i> Telusuri Tutor Tersedia
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- REAL PLATFORM STATISTICS FROM DATABASE -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <div class="hero-stats-card h-100">
                    <div class="fs-2 fw-bold text-primary mb-1"><?= (int) $stats['total_students'] ?></div>
                    <div class="small text-muted fw-medium">Mahasiswa Terdaftar</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="hero-stats-card h-100">
                    <div class="fs-2 fw-bold text-primary mb-1"><?= (int) $stats['total_tutors'] ?></div>
                    <div class="small text-muted fw-medium">Tutor Sebaya Aktif</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="hero-stats-card h-100">
                    <div class="fs-2 fw-bold text-primary mb-1"><?= (int) $stats['total_subjects'] ?></div>
                    <div class="small text-muted fw-medium">Mata Kuliah Bimbingan</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="hero-stats-card h-100">
                    <div class="fs-2 fw-bold text-success mb-1"><?= (int) $stats['total_sessions_completed'] ?></div>
                    <div class="small text-muted fw-medium">Sesi Belajar Tuntas</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS SECTION -->
<section class="py-5 bg-light" id="cara-kerja">
    <div class="container py-3">
        <div class="text-center max-w-2xl mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill mb-2">Alur Pembelajaran</span>
            <h2 class="fw-bold text-dark">Bagaimana Cara Kerja TemanBelajar?</h2>
            <p class="text-secondary">Proses sederhana menghubungkan mahasiswa pemelajar dengan tutor sebaya yang berpengalaman.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="tb-card p-4 h-100 bg-white">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold fs-5">1</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Cari Tutor</h5>
                    <p class="text-secondary small mb-0">Temukan tutor berdasarkan mata kuliah, program studi, tarif, atau metode belajar yang cocok untuk Anda.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="tb-card p-4 h-100 bg-white">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold fs-5">2</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Ajukan Jadwal</h5>
                    <p class="text-secondary small mb-0">Pilih slot waktu belajar, durasi, topik bahasan, dan metode belajar (tatap muka atau online).</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="tb-card p-4 h-100 bg-white">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold fs-5">3</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Belajar Bersama</h5>
                    <p class="text-secondary small mb-0">Setelah disetujui, ikuti sesi bimbingan interaktif, bahas tugas, dan bedah materi sampai paham.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="tb-card p-4 h-100 bg-white">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold fs-5">4</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Beri Ulasan</h5>
                    <p class="text-secondary small mb-0">Beri ulasan dan rating objektif untuk membantu tutor membangun reputasi akademik terpercaya.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED TUTORS FROM DATABASE -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-2">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill mb-2">Rekomendasi Terbaik</span>
                <h2 class="fw-bold text-dark mb-1">Tutor Sebaya Pilihan</h2>
                <p class="text-secondary mb-0">Mahasiswa berprestasi yang siap membimbing materi perkuliahan Anda.</p>
            </div>
            <div>
                <a href="<?= url('/tutors') ?>" class="btn btn-tb-outline">
                    Lihat Semua Tutor <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php if (empty($featuredTutors)): ?>
                <div class="col-12">
                    <div class="tb-card p-5 text-center bg-light">
                        <i class="bi bi-people fs-1 text-muted mb-2"></i>
                        <p class="text-muted mb-0">Belum ada tutor aktif saat ini.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($featuredTutors as $tutor): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="tb-card p-3 h-100 d-flex flex-column">
                            <!-- Card Header: Avatar & Info -->
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <?php if (!empty($tutor['avatar']) && file_exists(dirname(__DIR__, 3) . '/public/uploads/avatars/' . $tutor['avatar'])): ?>
                                    <img src="<?= url('uploads/avatars/' . e($tutor['avatar'])) ?>" alt="<?= e($tutor['full_name']) ?>" class="avatar-img" width="56" height="56">
                                <?php else: ?>
                                    <div class="avatar-placeholder" style="width: 56px; height: 56px; font-size: 1.3rem;">
                                        <?= strtoupper(substr($tutor['full_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold mb-0 text-dark text-truncate" title="<?= e($tutor['full_name']) ?>"><?= e($tutor['full_name']) ?></h6>
                                    <small class="text-muted d-block text-truncate"><?= e($tutor['program_name'] ?? 'Mahasiswa') ?></small>
                                    <span class="badge badge-verified mt-1">
                                        <i class="bi bi-patch-check-fill"></i> Terverifikasi
                                    </span>
                                </div>
                            </div>

                            <!-- Headline -->
                            <p class="small text-secondary mb-3 flex-grow-1 text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= e($tutor['headline'] ?: 'Tutor sebaya antusias membantu pemahaman akademik.') ?>
                            </p>

                            <!-- Subjects badges -->
                            <div class="mb-3 d-flex flex-wrap gap-1">
                                <?php 
                                $subCount = 0;
                                foreach ($tutor['subjects'] as $sub): 
                                    if ($subCount >= 2) break;
                                    $subCount++;
                                ?>
                                    <span class="badge bg-light text-dark border small fw-normal"><?= e($sub['subject_name']) ?></span>
                                <?php endforeach; ?>
                                <?php if (count($tutor['subjects']) > 2): ?>
                                    <span class="badge bg-light text-muted border small fw-normal">+<?= count($tutor['subjects']) - 2 ?> lainnya</span>
                                <?php endif; ?>
                            </div>

                            <hr class="my-2 border-light-subtle">

                            <!-- Rating & Price -->
                            <div class="d-flex justify-content-between align-items-center mb-3 pt-1">
                                <div>
                                    <span class="star-rating small">
                                        <i class="bi bi-star-fill"></i>
                                    </span>
                                    <span class="rating-score small"><?= number_format($tutor['avg_rating'], 1) ?></span>
                                    <span class="text-muted small">(<?= $tutor['review_count'] ?>)</span>
                                </div>
                                <div>
                                    <?php if (!empty($tutor['is_free'])): ?>
                                        <span class="badge badge-free">Gratis</span>
                                    <?php else: ?>
                                        <span class="badge badge-paid"><?= format_rupiah($tutor['hourly_rate']) ?>/sesi</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Action -->
                            <a href="<?= url('/tutors/' . $tutor['id']) ?>" class="btn btn-outline-primary btn-sm w-100 fw-semibold rounded-3 py-2">
                                Lihat Profil & Ajukan Sesi
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- POPULAR SUBJECTS SECTION -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center max-w-2xl mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill mb-2">Materi Akademik</span>
            <h2 class="fw-bold text-dark">Mata Kuliah Paling Sering Dicari</h2>
            <p class="text-secondary">Pilih mata kuliah yang ingin Anda pelajari dan temukan teman belajar yang siap mendampingi.</p>
        </div>

        <div class="row g-3">
            <?php foreach ($popularSubjects as $subject): ?>
                <div class="col-md-6 col-lg-4">
                    <a href="<?= url('/tutors?subject_id=' . $subject['id']) ?>" class="text-decoration-none">
                        <div class="tb-card p-3 h-100 d-flex align-items-center justify-content-between bg-white">
                            <div>
                                <span class="badge bg-light text-primary border small mb-1"><?= e($subject['code']) ?> • Smt <?= $subject['semester'] ?></span>
                                <h6 class="fw-bold text-dark mb-1"><?= e($subject['name']) ?></h6>
                                <small class="text-muted"><?= e($subject['program_name']) ?></small>
                            </div>
                            <div class="text-end ps-2">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1">
                                    <?= (int) $subject['tutor_count'] ?> Tutor
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- TESTIMONIALS (FROM AUTHENTIC DATABASE REVIEWS) -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="text-center max-w-2xl mx-auto mb-5">
            <span class="badge bg-success-subtle text-success fw-semibold px-3 py-1 rounded-pill mb-2">Ulasan Mahasiswa</span>
            <h2 class="fw-bold text-dark">Apa Kata Rekan Mahasiswa?</h2>
            <p class="text-secondary">Pengalaman nyata mahasiswa yang telah merasakan manfaat belajar bersama tutor sebaya.</p>
        </div>

        <div class="row g-4">
            <?php if (empty($testimonials)): ?>
                <div class="col-12 text-center text-muted">
                    <p>Belum ada ulasan yang dipublikasikan.</p>
                </div>
            <?php else: ?>
                <?php foreach ($testimonials as $t): ?>
                    <div class="col-md-4">
                        <div class="tb-card p-4 h-100 bg-light d-flex flex-column">
                            <div class="d-flex align-items-center gap-1 mb-3 text-warning">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star-<?= $i <= $t['rating'] ? 'fill' : 'light' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="text-secondary small fst-italic flex-grow-1 mb-4">
                                "<?= e($t['comment']) ?>"
                            </p>
                            <div class="d-flex align-items-center gap-3 pt-2 border-top">
                                <div class="avatar-placeholder" style="width: 40px; height: 40px; font-size: 1rem;">
                                    <?= strtoupper(substr($t['student_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark small"><?= e($t['student_name']) ?></div>
                                    <div class="text-muted small">Belajar <strong><?= e($t['subject_name']) ?></strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CALL TO ACTION BANNER -->
<section class="py-5" style="background: linear-gradient(135deg, var(--tb-primary) 0%, var(--tb-secondary) 100%);">
    <div class="container py-4 text-center text-white">
        <h2 class="display-6 fw-bold mb-3 text-white">Siap Meningkatkan Nilai Akademikmu?</h2>
        <p class="lead mb-4 text-white-50 max-w-2xl mx-auto">
            Jangan biarkan kesulitan memahami mata kuliah menghambat impianmu. Cari teman belajar sekarang atau bagikan ilmumu sebagai tutor!
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?= url('/tutors') ?>" class="btn btn-light text-primary btn-lg fw-bold px-4 shadow">
                <i class="bi bi-search me-1"></i> Mulai Cari Tutor
            </a>
            <?php if (!auth()): ?>
                <a href="<?= url('/register') ?>" class="btn btn-outline-light btn-lg fw-bold px-4">
                    <i class="bi bi-person-plus me-1"></i> Gabung TemanBelajar
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
