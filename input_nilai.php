<?php 
include '../includes/header_guru.php'; 
include '../includes/db.php';

// 1. Ambil ID User dari session
$id_user = $_SESSION['id_user'];

// 2. Ambil Profil Guru
$query_profil = mysqli_query($koneksi, "SELECT * FROM guru WHERE id_user = '$id_user'");
$data_guru = mysqli_fetch_assoc($query_profil);

$mapel_guru = strtoupper($data_guru['mata_pelajaran']);
$is_pai  = (strpos($mapel_guru, 'PAI') !== false);
$is_pjok = (strpos($mapel_guru, 'PJOK') !== false);
$is_guru_khusus = ($is_pai || $is_pjok);

preg_match('/\d+/', $mapel_guru, $matches);
$kelas_diampu = isset($matches[0]) ? trim($matches[0]) : '';

// 3. Ambil Filter
$filter_kelas    = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$filter_mapel    = isset($_GET['mapel']) ? $_GET['mapel'] : '';
$filter_semester = isset($_GET['semester']) ? $_GET['semester'] : 'Ganjil';

// 4. Query Dropdown Kelas & Mapel
if ($is_guru_khusus) {
    $query_daftar_kelas = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC");
} else {
    $query_daftar_kelas = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM siswa WHERE TRIM(kelas) = '$kelas_diampu'");
    $filter_kelas = $filter_kelas ?: $kelas_diampu;
}

if ($is_pai) {
    $query_mapel = mysqli_query($koneksi, "SELECT DISTINCT nama_mapel FROM mapel WHERE nama_mapel LIKE '%Agama%' OR nama_mapel LIKE '%PAI%'");
} elseif ($is_pjok) {
    $query_mapel = mysqli_query($koneksi, "SELECT DISTINCT nama_mapel FROM mapel WHERE nama_mapel LIKE '%PJOK%' OR nama_mapel LIKE '%Olahraga%'");
} else {
    $query_mapel = mysqli_query($koneksi, "SELECT DISTINCT nama_mapel FROM mapel WHERE nama_mapel NOT LIKE '%Agama%' AND nama_mapel NOT LIKE '%PAI%' AND nama_mapel NOT LIKE '%PJOK%' AND nama_mapel NOT LIKE '%Olahraga%' ORDER BY nama_mapel ASC");
}

$query_siswa = null;
if (!empty($filter_kelas)) {
    $query_siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE TRIM(kelas) = '$filter_kelas' ORDER BY nama_lengkap ASC");
}
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0 p-4" style="border-radius: 20px;">
        <div class="mb-4">
            <h4 class="fw-bold text-dark"><i class="fas fa-edit me-2 text-primary"></i>Input Nilai Siswa</h4>
            <p class="text-muted small">Login: <strong><?= htmlspecialchars($data_guru['nama_lengkap']); ?></strong></p>
        </div>

        <form method="GET" action="" class="row g-3 mb-4 p-3 bg-light rounded-3 mx-1">
            <div class="col-md-3">
                <label class="form-label small fw-bold">PILIH KELAS:</label>
                <select name="kelas" class="form-select border-0 shadow-sm" required onchange="this.form.submit()">
                    <?php if($is_guru_khusus): ?><option value="">-- Pilih Kelas --</option><?php endif; ?>
                    <?php while($k = mysqli_fetch_assoc($query_daftar_kelas)): ?>
                        <option value="<?= $k['kelas']; ?>" <?= ($filter_kelas == $k['kelas']) ? 'selected' : ''; ?>>Kelas <?= $k['kelas']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold">MATA PELAJARAN:</label>
                <select name="mapel" class="form-select border-0 shadow-sm" required>
                    <option value="">-- Pilih Mapel --</option>
                    <?php while($m = mysqli_fetch_assoc($query_mapel)): ?>
                        <option value="<?= $m['nama_mapel']; ?>" <?= ($filter_mapel == $m['nama_mapel']) ? 'selected' : ''; ?>><?= $m['nama_mapel']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">SEMESTER:</label>
                <select name="semester" class="form-select border-0 shadow-sm">
                    <option value="Ganjil" <?= ($filter_semester == 'Ganjil') ? 'selected' : ''; ?>>Ganjil</option>
                    <option value="Genap" <?= ($filter_semester == 'Genap') ? 'selected' : ''; ?>>Genap</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 shadow-sm fw-bold">CARI</button>
            </div>
        </form>

        <?php if ($query_siswa && mysqli_num_rows($query_siswa) > 0 && !empty($filter_mapel)): ?>
            <form method="POST" action="proses_simpan_nilai.php">
                <input type="hidden" name="id_guru" value="<?= $id_user; ?>">
                <input type="hidden" name="mapel" value="<?= $filter_mapel; ?>">
                <input type="hidden" name="semester" value="<?= $filter_semester; ?>">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th width="50" class="py-3">No</th>
                                <th class="text-start">Nama Lengkap</th>
                                <th width="120">Tugas</th>
                                <th width="120">UTS</th>
                                <th width="120">UAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($s = mysqli_fetch_assoc($query_siswa)): 
                                // Sinkronisasi: Ambil nilai jika sudah ada di tabel nilai
                                $nisn_cek = $s['nisn'];
                                $q_cek = mysqli_query($koneksi, "SELECT * FROM nilai WHERE nisn='$nisn_cek' AND mapel='$filter_mapel' AND semester='$filter_semester'");
                                $d_n = mysqli_fetch_assoc($q_cek);
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td>
                                    <input type="hidden" name="nisn[]" value="<?= $s['nisn']; ?>">
                                    <div class="fw-bold"><?= htmlspecialchars($s['nama_lengkap']); ?></div>
                                    <small class="text-muted">NISN: <?= $s['nisn']; ?></small>
                                </td>
                                <td><input type="number" name="nilai_tugas[]" class="form-control text-center" value="<?= $d_n['nilai_tugas'] ?? ''; ?>" min="0" max="100" required></td>
                                <td><input type="number" name="nilai_uts[]" class="form-control text-center" value="<?= $d_n['nilai_uts'] ?? ''; ?>" min="0" max="100" required></td>
                                <td><input type="number" name="nilai_uas[]" class="form-control text-center" value="<?= $d_n['nilai_uas'] ?? ''; ?>" min="0" max="100" required></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-end">
                    <button type="submit" name="simpan" class="btn btn-success px-5 py-2 rounded-pill fw-bold shadow">
                        <i class="fas fa-save me-2"></i>SIMPAN NILAI
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?>