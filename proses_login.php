<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dan bersihkan
    $identitas = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password  = md5($_POST['password']);

    // Cari di tabel users berdasarkan kolom identitas (bisa berisi NIP atau NISN)
    $query = mysqli_query($koneksi, 
        "SELECT * FROM users 
         WHERE identitas='$identitas' 
         AND password='$password'"
    );

    if (mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);

        // Set Session untuk digunakan di halaman dashboard
        $_SESSION['login']     = true;
        $_SESSION['id_user']   = $user['id_user'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['identitas'] = $user['identitas'];
        $_SESSION['nama']      = $user['nama'];

        // REDIRECT OTOMATIS BERDASARKAN ROLE
        if ($user['role'] == 'admin') {
            header("Location: ../dashboard_admin/index.php");
        } elseif ($user['role'] == 'guru') {
            header("Location: ../dashboard_guru/index.php");
        } elseif ($user['role'] == 'siswa') {
            header("Location: ../dashboard_siswa/index.php");
        } else {
            // Jika role tidak dikenal, lempar ke halaman utama
            header("Location: ../index.php");
        }
        exit;

    } else {
        // Jika salah, munculkan peringatan
        echo "<script>
            alert('NIP / NISN atau Password salah!');
            window.location='login.php';
        </script>";
    }
}
?>