<?php
// 1. KONEKSI KE DATABASE
include '../includes/db.php'; 

// 2. AMBIL DATA DARI URL
if (!isset($_GET['kelas']) || empty($_GET['kelas'])) {
    header("Location: ruang_kelas.php");
    exit;
}

$nama_kelas_url = $_GET['kelas'];
$nama_kelas_db = mysqli_real_escape_string($koneksi, $nama_kelas_url);

// Logika untuk mencocokkan angka kelas jika perlu
$angka_kelas = preg_replace('/[^0-9]/', '', $nama_kelas_url);

// 3. QUERY DAFTAR SISWA
$query_siswa = mysqli_query($koneksi, "SELECT * FROM siswa 
    WHERE TRIM(kelas) = '$nama_kelas_db' 
    OR TRIM(kelas) = '$angka_kelas' 
    ORDER BY nama_lengkap ASC");

$total_siswa = mysqli_num_rows($query_siswa);

// 4. IDENTITAS HALAMAN (Agar Sidebar Mengetahui Menu Mana yang Aktif)
$page  = 'ruang_kelas'; // Sesuaikan dengan logika di sidebar.php Anda
$title = "Daftar Siswa Kelas " . htmlspecialchars($nama_kelas_url);

// 5. PANGGIL HEADER & SIDEBAR (Sama seperti index.php)
// Pastikan path folder 'includes' benar. 
// Jika daftar_siswa.php ada di folder 'admin', gunakan include "includes/header.php";
include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper pt-4 px-3">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark">Kelas: <?php echo htmlspecialchars($nama_kelas_url); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Admin</a></li>
                        <li class="breadcrumb-item"><a href="ruang_kelas.php">Ruang Kelas</a></li>
                        <li class="breadcrumb-item active">Daftar Siswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            <div class="card card-teal card-outline shadow-sm">
                <div class="card-header border-0 bg-white pt-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-users mr-2 text-teal"></i> Tabel Data Siswa
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-teal px-3 py-2" style="font-size: 0.9rem;">
                            Total: <?php echo $total_siswa; ?> Siswa
                        </span>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center" width="70">No</th>
                                    <th>Nama Lengkap</th>
                                    <th>NISN</th>
                                    <th>Alamat</th>
                                    <th class="text-right pr-4">Kontak Ortu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                if ($total_siswa > 0) {
                                    while($s = mysqli_fetch_assoc($query_siswa)) {
                                ?>
                                <tr>
                                    <td class="text-center text-muted font-weight-bold"><?php echo str_pad($no++, 2, "0", STR_PAD_LEFT); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar mr-3">
                                                <i class="fas fa-user-circle fa-2x text-gray"></i>
                                            </div>
                                            <div>
                                                <span class="font-weight-bold text-dark d-block"><?php echo htmlspecialchars($s['nama_lengkap']); ?></span>
                                                <small class="text-muted">Siswa Aktif</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-teal px-2 py-1">
                                            <?php echo htmlspecialchars($s['nisn']); ?>
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size: 0.9rem;">
                                        <?php echo htmlspecialchars($s['alamat']); ?>
                                    </td>
                                    <td class="text-right pr-4">
                                        <?php if(!empty($s['no_hp_ortu'])): ?>
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $s['no_hp_ortu']); ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fab fa-whatsapp"></i> Hubungi
                                        </a>
                                        <?php else: ?>
                                        <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'><i>Belum ada data siswa untuk kelas ini.</i></td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="ruang_kelas.php" class="btn btn-default">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Kelas
                    </a>
                </div>
            </div>

        </div>
    </section>
</div>

<?php 
// 6. PANGGIL FOOTER
include "includes/footer.php"; 
?>