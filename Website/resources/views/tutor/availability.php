<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/tutor/dashboard') ?>">Dashboard Tutor</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Jadwal Ketersediaan</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Atur Slot Jadwal Ketersediaan</h3>
            <p class="text-secondary small mb-0">Tentukan hari dan jam rutin Anda bersedia memberikan bimbingan kepada mahasiswa.</p>
        </div>
        <div>
            <a href="<?= url('/tutor/dashboard') ?>" class="btn btn-light border btn-sm px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="tb-card p-4 p-md-5 bg-white">
                <form action="<?= url('/tutor/availability') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold text-dark mb-0">Daftar Slot Ketersediaan Rutin</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" id="addSlotBtn">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Slot Baru
                        </button>
                    </div>

                    <p class="small text-muted mb-4">
                        Mahasiswa dapat melihat slot ini sebagai panduan saat mengajukan jadwal bimbingan belajar.
                    </p>

                    <div id="slotsContainer" class="d-flex flex-column gap-3 mb-4">
                        <?php if (empty($availability)): ?>
                            <!-- Default Initial Slot -->
                            <div class="slot-row p-3 bg-light rounded-3 border d-flex flex-column flex-md-row align-items-md-center gap-3">
                                <div class="flex-grow-1">
                                    <label class="form-label small fw-semibold text-secondary mb-1">Hari</label>
                                    <select name="days[]" class="form-select form-select-sm">
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                        <option value="Minggu">Minggu</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label small fw-semibold text-secondary mb-1">Jam Mulai</label>
                                    <input type="time" name="start_times[]" class="form-control form-select-sm" value="16:00" required>
                                </div>
                                <div>
                                    <label class="form-label small fw-semibold text-secondary mb-1">Jam Selesai</label>
                                    <input type="time" name="end_times[]" class="form-control form-select-sm" value="18:00" required>
                                </div>
                                <div class="pt-md-4">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-slot-btn" title="Hapus Slot">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($availability as $idx => $slot): ?>
                                <div class="slot-row p-3 bg-light rounded-3 border d-flex flex-column flex-md-row align-items-md-center gap-3">
                                    <div class="flex-grow-1">
                                        <label class="form-label small fw-semibold text-secondary mb-1">Hari</label>
                                        <select name="days[]" class="form-select form-select-sm">
                                            <?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day): ?>
                                                <option value="<?= $day ?>" <?= ($slot['day_of_week'] === $day) ? 'selected' : '' ?>><?= $day ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label small fw-semibold text-secondary mb-1">Jam Mulai</label>
                                        <input type="time" name="start_times[]" class="form-control form-select-sm" value="<?= substr($slot['start_time'], 0, 5) ?>" required>
                                    </div>
                                    <div>
                                        <label class="form-label small fw-semibold text-secondary mb-1">Jam Selesai</label>
                                        <input type="time" name="end_times[]" class="form-control form-select-sm" value="<?= substr($slot['end_time'], 0, 5) ?>" required>
                                    </div>
                                    <div class="pt-md-4">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-slot-btn" title="Hapus Slot">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="border-top pt-3 d-flex justify-content-end gap-2">
                        <a href="<?= url('/tutor/dashboard') ?>" class="btn btn-light border px-3">Batal</a>
                        <button type="submit" class="btn btn-tb-primary px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Jadwal Ketersediaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('slotsContainer');
    const addBtn = document.getElementById('addSlotBtn');

    addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'slot-row p-3 bg-light rounded-3 border d-flex flex-column flex-md-row align-items-md-center gap-3';
        row.innerHTML = `
            <div class="flex-grow-1">
                <label class="form-label small fw-semibold text-secondary mb-1">Hari</label>
                <select name="days[]" class="form-select form-select-sm">
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>
            </div>
            <div>
                <label class="form-label small fw-semibold text-secondary mb-1">Jam Mulai</label>
                <input type="time" name="start_times[]" class="form-control form-select-sm" value="16:00" required>
            </div>
            <div>
                <label class="form-label small fw-semibold text-secondary mb-1">Jam Selesai</label>
                <input type="time" name="end_times[]" class="form-control form-select-sm" value="18:00" required>
            </div>
            <div class="pt-md-4">
                <button type="button" class="btn btn-outline-danger btn-sm remove-slot-btn" title="Hapus Slot">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
    });

    container.addEventListener('click', function (e) {
        if (e.target.closest('.remove-slot-btn')) {
            const rows = container.querySelectorAll('.slot-row');
            if (rows.length > 1) {
                e.target.closest('.slot-row').remove();
            } else {
                alert('Minimal sisakan satu slot ketersediaan.');
            }
        }
    });
});
</script>
