<?php
include '../includes/db.php';
session_start();

if (isset($_POST['simpan'])) {
    $id_user    = $_POST['id_guru']; 
    $mapel      = $_POST['mapel'];
    $semester   = $_POST['semester'];
    $nisn_array = $_POST['nisn'];
    
    $tugas_array = $_POST['nilai_tugas'];
    $uts_array   = $_POST['nilai_uts'];
    $uas_array   = $_POST['nilai_uas'];

    $berhasil = 0;
    $gagal = 0;

    foreach ($nisn_array as $index => $nisn) {
        $n_tugas   = $tugas_array[$index];
        $n_uts     = $uts_array[$index];
        $n_uas     = $uas_array[$index];
        $tgl_input = date('Y-m-d H:i:s');

        /**
         * Sinkronisasi ke tabel nilai:
         * Kita gunakan REPLACE INTO agar data lama tertimpa data baru (Update otomatis)
         * Syarat: Pastikan kolom id_nilai adalah PRIMARY KEY
         */
        
        // Cari ID jika data sudah ada untuk menghindari duplikasi id_nilai
        $cek = mysqli_query($koneksi, "SELECT id_nilai FROM nilai WHERE nisn='$nisn' AND mapel='$mapel' AND semester='$semester'");
        $d_cek = mysqli_fetch_assoc($cek);
        
        if ($d_cek) {
            $id_lama = $d_cek['id_nilai'];
            $query = "UPDATE nilai SET 
                        id_user='$id_user', nilai_tugas='$n_tugas', nilai_uts='$n_uts', nilai_uas='$n_uas', tgl_input='$tgl_input' 
                      WHERE id_nilai='$id_lama'";
        } else {
            $query = "INSERT INTO nilai (id_user, nisn, mapel, nilai_tugas, nilai_uts, nilai_uas, semester, tgl_input) 
                      VALUES ('$id_user', '$nisn', '$mapel', '$n_tugas', '$n_uts', '$n_uas', '$semester', '$tgl_input')";
        }
        
        if (mysqli_query($koneksi, $query)) {
            $berhasil++;
        } else {
            $gagal++;
        }
    }

    if ($berhasil > 0) {
        echo "<script>
                alert('Berhasil mensinkronkan $berhasil data nilai ke rekap.');
                window.location='rekap_nilai_siswa.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan nilai.');
                window.location='input_nilai.php';
              </script>";
    }
}
?>