<?php
// 1. KONEKSI KE DATABASE
include "../includes/db.php";

// 2. IDENTITAS HALAMAN
$page  = 'jadwal';
$title = 'Jadwal Pelajaran | SDN Pandean 1';

// 3. LOGIKA JADWAL (Tetap dipertahankan)
$kelas_aktif = isset($_GET['kelas']) ? mysqli_real_escape_string($koneksi, $_GET['kelas']) : '1';

// Otomatisasi ID Wali Kelas
$query_wali = mysqli_query($koneksi, "SELECT id_user FROM guru WHERE mata_pelajaran = 'Wali Kelas $kelas_aktif' LIMIT 1");
$data_wali = mysqli_fetch_assoc($query_wali);
$id_wali_otomatis = isset($data_wali['id_user']) ? $data_wali['id_user'] : "NULL";

// PROSES SIMPAN DATA JADWAL
if (isset($_POST['tambah_jadwal'])) {
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mapel']);
    $hari = $_POST['hari'];
    $mulai = $_POST['jam_mulai'];
    $selesai = $_POST['jam_selesai'];
    $kelas = $_POST['kelas'];
    
    $id_user_input = !empty($_POST['id_user']) ? $_POST['id_user'] : $id_wali_otomatis;
    $id_user_final = ($id_user_input !== "NULL") ? "'$id_user_input'" : "NULL";

    $query = "INSERT INTO jadwal (id_user, mapel, hari, jam_mulai, jam_selesai, kelas) 
              VALUES ($id_user_final, '$mapel', '$hari', '$mulai', '$selesai', '$kelas')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Jadwal Berhasil Ditambahkan!'); window.location='jadwal.php?kelas=$kelas';</script>";
    }
}

// PROSES HAPUS DATA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM jadwal WHERE id_jadwal = '$id'");
    echo "<script>window.location='jadwal.php?kelas=$kelas_aktif';</script>";
}

