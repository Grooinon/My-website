<?php
use App\Helpers\Auth;
use App\Models\Notification;

$currentUser = Auth::user();
$unreadNotifCount = $currentUser ? Notification::getUnreadCount((int) $currentUser['id']) : 0;
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
?>
<nav class="navbar navbar-expand-lg navbar-tb sticky-top py-2">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="<?= url('/') ?>">
            <span class="brand-logo-box">
                <i class="bi bi-mortarboard-fill fs-5"></i>
            </span>
            <span class="text-dark fs-5 tracking-tight">Teman<span class="text-primary">Belajar</span></span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPath === '/' ? 'active' : '' ?>" href="<?= url('/') ?>">
                        <i class="bi bi-house-door me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($currentPath, '/tutors') ? 'active' : '' ?>" href="<?= url('/tutors') ?>">
                        <i class="bi bi-search me-1"></i> Cari Tutor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($currentPath, '/how-it-works') ? 'active' : '' ?>" href="<?= url('/how-it-works') ?>">
                        <i class="bi bi-lightbulb me-1"></i> Cara Kerja
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($currentPath, '/about') ? 'active' : '' ?>" href="<?= url('/about') ?>">
                        <i class="bi bi-info-circle me-1"></i> Tentang
                    </a>
                </li>
            </ul>

            <!-- Right Action Buttons -->
            <div class="d-flex align-items-center gap-2">
                <?php if ($currentUser): ?>
                    <!-- Notification Bell -->
                    <a href="<?= url('/notifications') ?>" class="btn btn-light position-relative rounded-circle p-2 text-secondary me-1" title="Notifikasi">
                        <i class="bi bi-bell fs-5"></i>
                        <?php if ($unreadNotifCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $unreadNotifCount ?>
                                <span class="visually-hidden">notifikasi belum dibaca</span>
                            </span>
                        <?php endif; ?>
                    </a>

                    <!-- Role Quick Actions -->
                    <?php if ($currentUser['role'] === 'admin'): ?>
                        <a href="<?= url('/admin/dashboard') ?>" class="btn btn-outline-danger btn-sm rounded-pill fw-semibold px-3">
                            <i class="bi bi-shield-lock-fill me-1"></i> Admin Panel
                        </a>
                    <?php else: ?>
                        <?php if (!empty($currentUser['is_tutor'])): ?>
                            <a href="<?= url('/tutor/dashboard') ?>" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold px-3">
                                <i class="bi bi-person-badge-fill me-1"></i> Mode Tutor
                            </a>
                        <?php else: ?>
                            <a href="<?= url('/tutor/profile/edit') ?>" class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold px-3">
                                <i class="bi bi-plus-circle me-1"></i> Jadi Tutor
                            </a>
                        <?php endif; ?>

                        <a href="<?= url('/student/dashboard') ?>" class="btn btn-light btn-sm rounded-pill fw-semibold px-3 text-primary border">
                            <i class="bi bi-journal-bookmark-fill me-1"></i> Dashboard Belajar
                        </a>
                    <?php endif; ?>

                    <!-- User Profile Dropdown -->
                    <div class="dropdown ms-1">
                        <button class="btn btn-light rounded-pill p-1 d-flex align-items-center gap-2 border dropdown-toggle shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if (!empty($currentUser['avatar']) && file_exists(dirname(__DIR__, 3) . '/public/uploads/avatars/' . $currentUser['avatar'])): ?>
                                <img src="<?= url('uploads/avatars/' . e($currentUser['avatar'])) ?>" alt="Avatar" class="avatar-img" width="32" height="32">
                            <?php else: ?>
                                <div class="avatar-placeholder" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                    <?= strtoupper(substr($currentUser['full_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <span class="small fw-semibold pe-1 d-none d-md-inline"><?= e($currentUser['full_name']) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2 mt-2" style="border-radius: 12px; min-width: 200px;">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-bold text-dark text-truncate"><?= e($currentUser['full_name']) ?></div>
                                <div class="small text-muted text-truncate"><?= e($currentUser['email']) ?></div>
                                <span class="badge bg-light text-primary border mt-1">NIM: <?= e($currentUser['nim']) ?></span>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="<?= url('/profile') ?>">
                                    <i class="bi bi-person-gear me-2 text-primary"></i> Edit Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="<?= url('/profile/change-password') ?>">
                                    <i class="bi bi-key me-2 text-secondary"></i> Ganti Password
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="<?= url('/logout') ?>" method="POST" class="m-0">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="btn btn-tb-outline py-2 px-3 fs-6">Masuk</a>
                    <a href="<?= url('/register') ?>" class="btn btn-tb-primary py-2 px-3 fs-6">Daftar Akun</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
