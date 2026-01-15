<?php
include "../includes/db.php";

// Ambil ID dari URL
$id = $_GET['id'];

// 1. Ambil nama file gambar dulu sebelum datanya dihapus
$query_gambar = mysqli_query($koneksi, "SELECT gambar FROM berita WHERE id_berita = '$id'");
$data = mysqli_fetch_assoc($query_gambar);
$nama_file = $data['gambar'];

// 2. Hapus file fisik dari folder assets
if ($nama_file != "" && file_exists("../assets/img/berita/" . $nama_file)) {
    unlink("../assets/img/berita/" . $nama_file);
}

// 3. Hapus data dari database
$query_hapus = mysqli_query($koneksi, "DELETE FROM berita WHERE id_berita = '$id'");

if ($query_hapus) {
    echo "<script>alert('Berita berhasil dihapus!'); window.location='berita_tampil.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data'); window.location='berita_tampil.php';</script>";
}
?>