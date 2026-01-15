<?php
// 1. KONEKSI KE DATABASE
include '../includes/db.php';

// 2. IDENTITAS HALAMAN (Penting untuk Sidebar)
$page  = 'galeri';
$title = 'Manajemen Galeri | SDN Pandean 1';

// 3. LOGIKA CRUD GALERI
$target_dir = "../assets/img/galeri/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Logika Hapus Gambar
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = mysqli_prepare($koneksi, "SELECT gambar FROM galeri WHERE id_galeri=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    
    if ($data) {
        if (file_exists($target_dir . $data['gambar'])) {
            unlink($target_dir . $data['gambar']);
        }
        mysqli_query($koneksi, "DELETE FROM galeri WHERE id_galeri='$id'");
    }
    header("Location: galeri.php");
    exit;
}

// Logika Tambah Gambar
if (isset($_POST['submit'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tgl_input = $_POST['tanggal']; 
    $tanggal = date('d F Y', strtotime($tgl_input));
    
    $nama_file = $_FILES['gambar']['name'];
    $tmp_file = $_FILES['gambar']['tmp_name'];
    $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_baru = "GLR_" . time() . "." . $ekstensi; 
    
    if (move_uploaded_file($tmp_file, $target_dir . $nama_baru)) {
        mysqli_query($koneksi, "INSERT INTO galeri (judul, tanggal, gambar) VALUES ('$judul', '$tanggal', '$nama_baru')");
        echo "<script>alert('Galeri berhasil ditambahkan!'); window.location='galeri.php';</script>";
    }
}

// 4. PANGGIL HEADER & SIDEBAR
include "includes/header.php";
include "includes/sidebar.php";
?>

<style>
    :root { --primary: #008080; }
    .text-teal { color: var(--primary) !important; }
    .card-teal.card-outline { border-top: 4px solid var(--primary); }
    .img-preview { width: 80px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }
    .btn-teal { background: var(--primary); color: white; border-radius: 10px; padding: 0.5rem 1.2rem; border: none; }
    .btn-teal:hover { background: #006666; color: white; box-shadow: 0 4px 10px rgba(0, 128, 128, 0.2); }
</style>

<div class="content-wrapper pt-4 px-3">
    <div class="content-header">
        <div class="container-fluid">
            <h2 class="m-0 font-weight-bold text-dark">Manajemen Galeri Kegiatan</h2>
            <p class="text-muted">Kelola dokumentasi foto kegiatan sekolah untuk tampilan website publik.</p>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-teal card-outline shadow-sm" style="border-radius: 15px;">
                <div class="card-header border-transparent d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-images mr-1 text-teal"></i> Daftar Foto Kegiatan</h3>
                    <button class="btn btn-teal btn-sm ml-auto font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalTambah">
                        <i class="fas fa-plus mr-1"></i> Tambah Foto Baru
                    </button>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 table-hover">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th width="60" class="text-center">NO</th>
                                    <th width="120">PREVIEW</th>
                                    <th>JUDUL KEGIATAN</th>
                                    <th>WAKTU PELAKSANAAN</th>
                                    <th width="100" class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY id_galeri DESC");
                                if (mysqli_num_rows($query) > 0) {
                                    while($g = mysqli_fetch_assoc($query)):
                                ?>
                                <tr>
                                    <td class="text-center text-muted font-weight-bold"><?php echo str_pad($no++, 2, "0", STR_PAD_LEFT); ?></td>
                                    <td><img src="../assets/img/galeri/<?= $g['gambar']; ?>" class="img-preview shadow-sm"></td>
                                    <td class="font-weight-bold text-dark"><?= htmlspecialchars($g['judul']); ?></td>
                                    <td><span class="badge badge-success px-3 py-2" style="border-radius: 8px;"><?= htmlspecialchars($g['tanggal']); ?></span></td>
                                    <td class="text-center">
                                        <a href="galeri.php?hapus=<?= $g['id_galeri']; ?>" class="btn btn-xs btn-outline-danger border-0 px-2" onclick="return confirm('Hapus foto ini dari galeri?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; } else { ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted italic">Belum ada foto kegiatan di galeri.</td>
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

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 bg-teal-custom text-white" style="background-color: var(--primary); border-radius: 20px 20px 0 0;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-upload mr-2"></i> Tambah Galeri Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label class="text-sm font-weight-bold text-muted small">JUDUL KEGIATAN</label>
                    <input type="text" name="judul" class="form-control" placeholder="Misal: Upacara Bendera" style="border-radius: 10px;" required>
                </div>
                <div class="form-group mb-3">
                    <label class="text-sm font-weight-bold text-muted small">TANGGAL KEGIATAN</label>
                    <input type="date" name="tanggal" class="form-control" style="border-radius: 10px;" required>
                </div>
                <div class="form-group">
                    <label class="text-sm font-weight-bold text-muted small">PILIH FILE GAMBAR</label>
                    <div class="custom-file shadow-sm">
                        <input type="file" name="gambar" class="custom-file-input" id="customFile" accept="image/*" required>
                        <label class="custom-file-label" for="customFile" style="border-radius: 10px;">Cari foto...</label>
                    </div>
                    <small class="text-muted d-block mt-2 font-italic text-xs">*Gunakan format JPG, PNG, atau JPEG</small>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 10px;">Batal</button>
                <button type="submit" name="submit" class="btn btn-teal px-4 font-weight-bold shadow-sm" style="border-radius: 10px;">Simpan Galeri</button>
            </div>
        </form>
    </div>
</div>

<?php 
// 5. PANGGIL FOOTER
include "includes/footer.php"; 
?>

<script>
    $(document).ready(function () {
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    });
</script>