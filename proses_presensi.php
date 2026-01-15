<?php
include '../includes/db.php';
session_start();

// Mengatur zona waktu agar sesuai dengan waktu lokal (WIB)
date_default_timezone_set('Asia/Jakarta');

if (isset($_POST['submit_presensi'])) {
    // Mengambil data dari form dengan proteksi sederhana
    // Pastikan input name di form index.php adalah 'id_guru', 'nip', dan 'status'
    $id_guru = mysqli_real_escape_string($koneksi, $_POST['id_guru']);
    $nip     = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $status  = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    $tanggal = date('Y-m-d');
    $jam     = date('H:i:s');

    // 1. Cek apakah variabel id_guru atau nip kosong untuk menghindari error database
    if (empty($id_guru) || empty($nip)) {
        echo "<script>alert('Data Guru tidak terdeteksi. Silahkan login ulang.'); window.location='index.php';</script>";
        exit;
    }

    // 2. Cek duplikasi agar tidak double absen di tanggal yang sama
    // Menggunakan Prepared Statement untuk keamanan
    $stmt_cek = $koneksi->prepare("SELECT id FROM presensi WHERE nip = ? AND tanggal = ?");
    $stmt_cek->bind_param("ss", $nip, $tanggal);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();
    
    if ($result_cek->num_rows > 0) {
        echo "<script>alert('Anda sudah melakukan presensi hari ini!'); window.location='index.php';</script>";
    } else {
        // 3. Query Insert menggunakan Prepared Statement
        // Struktur kolom: id_guru, nip, tanggal, jam_masuk, status
        $stmt_insert = $koneksi->prepare("INSERT INTO presensi (id_guru, nip, tanggal, jam_masuk, status) VALUES (?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("sssss", $id_guru, $nip, $tanggal, $jam, $status);
        
        if ($stmt_insert->execute()) {
            echo "<script>alert('Presensi berhasil disimpan!'); window.location='index.php';</script>";
        } else {
            echo "Terjadi kesalahan saat menyimpan: " . $koneksi->error;
        }
        $stmt_insert->close();
    }
    $stmt_cek->close();

} else {
    // Jika mencoba akses langsung tanpa submit form
    header("Location: index.php");
    exit;
}
?>