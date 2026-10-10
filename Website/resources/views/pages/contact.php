<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill mb-2">Pusat Kontak</span>
                <h2 class="display-6 fw-bold text-dark mb-3">Hubungi Tim TemanBelajar</h2>
                <p class="lead text-secondary max-w-2xl mx-auto">
                    Kirimkan pertanyaan, kendala teknis, atau saran pengembangan fitur untuk kemajuan platform bimbingan kampus.
                </p>
            </div>

            <div class="row g-4">
                <!-- Contact Info Card -->
                <div class="col-md-5">
                    <div class="tb-card p-4 bg-primary text-white h-100">
                        <h5 class="fw-bold mb-3 text-white">Informasi Akademik</h5>
                        <p class="small text-white-50 mb-4">
                            Platform TemanBelajar dikembangkan sebagai media kolaborasi pembelajaran mahasiswa Program Studi Sistem Informasi.
                        </p>

                        <div class="d-flex flex-column gap-3 small">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-geo-alt-fill fs-5 text-white-50"></i>
                                <div>Gedung Fakultas Ilmu Komputer, Kampus Pusat</div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-envelope-fill fs-5 text-white-50"></i>
                                <div>bantuan@temanbelajar.ac.id</div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-whatsapp fs-5 text-white-50"></i>
                                <div>+62 812-3456-7890 (Layanan Mahasiswa)</div>
                            </div>
                        </div>

                        <hr class="border-white-50 my-4">

                        <div class="small text-white-50">
                            Jam Operasional Layanan:<br>
                            <strong>Senin - Jumat: 08:00 - 17:00 WIB</strong>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-md-7">
                    <div class="tb-card p-4 p-md-5 bg-white">
                        <h5 class="fw-bold text-dark mb-3">Kirim Pesan atau Masukan</h5>
                        <form action="<?= url('/contact') ?>" method="POST">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="contact_name" class="form-label small fw-semibold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="contact_name" name="name" value="<?= e(auth()['full_name'] ?? '') ?>" placeholder="Nama Anda" required>
                            </div>

                            <div class="mb-3">
                                <label for="contact_email" class="form-label small fw-semibold text-secondary">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="contact_email" name="email" value="<?= e(auth()['email'] ?? '') ?>" placeholder="email@mahasiswa.ac.id" required>
                            </div>

                            <div class="mb-3">
                                <label for="contact_message" class="form-label small fw-semibold text-secondary">Pesan / Kendala / Masukan <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="contact_message" name="message" rows="4" placeholder="Tuliskan pesan atau saran Anda secara rinci..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-tb-primary w-100 py-2 fw-semibold">
                                <i class="bi bi-send me-1"></i> Kirim Pesan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
