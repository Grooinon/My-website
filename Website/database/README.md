# Dokumentasi Database TemanBelajar (`temanbelajar_db`)

Database ini dirancang untuk platform **TemanBelajar — Platform Tutor Sebaya Mahasiswa** menggunakan MySQL 8 / MariaDB (kompatibel penuh dengan XAMPP), dan didukung fallback SQLite untuk pengujian lokal instan.

---

## 1. Struktur Tabel Relasional

1. `users`: Menyimpan data akun mahasiswa dan admin (role, nim, email, password_hash, status tutor, status aktif).
2. `study_programs`: Data program studi kampus (kode, nama prodi, fakultas).
3. `student_profiles`: Profil mahasiswa pemohon bimbingan (bio, minat belajar).
4. `study_subjects`: Daftar mata kuliah kurikulum akademik perguruan tinggi.
5. `tutor_profiles`: Profil mahasiswa tutor (headline, bio, prestasi, tarif gratis/berbayar, metode online/offline/keduanya, status verifikasi).
6. `tutor_subjects`: Relasi mata kuliah yang diampu oleh masing-masing tutor beserta nilai akademik (grade) dan catatan pengajaran.
7. `tutor_availability`: Slot jadwal rutin ketersediaan tutor (hari, jam mulai, jam selesai).
8. `study_sessions`: Transaksi pengajuan sesi bimbingan belajar dengan status mesin (*pending*, *accepted*, *rejected*, *cancelled*, *completed*), dilengkapi pencegahan tabrakan waktu (*schedule conflict*).
9. `reviews`: Ulasan dan rating bintang (1-5) dari mahasiswa peserta sesi yang telah selesai.
10. `notifications`: Notifikasi internal pengguna berbasis database.
11. `activity_logs`: Audit trail aktivitas sistem (login, pemesanan, approval, moderasi admin).

---

## 2. Cara Impor Database Melalui phpMyAdmin (XAMPP)

1. Pastikan modul **Apache** dan **MySQL** telah aktif di XAMPP Control Panel.
2. Buka browser dan akses `http://localhost/phpmyadmin`.
3. Klik menu **Databases** / **Basis Data**, masukkan nama basis data `temanbelajar_db`, pilih *Collation* `utf8mb4_unicode_ci`, lalu klik **Create**.
4. Klik database `temanbelajar_db` yang baru dibuat di panel sebelah kiri.
5. Pilih tab **Import** di bagian atas.
6. Klik **Choose File** dan pilih file `database/temanbelajar_db.sql`.
7. Klik tombol **Import** (atau **Go**) di bagian bawah halaman.
8. Seluruh 11 tabel dan data awal/demo akan terpasang dengan sukses.

---

## 3. Cara Impor Melalui Command Line (MySQL CLI)

Jalankan perintah berikut di PowerShell atau Command Prompt:

```bash
mysql -u root -p < database/temanbelajar_db.sql
```
*(Tekan Enter saat diminta password jika menggunakan pengaturan standar XAMPP tanpa password).*

---

## 4. Inisialisasi Otomatis via PHP Seeder

Anda juga dapat menginisialisasi atau mereset database secara otomatis menggunakan script PHP yang disediakan:

```bash
php database/seed.php
```

Script ini akan otomatis mendeteksi konfigurasi pada file `.env` (baik MySQL maupun SQLite) dan menginjeksi struktur tabel serta data demo yang realistis.

---

## 5. Akun Demo Bawaan

| Role | Nama Pengguna | Email | Password | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | Admin Akademik TB | `admin@temanbelajar.ac.id` | `password123` | Akses penuh dashboard admin |
| **Tutor** | Bintang Pratama | `bintang@temanbelajar.ac.id` | `password123` | Tutor Web Dev & Struktur Data (Gratis) |
| **Tutor** | Nadia Salsabila | `nadia@temanbelajar.ac.id` | `password123` | Tutor Basis Data & Analisis Sistem (Gratis) |
| **Tutor** | Rian Hidayat | `rian@temanbelajar.ac.id` | `password123` | Tutor Jaringan & AI (Berbayar Rp25.000) |
| **Mahasiswa** | Dina Wahyuni | `dina@temanbelajar.ac.id` | `password123` | Mahasiswa pemohon sesi |
| **Mahasiswa** | Fajar Nugroho | `fajar@temanbelajar.ac.id` | `password123` | Mahasiswa pemohon sesi |
| **Mahasiswa** | Siti Rahma | `siti@temanbelajar.ac.id` | `password123` | Mahasiswa pemohon sesi |
| **Mahasiswa** | Arif Wibowo | `arif@temanbelajar.ac.id` | `password123` | Mahasiswa baru 2024 |
| **Mahasiswa** | Mega Putri | `mega@temanbelajar.ac.id` | `password123` | Mahasiswa baru 2024 |
