<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mata Kuliah</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">Manajemen Mata Kuliah</h3>
            <p class="text-secondary small mb-0">Kelola kurikulum mata kuliah yang dapat diajarkan oleh tutor sebaya.</p>
        </div>
        <div>
            <button type="button" class="btn btn-tb-primary btn-sm px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="prepareAddSubject();">
                <i class="bi bi-plus-lg me-1"></i> Tambah Mata Kuliah
            </button>
        </div>
    </div>

    <div class="tb-card p-4 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light small">
                    <tr>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>Program Studi</th>
                        <th>Semester</th>
                        <th>Deskripsi Materi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $sub): ?>
                        <tr>
                            <td><span class="badge bg-primary text-white font-monospace"><?= e($sub['code']) ?></span></td>
                            <td class="fw-semibold text-dark"><?= e($sub['name']) ?></td>
                            <td><small class="text-secondary"><?= e($sub['program_name']) ?></small></td>
                            <td><span class="badge bg-light text-dark border">Smt <?= $sub['semester'] ?></span></td>
                            <td><small class="text-muted d-block text-truncate" style="max-width: 280px;"><?= e($sub['description'] ?? '-') ?></small></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2" onclick='prepareEditSubject(<?= json_encode($sub) ?>);'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="<?= url('/admin/subjects/' . $sub['id'] . '/delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?');">
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

<!-- Modal Create / Edit Subject -->
<div class="modal fade" id="subjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark" id="subjectModalTitle">Tambah Mata Kuliah</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('/admin/subjects') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="sub_id" value="0">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="sub_prog_id" class="form-label small fw-semibold text-secondary">Program Studi <span class="text-danger">*</span></label>
                        <select class="form-select" id="sub_prog_id" name="study_program_id" required>
                            <option value="">-- Pilih Program Studi --</option>
                            <?php foreach ($programs as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= e($p['name']) ?> (<?= e($p['code']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="sub_code" class="form-label small fw-semibold text-secondary">Kode MK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sub_code" name="code" placeholder="Misal: SI302" required>
                        </div>
                        <div class="col-6">
                            <label for="sub_semester" class="form-label small fw-semibold text-secondary">Semester <span class="text-danger">*</span></label>
                            <select class="form-select" id="sub_semester" name="semester" required>
                                <?php for ($s = 1; $s <= 8; $s++): ?>
                                    <option value="<?= $s ?>">Semester <?= $s ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="sub_name" class="form-label small fw-semibold text-secondary">Nama Mata Kuliah <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sub_name" name="name" placeholder="Misal: Pemrograman Web Lanjut" required>
                    </div>

                    <div class="mb-3">
                        <label for="sub_desc" class="form-label small fw-semibold text-secondary">Deskripsi Ringkas Materi</label>
                        <textarea class="form-control" id="sub_desc" name="description" rows="3" placeholder="Pokok bahasan yang dipelajari..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tb-primary px-4 fw-semibold">Simpan Mata Kuliah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function prepareAddSubject() {
    document.getElementById('subjectModalTitle').textContent = 'Tambah Mata Kuliah';
    document.getElementById('sub_id').value = '0';
    document.getElementById('sub_code').value = '';
    document.getElementById('sub_name').value = '';
    document.getElementById('sub_prog_id').value = '';
    document.getElementById('sub_semester').value = '1';
    document.getElementById('sub_desc').value = '';
}

function prepareEditSubject(sub) {
    document.getElementById('subjectModalTitle').textContent = 'Edit Mata Kuliah';
    document.getElementById('sub_id').value = sub.id;
    document.getElementById('sub_code').value = sub.code;
    document.getElementById('sub_name').value = sub.name;
    document.getElementById('sub_prog_id').value = sub.study_program_id;
    document.getElementById('sub_semester').value = sub.semester;
    document.getElementById('sub_desc').value = sub.description || '';
    const modal = new bootstrap.Modal(document.getElementById('subjectModal'));
    modal.show();
}
</script>
