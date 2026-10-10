<?php

/**
 * TemanBelajar - Automated Test Runner
 * Comprehensive Automated Tests covering 14 Core Flows
 */

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Helpers\Database;
use App\Helpers\Csrf;
use App\Helpers\Sanitizer;
use App\Models\User;
use App\Models\TutorProfile;
use App\Models\StudySession;
use App\Models\StudySubject;
use App\Models\Review;

echo "====================================================================\n";
echo " TemanBelajar — Automated Test Suite Execution\n";
echo "====================================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertTest(string $description, bool $condition): void {
    global $passCount, $failCount;
    if ($condition) {
        echo " [PASS] {$description}\n";
        $passCount++;
    } else {
        echo " [FAIL] {$description}\n";
        $failCount++;
    }
}

// --------------------------------------------------------------------
// TEST 1: Registrasi Mahasiswa & Validasi Unik
// --------------------------------------------------------------------
echo "--- 1. Testing Registrasi Mahasiswa ---\n";
$testEmail = 'mhs_test_' . time() . '@univ.ac.id';
$testNim = 'NIM_' . substr((string) microtime(true), -8);

$testUserId = User::create([
    'full_name' => 'Budi Santoso Test',
    'email' => $testEmail,
    'password' => 'secret123',
    'nim' => $testNim,
    'study_program_id' => 1,
    'graduation_year' => 2023,
    'phone' => '081299990000',
    'role' => 'student',
    'is_tutor' => 0
]);
assertTest("Registrasi akun mahasiswa baru berhasil (ID: {$testUserId})", $testUserId > 0);

$createdUser = User::findById($testUserId);
assertTest("Password tersimpan dengan enkripsi bcrypt yang valid", password_verify('secret123', $createdUser['password']));
assertTest("Email tersimpan dengan benar", $createdUser['email'] === $testEmail);

// Duplicate check
$dupUser = User::findByEmail($testEmail);
assertTest("Pengecekan duplikasi email berhasil mendeteksi akun", $dupUser !== null);

// --------------------------------------------------------------------
// TEST 2: Otentikasi & Verifikasi Kata Sandi
// --------------------------------------------------------------------
echo "\n--- 2. Testing Login & Verifikasi Password ---\n";
$loginSuccess = password_verify('secret123', $createdUser['password']);
$loginWrong = password_verify('wrongpassword', $createdUser['password']);
assertTest("Verifikasi kata sandi benar berhasil", $loginSuccess === true);
assertTest("Verifikasi kata sandi salah berhasil ditolak", $loginWrong === false);

// --------------------------------------------------------------------
// TEST 3: Pembuatan & Pembaruan Profil Tutor
// --------------------------------------------------------------------
echo "\n--- 3. Testing Pembuatan Profil Tutor ---\n";
$tutorProfileId = TutorProfile::updateOrCreate($testUserId, [
    'headline' => 'Tutor Pemrograman Web Modern',
    'bio' => 'Senang berbagi ilmu tentang arsitektur MVC PHP dan Bootstrap.',
    'academic_achievements' => 'IPK 3.90',
    'teaching_experience' => 'Asisten Lab',
    'preferred_method' => 'both',
    'hourly_rate' => 0,
    'is_free' => 1,
    'is_available' => 1,
]);
assertTest("Profil tutor berhasil dibuat (Profile ID: {$tutorProfileId})", $tutorProfileId > 0);

$refreshedUser = User::findById($testUserId);
assertTest("Flag is_tutor pada user otomatis diperbarui menjadi aktif", (int) $refreshedUser['is_tutor'] === 1);

// Add subject
$subAdded = TutorProfile::addSubject($tutorProfileId, 1, 'A', 'Membimbing logika pemrograman');
assertTest("Penambahan mata kuliah ajar tutor berhasil", $subAdded === true);
$tutorSubjects = TutorProfile::getSubjects($tutorProfileId);
assertTest("Daftar mata kuliah tutor terisi dengan benar", count($tutorSubjects) >= 1);

// --------------------------------------------------------------------
// TEST 4: Pencarian & Filter Tutor
// --------------------------------------------------------------------
echo "\n--- 4. Testing Pencarian & Filter Tutor ---\n";
$filterResults = TutorProfile::getAllWithFilters(['search' => 'Budi Santoso']);
assertTest("Pencarian tutor berdasarkan nama berhasil menemukan hasil", count($filterResults) >= 1);

$filterBySubject = TutorProfile::getAllWithFilters(['subject_id' => 1]);
assertTest("Filter tutor berdasarkan mata kuliah berhasil", count($filterBySubject) >= 1);

$filterFree = TutorProfile::getAllWithFilters(['price_type' => 'free']);
assertTest("Filter tutor bertarif gratis berhasil", count($filterFree) >= 1);

