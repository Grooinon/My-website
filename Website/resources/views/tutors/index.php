<div class="container py-4">
    <!-- Breadcrumb & Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="<?= url('/') ?>">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cari Tutor Sebaya</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-0">Cari Tutor Sebaya</h3>
                <p class="text-secondary small mb-0">Temukan teman belajar mahasiswa terverifikasi untuk membantu materi perkuliahan Anda.</p>
            </div>
            <div class="small text-muted">
                Ditemukan <strong class="text-primary"><?= (int) $totalTutors ?></strong> tutor yang sesuai
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Filter Sidebar -->
        <div class="col-lg-3">
            <div class="tb-card p-4 bg-white sticky-top" style="top: 80px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-funnel text-primary me-1"></i> Filter Pencarian
                    </h6>
                    <a href="<?= url('/tutors') ?>" class="text-decoration-none small text-muted">Reset</a>
                </div>

                <form action="<?= url('/tutors') ?>" method="GET" class="d-flex flex-column gap-3">
                    <!-- Keyword Search -->
                    <div>
                        <label class="form-label small fw-semibold text-secondary">Kata Kunci</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" class="form-control border-start-0 ps-0" value="<?= e($filters['search']) ?>" placeholder="Nama / Matkul...">
                        </div>
                    </div>

                    <!-- Program Studi -->
                    <div>
                        <label class="form-label small fw-semibold text-secondary">Program Studi</label>
                        <select name="program_id" class="form-select form-select-sm">
                            <option value="">Semua Program Studi</option>
                            <?php foreach ($studyPrograms as $prog): ?>
                                <option value="<?= $prog['id'] ?>" <?= ($filters['program_id'] == $prog['id']) ? 'selected' : '' ?>>
                                    <?= e($prog['name']) ?> (<?= e($prog['code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Mata Kuliah -->
                    <div>
                        <label class="form-label small fw-semibold text-secondary">Mata Kuliah Spesifik</label>
                        <select name="subject_id" class="form-select form-select-sm">
                            <option value="">Semua Mata Kuliah</option>
                            <?php foreach ($studySubjects as $sub): ?>
                                <option value="<?= $sub['id'] ?>" <?= ($filters['subject_id'] == $sub['id']) ? 'selected' : '' ?>>
                                    <?= e($sub['name']) ?> (<?= e($sub['code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Metode Belajar -->
                    <div>
                        <label class="form-label small fw-semibold text-secondary">Metode Belajar</label>
                        <select name="method" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['method'] === 'all') ? 'selected' : '' ?>>Semua Metode</option>
                            <option value="online" <?= ($filters['method'] === 'online') ? 'selected' : '' ?>>Online (Daring)</option>
                            <option value="offline" <?= ($filters['method'] === 'offline') ? 'selected' : '' ?>>Tatap Muka (Luring)</option>
                        </select>
                    </div>

                    <!-- Tarif Sesi -->
                    <div>
                        <label class="form-label small fw-semibold text-secondary">Tarif Sesi</label>
                        <select name="price_type" class="form-select form-select-sm">
                            <option value="" <?= ($filters['price_type'] === '') ? 'selected' : '' ?>>Semua Tarif</option>
                            <option value="free" <?= ($filters['price_type'] === 'free') ? 'selected' : '' ?>>Gratis (Sukarela)</option>
                            <option value="paid" <?= ($filters['price_type'] === 'paid') ? 'selected' : '' ?>>Berbayar Ringan</option>
                        </select>
                    </div>

                    <!-- Minimal Rating -->
                    <div>
                        <label class="form-label small fw-semibold text-secondary">Rating Minimal</label>
                        <select name="min_rating" class="form-select form-select-sm">
                            <option value="" <?= empty($filters['min_rating']) ? 'selected' : '' ?>>Semua Rating</option>
                            <option value="4" <?= ($filters['min_rating'] == 4) ? 'selected' : '' ?>>⭐ 4.0 Bintang ke atas</option>
                            <option value="4.5" <?= ($filters['min_rating'] == 4.5) ? 'selected' : '' ?>>⭐ 4.5 Bintang ke atas</option>
                        </select>
                    </div>

                    <!-- Ketersediaan -->
                    <div>
                        <label class="form-label small fw-semibold text-secondary">Status Ketersediaan</label>
                        <select name="available" class="form-select form-select-sm">
                            <option value="" <?= ($filters['available'] === '') ? 'selected' : '' ?>>Semua Status</option>
                            <option value="1" <?= ($filters['available'] === 1) ? 'selected' : '' ?>>Siap Menerima Sesi</option>
                        </select>
                    </div>

                    <!-- Pengurutan -->
                    <div>
                        <label class="form-label small fw-semibold text-secondary">Urutkan Berdasarkan</label>
                        <select name="sort" class="form-select form-select-sm">
                            <option value="rating_desc" <?= ($filters['sort'] === 'rating_desc') ? 'selected' : '' ?>>Rating Tertinggi</option>
                            <option value="name_asc" <?= ($filters['sort'] === 'name_asc') ? 'selected' : '' ?>>Nama (A - Z)</option>
                            <option value="price_asc" <?= ($filters['sort'] === 'price_asc') ? 'selected' : '' ?>>Biaya Terendah</option>
                            <option value="newest" <?= ($filters['sort'] === 'newest') ? 'selected' : '' ?>>Tutor Terbaru</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-tb-primary w-100 py-2 mt-2 fw-semibold">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Tutors Grid -->
        <div class="col-lg-9">
            <?php if (empty($tutors)): ?>
                <!-- Empty State -->
                <div class="tb-card p-5 bg-white tb-empty-state">
                    <div class="tb-empty-state-icon mx-auto">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Tutor Tidak Ditemukan</h5>
                    <p class="text-secondary small max-w-md mx-auto mb-4">
                        Tidak ada tutor yang sesuai dengan kriteria pencarian atau filter yang Anda terapkan. Silakan coba kata kunci lain atau reset filter.
                    </p>
                    <a href="<?= url('/tutors') ?>" class="btn btn-tb-primary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Semua Filter
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($tutors as $tutor): ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="tb-card p-3 h-100 d-flex flex-column bg-white">
                                <!-- Card Header -->
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <?php if (!empty($tutor['avatar']) && file_exists(dirname(__DIR__, 3) . '/public/uploads/avatars/' . $tutor['avatar'])): ?>
                                        <img src="<?= url('uploads/avatars/' . e($tutor['avatar'])) ?>" alt="<?= e($tutor['full_name']) ?>" class="avatar-img" width="54" height="54">
                                    <?php else: ?>
                                        <div class="avatar-placeholder" style="width: 54px; height: 54px; font-size: 1.25rem;">
                                            <?= strtoupper(substr($tutor['full_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="overflow-hidden">
                                        <h6 class="fw-bold mb-0 text-dark text-truncate" title="<?= e($tutor['full_name']) ?>"><?= e($tutor['full_name']) ?></h6>
                                        <small class="text-muted d-block text-truncate"><?= e($tutor['program_name'] ?? 'Mahasiswa') ?> '<?= substr((string)$tutor['graduation_year'], 2) ?></small>
                                        <span class="badge badge-verified mt-1">
                                            <i class="bi bi-patch-check-fill"></i> Terverifikasi
                                        </span>
                                    </div>
                                </div>

                                <!-- Headline -->
                                <p class="small text-secondary mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 40px;">
                                    <?= e($tutor['headline'] ?: 'Tutor sebaya antusias membantu pemahaman akademik perkuliahan.') ?>
                                </p>

                                <!-- Subjects Badges -->
                                <div class="mb-3 d-flex flex-wrap gap-1">
                                    <?php 
                                    $cnt = 0;
                                    foreach ($tutor['subjects'] as $sub): 
                                        if ($cnt >= 2) break;
                                        $cnt++;
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
                                    Lihat Profil Lengkap <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-4 pt-2">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= url('/tutors?' . http_build_query(array_merge($_GET, ['page' => $currentPage - 1]))) ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                <li class="page-item <?= ($p === $currentPage) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= url('/tutors?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>">
                                        <?= $p ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= url('/tutors?' . http_build_query(array_merge($_GET, ['page' => $currentPage + 1]))) ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
