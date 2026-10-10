<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Flash;
use App\Models\Review;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\StudySession;

class ReviewController
{
    public function store(): void
    {
        if (!Auth::check()) {
            Flash::error('Silakan masuk terlebih dahulu.');
            redirect('/login');
            return;
        }

        $studentId = Auth::id();
        $sessionId = (int) ($_POST['study_session_id'] ?? 0);
        $rating = (int) ($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');

        // Check review eligibility
        $check = Review::canUserReview($sessionId, $studentId);
        if (!$check['allowed']) {
            Flash::error($check['message']);
            redirect('/student/sessions');
            return;
        }

        if ($rating < 1 || $rating > 5) {
            Flash::error('Rating bintang harus bernilai antara 1 sampai 5.');
            redirect('/student/sessions');
            return;
        }

        if (empty($comment)) {
            Flash::error('Ulasan tidak boleh kosong. Harap tuliskan pengalaman belajar Anda.');
            redirect('/student/sessions');
            return;
        }

        $session = $check['session'];

        // Save review
        Review::create([
            'study_session_id' => $sessionId,
            'student_id' => $studentId,
            'tutor_id' => (int) $session['tutor_id'],
            'rating' => $rating,
            'comment' => $comment,
        ]);

        $student = Auth::user();

        // Notify tutor
        Notification::create(
            (int) $session['tutor_id'],
            'Ulasan Baru Diterima',
            "Mahasiswa {$student['full_name']} memberikan rating bintang {$rating} dan ulasan untuk sesi belajar Anda.",
            '/tutor/dashboard',
            'review'
        );

        ActivityLog::log($studentId, 'review_created', "Mengirim ulasan rating {$rating} untuk sesi belajar ID {$sessionId}.");

        Flash::success('Terima kasih! Ulasan dan rating Anda berhasil disimpan.');
        redirect('/student/sessions');
    }
}
