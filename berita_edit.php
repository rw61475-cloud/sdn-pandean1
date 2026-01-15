<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. AMBIL ID DARI URL
$id = $_GET['id'];
$query_ambil = mysqli_query($koneksi, "SELECT * FROM berita WHERE id_berita = '$id'");
$data = mysqli_fetch_assoc($query_ambil);

// 3. LOGIKA UPDATE DATA
if(isset($_POST['update'])){
    $judul   = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi     = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $tanggal = $_POST['tanggal'];
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);

    $nama_gambar = $_FILES['gambar']['name'];
    $sumber_tmp  = $_FILES['gambar']['tmp_name'];
    
    // Pastikan path folder benar. Keluar satu folder ke root, lalu masuk ke assets
    $folder_tujuan = "../assets/img/berita/";

    // Cek apakah folder berita sudah ada, jika belum buat otomatis
    if (!file_exists($folder_tujuan)) {
        mkdir($folder_tujuan, 0777, true);
    }

    if(!empty($nama_gambar)) {
        // Jika upload berhasil, update database termasuk nama gambar baru
        if(move_uploaded_file($sumber_tmp, $folder_tujuan . $nama_gambar)) {
            $query = mysqli_query($koneksi, "UPDATE berita SET judul='$judul', isi='$isi', gambar='$nama_gambar', tanggal='$tanggal', penulis='$penulis' WHERE id_berita='$id'");
        }
    } else {
        // Jika gambar tidak diganti, update data lainnya saja
        $query = mysqli_query($koneksi, "UPDATE berita SET judul='$judul', isi='$isi', tanggal='$tanggal', penulis='$penulis' WHERE id_berita='$id'");
    }

    if($query){
        echo "<script>alert('Berita Berhasil