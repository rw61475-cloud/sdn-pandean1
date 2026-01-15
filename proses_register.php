<?php
include "../includes/db.php";

// 1. LOGIKA HAPUS AKUN
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    // Hapus di users (tabel siswa/guru akan mengikuti jika ada RELASI ON DELETE CASCADE)
    $hapus = mysqli_query($koneksi, "DELETE FROM users WHERE id_user = '$id'");
    header("Location: register.php?pesan=terhapus");
}

// 2. LOGIKA SIMPAN AKUN BARU
if (isset($_POST['simpan'])) {
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $identitas = mysqli_real_escape_string($koneksi, $_POST['identitas']);
    $email     = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role      = $_POST['role'];
    $pass_raw  = $_POST['password'];

    // CEK APAKAH NISN/NIP SUDAH ADA
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE identitas = '$identitas'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('❌ Akun dengan NISN/NIP ini sudah ada!'); window.location='register.php';</script>";
        exit;
    }

    // ENKRIPSI PASSWORD (Penting agar tidak 'Password Salah')
    $password_hash = password_hash($pass_raw, PASSWORD_DEFAULT);

    // SIMPAN KE TABEL USERS
    $query_user = "INSERT INTO users (identitas, nama_lengkap, email, password, role) 
                   VALUES ('$identitas', '$nama', '$email', '$password_hash', '$role')";
    
    if (mysqli_query($koneksi, $query_user)) {
        $id_baru = mysqli_insert_id($koneksi);

        // OTOMATISASI: Jika dia siswa, buatkan record di tabel siswa agar bisa pilih kelas
        if ($role == 'siswa') {
            mysqli_query($koneksi, "INSERT INTO siswa (id_user, nisn, nama_lengkap) VALUES ('$id_baru', '$identitas', '$nama')");
        } 
        // Jika dia guru, buatkan record di tabel guru
        elseif ($role == 'guru') {
            mysqli_query($koneksi, "INSERT INTO guru (id_user, nip, nama_lengkap) VALUES ('$id_baru', '$identitas', '$nama')");
        }

        echo "<script>alert('✅ Akun berhasil dibuat!'); window.location='register.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}