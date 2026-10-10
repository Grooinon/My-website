<?php

namespace App\Controllers;

use App\Helpers\Flash;

class PageController
{
    public function about(): void
    {
        view('pages/about', ['title' => 'Tentang TemanBelajar — Platform Tutor Sebaya Mahasiswa']);
    }

    public function howItWorks(): void
    {
        view('pages/how-it-works', ['title' => 'Cara Kerja Platform — TemanBelajar']);
    }

    public function faq(): void
    {
        view('pages/faq', ['title' => 'Pertanyaan Umum (FAQ) — TemanBelajar']);
    }

    public function contact(): void
    {
        view('pages/contact', ['title' => 'Hubungi Kami & Pusat Bantuan — TemanBelajar']);
    }

    public function submitContact(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            Flash::error('Harap lengkapi semua kolom formulir kontak.');
            redirect('/contact');
            return;
        }

        Flash::success('Pesan dan masukan Anda telah kami terima! Tim TemanBelajar akan segera meninjau.');
        redirect('/contact');
    }

    public function terms(): void
    {
        view('pages/terms', ['title' => 'Syarat dan Ketentuan — TemanBelajar']);
    }

    public function privacy(): void
    {
        view('pages/privacy', ['title' => 'Kebijakan Privasi — TemanBelajar']);
    }
}
