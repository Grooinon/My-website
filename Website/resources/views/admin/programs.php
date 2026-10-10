<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Program Studi</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Manajemen Program Studi</h3>
            <p class="text-secondary small mb-0">Kelola master data jurusan dan fakultas di lingkungan kampus.</p>
        </div>
        <div>
            <button type="button" class="btn btn-tb-primary btn-sm px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#programModal" onclick="prepareAddProgram();">
                <i class="bi bi-plus-lg me-1"></i> Tambah Program Studi
            </button>
        </div>
    </div>

    <div class="tb-card p-4 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light small">
                    <tr>
                        <th>Kode Prodi</th>
                        <th>Nama Program Studi</th>
                        <th>Fakultas</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($programs as $prog): ?>
                        <tr>
                            <td><span class="badge bg-primary text-white font-monospace"><?= e($prog['code']) ?></span></td>
                            <td class="fw-semibold text-dark"><?= e($prog['name']) ?></td>
                            <td><span class="text-secondary"><?= e($prog['faculty']) ?></span></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2" onclick='prepareEditProgram(<?= json_encode($prog) ?>);'>
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <form action="<?= url('/admin/programs/' . $prog['id'] . '/delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program studi ini?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create / Edit Program -->
<div class="modal fade" id="programModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark" id="programModalTitle">Tambah Program Studi</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('/admin/programs') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="prog_id" value="0">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="prog_code" class="form-label small fw-semibold text-secondary">Kode Prodi (Singkatan) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="prog_code" name="code" placeholder="Misal: SI, TI, BD" required>
                    </div>
                    <div class="mb-3">
                        <label for="prog_name" class="form-label small fw-semibold text-secondary">Nama Program Studi Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="prog_name" name="name" placeholder="Misal: Sistem Informasi" required>
                    </div>
                    <div class="mb-3">
                        <label for="prog_faculty" class="form-label small fw-semibold text-secondary">Fakultas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="prog_faculty" name="faculty" placeholder="Misal: Fakultas Ilmu Komputer" required>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tb-primary px-4 fw-semibold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function prepareAddProgram() {
    document.getElementById('programModalTitle').textContent = 'Tambah Program Studi';
    document.getElementById('prog_id').value = '0';
    document.getElementById('prog_code').value = '';
    document.getElementById('prog_name').value = '';
    document.getElementById('prog_faculty').value = '';
}

function prepareEditProgram(prog) {
    document.getElementById('programModalTitle').textContent = 'Edit Program Studi';
    document.getElementById('prog_id').value = prog.id;
    document.getElementById('prog_code').value = prog.code;
    document.getElementById('prog_name').value = prog.name;
    document.getElementById('prog_faculty').value = prog.faculty;
    const modal = new bootstrap.Modal(document.getElementById('programModal'));
    modal.show();
}
</script>
