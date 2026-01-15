<?php 
include '../includes/header_guru.php'; 
include '../includes/db.php';

// 1. Ambil ID User dari session
$id_user = $_SESSION['id_user'];

// 2. Ambil Profil Guru untuk menentukan kelas ampuan
$query_guru = mysqli_query($koneksi, "SELECT * FROM guru WHERE id_user = '$id_user'");
$data_guru = mysqli_fetch_assoc($query_guru);

$mapel_guru = strtoupper($data_guru['mata_pelajaran']);
preg_match('/\d+/', $mapel_guru, $matches);
$kelas_diampu = isset($matches[0]) ? trim($matches[0]) : '';

// 3. Filter dari URL (GET)
$filter_kelas    = isset($_GET['kelas']) ? $_GET['kelas'] : $kelas_diampu;
$filter_mapel    = isset($_GET['mapel']) ? $_GET['mapel'] : '';
$filter_semester = isset($_GET['semester']) ? $_GET['semester'] : 'Ganjil';

// 4. Query untuk daftar Mapel (untuk filter dropdown)
$query_mapel_list = mysqli_query($koneksi, "SELECT DISTINCT nama_mapel FROM mapel ORDER BY nama_mapel ASC");
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0 p-4" style="border-radius: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark"><i class="fas fa-file-invoice me-2 text-success"></i>Rekap Nilai Siswa</h4>
                <p class="text-muted small">Melihat hasil akhir nilai Kelas <?= htmlspecialchars($filter_kelas); ?></p>
            </div>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fas fa-print me-1"></i> Cetak Rekap
            </button>
        </div>

        <form method="GET" action="" class="row g-3 mb-4 p-3 bg-light rounded-3 mx-1">
            <input type="hidden" name="kelas" value="<?= $filter_kelas; ?>">
            <div class="col-md-6">
                <label class="form-label small fw-bold">PILIH MATA PELAJARAN:</label>
                <select name="mapel" class="form-select border-0 shadow-sm" required onchange="this.form.submit()">
                    <option value="">-- Semua Mapel --</option>
                    <?php while($m = mysqli_fetch_assoc($query_mapel_list)): ?>
                        <option value="<?= $m['nama_mapel']; ?>" <?= ($filter_mapel == $m['nama_mapel']) ? 'selected' : ''; ?>>
                            <?= $m['nama_mapel']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">SEMESTER:</label>
                <select name="semester" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                    <option value="Ganjil" <?= ($filter_semester == 'Ganjil') ? 'selected' : ''; ?>>Ganjil</option>
                    <option value="Genap" <?= ($filter_semester == 'Genap') ? 'selected' : ''; ?>>Genap</option>
                </select>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="bg-dark text-white">
                    <tr class="text-center">
                        <th width="50" class="py-3">No</th>
                        <th class="text-start">Nama Lengkap</th>
                        <th>Mapel</th>
                        <th>Tugas</th>
                        <th>UTS</th>
                        <th>UAS</th>
                        <th class="bg-primary">Rata-Rata</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // Logika Sinkronisasi: JOIN Tabel Siswa dengan Tabel Nilai
                    $sql = "SELECT s.nama_lengkap, s.nisn, n.mapel, n.nilai_tugas, n.nilai_uts, n.nilai_uas 
                            FROM siswa s 
                            LEFT JOIN nilai n ON s.nisn = n.nisn 
                            AND n.semester = '$filter_semester' ";
                    
                    if(!empty($filter_mapel)) {
                        $sql .= "AND n.mapel = '$filter_mapel' ";
                    }
                    
                    $sql .= "WHERE s.kelas = '$filter_kelas' ORDER BY s.nama_lengkap ASC";
                    
                    $result = mysqli_query($koneksi, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Hitung Rata-rata
                            $tugas = $row['nilai_tugas'] ?? 0;
                            $uts   = $row['nilai_uts'] ?? 0;
                            $uas   = $row['nilai_uas'] ?? 0;
                            $rata  = ($tugas + $uts + $uas) > 0 ? ($tugas + $uts + $uas) / 3 : 0;
                            
                            $text_color = ($rata < 75 && $rata > 0) ? 'text-danger' : 'text-dark';
                    ?>
                    <tr class="text-center">
                        <td class="text-muted"><?= $no++; ?></td>
                        <td class="text-start">
                            <div class="fw-bold"><?= htmlspecialchars($row['nama_lengkap']); ?></div>
                            <small class="text-muted">NISN: <?= $row['nisn']; ?></small>
                        </td>
                        <td><?= $row['mapel'] ?? '<span class="text-danger italic small">Belum diinput</span>'; ?></td>
                        <td><?= $tugas; ?></td>
                        <td><?= $uts; ?></td>
                        <td><?= $uas; ?></td>
                        <td class="fw-bold <?= $text_color; ?> bg-light">
                            <?= number_format($rata, 1); ?>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-5 text-muted'>Data siswa untuk kelas ini tidak ditemukan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>