<?php 
include '../includes/header_guru.php'; 
include '../includes/db.php';

$nisn = $_GET['nisn'];
// Ambil data siswa berdasarkan NISN
$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nisn = '$nisn'");
$data = mysqli_fetch_assoc($query);

// Proses Update
if (isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $kelas  = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $hp     = mysqli_real_escape_string($koneksi, $_POST['no_hp_ortu']);

    $sql = "UPDATE siswa SET 
            nama_lengkap = '$nama', 
            kelas = '$kelas', 
            alamat = '$alamat', 
            no_hp_ortu = '$hp' 
            WHERE nisn = '$nisn'";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Berhasil Update!'); window.location='data_siswa.php';</script>";
    }
}
?>

<div class="container mt-5">
    <div class="card shadow-sm border-0 p-4 mx-auto" style="border-radius: 24px; max-width: 550px;">
        <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                <i class="fas fa-user-edit fa-2x text-primary"></i>
            </div>
            <h4 class="fw-bold">Edit Profil Siswa</h4>
        </div>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">NISN Siswa</label>
                <input type="text" class="form-control bg-light py-2" value="<?= $data['nisn']; ?>" readonly style="cursor: not-allowed;">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control py-2" value="<?= $data['nama_lengkap']; ?>" required>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label small fw-bold">Kelas</label>
                    <input type="text" name="kelas" class="form-control py-2" value="<?= $data['kelas']; ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">No. HP Orang Tua (WA)</label>
                <input type="text" name="no_hp_ortu" class="form-control py-2" value="<?= $data['no_hp_ortu']; ?>">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Alamat Rumah</label>
                <textarea name="alamat" class="form-control py-2" rows="3"><?= $data['alamat']; ?></textarea>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" name="update" class="btn btn-primary py-2 fw-bold rounded-pill">Simpan Perubahan</button>
                <a href="data_siswa.php" class="btn btn-light py-2 rounded-pill border">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>