<?php
include "../includes/db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($koneksi, "DELETE FROM jadwal WHERE id_jadwal = '$id'");

    if ($query) {
        echo "<script>alert('Jadwal berhasil dihapus!'); window.location.href='jadwal.php';</script>";
    }
}
?>