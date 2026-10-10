# Panduan Kontribusi — TemanBelajar

Terima kasih atas minat Anda untuk berkontribusi pada pengembangan **TemanBelajar — Platform Tutor Sebaya Mahasiswa**!

---

## 1. Kode Etik
Kami menjunjung tinggi integritas akademik dan kerja sama yang saling menghargai. Semua kontributor diharapkan bersikap sopan, inklusif, dan mengedepankan kualitas kode yang rapi serta aman.

## 2. Alur Pengajuan Fitur atau Perbaikan (Workflow)
1. *Fork* repository ini ke akun GitHub Anda.
2. Buat branch baru untuk fitur atau perbaikan Anda:
   ```bash
   git checkout -b feat/nama-fitur-baru
   # atau
   git checkout -b fix/perbaikan-bug
   ```
3. Lakukan perubahan dengan mematuhi standar penamaan PSR-4 dan arsitektur MVC sederhana.
4. Pastikan semua query database menggunakan **PDO Prepared Statements** dan variabel di-escape menggunakan `e()` atau `htmlspecialchars`.
5. Jalankan rangkaian tes otomatis sebelum commit:
   ```bash
   php tests/run_tests.php
   ```
6. Buat commit dengan pesan yang jelas menggunakan konvensi *Conventional Commits*:
   ```bash
   git commit -m "feat: tambahkan fitur filter rating tutor"
   ```
7. Push ke branch Anda dan buka *Pull Request* (PR) dengan deskripsi yang lengkap.

## 3. Aturan Keamanan Penting
- Jangan pernah menyertakan kredensial asli, password, atau file `.env` ke dalam commit.
- Pastikan seluruh rute yang mengubah data (`POST`) dilengkapi dengan verifikasi token CSRF.
- Semua form input harus divalidasi di backend, bukan hanya di sisi peramban (*client*).
