<?php
include '../includes/auth_guru.php';
include '../includes/db.php';

$id_user = $_SESSION['id_user'];

$nama   = $_POST['nama_lengkap'];
$alamat = $_POST['alamat'];
$no_hp  = $_POST['no_hp'];

mysqli_query($koneksi,
    "UPDATE guru SET
     nama_lengkap='$nama',
     alamat='$alamat',
     no_hp='$no_hp'
     WHERE id_user='$id_user'"
);

echo "<script>
    alert('Biodata berhasil diperbarui');
    window.location='biodata.php';
</script>";
