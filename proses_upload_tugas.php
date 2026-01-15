<?php
include '../includes/db.php';
session_start();

if (isset($_POST['submit_tugas'])) {
    $id_tugas  = $_POST['id_tugas'];
    $id_jadwal = $_POST['id_jadwal'];
    $id_user   = $_SESSION['id_user']; // ID Siswa yang sedang login
    
    $nama_file = $_FILES['file_jawaban']['name'];
    $tmp_file  = $_FILES['file_jawaban']['tmp_name'];
    
    // 1. Tentukan Folder tujuan
    $folder = "uploads_jawaban/";
    
    // 2. Buat folder jika belum ada
    if (!is_dir($folder)) { 
        mkdir($folder, 0777, true); 
    }

    // 3. Beri nama unik agar file tidak tertukar antar siswa
    $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_baru = "TUGAS_ID" . $id_tugas . "_SISWA" . $id_user . "_" . date('dmYHis') . "." . $ekstensi;

    // 4. Proses Pindahan File dan Simpan ke Database
    if (move_uploaded_file($tmp_file, $folder . $nama_baru)) {
        // Simpan ke tabel tugas_siswa (Pastikan tabel ini sudah kamu buat di SQL sebelumnya)
        $query = "INSERT INTO tugas_siswa (id_tugas, id_user, file_jawaban) VALUES ('$id_tugas', '$id_user', '$nama_baru')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Berhasil! Tugas kamu sudah terkirim.'); window.location='daftar_tugas_siswa.php?id_jadwal=$id_jadwal';</script>";
        } else {
            echo "Error Database: " . mysqli_error($koneksi);
        }
    } else {
        echo "<script>alert('Gagal mengunggah file. Coba periksa folder uploads_jawaban'); window.history.back();</script>";
    }
} else {
    // Jika diakses langsung tanpa submit, tendang balik
    header("Location: jadwal_siswa.php");
}