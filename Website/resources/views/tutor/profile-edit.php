<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/tutor/dashboard') ?>">Dashboard Tutor</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengaturan Profil Tutor</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Pengaturan Profil & Tarif Tutor</h3>
            <p class="text-secondary small mb-0">Lengkapi informasi profil agar mahasiswa tertarik belajar dan berdiskusi bersama Anda.</p>
        </div>
        <div>
            <a href="<?= url('/tutor/dashboard') ?>" class="btn btn-light border btn-sm px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="tb-card p-4 p-md-5 bg-white">
                <form action="<?= url('/tutor/profile/edit') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <!-- Headline -->
                        <div class="col-md-12">
                            <label for="headline" class="form-label small fw-semibold text-secondary">Headline / Slogan Profil Tutor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="headline" name="headline" value="<?= e($tutorProfile['headline'] ?? '') ?>" placeholder="Contoh: Tutor Pemrograman Web & Basis Data (IPK 3.85)" required>
                            <div class="form-text small">Kalimat singkat yang menarik perhatian mahasiswa pencari tutor.</div>
                        </div>

                        <!-- Bio -->
                        <div class="col-md-12">
                            <label for="bio" class="form-label small fw-semibold text-secondary">Deskripsi Pendekatan Mengajar & Bio <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Ceritakan gaya mengajar Anda, metode pembahasan tugas, keramahan, dan cara Anda membantu rekan mahasiswa..." required><?= e($tutorProfile['bio'] ?? '') ?></textarea>
                        </div>

                        <!-- Academic Achievements -->
                        <div class="col-md-6">
                            <label for="academic_achievements" class="form-label small fw-semibold text-secondary">Prestasi Akademik</label>
                            <textarea class="form-control" id="academic_achievements" name="academic_achievements" rows="3" placeholder="Contoh: Asisten Laboratorium 2023, Finalis Gemastik, Peraih Beasiswa Unggulan"><?= e($tutorProfile['academic_achievements'] ?? '') ?></textarea>
                        </div>

                        <!-- Teaching Experience -->
                        <div class="col-md-6">
                            <label for="teaching_experience" class="form-label small fw-semibold text-secondary">Pengalaman Mengajar / Mentor</label>
                            <textarea class="form-control" id="teaching_experience" name="teaching_experience" rows="3" placeholder="Contoh: Tutor sebaya mata kuliah Algoritma sejak semester 3, mentor kelompok belajar"><?= e($tutorProfile['teaching_experience'] ?? '') ?></textarea>
                        </div>

                        <!-- Preferred Method -->
                        <div class="col-md-6">
                            <label for="preferred_method" class="form-label small fw-semibold text-secondary">Metode Belajar yang Didukung <span class="text-danger">*</span></label>
                            <select class="form-select" id="preferred_method" name="preferred_method" required>
                                <option value="both" <?= (($tutorProfile['preferred_method'] ?? 'both') === 'both') ? 'selected' : '' ?>>Daring & Tatap Muka (Keduanya)</option>
                                <option value="online" <?= (($tutorProfile['preferred_method'] ?? '') === 'online') ? 'selected' : '' ?>>Hanya Online (Google Meet / Zoom)</option>
                                <option value="offline" <?= (($tutorProfile['preferred_method'] ?? '') === 'offline') ? 'selected' : '' ?>>Hanya Tatap Muka (Kampus / Kafe)</option>
                            </select>
                        </div>

                        <!-- Availability Active status -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary d-block">Status Ketersediaan</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" <?= (!isset($tutorProfile['is_available']) || $tutorProfile['is_available'] == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label small fw-semibold text-dark" for="is_available">
                                    Aktif & Siap Menerima Permintaan Bimbingan
                                </label>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="col-md-12 pt-3 border-top">
                            <h6 class="fw-bold text-dark mb-2">Penetapan Biaya Sesi Bimbingan</h6>
                            <p class="small text-muted mb-3">Sebagai platform mahasiswa, kami merekomendasikan opsi bimbingan gratis sebagai pilihan utama.</p>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_free_checkbox" name="is_free" value="1" <?= (!isset($tutorProfile['is_free']) || $tutorProfile['is_free'] == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label small fw-semibold text-dark" for="is_free_checkbox">
                                    Bimbingan Sukarela / Gratis (Rekomendasi untuk Mahasiswa)
                                </label>
                            </div>

                            <div id="hourly_rate_container" class="col-md-6" style="display: none;">
                                <label for="hourly_rate_input" class="form-label small fw-semibold text-secondary">Tarif Sesi Ringan (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted">Rp</span>
                                    <input type="number" class="form-control" id="hourly_rate_input" name="hourly_rate" value="<?= (int) ($tutorProfile['hourly_rate'] ?? 0) ?>" step="5000" min="0" placeholder="25000">
                                </div>
                                <div class="form-text small">Tarif ditampilkan kepada pemesan. Transaksi dilakukan secara langsung tanpa pemotongan platform.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="<?= url('/tutor/dashboard') ?>" class="btn btn-light border px-3">Batal</a>
                        <button type="submit" class="btn btn-tb-primary px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Profil Tutor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
