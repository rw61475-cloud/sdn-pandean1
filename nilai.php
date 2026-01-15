<?php
$page = 'nilai'; 
$title = 'Rekapan Nilai';

// Memanggil header yang berisi koneksi dan session_start()
include '../includes/header_siswa.php'; 

/** * LOGIKA SINKRONISASI DATA
 */
// Pastikan username diambil dari session login asli
if (!isset($_SESSION['username']) || $_SESSION['username'] == '1') {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Sesi tidak valid. Silakan logout dan login kembali dengan NISN Anda.</div></div>";
    exit;
}

$nisn_session = mysqli_real_escape_string($koneksi, $_SESSION['username']);

// Query mengambil data dari tabel 'nilai' berdasarkan NISN yang sedang login
$query_nilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE nisn = '$nisn_session' ORDER BY semester ASC");

$data_nilai_array = [];
$total_rata_rata = 0;
$count_mapel = 0;

if ($query_nilai && mysqli_num_rows($query_nilai) > 0) {
    while ($row = mysqli_fetch_assoc($query_nilai)) {
        // Menggunakan nama kolom sesuai gambar struktur DB Anda
        $tugas = $row['nilai_tugas'];
        $uts   = $row['nilai_uts'];
        $uas   = $row['nilai_uas'];
        
        // Perhitungan Rata-rata
        $na = ($tugas + $uts + $uas) / 3;
        $row['nilai_akhir'] = round($na, 1);
        
        $data_nilai_array[] = $row;
        $total_rata_rata += $na;
        $count_mapel++;
    }
}

$rata_rata_final = ($count_mapel > 0) ? round($total_rata_rata / $count_mapel, 1) : 0;
?>

<div class="row mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-dark">Rekapan Nilai Akademik</h3>
        <p class="text-muted">Menampilkan data untuk NISN: <strong><?= $nisn_session ?></strong></p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <small class="text-muted d-block">Rata-rata Nilai</small>
            <span class="fs-4 fw-bold text-primary"><?= $rata_rata_final ?></span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <small class="text-muted d-block">Mata Pelajaran</small>
            <span class="fs-4 fw-bold text-success"><?= $count_mapel ?></span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <small class="text-muted d-block">Status</small>
            <span class="fs-4 fw-bold"><?= ($rata_rata_final >= 75) ? 'TUNTAS' : 'REMIDIAL' ?></span>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Mata Pelajaran</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data_nilai_array)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Data nilai belum tersedia untuk NISN: <?= $nisn_session ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data_nilai_array as $n): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?= htmlspecialchars($n['mapel']) ?> (<?= htmlspecialchars($n['semester']) ?>)</td>
                            <td class="text-center"><?= $n['nilai_tugas'] ?></td>
                            <td class="text-center"><?= $n['nilai_uts'] ?></td>
                            <td class="text-center"><?= $n['nilai_uas'] ?></td>
                            <td class="text-center fw-bold"><?= $n['nilai_akhir'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>