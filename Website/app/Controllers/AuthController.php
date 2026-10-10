<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Flash;
use App\Helpers\Sanitizer;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StudyProgram;
use App\Models\ActivityLog;

class AuthController
{
    public function showLogin(): void
    {
        view('auth/login', ['title' => 'Masuk — TemanBelajar']);
    }

    public function processLogin(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        set_old(['email' => $email]);

        if (empty($email) || empty($password)) {
            Flash::error('Harap masukkan alamat email dan kata sandi.');
            redirect('/login');
            return;
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Flash::error('Email atau kata sandi yang Anda masukkan salah.');
            redirect('/login');
            return;
        }

        if (empty($user['is_active'])) {
            Flash::error('Akun Anda dinonaktifkan oleh administrator. Silakan hubungi bagian akademik.');
            redirect('/login');
            return;
        }

        // Login with session regeneration
        Auth::login($user);
        clear_old();

        ActivityLog::log($user['id'], 'user_login', 'Pengguna berhasil masuk ke sistem.');

        Flash::success("Selamat datang kembali, {$user['full_name']}!");

        $returnUrl = $_SESSION['return_url'] ?? null;
        unset($_SESSION['return_url']);

        if ($returnUrl && !str_contains($returnUrl, '/login') && !str_contains($returnUrl, '/register')) {
            redirect($returnUrl);
            return;
        }

        if ($user['role'] === 'admin') {
            redirect('/admin/dashboard');
        } else {
            redirect('/student/dashboard');
        }
    }

    public function showRegister(): void
    {
        $programs = StudyProgram::all();
        view('auth/register', [
            'title' => 'Daftar Akun Mahasiswa — TemanBelajar',
            'programs' => $programs
        ]);
    }

    public function processRegister(): void
    {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $nim = trim($_POST['nim'] ?? '');
        $studyProgramId = (int) ($_POST['study_program_id'] ?? 0);
        $graduationYear = (int) ($_POST['graduation_year'] ?? 0);
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirmation'] ?? '';

        set_old([
            'full_name' => $fullName,
            'email' => $email,
            'nim' => $nim,
            'study_program_id' => $studyProgramId,
            'graduation_year' => $graduationYear,
            'phone' => $phone
        ]);

        // Validation
        if (empty($fullName) || empty($email) || empty($nim) || empty($password) || empty($graduationYear)) {
            Flash::error('Harap lengkapi semua kolom yang wajib diisi.');
            redirect('/register');
            return;
        }

        if (!Sanitizer::isValidEmail($email)) {
            Flash::error('Format alamat email tidak valid.');
            redirect('/register');
            return;
        }

        if (strlen($password) < 6) {
            Flash::error('Kata sandi minimal harus terdiri dari 6 karakter.');
            redirect('/register');
            return;
        }

        if ($password !== $passwordConfirm) {
            Flash::error('Konfirmasi kata sandi tidak cocok.');
            redirect('/register');
            return;
        }

        if (User::findByEmail($email)) {
            Flash::error('Alamat email sudah terdaftar. Silakan gunakan email lain atau masuk.');
            redirect('/register');
            return;
        }

        if (User::findByNim($nim)) {
            Flash::error('NIM mahasiswa sudah terdaftar di sistem TemanBelajar.');
            redirect('/register');
            return;
        }

        // Create user
        $userId = User::create([
            'full_name' => $fullName,
            'email' => $email,
            'password' => $password,
            'nim' => $nim,
            'study_program_id' => $studyProgramId ?: null,
            'graduation_year' => $graduationYear,
            'phone' => $phone,
            'role' => 'student',
            'is_tutor' => 0
        ]);

        // Create initial student profile
        StudentProfile::updateOrCreate($userId, [
            'bio' => 'Mahasiswa aktif bersemangat belajar bersama rekan sejawat.',
            'interest_topics' => ''
        ]);

        ActivityLog::log($userId, 'user_registered', 'Pendaftaran akun mahasiswa baru.');

        $newUser = User::findById($userId);
        Auth::login($newUser);
        clear_old();

        Flash::success('Pendaftaran akun berhasil! Selamat datang di TemanBelajar.');
        redirect('/student/dashboard');
    }

    public function logout(): void
    {
        $userId = Auth::id();
        if ($userId) {
            ActivityLog::log($userId, 'user_logout', 'Pengguna keluar dari sistem.');
        }

        Auth::logout();
        Flash::info('Anda telah berhasil keluar.');
        redirect('/login');
    }

    public function showProfile(): void
    {
        $user = Auth::user();
        $studentProfile = StudentProfile::findByUserId($user['id']);
        $programs = StudyProgram::all();

        view('auth/profile', [
            'title' => 'Pengaturan Profil Saya — TemanBelajar',
            'user' => $user,
            'studentProfile' => $studentProfile,
            'programs' => $programs
        ]);
    }

    public function updateProfile(): void
    {
        $userId = Auth::id();
        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $studyProgramId = (int) ($_POST['study_program_id'] ?? 0);
        $graduationYear = (int) ($_POST['graduation_year'] ?? 0);
        $bio = trim($_POST['bio'] ?? '');
        $interestTopics = trim($_POST['interest_topics'] ?? '');

        if (empty($fullName) || empty($graduationYear)) {
            Flash::error('Nama lengkap dan tahun angkatan wajib diisi.');
            redirect('/profile');
            return;
        }

        $updateData = [
            'full_name' => $fullName,
            'phone' => $phone,
            'study_program_id' => $studyProgramId ?: null,
            'graduation_year' => $graduationYear
        ];

        // Handle Avatar Upload securely
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $error = Sanitizer::validateUpload($_FILES['avatar'], ['image/jpeg', 'image/png', 'image/webp'], 2097152);
            if ($error) {
                Flash::error($error);
                redirect('/profile');
                return;
            }

            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/avatars';
            $fileName = Sanitizer::storeUpload($_FILES['avatar'], $uploadDir, 'avatar_' . $userId);
            if ($fileName) {
                $updateData['avatar'] = $fileName;
            }
        }

        User::updateProfile($userId, $updateData);
        StudentProfile::updateOrCreate($userId, [
            'bio' => $bio,
            'interest_topics' => $interestTopics
        ]);

        Auth::refresh();
        ActivityLog::log($userId, 'profile_updated', 'Pengguna memperbarui profil pribadinya.');

        Flash::success('Profil Anda berhasil diperbarui!');
        redirect('/profile');
    }

    public function showChangePassword(): void
    {
        view('auth/change-password', ['title' => 'Ganti Kata Sandi — TemanBelajar']);
    }

    public function updatePassword(): void
    {
        $userId = Auth::id();
        $user = Auth::user();

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $newPasswordConfirm = $_POST['new_password_confirmation'] ?? '';

        if (!password_verify($currentPassword, $user['password'])) {
            Flash::error('Kata sandi saat ini tidak cocok.');
            redirect('/profile/change-password');
            return;
        }

        if (strlen($newPassword) < 6) {
            Flash::error('Kata sandi baru minimal harus 6 karakter.');
            redirect('/profile/change-password');
            return;
        }

        if ($newPassword !== $newPasswordConfirm) {
            Flash::error('Konfirmasi kata sandi baru tidak sesuai.');
            redirect('/profile/change-password');
            return;
        }

        User::changePassword($userId, $newPassword);
        ActivityLog::log($userId, 'password_changed', 'Pengguna mengganti kata sandi.');

        Flash::success('Kata sandi Anda berhasil diperbarui!');
        redirect('/profile');
    }
}
