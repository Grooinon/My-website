<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill mb-2">Tanya & Jawab</span>
                <h2 class="display-6 fw-bold text-dark mb-3">Pertanyaan yang Sering Diajukan</h2>
                <p class="lead text-secondary max-w-2xl mx-auto">
                    Temukan jawaban atas pertanyaan umum seputar layanan bimbingan sebaya di TemanBelajar.
                </p>
            </div>

            <div class="accordion tb-card bg-white p-3 p-md-4 shadow-sm" id="faqAccordion">
                <!-- Q1 -->
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header" id="h1">
                        <button class="accordion-button rounded-3 fw-bold text-dark shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c1">
                            Apakah saya perlu membuat akun terpisah untuk menjadi tutor?
                        </button>
                    </h2>
                    <div id="c1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small pt-1">
                            <strong>Tidak.</strong> Satu akun mahasiswa dapat bertindak sebagai pencari tutor sekaligus menjadi tutor sebaya. Anda cukup melengkapi profil tutor di menu "Jadi Tutor" untuk mengaktifkan mode mengajar.
                        </div>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header" id="h2">
                        <button class="accordion-button rounded-3 fw-bold text-dark shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c2">
                            Apakah bimbingan belajar ini dipungut biaya?
                        </button>
                    </h2>
                    <div id="c2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small pt-1">
                            Sebagian besar tutor sebaya menyediakan bimbingan secara <strong>Gratis (Sukarela)</strong> untuk membantu sesama rekan mahasiswa. Jika tutor menetapkan tarif ringan, tarif tersebut ditampilkan secara transparan di profil tutor.
                        </div>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header" id="h3">
                        <button class="accordion-button rounded-3 fw-bold text-dark shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c3">
                            Bagaimana sistem mencegah jadwal bimbingan yang bentrok?
                        </button>
                    </h2>
                    <div id="c3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small pt-1">
                            Sistem TemanBelajar memiliki algoritma validasi di backend yang memeriksa apakah tutor telah memiliki sesi lain yang disetujui pada rentang waktu yang sama. Jika terdapat tumpang-tindih (overlap), pengajuan atau persetujuan sesi otomatis dicegah.
                        </div>
                    </div>
                </div>

                <!-- Q4 -->
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header" id="h4">
                        <button class="accordion-button rounded-3 fw-bold text-dark shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c4">
                            Siapa saja yang berhak memberikan ulasan dan rating kepada tutor?
                        </button>
                    </h2>
                    <div id="c4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small pt-1">
                            Hanya mahasiswa yang telah memesan sesi dan sesi tersebut telah <strong>diselesaikan (status: completed)</strong> yang dapat memberikan satu kali ulasan dan rating bintang (1-5). Pengguna lain tidak dapat memberikan ulasan fiktif.
                        </div>
                    </div>
                </div>

                <!-- Q5 -->
                <div class="accordion-item border-0">
                    <h2 class="accordion-header" id="h5">
                        <button class="accordion-button rounded-3 fw-bold text-dark shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c5">
                            Bagaimana jika saya berhalangan hadir pada sesi bimbingan?
                        </button>
                    </h2>
                    <div id="c5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small pt-1">
                            Mahasiswa pemohon dapat membatalkan sesi sebelum waktu pelaksanaan melalui menu <em>Dashboard Belajar &gt; Sesi Belajar Saya</em>. Tutor juga akan menerima notifikasi pembatalan secara otomatis.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Still Have Question Box -->
            <div class="tb-card p-4 bg-light text-center mt-5 border">
                <h5 class="fw-bold text-dark mb-1">Masih memiliki pertanyaan lain?</h5>
                <p class="text-secondary small mb-3">Tim dukungan TemanBelajar siap membantu Anda.</p>
                <a href="<?= url('/contact') ?>" class="btn btn-tb-primary btn-sm px-4">
                    <i class="bi bi-envelope me-1"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</div>
