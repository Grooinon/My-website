<?php
require __DIR__ . '/header.php';
require __DIR__ . '/navbar.php';
?>

<main class="flex-grow-1">
    <!-- Flash Messages Container -->
    <?php if (!empty($flashMessages)): ?>
        <div class="container mt-3">
            <?php foreach ($flashMessages as $msg): ?>
                <div class="alert alert-<?= e($msg['type']) ?> alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <?php if ($msg['type'] === 'success'): ?>
                        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <?php elseif ($msg['type'] === 'danger'): ?>
                        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                    <?php elseif ($msg['type'] === 'warning'): ?>
                        <i class="bi bi-exclamation-circle-fill fs-5 text-warning"></i>
                    <?php else: ?>
                        <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                    <?php endif; ?>
                    <div class="flex-grow-1">
                        <?= e($msg['message']) ?>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Main Dynamic Content -->
    <?= $content ?>
</main>

<?php
require __DIR__ . '/footer.php';
?>
