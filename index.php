<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. IDENTITAS HALAMAN (Untuk Sidebar Otomatis)
$page  = 'dashboard';
$title = 'Admin Dashboard';

// 3. QUERY AMBIL TOTAL STATISTIK
$query_siswa = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa");
$total_siswa = mysqli_fetch_assoc($query_siswa)['total'] ?? 0;

$query_guru = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM guru");
$total_guru = mysqli_fetch_assoc($query_guru)['total'] ?? 0;

$query_kelas = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kelas");
$total_kelas = mysqli_fetch_assoc($query_kelas)['total'] ?? 0;

$query_mapel_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mapel");
$total_mapel_semua = mysqli_fetch_assoc($query_mapel_total)['total'] ?? 0;

// 4. LOGIKA STATISTIK PRESENSI GURU HARI INI
$hari_ini = date('Y-m-d');
$query_presensi_today = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM presensi WHERE tanggal = '$hari_ini' AND status = 'Hadir'");
$jumlah_guru_hadir = mysqli_fetch_assoc($query_presensi_today)['total'] ?? 0;

// Hitung Persentase Kehadiran Guru untuk Progress Bar
$persen_guru = ($total_guru > 0) ? ($jumlah_guru_hadir / $total_guru) * 100 : 0;

// 5. PANGGIL HEADER & SIDEBAR OTOMATIS
include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper pt-4 px-3">
    <div class="content-header">
        <div class="container-fluid">
            <h2 class="m-0 font-weight-bold text-dark">Monitoring Sistem Informasi Sekolah</h2>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info shadow-sm">
                        <div class="inner">
                            <h3><?php echo number_format($total_siswa, 0, ',', '.'); ?></h3>
                            <p>Total Siswa</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-graduate"></i></div>
                        <a href="siswa.php" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-teal shadow-sm">
                        <div class="inner">
                            <h3 class="text-white"><?php echo number_format($total_guru, 0, ',', '.'); ?></h3>
                            <p class="text-white">Tenaga Pengajar</p>
                        </div>
                        <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <a href="guru.php" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow-sm">
                        <div class="inner">
                            <h3><?php echo number_format($total_kelas, 0, ',', '.'); ?></h3>
                            <p>Ruang Kelas</p>
                        </div>
                        <div class="icon"><i class="fas fa-school"></i></div>
                        <a href="ruang_kelas.php" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger shadow-sm">
                        <div class="inner">
                            <h3><?php echo number_format($total_mapel_semua, 0, ',', '.'); ?></h3>
                            <p>Mata Pelajaran</p>
                        </div>
                        <div class="icon"><i class="fas fa-book"></i></div>
                        <a href="mata_pelajaran.php" class="small-box-footer">Cek Mapel <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-teal card-outline shadow-sm">
                        <div class="card-header border-transparent">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-user-check mr-1 text-teal"></i> Kehadiran Guru Hari Ini
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0 table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Guru</th>
                                            <th>Jam Masuk</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Menggunakan nama_lengkap sesuai struktur tabel guru Anda
                                        $sql_presensi = "SELECT p.*, g.nama_lengkap 
                                                        FROM presensi p 
                                                        JOIN guru g ON p.nip = g.nip 
                                                        WHERE p.tanggal = '$hari_ini' 
                                                        ORDER BY p.jam_masuk DESC LIMIT 5";
                                        
                                        $query_tabel_presensi = mysqli_query($koneksi, $sql_presensi);

                                        if ($query_tabel_presensi && mysqli_num_rows($query_tabel_presensi) > 0) {
                                            while ($row = mysqli_fetch_assoc($query_tabel_presensi)) {
                                                $status = $row['status'];
                                                $badge_color = ($status == 'Hadir') ? 'badge-success' : (($status == 'Izin' || $status == 'Sakit') ? 'badge-warning' : 'badge-info');
                                        ?>
                                        <tr>
                                            <td class="font-weight-bold"><?php echo $row['nama_lengkap']; ?></td>
                                            <td><i class="far fa-clock text-primary mr-1"></i> <?php echo $row['jam_masuk']; ?> WIB</td>
                                            <td class="text-center">
                                                <span class="badge <?php echo $badge_color; ?> px-2 py-1"><?php echo $status; ?></span>
                                            </td>
                                        </tr>
                                        <?php 
                                            }
                                        } else {
                                            echo "<tr><td colspan='3' class='text-center py-4 text-muted italic'>Belum ada guru yang melakukan presensi hari ini.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-center bg-white">
                            <a href="rekap_presensi.php" class="text-teal font-weight-bold">Lihat Laporan Kehadiran Lengkap</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-teal">
                            <h3 class="card-title font-weight-bold text-white"><i class="fas fa-info-circle mr-1"></i> Informasi Sekolah</h3>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><b>Semester:</b> <span class="float-right badge badge-light border">Ganjil 2025/2026</span></p>
                            <p class="mb-2 text-sm text-muted">Update: <?php echo date('d M Y - H:i'); ?> WIB</p>
                            <hr>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-sm">Guru Hadir Hari Ini:</span>
                                    <span class="text-sm font-weight-bold"><?= $jumlah_guru_hadir; ?>/<?= $total_guru; ?></span>
                                </div>
                                <div class="progress progress-xs mt-1 shadow-sm">
                                    <div class="progress-bar bg-teal" style="width: <?= $persen_guru; ?>%"></div>
                                </div>
                            </div>

                            <div class="list-group list-group-flush mt-3">
                                <a href="data_siswa.php" class="list-group-item list-group-item-action text-sm">
                                    <i class="fas fa-user-graduate mr-2 text-info"></i> Data Siswa
                                </a>
                                <a href="rekap_presensi.php" class="list-group-item list-group-item-action text-sm">
                                    <i class="fas fa-check-circle mr-2 text-success"></i> Kehadiran Guru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php 
include "includes/footer.php"; 
?>