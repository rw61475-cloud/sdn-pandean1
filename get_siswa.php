<?php
include '../includes/db.php';

$id_jadwal = $_GET['id_jadwal'];

// 1. Cari tahu kelas mana yang terkait dengan id_jadwal ini
$q_kelas = mysqli_query($koneksi, "SELECT kelas FROM jadwal WHERE id_jadwal = '$id_jadwal'");
$data_jadwal = mysqli_fetch_assoc($q_kelas);
$kelas_target = $data_jadwal['kelas'];

// 2. Ambil siswa yang hanya berada di kelas tersebut
$query = mysqli_query($koneksi, "SELECT id_user, nisn, nama_lengkap FROM siswa WHERE kelas = '$kelas_target' ORDER BY nama_lengkap ASC");

if (mysqli_num_rows($query) > 0) {
    echo '
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">NAMA SISWA</th>
                    <th class="text-center">STATUS</th>
                    <th class="pe-4">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>';

    while ($s = mysqli_fetch_assoc($query)) {
        $sid = $s['id_user'];
        echo '
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold">'.htmlspecialchars($s['nama_lengkap']).'</div>
                        <div class="text-muted small">'.$s['nisn'].'</div>
                    </td>
                    <td class="text-center">
                        <select name="status['.$sid.']" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="Hadir">Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alfa">Alfa</option>
                        </select>
                    </td>
                    <td class="pe-4">
                        <input type="text" name="keterangan['.$sid.']" class="form-control form-control-sm" placeholder="Catatan...">
                    </td>
                </tr>';
    }

    echo '
            </tbody>
        </table>
    </div>
    <button type="submit" name="simpan_presensi" class="btn mt-4 px-5 py-2 fw-bold text-white" style="background: #0f766e;">Simpan Presensi</button>';
} else {
    echo '<div class="alert alert-warning">Tidak ada siswa yang terdaftar di Kelas '.$kelas_target.'</div>';
}
?>