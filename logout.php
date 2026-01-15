<?php
/**
 * File: auth/logout.php
 * Deskripsi: Menghapus session pengguna dan mengarahkan kembali ke halaman login.
 * Lokasi: UAS/auth/logout.php
 */

// 1. Mulai session untuk mengakses data yang akan dihapus
session_start();

// 2. Hapus semua variabel session
$_SESSION = array();

// 3. Hapus cookie session dari browser untuk keamanan maksimal
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Hancurkan seluruh data session di server
session_destroy();

// 5. Berikan notifikasi dan arahkan kembali ke login.php
// Karena login.php dan logout.php berada di folder yang sama (auth), 
// maka cukup tulis 'login.php' tanpa '../'
echo "<script>
    alert('Anda telah berhasil keluar dari sistem SDN Pandean 1.');
    window.location.href = 'login.php'; 
</script>";
exit;
?>