// Persiapan Data Dropdown Modal
$query_guru_list = mysqli_query($koneksi, "SELECT id_user, nama_lengkap FROM guru 
                                           WHERE mata_pelajaran LIKE '%PAI%' 
                                           OR mata_pelajaran LIKE '%PJOK%' 
                                           ORDER BY nama_lengkap ASC");

$query_mapel_list = mysqli_query($koneksi, "SELECT nama_mapel FROM mapel 
                                            WHERE tingkat_kelas = '$kelas_aktif' 
                                            ORDER BY nama_mapel ASC");

// 4. PANGGIL HEADER & SIDEBAR
include "includes/header.php";
include "includes/sidebar.php";
?>

<style>
    :root { --primary: #008080; --bg-light: #f8fafc; }
    .text-teal { color: var(--primary) !important; }
    .bg-teal-custom { background-color: var(--primary) !important; color: white !important; }
    .card-schedule { border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: #fff; }
    .card-hari { border-left: 5px solid var(--primary); }
    .btn-rounded { border-radius: 20px; font-weight: 600; padding: 0.5rem 1.5rem; }
</style>

<div class="content-wrapper pt-4 px-3">
    <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
            <h2 class="font-weight-bold text-dark mb-0">Jadwal Pelajaran</h2>
            <p class="text-muted">Manajemen jam belajar Kelas <?php echo $kelas_aktif; ?></p>
        </div>
        <div class="col-sm-6 text-right">
            <button class="btn bg-teal-custom shadow-sm px-4" style="border-radius: 12px;" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus mr-2"></i> Tambah Jadwal
            </button>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="mb-4 bg-white p-3 rounded shadow-sm border d-flex align-items-center overflow-auto">
                <span class="mr-3 font-weight-bold text-muted small text-uppercase" style="white-space:nowrap;">Pilih Kelas:</span>
                <?php for($i=1; $i<=6; $i++): ?>
                    <a href="jadwal.php?kelas=<?php echo $i; ?>" 
                        class="btn <?php echo ($kelas_aktif == $i) ? 'btn-info shadow-sm' : 'btn-outline-info'; ?> btn-rounded mr-2">
                        Kelas <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>

            <div class="row">
                <?php
                $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                foreach ($hari_list as $h) :
                ?>
                <div class="col-md-6 mb-4">
                    <div class="card-schedule card-hari h-100">
                        <div class="card-header bg-transparent border-0 pt-3">
                            <h5 class="card-title font-weight-bold text-teal"><i class="far fa-clock mr-2"></i> <?php echo $h; ?></h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover m-0">
                                    <thead>
                                        <tr class="small text-muted text-uppercase">
                                            <th style="padding-left: 20px;">Waktu</th>
                                            <th>Mapel</th>
                                            <th>Guru</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT j.*, g.nama_lengkap 
                                                FROM jadwal j
                                                LEFT JOIN guru g ON j.id_user = g.id_user
                                                WHERE j.hari='$h' AND j.kelas='$kelas_aktif' 
                                                ORDER BY j.jam_mulai ASC";
                                        $res = mysqli_query($koneksi, $sql);
                                        if(mysqli_num_rows($res) > 0){
                                            while($row = mysqli_fetch_assoc($res)){
                                        ?>
                                        <tr>
                                            <td style='padding-left: 20px;'>
                                                <span class='badge badge-light border text-muted'>
                                                    <?php echo substr($row['jam_mulai'],0,5); ?> - <?php echo substr($row['jam_selesai'],0,5); ?>
                                                </span>
                                            </td>
                                            <td class='font-weight-bold text-dark'><?php echo $row['mapel']; ?></td>
                                            <td><small class='text-muted'><?php echo ($row['nama_lengkap'] ?? 'Wali Kelas'); ?></small></td>
                                            <td class='text-center'>
                                                <a href='jadwal.php?kelas=<?php echo $kelas_aktif; ?>&hapus=<?php echo $row['id_jadwal']; ?>' 
                                                   class='btn btn-xs btn-outline-danger border-0' onclick='return confirm("Hapus jadwal ini?")'>
                                                    <i class='fas fa-trash'></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php 
                                            }
                                        } else {
                                            echo "<tr><td colspan='4' class='text-center py-4 text-muted small'><i>Belum ada jadwal.</i></td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form action="" method="POST" class="modal-content" style="border-radius:20px; overflow:hidden; border:none;">
            <div class="modal-header bg-teal-custom border-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-calendar-plus mr-2"></i> Tambah Jadwal Kelas <?php echo $kelas_aktif; ?></h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="kelas" value="<?php echo $kelas_aktif; ?>">
                
                <div class="form-group">
                    <label class="text-muted small font-weight-bold">MATA PELAJARAN</label>
                    <select name="mapel" class="form-control" style="border-radius:10px;" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        <?php 
                        if(mysqli_num_rows($query_mapel_list) > 0) {
                            mysqli_data_seek($query_mapel_list, 0);
                            while($m = mysqli_fetch_assoc($query_mapel_list)): ?>
                                <option value="<?php echo $m['nama_mapel']; ?>"><?php echo $m['nama_mapel']; ?></option>
                            <?php endwhile; 
                        } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="text-muted small font-weight-bold">GURU PENGAMPU</label>
                    <select name="id_user" class="form-control" style="border-radius:10px;">
                        <option value="">-- Gunakan Wali Kelas Otomatis --</option>
                        <?php 
                        mysqli_data_seek($query_guru_list, 0);
                        while($g = mysqli_fetch_assoc($query_guru_list)): ?>
                            <option value="<?php echo $g['id_user']; ?>"><?php echo $g['nama_lengkap']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <small class="text-info">* Pilih jika guru adalah guru bidang (PAI/PJOK)</small>
                </div>

                <div class="form-group">
                    <label class="text-muted small font-weight-bold">HARI</label>
                    <select name="hari" class="form-control" style="border-radius:10px;" required>
                        <?php foreach($hari_list as $hr) echo "<option value='$hr'>$hr</option>"; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label class="text-muted small font-weight-bold">JAM MULAI</label>
                        <input type="time" name="jam_mulai" class="form-control" style="border-radius:10px;" required>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small font-weight-bold">JAM SELESAI</label>
                        <input type="time" name="jam_selesai" class="form-control" style="border-radius:10px;" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" name="tambah_jadwal" class="btn bg-teal-custom btn-block shadow-sm" style="border-radius:10px;">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<?php 
// 5. PANGGIL FOOTER
include "includes/footer.php"; 
?>