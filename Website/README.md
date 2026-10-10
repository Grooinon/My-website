# TemanBelajar — Platform Tutor Sebaya Mahasiswa

> **"Belajar Bersama, Berkembang Bersama."**

TemanBelajar adalah platform web *full-stack* berbasis arsitektur **PHP Native MVC** yang mempertemukan mahasiswa yang membutuhkan bimbingan materi perkuliahan dengan mahasiswa berprestasi yang bersedia menjadi tutor sebaya.

Aplikasi ini dibangun khusus sebagai proyek akademik Sistem Informasi yang dirancang bersih, aman, mudah dipelajari, dan siap dijalankan secara lokal menggunakan **XAMPP (Apache & MySQL)** maupun server bawaan PHP.

---

## 1. Latar Belakang & Masalah

Di lingkungan perguruan tinggi, mahasiswa sering mengalami kesulitan memahami konsep mata kuliah tertentu (seperti Algoritma, Pemrograman Web, Basis Data, Struktur Data, atau Matematika Diskrit). Di sisi lain:
- Mahasiswa sering merasa canggung atau sungkan bertanya langsung secara terus-menerus kepada dosen pengampu.
- Tidak semua mahasiswa mengetahui siapa rekan sejawat atau kakak tingkat yang menguasai mata kuliah tersebut dan bersedia mengajar.
- Mahasiswa berprestasi yang ingin mengasah keterampilan komunikasi, pedagogi, dan portofolio bimbingan belum memiliki media yang terstruktur.

**TemanBelajar** hadir sebagai platform kampus yang menjembatani kebutuhan tersebut secara aman, terverifikasi, dan transparan.

---

## 2. Fitur Utama Aplikasi

### A. Mahasiswa Pemelajar
- **Pencarian & Filter Canggih**: Telusuri tutor berdasarkan nama, mata kuliah spesifik, program studi, metode (online/offline), tarif gratis/berbayar, dan rating minimal.
- **Pemesanan Sesi Belajar**: Ajukan sesi bimbingan dengan memilih mata kuliah, tanggal, jam mulai, durasi (60-120 menit), serta topik kesulitan.
- **Pencegahan Bentrok Jadwal Backend**: Sistem secara ketat memeriksa apakah tutor telah memiliki jadwal bimbingan lain yang disetujui pada waktu yang sama.
- **Dashboard Mahasiswa**: Pantau status pengajuan sesi (*pending*, *accepted*, *completed*, *rejected*, *cancelled*), jadwal mendatang, dan notifikasi.
- **Ulasan & Rating Bintang**: Berikan rating 1–5 dan ulasan objektif setelah sesi diselesaikan oleh tutor.

### B. Tutor Sebaya (Satu Akun Fleksibel)
- **Aktivasi Mode Tutor Mandiri**: Satu akun mahasiswa dapat belajar sekaligus mengaktifkan mode tutor tanpa perlu membuat dua akun terpisah.
- **Kelola Mata Kuliah Ajar**: Tambahkan mata kuliah kurikulum yang dikuasai beserta nilai akademik (A, A-, B+) dan catatan kompetensi.
- **Jadwal Ketersediaan Rutin**: Atur slot hari dan jam rutin ketersediaan bimbingan.
- **Tarif Fleksibel**: Pilihan bimbingan sukarela (*Gratis*) sebagai opsi utama mahasiswa atau penetapan tarif ringan transparan.
- **Manajemen Sesi**: Setujui atau tolak permintaan sesi dengan alasan, serta tandai sesi sebagai selesai (*completed*).

### C. Administrator Akademik
- **Statistik Platform Real-Time**: Total mahasiswa, tutor aktif, mata kuliah, sesi tuntas, dan tingkat kepuasan dihitung langsung dari basis data.
- **Manajemen Pengguna**: Pantau daftar pengguna, cari berdasarkan NIM/email, dan aktifkan/nonaktifkan akun.
- **CRUD Kurikulum**: Kelola master data Program Studi dan Mata Kuliah kampus.
- **Moderasi Ulasan**: Kontrol visibilitas ulasan publik untuk menjaga etika akademik.
- **Audit Trail (Log Aktivitas)**: Rekam jejak seluruh aktivitas login, pemesanan, approval, dan aksi admin.
- **CLI Admin Generator**: Perintah aman CLI untuk membuat akun admin tanpa menyimpan password dalam kode sumber.

