<?php
// Hubungkan ke database menggunakan file yang sudah Anda miliki
include '../includes/db.php';

if (isset($_POST['identitas'])) {
    $identitas = mysqli_real_escape_string($koneksi, $_POST['identitas']);
    
    // 1. Cari di tabel guru terlebih dahulu berdasarkan NIP
    $query_guru = mysqli_query($koneksi, "SELECT nama_lengkap, 'guru' as role FROM guru WHERE nip = '$identitas'");
    
    if (mysqli_num_rows($query_guru) > 0) {
        $data = mysqli_fetch_assoc($query_guru);
        echo json_encode($data);
    } else {
        // 2. Jika tidak ada di guru, cari di tabel siswa berdasarkan NISN
        $query_siswa = mysqli_query($koneksi, "SELECT nama_lengkap, 'siswa' as role FROM siswa WHERE nisn = '$identitas'");
        
        if (mysqli_num_rows($query_siswa) > 0) {
            $data = mysqli_fetch_assoc($query_siswa);
            echo json_encode($data);
        } else {
            // Jika tidak ditemukan di keduanya
            echo json_encode(['nama_lengkap' => '', 'role' => '']);
        }
    }
}
?>