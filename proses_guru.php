<?php
include "../includes/db.php";

// Pastikan parameter aksi ada
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// Folder tujuan upload
$target_dir = "../dashboard_guru/foto/";

if ($aksi == 'tambah') {
    $nip    = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $mapel  = mysqli_real_escape_string($koneksi, $_POST['mata_pelajaran']);
    $hp     = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    
    // Logika Upload Foto saat Tambah
    $nama_file = "";
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_file = "guru_" . $nip . "." . $ext; // Rename foto agar unik berdasarkan NIP
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $nama_file);
    }

    $query = "INSERT INTO guru (nip, nama_lengkap, mata_pelajaran, no_hp, foto) 
              VALUES ('$nip', '$nama', '$mapel', '$hp', '$nama_file')";
    
    if (mysqli_query($koneksi, $query)) {
        header("location:guru.php?status=sukses_tambah");
    } else {
        echo "Gagal Tambah: " . mysqli_error($koneksi);
    }

} elseif ($aksi == 'edit') {
    $nip    = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $mapel  = mysqli_real_escape_string($koneksi, $_POST['mata_pelajaran']);
    $hp     = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    // 1. Cek apakah ada file foto baru yang diunggah
    if (!empty($_FILES['foto']['name'])) {
        
        // Ambil info foto lama untuk dihapus
        $data_lama = mysqli_query($koneksi, "SELECT foto FROM guru WHERE nip='$nip'");
        $row = mysqli_fetch_assoc($data_lama);
        
        if (!empty($row['foto']) && file_exists($target_dir . $row['foto'])) {
            unlink($target_dir . $row['foto']); // Hapus file fisik lama
        }

        // Upload foto baru
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_file_baru = "guru_" . $nip . "_" . time() . "." . $ext; // Tambah timestamp agar tidak bentrok cache browser
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $nama_file_baru);

        // Update dengan foto baru
        $query = "UPDATE guru SET 
                  nama_lengkap='$nama', 
                  mata_pelajaran='$mapel', 
                  no_hp='$hp',
                  foto='$nama_file_baru'
                  WHERE nip='$nip'";
    } else {
        // Update tanpa mengubah foto
        $query = "UPDATE guru SET 
                  nama_lengkap='$nama', 
                  mata_pelajaran='$mapel', 
                  no_hp='$hp' 
                  WHERE nip='$nip'";
    }
    
    if (mysqli_query($koneksi, $query)) {
        header("location:guru.php?status=sukses_edit");
    } else {
        echo "Gagal Update: " . mysqli_error($koneksi);
    }

} elseif ($aksi == 'hapus') {
    $nip = mysqli_real_escape_string($koneksi, $_GET['nip']);
    
    // Hapus foto fisik sebelum hapus data di database
    $data_lama = mysqli_query($koneksi, "SELECT foto FROM guru WHERE nip='$nip'");
    $row = mysqli_fetch_assoc($data_lama);
    if (!empty($row['foto']) && file_exists($target_dir . $row['foto'])) {
        unlink($target_dir . $row['foto']);
    }

    $query = "DELETE FROM guru WHERE nip='$nip'";
    
    if (mysqli_query($koneksi, $query)) {
        header("location:guru.php?status=sukses_hapus");
    } else {
        echo "Gagal Hapus: " . mysqli_error($koneksi);
    }
} else {
    header("location:guru.php");
}
?>