# Catatan Perubahan (Changelog)

Seluruh perubahan penting pada proyek **TemanBelajar** didokumentasikan dalam berkas ini.

Format berkas ini mengacu pada panduan [Keep a Changelog](https://keepachangelog.com/id/1.0.0/) dan mematuhi [Semantic Versioning](https://semver.org/).

---

## [1.0.0] - 2026-10-09

### Ditambahkan
- **Arsitektur Inti**: Pola MVC PHP native modular dengan front controller (`public/index.php`), dynamic routing, middleware, dan autoloading PSR-4.
- **Koneksi Database Ganda**: Mendukung penuh MySQL 8 (standar instalasi XAMPP) dan SQLite untuk pengujian lokal instan tanpa konfigurasi rumit.
- **Autentikasi & Otorisasi**:
  - Registrasi mahasiswa dengan validasi NIM dan email unik.
  - Login aman dengan `password_hash()` BCRYPT dan verifikasi `password_verify()`.
  - Regenerasi session ID saat login untuk menangkal *session fixation*.
  - Pemisahan hak akses berbasis role (`student`, `tutor`, `admin`).
- **Fitur Tutor Sebaya**:
  - Satu akun mahasiswa dapat mengaktifkan peran tutor tanpa membuat akun baru.
  - Pengelolaan mata kuliah ajar, nilai akademik, dan catatan kompetensi.
  - Pengaturan slot waktu ketersediaan mingguan.
  - Opsi bimbingan gratis atau berbayar ringan.
- **Pencarian & Katalog Tutor**:
  - Pencarian multi-parameter berdasarkan nama, prodi, mata kuliah, metode belajar (online/offline), tarif gratis/berbayar, dan rating minimal.
  - Pengurutan data dan navigasi pagination.
  - Tampilan kondisi kosong (*empty state*) yang intuitif.
- **Pemesanan Sesi & Pencegahan Bentrok Waktu**:
  - Pengajuan sesi belajar dengan kalkulasi durasi otomatis.
  - **Deteksi bentrok jadwal di backend**: mencegah tutor menyetujui dua sesi yang bertabrakan pada tanggal dan rentang jam yang sama.
  - Validasi pencegahan tanggal lampau.
  - Siklus status sesi: `pending` -> `accepted`/`rejected` -> `completed` / `cancelled`.
- **Ulasan & Rating Bintang**:
  - Ulasan hanya dapat diberikan oleh mahasiswa pemesan setelah sesi berstatus `completed`.
  - Proteksi anti-ulasan ganda pada satu sesi.
  - Penghitungan rata-rata rating dinamis dari database.
- **Notifikasi Internal**:
  - Notifikasi berbasis database untuk pengajuan sesi, persetujuan, penolakan, pembatalan, penyelesaian, dan ulasan baru.
  - Penanda notifikasi dibaca dan belum dibaca.
- **Dashboard Administrator**:
  - Statistik platform komprehensif.
  - Pengelolaan pengguna dan toggle aktivasi akun.
  - CRUD master data program studi dan mata kuliah.
  - Moderasi visibilitas ulasan publik.
  - Audit trail dan pencatatan log aktivitas sistem.
- **CLI Utility**:
  - Pembuatan akun admin pertama via perintah CLI aman `php bin/create_admin.php`.
  - Migration & Seeder terotomatisasi `php database/seed.php`.
- **Pengujian Terotomatisasi**:
  - Rangkaian uji otomatis mencakup 37 skenario uji pada 14 alur utama melalui `php tests/run_tests.php`.
