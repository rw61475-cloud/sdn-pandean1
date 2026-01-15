<?php
// 1. KONEKSI KE DATABASE
include "../includes/db.php";

if (isset($_POST['simpan'])) {
    // 2. AMBIL DATA DARI FORM
    // mysqli_real_escape_string digunakan untuk keamanan dari SQL Injection
    $hari         = mysqli_real_escape_string($koneksi, $_POST['hari']);
    $kelas        = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jam_mulai    = mysqli_real_escape_string($koneksi, $_POST['jam_mulai']);
    $jam_selesai  = mysqli_real_escape_string($koneksi, $_POST['jam_selesai']);
    $mapel        = mysqli_real_escape_string($koneksi, $_POST['mapel']);
    $id_guru      = mysqli_real_escape_string($koneksi, $_POST['id_guru']);

    // 3. QUERY INSERT KE TABEL JADWAL
    $query = "INSERT INTO jadwal (id_guru, mapel, hari, jam_mulai, jam_selesai, kelas) 
              VALUES ('$id_guru', '$mapel', '$hari', '$jam_mulai', '$jam_selesai', '$kelas')";

    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, kembali ke halaman jadwal dengan pesan sukses
        echo "<script>
                alert('Jadwal berhasil ditambahkan!');
                window.location.href='jadwal.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
} else {
    // Jika mencoba akses langsung tanpa tekan tombol simpan
    header("Location: jadwal.php");
}
?>