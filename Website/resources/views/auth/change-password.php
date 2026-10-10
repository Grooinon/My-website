<div class="container py-4">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-3">
            <div class="dashboard-sidebar">
                <div class="d-flex flex-column gap-1">
                    <a href="<?= url('/profile') ?>" class="dashboard-nav-item">
                        <i class="bi bi-person-gear"></i> Edit Profil
                    </a>
                    <a href="<?= url('/profile/change-password') ?>" class="dashboard-nav-item active">
                        <i class="bi bi-shield-lock"></i> Ganti Password
                    </a>
                    <a href="<?= url('/student/dashboard') ?>" class="dashboard-nav-item">
                        <i class="bi bi-speedometer2"></i> Dashboard Mahasiswa
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="tb-card p-4 p-md-5 bg-white">
                <div class="mb-4 pb-2 border-bottom">
                    <h4 class="fw-bold text-dark mb-1">Ganti Kata Sandi</h4>
                    <p class="text-secondary small mb-0">Pastikan menggunakan kombinasi kata sandi yang aman dan sulit ditebak</p>
                </div>

                <form action="<?= url('/profile/change-password') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="current_password" class="form-label small fw-semibold text-secondary">Kata Sandi Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-key"></i></span>
                            <input type="password" class="form-control border-start-0 ps-0" id="current_password" name="current_password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label small fw-semibold text-secondary">Kata Sandi Baru (Min. 6 Karakter)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control border-start-0 ps-0" id="new_password" name="new_password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label small fw-semibold text-secondary">Ulangi Kata Sandi Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control border-start-0 ps-0" id="new_password_confirmation" name="new_password_confirmation" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="<?= url('/profile') ?>" class="btn btn-light border px-3">Batal</a>
                        <button type="submit" class="btn btn-tb-primary px-4 fw-semibold">
                            <i class="bi bi-shield-check me-1"></i> Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
