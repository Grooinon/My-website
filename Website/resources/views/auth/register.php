<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7">
            <div class="tb-card p-4 p-md-5 bg-white shadow-sm">
                <!-- Header -->
                <div class="text-center mb-4">
                    <div class="brand-logo-box mx-auto mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-person-plus-fill fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Daftar Akun Mahasiswa</h3>
                    <p class="text-secondary small mb-0">Bergabunglah dalam komunitas belajar sebaya kampus</p>
                </div>

                <!-- Form -->
                <form action="<?= url('/register') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="full_name" class="form-label small fw-semibold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="full_name" name="full_name" value="<?= e(old('full_name')) ?>" placeholder="Contoh: Muhammad Bintang Pratama" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email Kampus <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" value="<?= e(old('email')) ?>" placeholder="nama@mahasiswa.ac.id" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="nim" class="form-label small fw-semibold text-secondary">Nomor Induk Mahasiswa (NIM) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-card-heading"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="nim" name="nim" value="<?= e(old('nim')) ?>" placeholder="Contoh: 22051214001" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="study_program_id" class="form-label small fw-semibold text-secondary">Program Studi <span class="text-danger">*</span></label>
                            <select class="form-select" id="study_program_id" name="study_program_id" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <?php foreach ($programs as $prog): ?>
                                    <option value="<?= $prog['id'] ?>" <?= old('study_program_id') == $prog['id'] ? 'selected' : '' ?>>
                                        <?= e($prog['name']) ?> (<?= e($prog['code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="graduation_year" class="form-label small fw-semibold text-secondary">Tahun Angkatan <span class="text-danger">*</span></label>
                            <select class="form-select" id="graduation_year" name="graduation_year" required>
                                <option value="">-- Pilih Angkatan --</option>
                                <?php for ($year = date('Y'); $year >= date('Y') - 6; $year--): ?>
                                    <option value="<?= $year ?>" <?= old('graduation_year') == $year ? 'selected' : '' ?>><?= $year ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="phone" class="form-label small fw-semibold text-secondary">Nomor WhatsApp / Kontak (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-whatsapp"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="phone" name="phone" value="<?= e(old('phone')) ?>" placeholder="081234567890">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label small fw-semibold text-secondary">Kata Sandi (Min. 6 Karakter) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control border-start-0 ps-0" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mt-3 mb-4">
                        <input class="form-check-input shadow-none" type="checkbox" id="termsCheck" required>
                        <label class="form-check-label small text-secondary" for="termsCheck">
                            Saya menyetujui <a href="<?= url('/terms') ?>" target="_blank" class="text-primary text-decoration-none">Syarat & Ketentuan</a> serta bersedia menjaga integritas akademik.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-tb-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> Buat Akun Mahasiswa
                    </button>
                </form>

                <!-- Footer Link -->
                <div class="text-center mt-4 text-secondary small">
                    Sudah memiliki akun? <a href="<?= url('/login') ?>" class="text-primary fw-semibold text-decoration-none">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