// --------------------------------------------------------------------
// TEST 5 & 6: Pengajuan Sesi & Pencegahan Jadwal Bentrok (Collision Detection)
// --------------------------------------------------------------------
echo "\n--- 5 & 6. Testing Pengajuan Sesi & Deteksi Tabrakan Jadwal (Collision) ---\n";
$targetTutorId = 2; // Bintang Pratama
$studentId = $testUserId;
$testDate = date('Y-m-d', strtotime('+7 days'));
$startTime1 = '14:00:00';
$endTime1   = '15:30:00';

// Sesi 1: Disetujui
$sessionId1 = StudySession::create([
    'student_id' => $studentId,
    'tutor_id' => $targetTutorId,
    'study_subject_id' => 1,
    'session_date' => $testDate,
    'start_time' => $startTime1,
    'end_time' => $endTime1,
    'duration_minutes' => 90,
    'learning_method' => 'online',
    'location_or_link' => 'https://meet.google.com/test-1',
    'notes' => 'Sesi tes 1'
]);
assertTest("Pengajuan sesi belajar 1 berhasil (ID: {$sessionId1})", $sessionId1 > 0);

// Setujui sesi 1
StudySession::updateStatus($sessionId1, 'accepted');
$ses1 = StudySession::findById($sessionId1);
assertTest("Sesi 1 berhasil disetujui (Status: accepted)", $ses1['status'] === 'accepted');

// Sekarang tes bentrok jadwal:
// Skenario A: Waktu persis sama (14:00 - 15:30) -> HARUS BENTROK
$collisionA = StudySession::hasCollision($targetTutorId, $testDate, '14:00:00', '15:30:00');
assertTest("Deteksi bentrok: Waktu persis sama berhasil dideteksi bentrok", $collisionA === true);

// Skenario B: Waktu tumpang tindih parsial (14:30 - 16:00) -> HARUS BENTROK
$collisionB = StudySession::hasCollision($targetTutorId, $testDate, '14:30:00', '16:00:00');
assertTest("Deteksi bentrok: Tumpang tindih sebagian berhasil dideteksi bentrok", $collisionB === true);

// Skenario C: Waktu sebelum sesi pertama (12:00 - 13:30) -> TIDAK BENTROK
$collisionC = StudySession::hasCollision($targetTutorId, $testDate, '12:00:00', '13:30:00');
assertTest("Deteksi bentrok: Waktu di luar sesi (sebelum) lolos tanpa tabrakan", $collisionC === false);

// Skenario D: Waktu setelah sesi pertama (16:00 - 17:30) -> TIDAK BENTROK
$collisionD = StudySession::hasCollision($targetTutorId, $testDate, '16:00:00', '17:30:00');
assertTest("Deteksi bentrok: Waktu di luar sesi (sesudah) lolos tanpa tabrakan", $collisionD === false);

// --------------------------------------------------------------------
// TEST 7: Persetujuan & Penolakan Sesi
// --------------------------------------------------------------------
echo "\n--- 7. Testing Penolakan Sesi ---\n";
$sessionId2 = StudySession::create([
    'student_id' => $studentId,
    'tutor_id' => $targetTutorId,
    'study_subject_id' => 1,
    'session_date' => $testDate,
    'start_time' => '10:00:00',
    'end_time' => '11:00:00',
    'duration_minutes' => 60,
    'learning_method' => 'online',
    'notes' => 'Sesi tes 2'
]);
StudySession::updateStatus($sessionId2, 'rejected', 'Ada ujian di jam tersebut');
$ses2 = StudySession::findById($sessionId2);
assertTest("Penolakan sesi belajar dengan alasan berhasil disimpan", $ses2['status'] === 'rejected' && !empty($ses2['rejection_reason']));

// --------------------------------------------------------------------
// TEST 8: Pembatalan Sesi oleh Mahasiswa
// --------------------------------------------------------------------
echo "\n--- 8. Testing Pembatalan Sesi ---\n";
$sessionId3 = StudySession::create([
    'student_id' => $studentId,
    'tutor_id' => $targetTutorId,
    'study_subject_id' => 1,
    'session_date' => $testDate,
    'start_time' => '08:00:00',
    'end_time' => '09:00:00',
    'duration_minutes' => 60,
    'learning_method' => 'online',
    'notes' => 'Sesi tes 3'
]);
StudySession::updateStatus($sessionId3, 'cancelled');
$ses3 = StudySession::findById($sessionId3);
assertTest("Pembatalan sesi belajar berhasil (Status: cancelled)", $ses3['status'] === 'cancelled');

