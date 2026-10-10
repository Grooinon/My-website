<footer class="footer-tb mt-auto pt-5 pb-4">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="brand-logo-box">
                        <i class="bi bi-mortarboard-fill fs-5"></i>
                    </span>
                    <span class="text-white fs-5 fw-bold">Teman<span class="text-primary">Belajar</span></span>
                </div>
                <p class="small text-secondary mb-3">
                    "Belajar Bersama, Berkembang Bersama."<br>
                    Platform tutor sebaya berbasis kolaborasi akademik mahasiswa kampus untuk mempermudah pemahaman mata kuliah dan meningkatkan prestasi bersama.
                </p>
                <div class="d-flex gap-2">
                    <a href="https://github.com" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle p-2 text-white" title="GitHub Repository">
                        <i class="bi bi-github"></i>
                    </a>
                    <a href="<?= url('/contact') ?>" class="btn btn-sm btn-outline-secondary rounded-circle p-2 text-white" title="Kontak Bantuan">
                        <i class="bi bi-envelope"></i>
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="text-white fw-bold mb-3">Navigasi</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                    <li><a href="<?= url('/') ?>">Beranda</a></li>
                    <li><a href="<?= url('/tutors') ?>">Cari Tutor</a></li>
                    <li><a href="<?= url('/how-it-works') ?>">Cara Kerja</a></li>
                    <li><a href="<?= url('/about') ?>">Tentang Kami</a></li>
                </ul>
            </div>

            <!-- Support Links -->
            <div class="col-lg-3 col-md-6 col-6">
                <h6 class="text-white fw-bold mb-3">Bantuan & Informasi</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                    <li><a href="<?= url('/faq') ?>">Tanya Jawab (FAQ)</a></li>
                    <li><a href="<?= url('/contact') ?>">Hubungi Dukungan</a></li>
                    <li><a href="<?= url('/terms') ?>">Syarat & Ketentuan</a></li>
                    <li><a href="<?= url('/privacy') ?>">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <!-- Academic Info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Informasi Akademik</h6>
                <p class="small text-secondary mb-2">
                    <i class="bi bi-building me-1 text-primary"></i> Program Perkuliahan Sistem Informasi
                </p>
                <p class="small text-secondary mb-2">
                    <i class="bi bi-shield-check me-1 text-success"></i> Standar Keamanan PDO Prepared Statements & Enkripsi Kata Sandi
                </p>
                <span class="badge bg-secondary text-light fw-normal py-1 px-2">Versi 1.0.0 (XAMPP Ready)</span>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small text-secondary">
            <div>
                &copy; <?= date('Y') ?> <strong>TemanBelajar</strong>. Seluruh Hak Cipta Dilindungi.
            </div>
            <div>
                Dibuat untuk ekosistem pembelajaran kolaboratif mahasiswa.
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5.3 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Custom App JS -->
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
