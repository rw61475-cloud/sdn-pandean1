<?php 
include '../includes/header_guru.php'; 
include '../includes/db.php';

// 1. Ambil ID User dari session untuk identifikasi guru
$id_user = $_SESSION['id_user'];

// 2. Ambil Profil Guru untuk cek jabatan (Wali Kelas atau Mapel PAI/PJOK)
$query_profil = mysqli_query($koneksi, "SELECT * FROM guru WHERE id_user = '$id_user'");
$data_guru = mysqli_fetch_assoc($query_profil);

$mapel_guru = strtoupper($data_guru['mata_pelajaran']);
// Cek apakah guru adalah PAI atau PJOK
$is_guru_khusus = (strpos($mapel_guru, 'PAI') !== false || strpos($mapel_guru, 'PJOK') !== false);

// Ekstrak angka kelas jika dia Wali Kelas (Contoh: "Wali Kelas 1" ambil angka "1")
preg_match('/\d+/', $mapel_guru, $matches);
$kelas_diampu = isset($matches[0]) ? trim($matches[0]) : '';

// 3. Tentukan Query SQL berdasarkan hak akses
if ($is_guru_khusus) {
    // Guru PAI/PJOK: Ambil SEMUA siswa
    $query_siswa = "SELECT * FROM siswa ORDER BY kelas ASC, nama_lengkap ASC";
} else {
    // Wali Kelas: Hanya ambil siswa di kelas yang dia ampu
    $query_siswa = "SELECT * FROM siswa WHERE TRIM(kelas) = '$kelas_diampu' ORDER BY nama_lengkap ASC";
}

$result = mysqli_query($koneksi, $query_siswa);

// 4. Kelompokkan data ke dalam array berdasarkan kelas agar muncul per tabel
$siswa_per_kelas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $siswa_per_kelas[$row['kelas']][] = $row;
}
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="fas fa-users me-2 text-success"></i>Manajemen Data Siswa
            </h4>
            <p class="text-muted small mb-0">
                Login Sebagai: <strong><?= htmlspecialchars($data_guru['nama_lengkap']); ?></strong> 
                (<?= $is_guru_khusus ? 'Guru Mata Pelajaran' : 'Wali Kelas ' . $kelas_diampu ?>)
            </p>
        </div>
        <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if (empty($siswa_per_kelas)): ?>
        <div class="alert alert-warning shadow-sm border-0">
            <i class="fas fa-exclamation-triangle me-2"></i> Belum ada data siswa yang terdaftar untuk kelas Anda.
        </div>
    <?php else: ?>
        <?php foreach ($siswa_per_kelas as $kelas => $daftar_siswa): ?>
            <div class="card shadow-sm border-0 mb-5" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-success">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Data Siswa Kelas <?= htmlspecialchars($kelas); ?>
                    </h5>
                    <span class="badge bg-success rounded-pill"><?= count($daftar_siswa); ?> Siswa</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4" width="5%">No</th>
                                    <th width="15%">NISN</th>
                                    <th width="25%">Nama Lengkap</th>
                                    <th width="35%">Alamat</th>
                                    <th width="10%">HP Ortu</th>
                                    <th class="text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($daftar_siswa as $siswa): ?>
                                <tr>
                                    <td class="px-4 text-muted"><?= $no++; ?></td>
                                    <td><code class="fw-bold text-danger"><?= $siswa['nisn']; ?></code></td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($siswa['nama_lengkap']); ?></td>
                                    <td class="small text-muted text-wrap"><?= htmlspecialchars($siswa['alamat']); ?></td>
                                    <td class="small"><?= htmlspecialchars($siswa['no_hp_ortu']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_siswa.php?nisn=<?= $siswa['nisn']; ?>" class="btn btn-sm btn-outline-primary border-0">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="hapus_siswa.php?nisn=<?= $siswa['nisn']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus data siswa ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>