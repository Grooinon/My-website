<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <div class="tb-card p-5 bg-white shadow-sm">
                <div class="display-1 fw-extrabold text-danger mb-3">403</div>
                <h3 class="fw-bold text-dark mb-2">Akses Ditolak</h3>
                <p class="text-secondary small mb-4">
                    Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini diproteksi untuk hak akses tertentu (seperti Administrator atau Tutor).
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="<?= url('/') ?>" class="btn btn-light border px-3">
                        <i class="bi bi-house me-1"></i> Ke Beranda
                    </a>
                    <a href="<?= url('/student/dashboard') ?>" class="btn btn-tb-primary px-3">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard Mahasiswa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
