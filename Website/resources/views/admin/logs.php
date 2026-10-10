<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Audit Trail</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Audit Trail & Log Aktivitas Sistem</h3>
            <p class="text-secondary small mb-0">Catatan riwayat aktivitas operasional penting dan keamanan aplikasi.</p>
        </div>
        <div>
            <span class="badge bg-secondary px-3 py-2">Total Log: <?= (int) $totalLogs ?></span>
        </div>
    </div>

    <div class="tb-card p-4 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover small">
                <thead class="table-light">
                    <tr>
                        <th>Waktu (WIB)</th>
                        <th>Aksi Sistem</th>
                        <th>Pengguna Terkait</th>
                        <th>Detail Aktivitas</th>
                        <th>Alamat IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada catatan log aktivitas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td class="text-nowrap text-muted"><?= format_date_id($log['created_at']) ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace"><?= e($log['action']) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($log['full_name'])): ?>
                                        <strong><?= e($log['full_name']) ?></strong>
                                        <small class="text-muted d-block"><?= e($log['email']) ?> (<?= e($log['role']) ?>)</small>
                                    <?php else: ?>
                                        <span class="text-muted">Sistem / Anonim</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($log['details']) ?></td>
                                <td class="font-monospace text-muted"><?= e($log['ip_address'] ?? '127.0.0.1') ?></td>
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
                            <a class="page-link" href="<?= url('/admin/logs?page=' . $p) ?>">
                                <?= $p ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
