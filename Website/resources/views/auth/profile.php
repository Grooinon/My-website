<div class="container py-4">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            <div class="dashboard-sidebar">
                <div class="text-center pb-3 mb-3 border-bottom">
                    <?php if (!empty($user['avatar']) && file_exists(dirname(__DIR__, 3) . '/public/uploads/avatars/' . $user['avatar'])): ?>
                        <img src="<?= url('uploads/avatars/' . e($user['avatar'])) ?>" alt="Avatar" class="avatar-img mb-2" width="80" height="80">
                    <?php else: ?>
                        <div class="avatar-placeholder mx-auto mb-2" style="width: 80px; height: 80px; font-size: 2rem;">
                            <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <h6 class="fw-bold text-dark mb-0"><?= e($user['full_name']) ?></h6>
                    <small class="text-muted d-block"><?= e($user['email']) ?></small>
                    <span class="badge bg-primary-subtle text-primary border mt-1">NIM: <?= e($user['nim']) ?></span>
                </div>

                <div class="d-flex flex-column gap-1">
                    <a href="<?= url('/profile') ?>" class="dashboard-nav-item active">
                        <i class="bi bi-person-gear"></i> Edit Profil
                    </a>
                    <a href="<?= url('/profile/change-password') ?>" class="dashboard-nav-item">
                        <i class="bi bi-shield-lock"></i> Ganti Password
                    </a>
                    <a href="<?= url('/student/dashboard') ?>" class="dashboard-nav-item">
                        <i class="bi bi-speedometer2"></i> Dashboard Mahasiswa
                    </a>
                    <?php if (!empty($user['is_tutor'])): ?>
                        <a href="<?= url('/tutor/dashboard') ?>" class="dashboard-nav-item">
                            <i class="bi bi-mortarboard"></i> Dashboard Tutor
                        </a>
                    <?php else: ?>
                        <a href="<?= url('/tutor/profile/edit') ?>" class="dashboard-nav-item">
                            <i class="bi bi-plus-circle"></i> Daftar Jadi Tutor
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Main Form Area -->
        <div class="col-lg-9">
            <div class="tb-card p-4 p-md-5 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Pengaturan Profil Pengguna</h4>
                        <p class="text-secondary small mb-0">Perbarui identitas, kontak, dan informasi akademik Anda</p>
                    </div>
                </div>

                <form action="<?= url('/profile') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label small fw-semibold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= e($user['full_name']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email_static" class="form-label small fw-semibold text-secondary">Alamat Email (Akun)</label>
                            <input type="email" class="form-control bg-light" id="email_static" value="<?= e($user['email']) ?>" readonly disabled>
                            <div class="form-text small">Email kampus terikat pada identitas akun mahasiswa.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="nim_static" class="form-label small fw-semibold text-secondary">Nomor Induk Mahasiswa (NIM)</label>
                            <input type="text" class="form-control bg-light" id="nim_static" value="<?= e($user['nim']) ?>" readonly disabled>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label small fw-semibold text-secondary">Nomor WhatsApp / HP</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= e($user['phone'] ?? '') ?>" placeholder="081234567890">
                        </div>

                        <div class="col-md-6">
                            <label for="study_program_id" class="form-label small fw-semibold text-secondary">Program Studi</label>
                            <select class="form-select" id="study_program_id" name="study_program_id">
                                <option value="">-- Pilih Program Studi --</option>
                                <?php foreach ($programs as $prog): ?>
                                    <option value="<?= $prog['id'] ?>" <?= ($user['study_program_id'] == $prog['id']) ? 'selected' : '' ?>>
                                        <?= e($prog['name']) ?> (<?= e($prog['code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="graduation_year" class="form-label small fw-semibold text-secondary">Tahun Angkatan</label>
                            <select class="form-select" id="graduation_year" name="graduation_year">
                                <?php for ($year = date('Y'); $year >= date('Y') - 7; $year--): ?>
                                    <option value="<?= $year ?>" <?= ($user['graduation_year'] == $year) ? 'selected' : '' ?>><?= $year ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="avatar" class="form-label small fw-semibold text-secondary">Foto Profil (JPG, PNG, WEBP, Maks. 2MB)</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp">
                        </div>

                        <div class="col-md-12">
                            <label for="bio" class="form-label small fw-semibold text-secondary">Biografi Singkat</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Ceritakan ketertarikan Anda dalam perkuliahan..."><?= e($studentProfile['bio'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-12">
                            <label for="interest_topics" class="form-label small fw-semibold text-secondary">Topik yang Ingin Dipelajari (Pisahkan dengan koma)</label>
                            <input type="text" class="form-control" id="interest_topics" name="interest_topics" value="<?= e($studentProfile['interest_topics'] ?? '') ?>" placeholder="Contoh: Pemrograman Web, Machine Learning, Aljabar Linear">
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                        <button type="submit" class="btn btn-tb-primary px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
