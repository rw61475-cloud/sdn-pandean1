<?php 
include '../includes/header_guru.php'; 
include '../includes/db.php';

// 1. Ambil ID User dari session
$id_user = $_SESSION['id_user'];

// 2. Ambil Profil Guru untuk Nama dan Info Kelas
$query_profil = mysqli_query($koneksi, "SELECT * FROM guru WHERE id_user = '$id_user'");
$data_guru = mysqli_fetch_assoc($query_profil);

if (!$data_guru) {
    echo "<script>alert('Data profil guru tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Ambil angka kelas dari kolom mata_pelajaran
preg_match('/\d+/', $data_guru['mata_pelajaran'], $matches);
$kelas_walas = isset($matches[0]) ? $matches[0] : '';
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0 p-4" style="border-radius: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark"><i class="fas fa-list-alt me-2 text-primary"></i>Rekap Nilai Siswa</h4>
                <p class="text-muted">Daftar nilai yang telah diinput oleh <strong><?= htmlspecialchars($data_guru['nama_lengkap']); ?></strong> untuk <strong>Kelas <?= $kelas_walas; ?></strong>.</p>
            </div>
            <div>
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="fas fa-print me-2"></i>Cetak Laporan
                </button>
            </div>
        </div>

        <?php
        // Ambil list Mata Pelajaran yang unik yang pernah diinput oleh guru ini
        $sql_mapel = "SELECT DISTINCT mapel FROM nilai WHERE id_user = '$id_user' ORDER BY mapel ASC";
        $res_mapel = mysqli_query($koneksi, $sql_mapel);

        if (mysqli_num_rows($res_mapel) > 0):
            while($m = mysqli_fetch_assoc($res_mapel)):
                $current_mapel = $m['mapel'];
        ?>
            <div class="mb-5">
                <div class="bg-light p-3 rounded-3 mb-3 d-flex justify-content-between align-items-center border-start border-primary border-4">
                    <h5 class="m-0 fw-bold text-primary"><i class="fas fa-book me-2"></i>Mata Pelajaran: <?= $current_mapel ?></h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th width="50" class="rounded-start">No</th>
                                <th class="text-start">Nama Siswa</th>
                                <th>Tugas</th>
                                <th>UTS</th>
                                <th>UAS</th>
                                <th class="rounded-end">Rata-Rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            // Query untuk mengambil data nilai gabung dengan data siswa
                            $sql_nilai = "SELECT n.*, s.nama_lengkap 
                                          FROM nilai n 
                                          JOIN siswa s ON n.nisn = s.nisn 
                                          WHERE n.id_user = '$id_user' AND n.mapel = '$current_mapel' 
                                          ORDER BY s.nama_lengkap ASC";
                            $res_nilai = mysqli_query($koneksi, $sql_nilai);
                            
                            while($row = mysqli_fetch_assoc($res_nilai)):
                                // Hitung Rata-rata
                                $rata = ($row['nilai_tugas'] + $row['nilai_uts'] + $row['nilai_uas']) / 3;
                                // Tentukan warna berdasarkan KKM (Misal KKM 75)
                                $status_color = ($rata >= 75) ? 'text-success' : 'text-danger';
                            ?>
                                <tr class="text-center border-bottom">
                                    <td><?= $no++; ?></td>
                                    <td class="text-start">
                                        <div class="fw-bold"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                                        <div class="small text-muted">NISN: <?= $row['nisn'] ?></div>
                                    </td>
                                    <td><?= $row['nilai_tugas'] ?></td>
                                    <td><?= $row['nilai_uts'] ?></td>
                                    <td><?= $row['nilai_uas'] ?></td>
                                    <td>
                                        <span class="fw-bold <?= $status_color ?>">
                                            <?= number_format($rata, 1) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-folder-open fa-4x text-light"></i>
                </div>
                <h5 class="text-muted">Belum ada data nilai yang diinput.</h5>
                <a href="input_nilai.php" class="btn btn-primary mt-3 rounded-pill">
                    <i class="fas fa-plus me-2"></i>Mulai Input Nilai
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    @media print {
        .btn, .navbar, .footer { display: none !important; }
        .container { width: 100% !important; max-width: 100% !important; margin: 0 !important; }
        .card { box-shadow: none !important; border: none !important; }
    }
</style>

<?php include '../includes/footer.php'; ?>