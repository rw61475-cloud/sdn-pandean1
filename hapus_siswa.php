<?php 
include '../includes/db.php';

if (isset($_GET['nisn'])) {
    $nisn = $_GET['nisn'];
    
    // Gunakan NISN sebagai kunci penghapusan
    $hapus = mysqli_query($koneksi, "DELETE FROM siswa WHERE nisn = '$nisn'");

    if ($hapus) {
        echo "<script>alert('Siswa Berhasil Dihapus!'); window.location='data_siswa.php';</script>";
    } else {
        echo "<script>alert('Gagal Hapus Data!'); window.location='data_siswa.php';</script>";
    }
} else {
    header("Location: data_siswa.php");
}
?>