### D. Keamanan & Kualitas Kode
- **PDO Prepared Statements**: 100% kueri database terlindungi dari serangan SQL Injection.
- **Enkripsi Kata Sandi**: `password_hash()` menggunakan algoritma BCRYPT dan diverifikasi dengan `password_verify()`.
- **Perlindungan CSRF**: Token kriptografi di setiap formulir POST untuk mencegah *Cross-Site Request Forgery*.
- **Pencegahan XSS**: Sanitasi dan escaping karakter khusus menggunakan `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- **Session Security**: Session cookie dengan flag `HttpOnly`, `SameSite=Lax`, dan regenerasi Session ID otomatis saat login.

---

## 3. Teknologi yang Digunakan

| Komponen | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Frontend** | HTML5, CSS3, JavaScript | Desain modern, responsif, card membulat, transisi halus |
| **Framework CSS** | Bootstrap 5.3 | Grid responsif, utilitas tata letak, modal interaktif |
| **Ikon & Tipografi**| Bootstrap Icons & Google Fonts (Inter) | Tampilan bersih, kontras tinggi, dan ramah mahasiswa |
| **Backend** | PHP 8.2+ Native (Arsitektur MVC) | Tanpa dependensi framework rumit, mudah dipahami |
| **Database** | MySQL 8 / MariaDB & SQLite | Kompatibel penuh dengan XAMPP, didukung fallback SQLite |
| **Server Lokal** | XAMPP (Apache) / PHP Built-in Server | Fleksibel untuk berbagai lingkungan pengujian |
| **Version Control** | Git & GitHub | Siap diunggah ke repository publik |

---

## 4. Struktur Folder Project

```
Website/
├── app/
│   ├── Controllers/          # Pengendali alur logika (Auth, Home, Tutor, Session, Admin, dll.)
│   ├── Models/               # Model data relasional dengan PDO prepared statements
│   ├── Middleware/           # Proteksi rute (Auth, Guest, Tutor, Admin, CSRF)
│   ├── Helpers/              # Utilitas (Database, Auth, Csrf, View, Flash, Sanitizer, Env)
│   └── bootstrap.php         # Inisialisasi autoloader PSR-4, session, dan konfigurasi lingkungan
├── bin/
│   └── create_admin.php      # Perintah CLI aman untuk membuat akun administrator pertama
├── config/
│   ├── app.php               # Konfigurasi umum aplikasi
│   └── database.php          # Konfigurasi koneksi MySQL dan SQLite
├── database/
│   ├── temanbelajar_db.sql   # Skrip DDL MySQL 8 lengkap beserta data awal & demo
│   ├── seed.php              # Skrip CLI migration & seeder otomatis (MySQL & SQLite)
│   └── README.md             # Panduan impor basis data
├── public/
│   ├── assets/
│   │   ├── css/style.css     # Design system custom (palet Indigo/Blue, cards, badges)
│   │   └── js/app.js         # Interaksi klien (kalkulator durasi, picker rating, auto-dismiss alert)
│   ├── uploads/avatars/      # Direktori unggahan foto profil aman
│   ├── index.php             # Front controller (titik masuk seluruh permintaan web)
│   └── .htaccess             # Aturan penulisan ulang URL Apache
├── resources/
│   └── views/
│       ├── layouts/          # Layout bersama (header, navbar, footer, main container)
│       ├── home/             # Tampilan landing page interaktif
│       ├── auth/             # Form login, register, edit profil, ganti kata sandi
│       ├── tutors/           # Katalog pencarian tutor dan detail profil bimbingan
│       ├── student/          # Dashboard mahasiswa, riwayat sesi, notifikasi
│       ├── tutor/            # Dashboard tutor, kelola sesi, matkul ajar, slot ketersediaan
│       ├── admin/            # Dashboard admin, kelola user, prodi, matkul, ulasan, log
│       ├── pages/            # Halaman statis (Tentang, Cara Kerja, FAQ, Kontak, Syarat, Privasi)
│       └── errors/           # Halaman kesalahan ramah pengguna (403, 404, 500)
├── routes/
│   ├── Router.php            # Mesin routing URL dengan parameter dinamis
│   └── web.php               # Pendaftaran seluruh rute aplikasi
├── storage/
│   ├── database/             # Lokasi database SQLite lokal
│   └── logs/                 # Log aplikasi
├── tests/
│   ├── run_tests.php         # Rangkaian pengujian otomatis (37 skenario lulus 100%)
│   └── MANUAL_TESTING.md     # Panduan dan checklist pengujian manual UAT
├── .env.example              # Contoh variabel konfigurasi lingkungan
├── .gitignore                # Pengecualian berkas rahasia & temporer dari Git
├── composer.json             # Deklarasi proyek dan pemetaan namespace PSR-4
├── CONTRIBUTING.md           # Panduan kontribusi pengembang
├── CHANGELOG.md              # Riwayat rilis versi
├── LICENSE                   # Lisensi open-source (MIT)
└── README.md                 # Dokumentasi komprehensif proyek
```

---

## 5. Petunjuk Instalasi di Windows Menggunakan XAMPP

### Langkah 1: Kloning atau Salin Berkas Proyek
Pindahkan folder proyek ke dalam direktori `htdocs` XAMPP Anda:
```bash
# Contoh direktori penempatan:
C:\xampp\htdocs\Website
```

### Langkah 2: Buat Database di phpMyAdmin
1. Buka **XAMPP Control Panel**, lalu klik **Start** pada modul **Apache** dan **MySQL**.
2. Buka peramban dan akses: `http://localhost/phpmyadmin`
3. Buat database baru bernama: `temanbelajar_db` (dengan Collation `utf8mb4_unicode_ci`).
4. Klik tab **Import**, pilih file:
   ```
   database/temanbelajar_db.sql
   ```
