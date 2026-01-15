<?php
session_start();
include '../includes/db.php'; // Pastikan path koneksi benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identitas   = $_SESSION['identitas'];
    $old_pass    = md5($_POST['old_pass']);
    $new_pass    = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // 1. Cek apakah password lama benar
    $cek_user = mysqli_query($koneksi, "SELECT password FROM users WHERE identitas='$identitas'");
    $user = mysqli_fetch_assoc($cek_user);

    if ($old_pass !== $user['password']) {
        echo "<script>alert('Password lama salah!'); window.history.back();</script>";
        exit;
    }

    // 2. Cek apakah password baru dan konfirmasi cocok
    if ($new_pass !== $confirm_pass) {
        echo "<script>alert('Konfirmasi password baru tidak cocok!'); window.history.back();</script>";
        exit;
    }

    // 3. Update password baru (enkripsi MD5)
    $password_fix = md5($new_pass);
    $update = mysqli_query($koneksi, "UPDATE users SET password='$password_fix' WHERE identitas='$identitas'");

    if ($update) {
        echo "<script>alert('Password berhasil diperbarui! Silakan login ulang.'); window.location='../logout.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui password.'); window.history.back();</script>";
    }
}
?>