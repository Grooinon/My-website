<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/tutor/dashboard') ?>">Dashboard Tutor</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mata Kuliah Bimbingan</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Kelola Mata Kuliah Bimbingan</h3>
            <p class="text-secondary small mb-0">Pilih mata kuliah yang Anda kuasai dengan baik untuk diajarkan kepada rekan mahasiswa.</p>
        </div>
        <div>
            <button type="button" class="btn btn-tb-primary btn-sm px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Mata Kuliah Ajar
            </button>
        </div>
    </div>

    <!-- Subjects List -->
    <div class="tb-card p-4 bg-white">
        <?php if (empty($tutorSubjects)): ?>
            <div class="tb-empty-state py-5">
                <div class="tb-empty-state-icon mx-auto">
                    <i class="bi bi-book"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Belum Ada Mata Kuliah</h5>
                <p class="text-secondary small mb-3">Anda belum menambahkan mata kuliah ke profil tutor Anda. Mahasiswa tidak dapat menemukan Anda jika belum ada mata kuliah yang diampu.</p>
                <button type="button" class="btn btn-tb-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Mata Kuliah Sekarang
                </button>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($tutorSubjects as $ts): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 rounded-3 bg-light border h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary text-white"><?= e($ts['subject_code']) ?></span>
                                    <span class="badge bg-success-subtle text-success border">Nilai: <?= e($ts['grade']) ?></span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1"><?= e($ts['subject_name']) ?></h6>
                                <small class="text-muted d-block mb-2"><?= e($ts['program_name']) ?> • Semester <?= $ts['semester'] ?></small>
                                <?php if (!empty($ts['notes'])): ?>
                                    <p class="small text-secondary mb-0 fst-italic">"<?= e($ts['notes']) ?>"</p>
                                <?php endif; ?>
                            </div>
                            <div class="mt-3 pt-2 border-top text-end">
                                <form action="<?= url('/tutor/subjects/' . $ts['study_subject_id'] . '/delete') ?>" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini dari daftar bimbingan?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2">
                                        <i class="bi bi-trash3 me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark" id="addSubjectModalLabel">
                    <i class="bi bi-bookmark-plus text-primary me-2"></i>Tambah Mata Kuliah Ajar
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= url('/tutor/subjects') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="study_subject_id" class="form-label small fw-semibold text-secondary">Pilih Mata Kuliah Kurikulum <span class="text-danger">*</span></label>
                        <select class="form-select" id="study_subject_id" name="study_subject_id" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php foreach ($allSubjects as $sub): ?>
                                <option value="<?= $sub['id'] ?>">
                                    [<?= e($sub['code']) ?>] <?= e($sub['name']) ?> (<?= e($sub['program_name']) ?> - Smt <?= $sub['semester'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="grade" class="form-label small fw-semibold text-secondary">Nilai Akademik Anda pada Mata Kuliah Ini <span class="text-danger">*</span></label>
                        <select class="form-select" id="grade" name="grade" required>
                            <option value="A" selected>A (Sangat Baik / Istimewa)</option>
                            <option value="A-">A- (Amat Baik)</option>
                            <option value="B+">B+ (Baik Sekali)</option>
                            <option value="B">B (Baik)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label small fw-semibold text-secondary">Catatan Keahlian / Topik Spesifik yang Dikuasai</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Contoh: Menguasai bagian Normalisasi Database, perancangan ERD, dan kueri JOIN."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tb-primary px-4 fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i> Tambahkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
