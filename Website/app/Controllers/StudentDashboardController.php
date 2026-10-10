<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Models\StudySession;
use App\Models\Notification;
use App\Models\Database;

class StudentDashboardController
{
    public function index(): void
    {
        $userId = Auth::id();
        $user = Auth::user();

        // 1. Overview stats
        $stats = StudySession::getStudentStats($userId);

        // 2. Upcoming confirmed sessions
        $upcomingSessions = StudySession::getForStudent($userId, 'accepted', 1, 5);

        // 3. Pending requests
        $pendingSessions = StudySession::getForStudent($userId, 'pending', 1, 5);

        // 4. Completed sessions needing review
        $completedSessions = StudySession::getForStudent($userId, 'completed', 1, 5);

        // 5. Recent notifications
        $notifications = Notification::getForUser($userId, 5);

        view('student/dashboard', [
            'title' => 'Dashboard Mahasiswa — TemanBelajar',
            'user' => $user,
            'stats' => $stats,
            'upcomingSessions' => $upcomingSessions,
            'pendingSessions' => $pendingSessions,
            'completedSessions' => $completedSessions,
            'notifications' => $notifications,
        ]);
    }

    public function sessions(): void
    {
        $userId = Auth::id();
        $status = trim($_GET['status'] ?? 'all');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;

        $sessions = StudySession::getForStudent($userId, $status, $page, $perPage);
        $totalSessions = StudySession::countForStudent($userId, $status);
        $totalPages = ceil($totalSessions / $perPage);

        view('student/sessions', [
            'title' => 'Daftar Sesi Belajar Saya — TemanBelajar',
            'sessions' => $sessions,
            'currentStatus' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalSessions' => $totalSessions,
        ]);
    }
}
