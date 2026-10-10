<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/student/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pusat Notifikasi</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Pusat Notifikasi</h3>
        </div>
        <?php if ($unreadNotifCount > 0): ?>
            <div>
                <form action="<?= url('/notifications/read-all') ?>" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-check2-all me-1"></i> Tandai Semua Sudah Dibaca
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <div class="tb-card p-4 bg-white">
        <?php if (empty($notifications)): ?>
            <div class="tb-empty-state py-5">
                <div class="tb-empty-state-icon mx-auto">
                    <i class="bi bi-bell-slash"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Tidak Ada Notifikasi</h5>
                <p class="text-secondary small mb-0">Semua aktivitas bimbingan dan jadwal Anda telah mutakhir.</p>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($notifications as $n): ?>
                    <div class="list-group-item px-0 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 <?= empty($n['is_read']) ? 'bg-light-subtle' : '' ?>">
                        <div class="d-flex align-items-start gap-3">
                            <div class="p-2 rounded-circle <?= empty($n['is_read']) ? 'bg-primary text-white' : 'bg-light text-secondary' ?>" style="width: 36px; height: 36px; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-bell-fill" style="font-size: 0.9rem;"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark <?= empty($n['is_read']) ? 'text-primary' : '' ?>"><?= e($n['title']) ?></div>
                                <div class="text-secondary small"><?= e($n['message']) ?></div>
                                <small class="text-muted" style="font-size: 0.75rem;"><?= format_date_id($n['created_at']) ?></small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 ps-md-3">
                            <?php if (!empty($n['link'])): ?>
                                <a href="<?= url('/notifications/' . $n['id'] . '/read?redirect=' . urlencode($n['link'])) ?>" class="btn btn-sm btn-outline-primary">
                                    Buka Detail <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            <?php elseif (empty($n['is_read'])): ?>
                                <a href="<?= url('/notifications/' . $n['id'] . '/read') ?>" class="btn btn-sm btn-light border">
                                    Tandai Dibaca
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
