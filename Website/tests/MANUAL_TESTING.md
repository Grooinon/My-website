# Panduan & Checklist Pengujian Manual (Manual Testing Checklist)
**TemanBelajar — Platform Tutor Sebaya Mahasiswa**

Dokumen ini berisi panduan skenario pengujian manual langkah-demi-langkah (*User Acceptance Testing*) untuk memvalidasi seluruh fungsionalitas antarmuka dan aturan bisnis sistem.

---

## 1. Skenario Pengujian Registrasi Mahasiswa

| ID | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status |
|:---|:---|:---|:---|:---:|
| TC-01 | Pendaftaran akun baru dengan data valid | Akses `/register`, isi nama, email unik, NIM unik, prodi, angkatan, kata sandi min. 6 karakter. Klik "Daftar". | Pengguna berhasil terdaftar, otomatis login, dialihkan ke `/student/dashboard` dengan pesan sukses. | [PASS] |
| TC-02 | Pendaftaran dengan email duplikat | Akses `/register`, masukkan email yang sudah terdaftar (misal: `dina@temanbelajar.ac.id`). | Form menolak pendaftaran dan menampilkan pesan kesalahan "Alamat email sudah terdaftar". | [PASS] |
| TC-03 | Pendaftaran dengan konfirmasi sandi tidak cocok | Masukkan kata sandi `rahasia123` dan konfirmasi `berbeda123`. | Form menolak dengan pesan "Konfirmasi kata sandi tidak cocok". | [PASS] |
| TC-04 | Keamanan Role Admin pada form registrasi | Periksa formulir publik pendaftaran. | Tidak terdapat opsi untuk mendaftar sebagai Admin. Akun publik selalu default ber-role `student`. | [PASS] |

---

## 2. Skenario Pengujian Login & Logout

| ID | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status |
|:---|:---|:---|:---|:---:|
| TC-05 | Login mahasiswa dengan kredensial benar | Masuk dengan `dina@temanbelajar.ac.id` dan sandi `password123`. | Login berhasil, session ID diregenerasi secara aman, diarahkan ke dashboard mahasiswa. | [PASS] |
| TC-06 | Login dengan kata sandi salah | Masuk dengan email valid namun kata sandi keliru. | Login ditolak dengan pesan kesalahan umum tanpa membocorkan eksistensi akun. | [PASS] |
| TC-07 | Logout sesi pengguna | Klik menu profil di pojok kanan atas, pilih "Keluar". | Seluruh data session dihancurkan, session cookie dihapus, dialihkan ke halaman login. | [PASS] |

---

## 3. Skenario Pengujian Profil & Aktivasi Tutor

| ID | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status |
|:---|:---|:---|:---|:---:|
| TC-08 | Mahasiswa mengaktifkan mode tutor | Dari akun mahasiswa biasa, klik "Jadi Tutor" atau buka `/tutor/profile/edit`. Isi headline, bio, dan simpan. | Profil tutor dibuat, flag `is_tutor` menjadi aktif, tombol "Mode Tutor" muncul di navbar. | [PASS] |
| TC-09 | Menambah mata kuliah yang diampu | Buka menu *Dashboard Tutor &gt; Mata Kuliah Bimbingan*, tambah mata kuliah "Pemrograman Web I" nilai "A". | Mata kuliah tampil di profil publik tutor dan dapat dipilih oleh mahasiswa saat memesan. | [PASS] |
| TC-10 | Mengatur slot hari dan jam ketersediaan | Buka menu *Dashboard Tutor &gt; Jadwal Ketersediaan*, atur slot hari Senin 16:00 - 18:00 WIB. | Slot tersimpan dan muncul di tabel ketersediaan profil tutor. | [PASS] |

---

## 4. Skenario Pengujian Pencarian & Filter Tutor

| ID | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status |
|:---|:---|:---|:---|:---:|
| TC-11 | Pencarian berdasarkan kata kunci mata kuliah | Buka `/tutors`, masukkan kata kunci "Basis Data" di input pencarian. | Menampilkan kartu tutor yang memiliki keahlian Basis Data (Nadia Salsabila). | [PASS] |
| TC-12 | Filter berdasarkan metode online | Pilih filter metode "Online". | Hanya tutor yang mendukung bimbingan online/daring yang ditampilkan. | [PASS] |
| TC-13 | Filter berdasarkan tarif gratis | Pilih filter "Bimbingan Gratis". | Hanya menampilkan tutor bertanda badge hijau "Gratis". | [PASS] |
| TC-14 | Empty state pencarian | Cari kata kunci yang tidak ada (misal: "Xylophone999"). | Tampil halaman kosong (*empty state*) yang bersih dengan tombol "Reset Semua Filter". | [PASS] |

