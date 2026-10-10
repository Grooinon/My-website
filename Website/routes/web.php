<?php

use Routes\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\TutorController;
use App\Controllers\SessionController;
use App\Controllers\ReviewController;
use App\Controllers\NotificationController;
use App\Controllers\StudentDashboardController;
use App\Controllers\TutorDashboardController;
use App\Controllers\AdminController;
use App\Controllers\PageController;

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\TutorMiddleware;
use App\Middleware\AdminMiddleware;

// -------------------------------------------------------------
// PUBLIC INFORMATIONAL & CATALOG ROUTES
// -------------------------------------------------------------
Router::get('/', [HomeController::class, 'index']);
Router::get('/tutors', [TutorController::class, 'index']);
Router::get('/tutors/{id}', [TutorController::class, 'show']);

Router::get('/about', [PageController::class, 'about']);
Router::get('/how-it-works', [PageController::class, 'howItWorks']);
Router::get('/faq', [PageController::class, 'faq']);
Router::get('/contact', [PageController::class, 'contact']);
Router::post('/contact', [PageController::class, 'submitContact']);
Router::get('/terms', [PageController::class, 'terms']);
Router::get('/privacy', [PageController::class, 'privacy']);

// -------------------------------------------------------------
// AUTHENTICATION ROUTES (GUEST ONLY)
// -------------------------------------------------------------
Router::get('/login', [AuthController::class, 'showLogin'], [GuestMiddleware::class]);
Router::post('/login', [AuthController::class, 'processLogin'], [GuestMiddleware::class]);
Router::get('/register', [AuthController::class, 'showRegister'], [GuestMiddleware::class]);
Router::post('/register', [AuthController::class, 'processRegister'], [GuestMiddleware::class]);

// -------------------------------------------------------------
// LOGGED-IN COMMON ROUTES
// -------------------------------------------------------------
Router::get('/logout', [AuthController::class, 'logout']);
Router::post('/logout', [AuthController::class, 'logout']);

Router::get('/profile', [AuthController::class, 'showProfile'], [AuthMiddleware::class]);
Router::post('/profile', [AuthController::class, 'updateProfile'], [AuthMiddleware::class]);
Router::get('/profile/change-password', [AuthController::class, 'showChangePassword'], [AuthMiddleware::class]);
Router::post('/profile/change-password', [AuthController::class, 'updatePassword'], [AuthMiddleware::class]);

// Notifications
Router::get('/notifications', [NotificationController::class, 'index'], [AuthMiddleware::class]);
Router::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'], [AuthMiddleware::class]);
Router::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'], [AuthMiddleware::class]);

// -------------------------------------------------------------
// STUDENT ACTIONS & DASHBOARD
// -------------------------------------------------------------
Router::get('/student/dashboard', [StudentDashboardController::class, 'index'], [AuthMiddleware::class]);
Router::get('/student/sessions', [StudentDashboardController::class, 'sessions'], [AuthMiddleware::class]);
Router::post('/sessions/book', [SessionController::class, 'book'], [AuthMiddleware::class]);
Router::post('/sessions/{id}/cancel', [SessionController::class, 'cancel'], [AuthMiddleware::class]);
Router::post('/reviews', [ReviewController::class, 'store'], [AuthMiddleware::class]);

// -------------------------------------------------------------
// TUTOR ACTIONS & DASHBOARD
// -------------------------------------------------------------
// Tutor profile activation & setup (accessible by all authenticated students)
Router::get('/tutor/profile/edit', [TutorDashboardController::class, 'profile'], [AuthMiddleware::class]);
Router::post('/tutor/profile/edit', [TutorDashboardController::class, 'updateProfile'], [AuthMiddleware::class]);

// Tutor active dashboard (requires active tutor role)
Router::get('/tutor/dashboard', [TutorDashboardController::class, 'index'], [TutorMiddleware::class]);
Router::get('/tutor/sessions', [TutorDashboardController::class, 'sessions'], [TutorMiddleware::class]);
Router::post('/tutor/sessions/{id}/accept', [SessionController::class, 'accept'], [TutorMiddleware::class]);
Router::post('/tutor/sessions/{id}/reject', [SessionController::class, 'reject'], [TutorMiddleware::class]);
Router::post('/tutor/sessions/{id}/complete', [SessionController::class, 'complete'], [TutorMiddleware::class]);

Router::get('/tutor/subjects', [TutorDashboardController::class, 'subjects'], [TutorMiddleware::class]);
Router::post('/tutor/subjects', [TutorDashboardController::class, 'addSubject'], [TutorMiddleware::class]);
Router::post('/tutor/subjects/{id}/delete', [TutorDashboardController::class, 'removeSubject'], [TutorMiddleware::class]);

Router::get('/tutor/availability', [TutorDashboardController::class, 'availability'], [TutorMiddleware::class]);
Router::post('/tutor/availability', [TutorDashboardController::class, 'updateAvailability'], [TutorMiddleware::class]);

// -------------------------------------------------------------
// ADMINISTRATOR DASHBOARD & MODERATION
// -------------------------------------------------------------
Router::get('/admin/dashboard', [AdminController::class, 'index'], [AdminMiddleware::class]);
Router::get('/admin/users', [AdminController::class, 'users'], [AdminMiddleware::class]);
Router::post('/admin/users/{id}/toggle', [AdminController::class, 'toggleUserStatus'], [AdminMiddleware::class]);

Router::get('/admin/programs', [AdminController::class, 'programs'], [AdminMiddleware::class]);
Router::post('/admin/programs', [AdminController::class, 'saveProgram'], [AdminMiddleware::class]);
Router::post('/admin/programs/{id}/delete', [AdminController::class, 'deleteProgram'], [AdminMiddleware::class]);

Router::get('/admin/subjects', [AdminController::class, 'subjects'], [AdminMiddleware::class]);
Router::post('/admin/subjects', [AdminController::class, 'saveSubject'], [AdminMiddleware::class]);
Router::post('/admin/subjects/{id}/delete', [AdminController::class, 'deleteSubject'], [AdminMiddleware::class]);

Router::get('/admin/sessions', [AdminController::class, 'sessions'], [AdminMiddleware::class]);
Router::get('/admin/reviews', [AdminController::class, 'reviews'], [AdminMiddleware::class]);
Router::post('/admin/reviews/{id}/toggle', [AdminController::class, 'toggleReviewVisibility'], [AdminMiddleware::class]);
Router::get('/admin/logs', [AdminController::class, 'logs'], [AdminMiddleware::class]);