5. Klik **Go** / **Import** di bagian bawah. Seluruh tabel dan data awal/demo akan terpasang otomatis.

*Alternatif CLI via MySQL Command Line:*
```bash
mysql -u root -p temanbelajar_db < database/temanbelajar_db.sql
```

### Langkah 3: Konfigurasi Berkas `.env`
Salin file `.env.example` menjadi `.env`:
```bash
copy .env.example .env
```
Sesuaikan konfigurasi database Anda di file `.env`:
```env
APP_NAME="TemanBelajar"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/Website/public

# Koneksi Database MySQL (Standar XAMPP)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=temanbelajar_db
DB_USERNAME=root
DB_PASSWORD=
```

> **Tips:** Jika MySQL belum dijalankan dan Anda ingin mencoba aplikasi secara instan dengan *zero-configuration*, Anda cukup mengatur `DB_CONNECTION=sqlite` pada `.env` lalu menjalankan `php database/seed.php`.

### Langkah 4: Akses Aplikasi di Browser
Buka browser Anda dan akses:
```
http://localhost/Website/public/
```
Atau jika menggunakan server pengembangan bawaan PHP:
```bash
php -S 127.0.0.1:8000 -t public
```
Lalu buka: `http://127.0.0.1:8000`

---

## 6. Akun Demo Bawaan

Semua akun demo menggunakan kata sandi yang sama: **`password123`**

| Peran (Role) | Nama Akun | Email Login | Password | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | Admin Akademik TB | `admin@temanbelajar.ac.id` | `password123` | Akses penuh dashboard admin (`/admin/dashboard`) |
| **Tutor 1** | Bintang Pratama | `bintang@temanbelajar.ac.id` | `password123` | Mahasiswa prodi SI, tutor Web & Struktur Data (Gratis) |
| **Tutor 2** | Nadia Salsabila | `nadia@temanbelajar.ac.id` | `password123` | Mahasiswa prodi SI, tutor Basis Data & Analisis (Gratis) |
| **Tutor 3** | Rian Hidayat | `rian@temanbelajar.ac.id` | `password123` | Mahasiswa prodi TI, tutor Jaringan & AI (Tarif Rp25.000) |
| **Mahasiswa 1**| Dina Wahyuni | `dina@temanbelajar.ac.id` | `password123` | Mahasiswa pemohon sesi Web I (sudah selesai & diulas) |
| **Mahasiswa 2**| Fajar Nugroho | `fajar@temanbelajar.ac.id` | `password123` | Mahasiswa pemohon sesi Struktur Data (sudah diulas) |
| **Mahasiswa 3**| Siti Rahma | `siti@temanbelajar.ac.id` | `password123` | Mahasiswa pemohon sesi Basis Data (sudah diulas) |
| **Mahasiswa 4**| Arif Wibowo | `arif@temanbelajar.ac.id` | `password123` | Mahasiswa baru, sesi aktif terjadwal |
| **Mahasiswa 5**| Mega Putri | `mega@temanbelajar.ac.id` | `password123` | Mahasiswa baru, pengajuan sesi pending |

