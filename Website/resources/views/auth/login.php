<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="tb-card p-4 p-md-5 bg-white shadow-sm">
                <!-- Header -->
                <div class="text-center mb-4">
                    <div class="brand-logo-box mx-auto mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-mortarboard-fill fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Masuk ke TemanBelajar</h3>
                    <p class="text-secondary small mb-0">Gunakan akun mahasiswa atau admin untuk melanjutkan</p>
                </div>

                <!-- Form -->
                <form action="<?= url('/login') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email Kampus</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" value="<?= e(old('email')) ?>" placeholder="nama@temanbelajar.ac.id" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label small fw-semibold text-secondary mb-0">Kata Sandi</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-tb-primary w-100 py-2 fw-semibold mt-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                    </button>
                </form>

                <!-- Demo Credentials Helper Accordion -->
                <div class="mt-4 pt-3 border-top">
                    <div class="accordion accordion-flush" id="demoAccordion">
                        <div class="accordion-item border rounded-3 overflow-hidden">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed py-2 px-3 bg-light text-primary small fw-semibold shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne">
                                    <i class="bi bi-key-fill me-2"></i> Akun Demo untuk Pengujian Cepat
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#demoAccordion">
                                <div class="accordion-body small bg-white p-3 text-secondary">
                                    <p class="mb-2"><strong>Kata sandi semua akun demo:</strong> <code>password123</code></p>
                                    <ul class="list-unstyled mb-0 d-flex flex-column gap-1">
                                        <li><strong class="text-dark">Admin:</strong> <code>admin@temanbelajar.ac.id</code></li>
                                        <li><strong class="text-dark">Tutor 1:</strong> <code>bintang@temanbelajar.ac.id</code></li>
                                        <li><strong class="text-dark">Tutor 2:</strong> <code>nadia@temanbelajar.ac.id</code></li>
                                        <li><strong class="text-dark">Mahasiswa 1:</strong> <code>dina@temanbelajar.ac.id</code></li>
                                        <li><strong class="text-dark">Mahasiswa 2:</strong> <code>fajar@temanbelajar.ac.id</code></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Link -->
                <div class="text-center mt-4 text-secondary small">
                    Belum memiliki akun? <a href="<?= url('/register') ?>" class="text-primary fw-semibold text-decoration-none">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>
</div>
