<?php
session_start();
include '../includes/db.php';

if (isset($_POST['simpan_presensi'])) {
    $id_guru_pengabsen = $_SESSION['id_user'];
    $tanggal_hari_ini  = date('Y-m-d');
    $jam_absen         = date('H:i:s');
    
    $daftar_nisn  = $_POST['nisn']; 
    $status_array = $_POST['status']; 
    $kelas_array  = $_POST['kelas']; 

    foreach ($daftar_nisn as $nisn) {
        $status = $status_array[$nisn];
        $kelas  = $kelas_array[$nisn];
        
        $cek = mysqli_query($koneksi, "SELECT id_presensi FROM presensi_siswa WHERE nisn = '$nisn' AND tanggal = '$tanggal_hari_ini'");

        if (mysqli_num_rows($cek) > 0) {
            $query = "UPDATE presensi_siswa SET status = '$status', id_guru = '$id_guru_pengabsen', jam_absen = '$jam_absen' 
                      WHERE nisn = '$nisn' AND tanggal = '$tanggal_hari_ini'";
        } else {
            $query = "INSERT INTO presensi_siswa (nisn, id_guru, kelas, tanggal, status, jam_absen) 
                      VALUES ('$nisn', '$id_guru_pengabsen', '$kelas', '$tanggal_hari_ini', '$status', '$jam_absen')";
        }
        mysqli_query($koneksi, $query);
    }
    echo "<script>alert('Data Berhasil Disimpan!'); window.location='rekap_presensi_siswa.php';</script>";
}
?>