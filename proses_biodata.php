<?php
include '../includes/db.php';
session_start();

// Proteksi jika session hilang
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user        = $_SESSION['id_user'];
$nama_lengkap   = $_POST['nama_lengkap'];
$mata_pelajaran = $_POST['mata_pelajaran'];
$no_hp          = $_POST['no_hp'];
$alamat         = $_POST['alamat'];

// 1. Perbaikan Baris 21: Mencari berdasarkan id_guru (bukan id_user)
$check = $koneksi->prepare("SELECT id_user FROM guru WHERE id_user = ?");
$check->bind_param("i", $id_user);
$check->execute();
$res_check = $check->get_result();

if ($res_check->num_rows > 0) {
    // 2. Jika data sudah ada di tabel guru, lakukan UPDATE
    $sql = "UPDATE guru SET nama_lengkap=?, mata_pelajaran=?, no_hp=?, alamat=? WHERE id_user=?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssssi", $nama_lengkap, $mata_pelajaran, $no_hp, $alamat, $id_user);
} else {
    // 3. Jika data belum ada di tabel guru, lakukan INSERT
    // Ambil NIP dari session identitas
    $nip = $_SESSION['identitas'] ?? ''; 
    $sql = "INSERT INTO guru (id_guru, nip, nama_lengkap, mata_pelajaran, no_hp, alamat, foto) VALUES (?, ?, ?, ?, ?, ?, 'default.png')";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("isssss", $id_user, $nip, $nama_lengkap, $mata_pelajaran, $no_hp, $alamat);
}

// Eksekusi statement
if ($stmt->execute()) {
    echo "<script>alert('Biodata berhasil diperbarui!'); window.location='profil.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
?>