<?php 
include "../includes/db.php"; 
$page = 'presensi'; // Sesuaikan agar sidebar aktif
$title = 'Input Presensi Guru';
include "includes/header.php";
include "includes/sidebar.php";

// Logika Simpan Data
if(isset($_POST['submit'])){
    $id_guru = $_POST['id_guru'];
    $nip     = $_POST['nip'];
    $tanggal = date('Y-m-d');
    $jam     = date('H:i:s');
    $status  = $_POST['status'];

    $query = "INSERT INTO presensi (id_guru, nip, tanggal, jam_masuk, status) 
              VALUES ('$id_guru', '$nip', '$tanggal', '$jam', '$status')";
    
    if(mysqli_query($koneksi, $query)){
        echo "<script>alert('Presensi berhasil disimpan!'); window.location='presensi_guru.php';</script>";
    }
}
?>

<div class="content-wrapper pt-4 px-3">
    <div class="container-fluid">
        <div class="card card-teal card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-check-circle mr-2"></i> Form Presensi Guru Hari Ini</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pilih Guru</label>
                                <select name="id_guru" id="pilih_guru" class="form-control" required onchange="updateNip()">
                                    <option value="">-- Pilih Guru --</option>
                                    <?php
                                    $sql_guru = mysqli_query($koneksi, "SELECT * FROM guru");
                                    while($g = mysqli_fetch_assoc($sql_guru)){
                                        echo "<option value='".$g['id_guru']."' data-nip='".$g['nip']."'>".$g['nama_guru']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>NIP</label>
                                <input type="text" name="nip" id="display_nip" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Hadir">Hadir</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="DL">Dinas Luar (DL)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" name="submit" class="btn btn-teal btn-block">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateNip() {
    var select = document.getElementById("pilih_guru");
    var nip = select.options[select.selectedIndex].getAttribute("data-nip");
    document.getElementById("display_nip").value = nip;
}
</script>

<?php include "includes/footer.php"; ?>