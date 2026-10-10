<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Moderasi Ulasan</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Moderasi Ulasan & Rating</h3>
            <p class="text-secondary small mb-0">Tinjau umpan balik mahasiswa untuk memastikan kesopanan dan integritas akademik.</p>
        </div>
        <div>
            <span class="badge bg-primary px-3 py-2">Total: <?= (int) $totalReviews ?> Ulasan Terkumpul</span>
        </div>
    </div>

    <div class="tb-card p-4 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover small">
                <thead class="table-light">
                    <tr>
                        <th>Mahasiswa & Sesi</th>
                        <th>Tutor & Mata Kuliah</th>
                        <th>Rating Bintang</th>
                        <th>Komentar Ulasan</th>
                        <th>Status Tampil</th>
                        <th class="text-end">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reviews)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada ulasan yang masuk.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reviews as $rev): ?>
                            <tr>
                                <td>
                                    <strong><?= e($rev['student_name']) ?></strong>
                                    <small class="text-muted d-block font-monospace"><?= e($rev['session_code']) ?></small>
                                </td>
                                <td>
                                    <strong><?= e($rev['tutor_name']) ?></strong>
                                    <small class="text-secondary d-block"><?= e($rev['subject_name']) ?></small>
                                </td>
                                <td>
                                    <span class="text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star-<?= $i <= $rev['rating'] ? 'fill' : 'light' ?>"></i>
                                        <?php endfor; ?>
                                    </span>
                                    <span class="fw-bold text-dark ms-1"><?= $rev['rating'] ?></span>
                                </td>
                                <td>
                                    <p class="mb-0 text-secondary" style="max-width: 320px;">"<?= e($rev['comment']) ?>"</p>
                                    <small class="text-muted"><?= format_date_id($rev['created_at']) ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($rev['is_visible'])): ?>
                                        <span class="badge bg-success">Publik</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Disembunyikan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <form action="<?= url('/admin/reviews/' . $rev['id'] . '/toggle') ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status visibilitas ulasan ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm <?= !empty($rev['is_visible']) ? 'btn-outline-danger' : 'btn-outline-success' ?> py-1 px-2">
                                            <?= !empty($rev['is_visible']) ? 'Sembunyikan' : 'Tampilkan' ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4 pt-2">
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item <?= ($p === $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= url('/admin/reviews?page=' . $p) ?>">
                                <?= $p ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