---

## 7. Cara Membuat Akun Admin Pertama Melalui Perintah CLI

Untuk menjaga keamanan agar kredensial administrator tidak bocor dalam kode repository, sistem menyediakan alat pembuat admin berbasis CLI interaktif:

```bash
# Mode Interaktif (Sistem akan meminta input nama, email, password, dan NIM):
php bin/create_admin.php

# Atau Mode Argumen Langsung:
php bin/create_admin.php --name="Kepala Lab SI" --email="admin.lab@univ.ac.id" --password="PasswordKuatAnda123" --nim="ADM002"
```

Akun yang dibuat akan langsung memiliki hak akses administrator penuh di rute `/admin/dashboard`.

---

## 8. Menjalankan Rangkaian Pengujian Terotomatisasi

Aplikasi dilengkapi dengan skrip pengujian otomatis yang menguji seluruh aturan bisnis penting tanpa memerlukan pustaka eksternal yang rumit:

```bash
php tests/run_tests.php
```

### Cakupan Pengujian (37 Assertion — 100% Lulus):
1. Registrasi mahasiswa & pencegahan duplikasi email/NIM.
2. Otentikasi & verifikasi hash sandi BCRYPT.
3. Aktivasi mode tutor & pengelolaan mata kuliah ajar.
4. Pencarian katalog & multi-filter tutor.
5. Pemesanan sesi bimbingan belajar.
6. **Deteksi Bentrok Jadwal**: Pengujian bentrok waktu persis sama, bentrok tumpang-tindih parsial, serta lolos waktu non-bentrok.
7. Persetujuan dan penolakan sesi dengan alasan.
8. Pembatalan sesi oleh mahasiswa.
9. Penyelesaian sesi bimbingan oleh tutor.
10. Validasi pengiriman ulasan hanya untuk sesi selesai & pencegahan ulasan ganda.
11. Pengujian token keamanan CSRF.
12. Sanitasi input & pencegahan serangan XSS.
13. Pembatasan akses berbasis peran (*Role-based access barrier*).
14. Perhitungan statistik dinamis platform dari database.

Untuk pengujian antarmuka dan penerimaan pengguna (UAT), silakan membaca panduan:
[`tests/MANUAL_TESTING.md`](tests/MANUAL_TESTING.md).

---

## 9. Batasan Versi Saat Ini & Rencana Pengembangan

### Batasan Versi 1.0.0:
- Pembayaran untuk tutor yang menetapkan tarif ringan dilakukan secara langsung di luar platform (*peer-to-peer* tunai/transfer mahasiswa), platform tidak mengintegrasikan *payment gateway* berbayar demi menjaga kemudahan instalasi perkuliahan.
- Notifikasi menggunakan sistem internal berbasis basis data kampus (belum menyertakan push notification peramban berbayar).

### Rencana Pengembangan Mendatang (Roadmap):
- [ ] Integrasi Single Sign-On (SSO) akun kampus berbasis OAuth2 / Google Workspace Kampus.
- [ ] Fitur ruang obrolan langsung (*live chat*) antar-mahasiswa sebelum sesi bimbingan dimulai.
- [ ] Fitur berbagi materi dokumen (PDF/slide rangkuman materi) dalam sesi bimbingan.
- [ ] Export rekapitulasi jam bimbingan tutor ke format PDF/Excel untuk keperluan angka kredit keaktifan mahasiswa.

---

## 10. Lisensi & Kontribusi

Proyek ini dilisensikan di bawah [Lisensi MIT](LICENSE). Bebas digunakan, dipelajari, dan dikembangkan kembali untuk keperluan akademik perguruan tinggi.

Silakan membaca [CONTRIBUTING.md](CONTRIBUTING.md) untuk panduan berpartisipasi dalam pengembangan platform TemanBelajar.
