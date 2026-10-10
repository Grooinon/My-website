<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Flash;
use App\Models\TutorProfile;
use App\Models\StudySession;
use App\Models\StudySubject;
use App\Models\Review;
use App\Models\ActivityLog;

class TutorDashboardController
{
    public function index(): void
    {
        $userId = Auth::id();
        $tutorProfile = TutorProfile::findByUserId($userId);

        if (!$tutorProfile) {
            Flash::info('Silakan lengkapi profil tutor Anda terlebih dahulu.');
            redirect('/tutor/profile/edit');
            return;
        }

        $stats = StudySession::getTutorStats($userId);
        $pendingRequests = StudySession::getForTutor($userId, 'pending', 1, 5);
        $upcomingSessions = StudySession::getForTutor($userId, 'accepted', 1, 5);
        $recentReviews = Review::getForTutor($userId, 5);
        $subjects = TutorProfile::getSubjects((int) $tutorProfile['id']);

        view('tutor/dashboard', [
            'title' => 'Dashboard Tutor Sebaya — TemanBelajar',
            'tutorProfile' => $tutorProfile,
            'stats' => $stats,
            'pendingRequests' => $pendingRequests,
            'upcomingSessions' => $upcomingSessions,
            'recentReviews' => $recentReviews,
            'subjects' => $subjects,
        ]);
    }

    public function sessions(): void
    {
        $userId = Auth::id();
        $status = trim($_GET['status'] ?? 'all');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;

        $sessions = StudySession::getForTutor($userId, $status, $page, $perPage);
        $totalSessions = StudySession::countForTutor($userId, $status);
        $totalPages = ceil($totalSessions / $perPage);

        view('tutor/sessions', [
            'title' => 'Kelola Permintaan & Sesi Belajar — TemanBelajar',
            'sessions' => $sessions,
            'currentStatus' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalSessions' => $totalSessions,
        ]);
    }

    public function subjects(): void
    {
        $userId = Auth::id();
        $tutorProfile = TutorProfile::findByUserId($userId);
        if (!$tutorProfile) {
            redirect('/tutor/profile/edit');
            return;
        }

        $tutorSubjects = TutorProfile::getSubjects((int) $tutorProfile['id']);
        $allSubjects = StudySubject::all();

        view('tutor/subjects', [
            'title' => 'Kelola Mata Kuliah Bimbingan — TemanBelajar',
            'tutorSubjects' => $tutorSubjects,
            'allSubjects' => $allSubjects,
            'tutorProfile' => $tutorProfile,
        ]);
    }

    public function addSubject(): void
    {
        $userId = Auth::id();
        $tutorProfile = TutorProfile::findByUserId($userId);
        if (!$tutorProfile) {
            redirect('/tutor/profile/edit');
            return;
        }

        $subjectId = (int) ($_POST['study_subject_id'] ?? 0);
        $grade = trim($_POST['grade'] ?? 'A');
        $notes = trim($_POST['notes'] ?? '');

        if (!$subjectId) {
            Flash::error('Pilih mata kuliah yang ingin Anda ajarkan.');
            redirect('/tutor/subjects');
            return;
        }

        $success = TutorProfile::addSubject((int) $tutorProfile['id'], $subjectId, $grade, $notes);
        if ($success) {
            ActivityLog::log($userId, 'tutor_subject_added', "Menambahkan mata kuliah ajar ID {$subjectId}.");
            Flash::success('Mata kuliah bimbingan berhasil ditambahkan!');
        } else {
            Flash::warning('Mata kuliah tersebut sudah pernah Anda tambahkan sebelumnya.');
        }

        redirect('/tutor/subjects');
    }

    public function removeSubject(int $subjectId): void
    {
        $userId = Auth::id();
        $tutorProfile = TutorProfile::findByUserId($userId);
        if (!$tutorProfile) {
            redirect('/tutor/profile/edit');
            return;
        }

        TutorProfile::removeSubject((int) $tutorProfile['id'], $subjectId);
        ActivityLog::log($userId, 'tutor_subject_removed', "Menghapus mata kuliah ajar ID {$subjectId}.");

        Flash::success('Mata kuliah berhasil dihapus dari daftar bimbingan Anda.');
        redirect('/tutor/subjects');
    }

    public function availability(): void
    {
        $userId = Auth::id();
        $tutorProfile = TutorProfile::findByUserId($userId);
        if (!$tutorProfile) {
            redirect('/tutor/profile/edit');
            return;
        }

        $availability = TutorProfile::getAvailability((int) $tutorProfile['id']);

        view('tutor/availability', [
            'title' => 'Atur Jadwal Ketersediaan — TemanBelajar',
            'availability' => $availability,
            'tutorProfile' => $tutorProfile,
        ]);
    }

    public function updateAvailability(): void
    {
        $userId = Auth::id();
        $tutorProfile = TutorProfile::findByUserId($userId);
        if (!$tutorProfile) {
            redirect('/tutor/profile/edit');
            return;
        }

        $days = $_POST['days'] ?? [];
        $startTimes = $_POST['start_times'] ?? [];
        $endTimes = $_POST['end_times'] ?? [];

        $slots = [];
        foreach ($days as $i => $day) {
            if (!empty($day) && !empty($startTimes[$i]) && !empty($endTimes[$i])) {
                $slots[] = [
                    'day_of_week' => $day,
                    'start_time' => $startTimes[$i],
                    'end_time' => $endTimes[$i],
                ];
            }
        }

        TutorProfile::setAvailability((int) $tutorProfile['id'], $slots);
        ActivityLog::log($userId, 'tutor_availability_updated', 'Memperbarui jadwal slot ketersediaan bimbingan.');

        Flash::success('Jadwal ketersediaan Anda berhasil disimpan!');
        redirect('/tutor/availability');
    }

    public function profile(): void
    {
        $userId = Auth::id();
        $tutorProfile = TutorProfile::findByUserId($userId);

        view('tutor/profile-edit', [
            'title' => 'Pengaturan Profil Tutor — TemanBelajar',
            'tutorProfile' => $tutorProfile,
        ]);
    }

    public function updateProfile(): void
    {
        $userId = Auth::id();
        $headline = trim($_POST['headline'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $academicAchievements = trim($_POST['academic_achievements'] ?? '');
        $teachingExperience = trim($_POST['teaching_experience'] ?? '');
        $preferredMethod = trim($_POST['preferred_method'] ?? 'both');
        $isFree = isset($_POST['is_free']) ? 1 : 0;
        $hourlyRate = $isFree ? 0 : (float) ($_POST['hourly_rate'] ?? 0);
        $isAvailable = isset($_POST['is_available']) ? 1 : 0;

        if (empty($headline) || empty($bio)) {
            Flash::error('Headline dan deskripsi bio tutor wajib diisi.');
            redirect('/tutor/profile/edit');
            return;
        }

        TutorProfile::updateOrCreate($userId, [
            'headline' => $headline,
            'bio' => $bio,
            'academic_achievements' => $academicAchievements,
            'teaching_experience' => $teachingExperience,
            'preferred_method' => $preferredMethod,
            'hourly_rate' => $hourlyRate,
            'is_free' => $isFree,
            'is_available' => $isAvailable,
        ]);

        ActivityLog::log($userId, 'tutor_profile_updated', 'Memperbarui data profil tutor.');

        Flash::success('Profil tutor Anda berhasil diperbarui!');
        redirect('/tutor/dashboard');
    }
}
