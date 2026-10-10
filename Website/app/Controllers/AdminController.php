<?php

namespace App\Controllers;

use App\Helpers\Flash;
use App\Models\User;
use App\Models\StudyProgram;
use App\Models\StudySubject;
use App\Models\StudySession;
use App\Models\Review;
use App\Models\ActivityLog;

class AdminController
{
    public function index(): void
    {
        $stats = StudySession::getPlatformStats();
        $stats['total_users'] = User::countAll();
        $stats['total_sessions_all'] = StudySession::countAllAdmin();
        $latestLogs = ActivityLog::getLatest(1, 10);
        $recentSessions = StudySession::getAllAdmin([], 1, 6);

        view('admin/dashboard', [
            'title' => 'Dashboard Administrator — TemanBelajar',
            'stats' => $stats,
            'latestLogs' => $latestLogs,
            'recentSessions' => $recentSessions,
        ]);
    }

    public function users(): void
    {
        $search = trim($_GET['search'] ?? '');
        $role = trim($_GET['role'] ?? '');
        $isTutor = $_GET['is_tutor'] ?? '';
        $isActive = $_GET['is_active'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;

        $filters = [
            'search' => $search,
            'role' => $role,
            'is_tutor' => $isTutor,
            'is_active' => $isActive,
        ];

        $users = User::getAll($filters, $page, $perPage);
        $totalUsers = User::countAll($filters);
        $totalPages = ceil($totalUsers / $perPage);

        view('admin/users', [
            'title' => 'Manajemen Pengguna — TemanBelajar Admin',
            'users' => $users,
            'totalUsers' => $totalUsers,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'filters' => $filters,
        ]);
    }

    public function toggleUserStatus(int $id): void
    {
        User::toggleActiveStatus($id);
        ActivityLog::log(null, 'admin_toggle_user_status', "Mengubah status aktifasi pengguna ID {$id}.");
        Flash::success('Status aktif pengguna berhasil diperbarui.');
        redirect('/admin/users');
    }

    public function programs(): void
    {
        $programs = StudyProgram::all();
        view('admin/programs', [
            'title' => 'Manajemen Program Studi — TemanBelajar Admin',
            'programs' => $programs,
        ]);
    }

    public function saveProgram(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $faculty = trim($_POST['faculty'] ?? '');

        if (empty($code) || empty($name) || empty($faculty)) {
            Flash::error('Semua kolom program studi wajib diisi.');
            redirect('/admin/programs');
            return;
        }

        if ($id > 0) {
            StudyProgram::update($id, ['code' => $code, 'name' => $name, 'faculty' => $faculty]);
            ActivityLog::log(null, 'admin_program_updated', "Memperbarui prodi {$name} ({$code}).");
            Flash::success("Program studi [{$name}] berhasil diperbarui.");
        } else {
            StudyProgram::create(['code' => $code, 'name' => $name, 'faculty' => $faculty]);
            ActivityLog::log(null, 'admin_program_created', "Menambahkan prodi {$name} ({$code}).");
            Flash::success("Program studi [{$name}] berhasil ditambahkan.");
        }

        redirect('/admin/programs');
    }

    public function deleteProgram(int $id): void
    {
        StudyProgram::delete($id);
        ActivityLog::log(null, 'admin_program_deleted', "Menghapus prodi ID {$id}.");
        Flash::success('Program studi berhasil dihapus.');
        redirect('/admin/programs');
    }

    public function subjects(): void
    {
        $subjects = StudySubject::all();
        $programs = StudyProgram::all();

        view('admin/subjects', [
            'title' => 'Manajemen Mata Kuliah — TemanBelajar Admin',
            'subjects' => $subjects,
            'programs' => $programs,
        ]);
    }

    public function saveSubject(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $studyProgramId = (int) ($_POST['study_program_id'] ?? 0);
        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $semester = (int) ($_POST['semester'] ?? 1);
        $description = trim($_POST['description'] ?? '');

        if (empty($code) || empty($name) || empty($studyProgramId)) {
            Flash::error('Semua kolom wajib mata kuliah harus diisi.');
            redirect('/admin/subjects');
            return;
        }

        $data = [
            'study_program_id' => $studyProgramId,
            'code' => $code,
            'name' => $name,
            'semester' => $semester,
            'description' => $description,
        ];

        if ($id > 0) {
            StudySubject::update($id, $data);
            ActivityLog::log(null, 'admin_subject_updated', "Memperbarui mata kuliah {$name} ({$code}).");
            Flash::success("Mata kuliah [{$name}] berhasil diperbarui.");
        } else {
            StudySubject::create($data);
            ActivityLog::log(null, 'admin_subject_created', "Menambahkan mata kuliah {$name} ({$code}).");
            Flash::success("Mata kuliah [{$name}] berhasil ditambahkan.");
        }

        redirect('/admin/subjects');
    }

    public function deleteSubject(int $id): void
    {
        StudySubject::delete($id);
        ActivityLog::log(null, 'admin_subject_deleted', "Menghapus mata kuliah ID {$id}.");
        Flash::success('Mata kuliah berhasil dihapus.');
        redirect('/admin/subjects');
    }

    public function sessions(): void
    {
        $search = trim($_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? 'all');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 15;

        $filters = ['search' => $search, 'status' => $status];
        $sessions = StudySession::getAllAdmin($filters, $page, $perPage);
        $totalSessions = StudySession::countAllAdmin($filters);
        $totalPages = ceil($totalSessions / $perPage);

        view('admin/sessions', [
            'title' => 'Monitoring Sesi Belajar — TemanBelajar Admin',
            'sessions' => $sessions,
            'totalSessions' => $totalSessions,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'filters' => $filters,
        ]);
    }

    public function reviews(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 15;

        $reviews = Review::getAllAdmin($page, $perPage);
        $totalReviews = Review::countAllAdmin();
        $totalPages = ceil($totalReviews / $perPage);

        view('admin/reviews', [
            'title' => 'Moderasi Ulasan & Rating — TemanBelajar Admin',
            'reviews' => $reviews,
            'totalReviews' => $totalReviews,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    public function toggleReviewVisibility(int $id): void
    {
        Review::toggleVisibility($id);
        ActivityLog::log(null, 'admin_toggle_review_visibility', "Mengubah visibilitas ulasan ID {$id}.");
        Flash::success('Visibilitas ulasan berhasil diperbarui.');
        redirect('/admin/reviews');
    }

    public function logs(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 25;

        $logs = ActivityLog::getLatest($page, $perPage);
        $totalLogs = ActivityLog::countAll();
        $totalPages = ceil($totalLogs / $perPage);

        view('admin/logs', [
            'title' => 'Audit Trail & Log Aktivitas — TemanBelajar Admin',
            'logs' => $logs,
            'totalLogs' => $totalLogs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
