<?php 
session_start();

// Proteksi Login
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../auth/login.php");
    exit;
}

include '../includes/header_guru.php'; 
include '../includes/db.php';

// 1. Ambil ID User dari session untuk identifikasi guru
$id_user = $_SESSION['id_user'];

// 2. Ambil Profil Guru untuk cek jabatan (Wali Kelas atau Mapel Khusus)
$query_profil = mysqli_query($koneksi, "SELECT * FROM guru WHERE id_user = '$id_user'");
$data_guru = mysqli_fetch_assoc($query_profil);

$mapel_guru = strtoupper($data_guru['mata_pelajaran']);
// Cek apakah guru adalah PAI atau PJOK (Guru khusus biasanya mengajar semua kelas)
$is_guru_khusus = (strpos($mapel_guru, 'PAI') !== false || strpos($mapel_guru, 'PJOK') !== false);

// Ekstrak angka kelas jika dia Wali Kelas (Contoh: "Wali Kelas 3" menjadi "3")
preg_match('/\d+/', $mapel_guru, $matches);
$kelas_diampu = isset($matches[0]) ? trim($matches[0]) : '';

// 3. Tentukan Query SQL berdasarkan hak akses
if ($is_guru_khusus) {
    // Guru khusus melihat semua siswa
    $query_siswa = "SELECT * FROM siswa ORDER BY kelas ASC, nama_lengkap ASC";
} else {
    // Wali kelas hanya melihat kelasnya sendiri
    $query_siswa = "SELECT * FROM siswa WHERE TRIM(kelas) = '$kelas_diampu' ORDER BY nama_lengkap ASC";
}

$result = mysqli_query($koneksi, $query_siswa);

// 4. Kelompokkan data ke dalam array berdasarkan kelas
$siswa_per_kelas = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $siswa_per_kelas[$row['kelas']][] = $row;
    }
}

$hari_ini = date('d M Y');
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
    body { background-color: #f8f9fa; font-family: 'Plus Jakarta Sans', sans-serif; }
    .card { border-radius: 15px; border: none; }
    .btn-check:checked + .btn-outline-success { background-color: #28a745; color: white; }
    .btn-check:checked + .btn-outline-primary { background-color: #007bff; color: white; }
    .btn-check:checked + .btn-outline-warning { background-color: #ffc107; color: white; }
    .btn-check:checked + .btn-outline-danger { background-color: #dc3545; color: white; }
    .sticky-bar {
        position: sticky;
        bottom: 20px;
        z-index: 999;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid #dee2e6;
        border-radius: 15px;
    }
    .table thead { background-color: #f8fafc; }
</style>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="fas fa-edit me-2 text-success"></i>Presensi & Manajemen Siswa
            </h4>
            <p class="text-muted small mb-0">
                Guru: <strong><?= htmlspecialchars($data_guru['nama_lengkap']); ?></strong> | Tanggal: <strong><?= $hari_ini ?></strong>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="rekap_presensi_siswa.php" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                <i class="fas fa-list me-1"></i> Rekap Absen
            </a>
            <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <?php if (empty($siswa_per_kelas)): ?>
        <div class="alert alert-warning shadow-sm border-0">
            <i class="fas fa-exclamation-triangle me-2"></i> Belum ada data siswa yang terdaftar untuk kelas Anda (Kelas <?= $kelas_diampu ?>).
        </div>
    <?php else: ?>
        <form action="proses_presensi_siswa.php" method="POST">
            <?php foreach ($siswa_per_kelas as $kelas => $daftar_siswa): ?>
                <div class="card shadow-sm mb-5 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-success">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Kelas <?= htmlspecialchars($kelas); ?>
                        </h5>
                        <span class="badge bg-success rounded-pill"><?= count($daftar_siswa); ?> Siswa</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="text-uppercase small fw-bold text-muted">
                                    <tr>
                                        <th class="py-3 px-4">Siswa</th>
                                        <th width="40%" class="text-center">Status Kehadiran</th>
                                        <th width="15%" class="text-center">Aksi Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($daftar_siswa as $siswa): ?>
                                    <tr>
                                        <td class="px-4">
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($siswa['nama_lengkap']); ?></div>
                                            <div class="small text-muted text-uppercase">NISN: <?= $siswa['nisn']; ?></div>
                                            
                                            <input type="hidden" name="nisn[]" value="<?= $siswa['nisn']; ?>">
                                            <input type="hidden" name="kelas[<?= $siswa['nisn']; ?>]" value="<?= $kelas; ?>">
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check" name="status[<?= $siswa['nisn']; ?>]" id="H<?= $siswa['nisn']; ?>" value="Hadir" checked>
                                                <label class="btn btn-sm btn-outline-success px-3" for="H<?= $siswa['nisn']; ?>">Hadir</label>

                                                <input type="radio" class="btn-check" name="status[<?= $siswa['nisn']; ?>]" id="I<?= $siswa['nisn']; ?>" value="Izin">
                                                <label class="btn btn-sm btn-outline-primary px-3" for="I<?= $siswa['nisn']; ?>">Izin</label>

                                                <input type="radio" class="btn-check" name="status[<?= $siswa['nisn']; ?>]" id="S<?= $siswa['nisn']; ?>" value="Sakit">
                                                <label class="btn btn-sm btn-outline-warning px-3" for="S<?= $siswa['nisn']; ?>">Sakit</label>

                                                <input type="radio" class="btn-check" name="status[<?= $siswa['nisn']; ?>]" id="A<?= $siswa['nisn']; ?>" value="Alpha">
                                                <label class="btn btn-sm btn-outline-danger px-3" for="A<?= $siswa['nisn']; ?>">Alpa</label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group shadow-sm">
                                                <a href="edit_siswa.php?nisn=<?= $siswa['nisn']; ?>" class="btn btn-sm btn-white border" title="Edit Data">
                                                    <i class="fas fa-user-edit text-primary"></i>
                                                </a>
                                                <a href="hapus_siswa.php?nisn=<?= $siswa['nisn']; ?>" class="btn btn-sm btn-white border" onclick="return confirm('Hapus data siswa ini?')" title="Hapus Data">
                                                    <i class="fas fa-trash text-danger"></i>
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

            <div class="card sticky-bar shadow p-3 mb-5">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted d-none d-md-inline ms-3">Selesai mengisi? Klik simpan untuk merekam kehadiran hari ini ke database.</span>
                    <button type="submit" name="simpan_presensi" class="btn btn-success px-5 rounded-pill fw-bold shadow-sm me-3">
                        <i class="fas fa-save me-2"></i> Simpan Semua Presensi
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>