// --------------------------------------------------------------------
// TEST 9: Penyelesaian Sesi Belajar
// --------------------------------------------------------------------
echo "\n--- 9. Testing Penyelesaian Sesi Belajar ---\n";
StudySession::updateStatus($sessionId1, 'completed');
$ses1Completed = StudySession::findById($sessionId1);
assertTest("Sesi belajar berhasil ditandai selesai (Status: completed)", $ses1Completed['status'] === 'completed');

// --------------------------------------------------------------------
// TEST 10: Pemberian Ulasan & Pencegahan Ulasan Ganda
// --------------------------------------------------------------------
echo "\n--- 10. Testing Pemberian Ulasan & Rating ---\n";
$checkCanReview = Review::canUserReview($sessionId1, $studentId);
assertTest("Validasi hak mengulas untuk peserta sesi selesai berhasil", $checkCanReview['allowed'] === true);

$unauthorizedCheck = Review::canUserReview($sessionId1, 999999);
assertTest("Validasi hak mengulas untuk orang asing berhasil ditolak", $unauthorizedCheck['allowed'] === false);

$reviewId = Review::create([
    'study_session_id' => $sessionId1,
    'student_id' => $studentId,
    'tutor_id' => $targetTutorId,
    'rating' => 5,
    'comment' => 'Penjelasan sangat sistematis dan mudah dipahami dalam tes otomatis!'
]);
assertTest("Ulasan rating 5 bintang berhasil disimpan (Review ID: {$reviewId})", $reviewId > 0);

// Cegah ulasan ganda
$duplicateReviewCheck = Review::canUserReview($sessionId1, $studentId);
assertTest("Pencegahan ulasan ganda pada sesi yang sama berhasil", $duplicateReviewCheck['allowed'] === false);

// --------------------------------------------------------------------
// TEST 11: Validasi CSRF Token
// --------------------------------------------------------------------
echo "\n--- 11. Testing Keamanan CSRF ---\n";
$token = Csrf::generateToken();
assertTest("Token CSRF berhasil dibuat (Panjang: 64 karakter heksadesimal)", strlen($token) === 64);
assertTest("Validasi token CSRF asli berhasil lolos", Csrf::validate($token) === true);
assertTest("Validasi token CSRF palsu berhasil ditolak", Csrf::validate('fake_csrf_token_12345') === false);

// --------------------------------------------------------------------
// TEST 12: Sanitasi Input & Pencegahan XSS
// --------------------------------------------------------------------
echo "\n--- 12. Testing Sanitasi Input & Escaping XSS ---\n";
$maliciousInput = '<script>alert("hack")</script><b>Nama Mahasiswa</b>';
$escaped = e($maliciousInput);
assertTest("Fungsi e() berhasil mengamankan tag HTML script berbahaya", !str_contains($escaped, '<script>') && str_contains($escaped, '&lt;script&gt;'));

$cleaned = Sanitizer::clean($maliciousInput);
assertTest("Sanitizer::clean berhasil membuang tag berbahaya", !str_contains($cleaned, '<script>') && !str_contains($cleaned, '<b>'));

// --------------------------------------------------------------------
// TEST 13: Otorisasi & Perizinan Admin
// --------------------------------------------------------------------
echo "\n--- 13. Testing Pembatasan Role Admin ---\n";
$adminUser = User::findById(1);
assertTest("Akun admin memiliki role 'admin'", $adminUser['role'] === 'admin');
assertTest("Akun mahasiswa memiliki role 'student'", $createdUser['role'] === 'student');

// --------------------------------------------------------------------
// TEST 14: Perhitungan Statistik Dinamis Database
// --------------------------------------------------------------------
echo "\n--- 14. Testing Perhitungan Statistik Platform ---\n";
$stats = StudySession::getPlatformStats();
assertTest("Statistik total mahasiswa terhitung (> 0)", $stats['total_students'] > 0);
assertTest("Statistik total tutor aktif terhitung (> 0)", $stats['total_tutors'] > 0);
assertTest("Statistik total sesi selesai terhitung (> 0)", $stats['total_sessions_completed'] > 0);
assertTest("Statistik rating kepuasan valid (> 0)", $stats['avg_satisfaction'] > 0);

// --------------------------------------------------------------------
// SUMMARY
// --------------------------------------------------------------------
echo "\n====================================================================\n";
echo " HASIL AKHIR PENGUJIAN OTOMATIS:\n";
echo " Total Pengujian : " . ($passCount + $failCount) . "\n";
echo " Lulus (PASS)    : {$passCount}\n";
echo " Gagal (FAIL)    : {$failCount}\n";
echo " Status          : " . ($failCount === 0 ? "SEMUA PENGUJIAN BERHASIL (100% SUKSES)" : "ADA PENGUJIAN YANG GAGAL") . "\n";
echo "====================================================================\n";

exit($failCount === 0 ? 0 : 1);
