<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Flash;
use App\Models\StudySession;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\StudySubject;
use App\Models\TutorProfile;

class SessionController
{
    public function book(): void
    {
        if (!Auth::check()) {
            Flash::error('Silakan masuk terlebih dahulu untuk mengajukan sesi belajar.');
            redirect('/login');
            return;
        }

        $studentId = Auth::id();
        $tutorId = (int) ($_POST['tutor_id'] ?? 0);
        $subjectId = (int) ($_POST['study_subject_id'] ?? 0);
        $sessionDate = trim($_POST['session_date'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $durationMinutes = (int) ($_POST['duration_minutes'] ?? 60);
        $learningMethod = trim($_POST['learning_method'] ?? 'online');
        $locationOrLink = trim($_POST['location_or_link'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validation
        if ($studentId === $tutorId) {
            Flash::error('Anda tidak dapat mengajukan sesi belajar kepada diri Anda sendiri.');
            redirect('/tutors');
            return;
        }

        $tutor = User::findById($tutorId);
        if (!$tutor || empty($tutor['is_tutor'])) {
            Flash::error('Tutor yang dipilih tidak valid.');
            redirect('/tutors');
            return;
        }

        $subject = StudySubject::findById($subjectId);
        if (!$subject) {
            Flash::error('Mata kuliah yang dipilih tidak valid.');
            redirect("/tutors/{$tutorId}");
            return;
        }

        if (empty($sessionDate) || empty($startTime) || $durationMinutes <= 0) {
            Flash::error('Harap lengkapi tanggal belajar, jam mulai, dan durasi sesi.');
            redirect("/tutors/{$tutorId}");
            return;
        }

        // Validate date is not in the past
        $today = date('Y-m-d');
        if ($sessionDate < $today) {
            Flash::error('Tanggal sesi belajar tidak boleh berada di masa lalu.');
            redirect("/tutors/{$tutorId}");
            return;
        }

        // If today, ensure start time has not already passed
        if ($sessionDate === $today && $startTime < date('H:i:s')) {
            Flash::error('Jam mulai sesi belajar sudah terlewat untuk hari ini.');
            redirect("/tutors/{$tutorId}");
            return;
        }

        // Calculate end time
        $startTimestamp = strtotime("{$sessionDate} {$startTime}");
        $endTimestamp = $startTimestamp + ($durationMinutes * 60);
        $endTime = date('H:i:s', $endTimestamp);

        // Schedule collision detection for the tutor!
        if (StudySession::hasCollision($tutorId, $sessionDate, $startTime, $endTime)) {
            Flash::error("Maaf, tutor sudah memiliki jadwal bimbingan lain yang telah disetujui pada jam {$startTime} - {$endTime} tanggal tersebut. Silakan pilih jam atau hari lain.");
            redirect("/tutors/{$tutorId}");
            return;
        }

        // Create study session
        $sessionId = StudySession::create([
            'student_id' => $studentId,
            'tutor_id' => $tutorId,
            'study_subject_id' => $subjectId,
            'session_date' => $sessionDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => $durationMinutes,
            'learning_method' => in_array($learningMethod, ['online', 'offline']) ? $learningMethod : 'online',
            'location_or_link' => $locationOrLink,
            'notes' => $notes,
        ]);

        $session = StudySession::findById($sessionId);
        $student = Auth::user();

        // Send notification to tutor
        Notification::create(
            $tutorId,
            'Permintaan Sesi Belajar Baru',
            "Mahasiswa {$student['full_name']} mengajukan sesi belajar mata kuliah {$subject['name']} pada " . format_date_id($sessionDate) . " jam {$startTime} WIB.",
            '/tutor/sessions',
            'booking'
        );

        ActivityLog::log($studentId, 'session_requested', "Mengajukan sesi {$session['session_code']} kepada tutor {$tutor['full_name']}.");

        Flash::success("Permintaan sesi belajar berhasil diajukan dengan kode [{$session['session_code']}]. Mohon tunggu konfirmasi dari tutor.");
        redirect('/student/sessions');
    }

    public function cancel(int $id): void
    {
        $userId = Auth::id();
        $session = StudySession::findById($id);

        if (!$session) {
            Flash::error('Sesi belajar tidak ditemukan.');
            redirect('/student/sessions');
            return;
        }

        if ((int) $session['student_id'] !== $userId) {
            Flash::error('Anda tidak memiliki izin untuk membatalkan sesi belajar ini.');
            redirect('/student/sessions');
            return;
        }

        if (!in_array($session['status'], ['pending', 'accepted'])) {
            Flash::error('Hanya sesi dengan status pending atau disetujui yang dapat dibatalkan.');
            redirect('/student/sessions');
            return;
        }

        StudySession::updateStatus($id, 'cancelled');

        // Notify tutor
        Notification::create(
            (int) $session['tutor_id'],
            'Sesi Belajar Dibatalkan',
            "Mahasiswa {$session['student_name']} membatalkan sesi belajar {$session['session_code']} ({$session['subject_name']}).",
            '/tutor/sessions',
            'warning'
        );

        ActivityLog::log($userId, 'session_cancelled', "Membatalkan sesi belajar {$session['session_code']}.");

        Flash::success('Sesi belajar berhasil dibatalkan.');
        redirect('/student/sessions');
    }

    public function accept(int $id): void
    {
        $tutorId = Auth::id();
        $session = StudySession::findById($id);

        if (!$session) {
            Flash::error('Sesi belajar tidak ditemukan.');
            redirect('/tutor/sessions');
            return;
        }

        if ((int) $session['tutor_id'] !== $tutorId) {
            Flash::error('Anda bukan tutor yang dituju untuk sesi ini.');
            redirect('/tutor/sessions');
            return;
        }

        if ($session['status'] !== 'pending') {
            Flash::error('Hanya permintaan dengan status pending yang dapat disetujui.');
            redirect('/tutor/sessions');
            return;
        }

        // Crucial: check for conflicting schedule collision before accepting!
        if (StudySession::hasCollision($tutorId, $session['session_date'], $session['start_time'], $session['end_time'], $id)) {
            Flash::error('Gagal menyetujui sesi: Anda sudah memiliki jadwal bimbingan lain yang disetujui pada waktu yang bersamaan.');
            redirect('/tutor/sessions');
            return;
        }

        StudySession::updateStatus($id, 'accepted');

        // Notify student
        Notification::create(
            (int) $session['student_id'],
            'Permintaan Sesi Disetujui!',
            "Tutor {$session['tutor_name']} telah menyetujui jadwal sesi belajar {$session['session_code']} ({$session['subject_name']}) pada " . format_date_id($session['session_date']) . ".",
            '/student/sessions',
            'success'
        );

        ActivityLog::log($tutorId, 'session_accepted', "Menyetujui sesi belajar {$session['session_code']}.");

        Flash::success("Permintaan sesi belajar [{$session['session_code']}] berhasil disetujui!");
        redirect('/tutor/sessions');
    }

    public function reject(int $id): void
    {
        $tutorId = Auth::id();
        $session = StudySession::findById($id);
        $reason = trim($_POST['rejection_reason'] ?? 'Tutor berhalangan pada waktu tersebut.');

        if (!$session) {
            Flash::error('Sesi belajar tidak ditemukan.');
            redirect('/tutor/sessions');
            return;
        }

        if ((int) $session['tutor_id'] !== $tutorId) {
            Flash::error('Anda bukan tutor yang dituju untuk sesi ini.');
            redirect('/tutor/sessions');
            return;
        }

        if ($session['status'] !== 'pending') {
            Flash::error('Hanya permintaan dengan status pending yang dapat ditolak.');
            redirect('/tutor/sessions');
            return;
        }

        StudySession::updateStatus($id, 'rejected', $reason);

        // Notify student
        Notification::create(
            (int) $session['student_id'],
            'Permintaan Sesi Ditolak',
            "Tutor {$session['tutor_name']} belum dapat menerima sesi {$session['session_code']} dengan alasan: {$reason}",
            '/student/sessions',
            'danger'
        );

        ActivityLog::log($tutorId, 'session_rejected', "Menolak sesi {$session['session_code']}.");

        Flash::info("Permintaan sesi belajar [{$session['session_code']}] telah ditolak.");
        redirect('/tutor/sessions');
    }

    public function complete(int $id): void
    {
        $tutorId = Auth::id();
        $session = StudySession::findById($id);

        if (!$session) {
            Flash::error('Sesi belajar tidak ditemukan.');
            redirect('/tutor/sessions');
            return;
        }

        if ((int) $session['tutor_id'] !== $tutorId) {
            Flash::error('Anda bukan tutor untuk sesi ini.');
            redirect('/tutor/sessions');
            return;
        }

        if ($session['status'] !== 'accepted') {
            Flash::error('Hanya sesi yang berstatus disetujui yang dapat ditandai selesai.');
            redirect('/tutor/sessions');
            return;
        }

        StudySession::updateStatus($id, 'completed');

        // Notify student to leave a review!
        Notification::create(
            (int) $session['student_id'],
            'Sesi Belajar Telah Selesai',
            "Sesi belajar {$session['session_code']} bersama tutor {$session['tutor_name']} telah selesai. Silakan berikan ulasan dan rating Anda!",
            '/student/sessions',
            'review'
        );

        ActivityLog::log($tutorId, 'session_completed', "Menandai sesi belajar {$session['session_code']} selesai.");

        Flash::success("Sesi belajar [{$session['session_code']}] berhasil ditandai selesai.");
        redirect('/tutor/sessions');
    }
}