---

## 5. Skenario Pemesanan & Pencegahan Bentrok Jadwal (Schedule Collision)

| ID | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status |
|:---|:---|:---|:---|:---:|
| TC-15 | Pemesanan sesi bimbingan valid | Buka profil Kak Bintang, pilih matkul, tanggal H+3, jam 16:00 (durasi 90 menit). Kirim. | Sesi berstatus `pending` tersimpan di database, tutor menerima notifikasi lonceng internal. | [PASS] |
| TC-16 | Validasi pencegahan tanggal lampau | Coba pilih tanggal kemarin di form pengajuan. | Form membatasi input HTML5 `min` dan backend menolak tanggal di masa lalu. | [PASS] |
| TC-17 | Pencegahan pesan ke diri sendiri | Tutor mencoba memesan sesi pada profilnya sendiri. | Sistem menolak dengan pesan peringatan bahwa pengguna tidak bisa memesan dirinya sendiri. | [PASS] |
| TC-18 | **Deteksi Bentrok Jadwal Backend** | Ajukan sesi pada jam 16:00 - 17:30 pada tanggal yang sama dengan sesi lain yang telah disetujui untuk tutor tersebut. | Backend menolak pengajuan atau persetujuan dengan pesan bahwa tutor telah memiliki jadwal bimbingan lain pada jam tersebut. | [PASS] |

---

## 6. Skenario Siklus Hidup Sesi & Ulasan (Lifecycle)

| ID | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status |
|:---|:---|:---|:---|:---:|
| TC-19 | Tutor menyetujui sesi pending | Login sebagai tutor yang dituju, buka *Kelola Sesi*, klik tombol hijau "Setujui". | Status sesi berubah menjadi `accepted`, mahasiswa menerima notifikasi konfirmasi. | [PASS] |
| TC-20 | Tutor menolak sesi dengan alasan | Klik tombol "Tolak", masukkan alasan "Bentrok praktikum", kirim. | Status menjadi `rejected`, alasan tercatat, mahasiswa menerima notifikasi penolakan. | [PASS] |
| TC-21 | Mahasiswa membatalkan sesi pending | Mahasiswa membuka *Sesi Belajar Saya*, klik "Batalkan". | Status menjadi `cancelled`, tutor menerima notifikasi pembatalan. | [PASS] |
| TC-22 | Tutor menandai sesi selesai | Pada sesi `accepted`, tutor mengklik "Tandai Selesai". | Status menjadi `completed`, mahasiswa menerima notifikasi ajakan mengisi ulasan. | [PASS] |
| TC-23 | Mahasiswa memberikan ulasan bintang & komentar | Pada sesi `completed`, mahasiswa mengklik "Beri Ulasan", pilih 5 bintang, isi komentar, kirim. | Ulasan tersimpan, rating rata-rata tutor dihitung ulang secara dinamis, tombol ulasan terkunci. | [PASS] |
| TC-24 | Pencegahan ulasan ganda / manipulasi | Mahasiswa mencoba mengirim ulasan kedua kali untuk sesi yang sama. | Backend menolak permintaan dan mengembalikan pesan bahwa sesi sudah diulas sebelumnya. | [PASS] |

---

## 7. Skenario Keamanan & Hak Akses Admin

| ID | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status |
|:---|:---|:---|:---|:---:|
| TC-25 | Proteksi halaman admin dari mahasiswa biasa | Login sebagai mahasiswa biasa, coba ketik langsung URL `/admin/dashboard`. | Sistem mencegat dan menampilkan halaman **403 Forbidden (Akses Ditolak)**. | [PASS] |
| TC-26 | Dashboard admin dengan role admin | Login dengan `admin@temanbelajar.ac.id`, buka `/admin/dashboard`. | Akses diberikan, menampilkan statistik platform riil, log aktivitas, dan tabel monitoring. | [PASS] |
| TC-27 | Moderasi visibilitas ulasan oleh admin | Buka `/admin/reviews`, klik "Sembunyikan" pada salah satu ulasan. | Status visibilitas berubah menjadi tersembunyi dan tidak lagi muncul di profil publik tutor. | [PASS] |
| TC-28 | Nonaktifkan akun pengguna | Buka `/admin/users`, nonaktifkan salah satu akun mahasiswa. | Akun tidak dapat melakukan login lagi dan akan menerima pesan bahwa akun dinonaktifkan. | [PASS] |
| TC-29 | Perlindungan CSRF Token | Coba kirim request POST tanpa menyertakan `_csrf_token` yang sah. | Middleware menolak dengan error CSRF dan permintaan dibatalkan. | [PASS] |
