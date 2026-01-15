<?php
// 1. KONEKSI KE DATABASE
include "../includes/db.php";

// 2. TANGKAP PARAMETER KELAS
if (!isset($_GET['kelas']) || empty($_GET['kelas'])) {
    header("Location: mata_pelajaran.php");
    exit;
}

$kelas_pilihan = mysqli_real_escape_string($koneksi, $_GET['kelas']);

// 3. PROSES SIMPAN DATA (Jika Form Disubmit)
if (isset($_POST['simpan_mapel'])) {
    $nama_mapel = mysqli_real_escape_string($koneksi, $_POST['nama_mapel']);
    $kkm = mysqli_real_escape_string($koneksi, $_POST['kkm']);
    
    $query_tambah = mysqli_query($koneksi, "INSERT INTO mapel (nama_mapel, tingkat_kelas, kkm) VALUES ('$nama_mapel', '$kelas_pilihan', '$kkm')");
    
    if ($query_tambah) {
        echo "<script>alert('Mata Pelajaran berhasil ditambahkan!'); window.location.href='detail_mapel.php?kelas=$kelas_pilihan';</script>";
    }
}

// 4. PROSES EDIT DATA
if (isset($_POST['update_mapel'])) {
    $id_mapel = mysqli_real_escape_string($koneksi, $_POST['id_mapel']);
    $nama_mapel = mysqli_real_escape_string($koneksi, $_POST['nama_mapel']);
    $kkm = mysqli_real_escape_string($koneksi, $_POST['kkm']);
    
    $query_update = mysqli_query($koneksi, "UPDATE mapel SET nama_mapel='$nama_mapel', kkm='$kkm' WHERE id_mapel='$id_mapel'");
    
    if ($query_update) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='detail_mapel.php?kelas=$kelas_pilihan';</script>";
    }
}

// 5. PROSES HAPUS DATA
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $query_hapus = mysqli_query($koneksi, "DELETE FROM mapel WHERE id_mapel='$id_hapus'");
    
    if ($query_hapus) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='detail_mapel.php?kelas=$kelas_pilihan';</script>";
    }
}

// 6. QUERY AMBIL DATA
$query_mapel = mysqli_query($koneksi, "SELECT * FROM mapel WHERE tingkat_kelas = '$kelas_pilihan' ORDER BY nama_mapel ASC");
$total_mapel = mysqli_num_rows($query_mapel);

// 7. IDENTITAS HALAMAN (Untuk Sidebar Otomatis)
$page  = 'mata_pelajaran'; 
$title = "Detail Mapel Kelas " . $kelas_pilihan;

// 8. PANGGIL HEADER & SIDEBAR
include "includes/header.php";
include "includes/sidebar.php";
?>

<style>
    /* Tambahan Style agar tetap konsisten dengan desain premium Anda */
    .btn-teal { background: #008080; color: #fff; border-radius: 10px; font-weight: 600; border: none; }
    .btn-teal:hover { background: #006666; color: #fff; }
    .card-custom { border-radius: 15px; border: 1px solid #e2e8f0; }
    .mapel-icon { width: 40px; height: 40px; background: #e6f2f2; color: #008080; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    
    @media print {
        .main-sidebar, .main-header, .main-footer, .btn, .aksi-column, .nav-item { display: none !important; }
        .content-wrapper { margin-left: 0 !important; padding-top: 0 !important; width: 100% !important; }
    }
</style>

<div class="content-wrapper pt-4 px-3">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="font-weight-bold text-dark mb-0">Kurikulum Kelas <?php echo htmlspecialchars($kelas_pilihan); ?></h2>
                <p class="text-muted">Manajemen mata pelajaran dan standar nilai KKM.</p>
            </div>
            <div class="d-none d-sm-block">
                <button onclick="window.print()" class="btn btn-light border px-3 mr-2 shadow-sm">
                    <i class="fas fa-print mr-1"></i> Cetak
                </button>
                <button class="btn btn-teal px-4 py-2 shadow-sm" data-toggle="modal" data-target="#modalTambahMapel">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Mapel
                </button>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-custom shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="font-weight-bold text-dark"><i class="fas fa-book-open mr-2 text-teal"></i> Daftar Mata Pelajaran</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center" width="70">NO</th>
                                    <th>NAMA MATA PELAJARAN</th>
                                    <th class="text-center">KKM</th>
                                    <th class="text-right pr-4 aksi-column">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                if ($total_mapel > 0) {
                                    while($row = mysqli_fetch_assoc($query_mapel)) {
                                ?>
                                <tr>
                                    <td class="text-center text-muted font-weight-bold"><?php echo $no++; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mapel-icon mr-3"><i class="fas fa-bookmark"></i></div>
                                            <span class="font-weight-bold text-dark"><?php echo htmlspecialchars($row['nama_mapel']); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light border px-3 py-2 text-teal" style="font-size: 0.9rem;">
                                            <?php echo $row['kkm']; ?>
                                        </span>
                                    </td>
                                    <td class="text-right pr-4 aksi-column">
                                        <button class="btn btn-sm btn-light border" data-toggle="modal" data-target="#modalEdit<?php echo $row['id_mapel']; ?>" title="Edit">
                                            <i class="fas fa-edit text-primary"></i>
                                        </button>
                                        <a href="detail_mapel.php?kelas=<?php echo $kelas_pilihan; ?>&hapus=<?php echo $row['id_mapel']; ?>" 
                                           class="btn btn-sm btn-light border" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')" title="Hapus">
                                            <i class="fas fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalEdit<?php echo $row['id_mapel']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                            <div class="modal-header border-0 pt-4 px-4">
                                                <h5 class="modal-title font-weight-bold">Edit Mata Pelajaran</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="" method="POST">
                                                <input type="hidden" name="id_mapel" value="<?php echo $row['id_mapel']; ?>">
                                                <div class="modal-body px-4">
                                                    <div class="form-group">
                                                        <label class="small font-weight-bold text-muted">NAMA MATA PELAJARAN</label>
                                                        <input type="text" name="nama_mapel" class="form-control form-control-lg" value="<?php echo htmlspecialchars($row['nama_mapel']); ?>" required style="border-radius: 10px; font-size: 1rem;">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="small font-weight-bold text-muted">KKM</label>
                                                        <input type="number" name="kkm" class="form-control form-control-lg" value="<?php echo $row['kkm']; ?>" required style="border-radius: 10px; font-size: 1rem;">
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pb-4 px-4">
                                                    <button type="button" class="btn btn-light px-4" data-dismiss="modal">Batal</button>
                                                    <button type="submit" name="update_mapel" class="btn btn-teal px-4 shadow-sm">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <?php } } else { ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
                                        Belum ada mata pelajaran untuk kelas ini.
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalTambahMapel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 15px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold">Tambah Mata Pelajaran</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" method="POST">
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">NAMA MATA PELAJARAN</label>
                        <input type="text" name="nama_mapel" class="form-control form-control-lg" placeholder="Masukkan nama mapel..." required style="border-radius: 10px; font-size: 1rem;">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">STANDAR KKM</label>
                        <input type="number" name="kkm" class="form-control form-control-lg" value="75" required style="border-radius: 10px; font-size: 1rem;">
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan_mapel" class="btn btn-teal px-4 shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
include "includes/footer.php"; 
?>