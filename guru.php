<?php 
// 1. KONEKSI KE DATABASE & ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/db.php"; 

// 2. IDENTITAS HALAMAN
$page  = 'guru';
$title = 'Data Guru | SDN Pandean 1';

// 3. QUERY DATA GURU
$query_guru = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY nama_lengkap ASC");
$total_guru = mysqli_num_rows($query_guru);

// 4. PANGGIL HEADER & SIDEBAR
include "includes/header.php";
include "includes/sidebar.php";
?>

<style>
    :root { 
        --primary: #008080; 
        --primary-hover: #006666;
        --secondary: #0ea5e9;
        --soft-teal: #e6f2f2;
        --border-color: #e2e8f0;
    }

    /* Card & Table Modern */
    .card-modern { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); background: #fff; border: 1px solid var(--border-color); }
    .table thead th { background: #f8fafc; border: none; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; padding: 1.2rem; }
    .table td { vertical-align: middle !important; border-top: 1px solid #f1f5f9; padding: 1rem !important; }
    
    /* Avatar & Image */
    .img-guru { width: 48px; height: 48px; object-fit: cover; border-radius: 14px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 2px solid white; }
    .avatar-placeholder { width: 48px; height: 48px; background: var(--soft-teal); color: var(--primary); display: flex; align-items: center; justify-content: center; border-radius: 14px; font-weight: bold; }
    
    /* Custom Buttons */
    .btn-teal-custom { background: var(--primary); color: white !important; border-radius: 12px; font-weight: 600; padding: 10px 24px; border:none; transition: 0.3s; }
    .btn-teal-custom:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,128,128,0.2); }
    .btn-action { width: 36px; height: 36px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; border: 1px solid var(--border-color); background: white; }

    /* Modern Modal */
    .modal-content { border-radius: 24px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .modal-header { padding: 25px 30px; border-bottom: 1px solid var(--border-color); }
    .form-label-custom { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #64748b; margin-bottom: 7px; display: block; }
    .input-group-modern { background: #f8fafc; border: 1.5px solid var(--border-color); border-radius: 14px; padding: 2px 15px; display: flex; align-items: center; }
    .input-group-modern input, .input-group-modern select { border: none; background: transparent; width: 100%; padding: 10px 0; outline: none; font-weight: 500; }
    .input-readonly { background: #f1f5f9 !important; }

    /* Photo Preview */
    .preview-container { position: relative; width: 100px; height: 100px; margin: 0 auto; }
    .img-preview { width: 100px; height: 100px; border-radius: 20px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .btn-upload-overlap { position: absolute; bottom: -5px; right: -5px; background: var(--primary); color: white; width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; cursor: pointer; }
</style>

<div class="content-wrapper pt-4 px-3">
    <div class="content-header mb-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h2 class="m-0 font-weight-bold">Data Guru</h2>
                <p class="text-muted mb-0">Manajemen Tenaga Pendidik SDN Pandean 1</p>
            </div>
            <button class="btn btn-teal-custom" data-toggle="modal" data-target="#modalTambahGuru">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Guru
            </button>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-modern">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="tableGuru" class="table m-0">
                            <thead>
                                <tr>
                                    <th class="pl-4">Nama Guru</th>
                                    <th>NIP</th>
                                    <th>Tugas / Mapel</th>
                                    <th>No. HP</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($query_guru)) : 
                                    // Pastikan folder ini ada di direktori root project kamu
                                    $path_foto = "../dashboard_guru/foto/" . $row['foto'];
                                    $is_foto_exist = (!empty($row['foto']) && file_exists($path_foto));
                                ?>
                                <tr>
                                    <td class="pl-4">
                                        <div class="d-flex align-items-center">
                                            <?php if($is_foto_exist): ?>
                                                <img src="<?php echo $path_foto; ?>" class="img-guru mr-3">
                                            <?php else: ?>
                                                <div class="avatar-placeholder mr-3"><?php echo strtoupper(substr($row['nama_lengkap'], 0, 1)); ?></div>
                                            <?php endif; ?>
                                            <span class="font-weight-bold text-dark"><?php echo $row['nama_lengkap']; ?></span>
                                        </div>
                                    </td>
                                    <td class="text-muted font-weight-bold"><?php echo $row['nip']; ?></td>
                                    <td><span class="badge badge-light border px-2 py-2"><?php echo $row['mata_pelajaran']; ?></span></td>
                                    <td><?php echo $row['no_hp']; ?></td>
                                    <td class="text-center">
                                        <button class="btn-action text-info mr-1" 
                                                data-toggle="modal" data-target="#modalEditGuru" 
                                                data-nip="<?= $row['nip'] ?>" 
                                                data-nama="<?= $row['nama_lengkap'] ?>"
                                                data-mapel="<?= $row['mata_pelajaran'] ?>"
                                                data-alamat="<?= $row['alamat'] ?>"
                                                data-hp="<?= $row['no_hp'] ?>"
                                                data-foto="<?= ($is_foto_exist) ? $path_foto : '' ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="proses_guru.php?aksi=hapus&nip=<?= $row['nip'] ?>" class="btn-action text-danger" onclick="return confirm('Hapus data guru ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalTambahGuru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Tambah Guru Baru</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="proses_guru.php?aksi=tambah" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="preview-container">
                            <img src="https://ui-avatars.com/api/?name=Guru&background=f1f5f9" id="add_prev_foto" class="img-preview">
                            <label for="addFoto" class="btn-upload-overlap"><i class="fas fa-camera"></i></label>
                            <input type="file" name="foto" id="addFoto" class="d-none" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">NIP</label>
                        <div class="input-group-modern"><input type="text" name="nip" required></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Nama Lengkap</label>
                        <div class="input-group-modern"><input type="text" name="nama_lengkap" required></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Alamat</label>
                        <div class="input-group-modern"><input type="text" name="alamat"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Mata Pelajaran</label>
                            <div class="input-group-modern">
                                <select name="mata_pelajaran" required>
                                    <option value="Wali Kelas 1">Wali Kelas 1</option>
                                    <option value="Wali Kelas 2">Wali Kelas 2</option>
                                    <option value="Wali Kelas 3">Wali Kelas 3</option>
                                    <option value="Wali Kelas 4">Wali Kelas 4</option>
                                    <option value="Wali Kelas 5">Wali Kelas 5</option>
                                    <option value="Wali Kelas 6">Wali Kelas 6</option>
                                    <option value="PJOK">PJOK</option>
                                    <option value="PAI">PAI</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">No. HP</label>
                            <div class="input-group-modern"><input type="text" name="no_hp"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-teal-custom w-100 py-3">Simpan Data Guru</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditGuru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-info">Edit Data Guru</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="proses_guru.php?aksi=edit" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="preview-container">
                            <img src="" id="prev_foto" class="img-preview">
                            <label for="editFotoInput" class="btn-upload-overlap" style="background:var(--secondary);"><i class="fas fa-pen"></i></label>
                            <input type="file" name="foto" id="editFotoInput" class="d-none" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">NIP (Tidak dapat diubah)</label>
                        <div class="input-group-modern input-readonly"><input type="text" name="nip" id="edit_nip" readonly></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Nama Lengkap</label>
                        <div class="input-group-modern"><input type="text" name="nama_lengkap" id="edit_nama" required></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Alamat</label>
                        <div class="input-group-modern"><input type="text" name="alamat" id="edit_alamat"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Mata Pelajaran</label>
                            <div class="input-group-modern">
                                <select name="mata_pelajaran" id="edit_mapel" required>
                                    <option value="Wali Kelas 1">Wali Kelas 1</option>
                                    <option value="Wali Kelas 2">Wali Kelas 2</option>
                                    <option value="Wali Kelas 3">Wali Kelas 3</option>
                                    <option value="Wali Kelas 4">Wali Kelas 4</option>
                                    <option value="Wali Kelas 5">Wali Kelas 5</option>
                                    <option value="Wali Kelas 6">Wali Kelas 6</option>
                                    <option value="PJOK">PJOK</option>
                                    <option value="PAI">PAI</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">No. HP</label>
                            <div class="input-group-modern"><input type="text" name="no_hp" id="edit_hp"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-info w-100 py-3 text-white" style="border-radius:14px; font-weight:bold;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>

<script>
$(document).ready(function() {
    // 1. Inisialisasi DataTable
    if (!$.fn.DataTable.isDataTable('#tableGuru')) {
        $('#tableGuru').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": { "search": "Cari Guru:", "lengthMenu": "Tampilkan _MENU_ data" }
        });
    }

    // 2. Logika Modal Edit
    $('#modalEditGuru').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var modal = $(this);
        
        modal.find('#edit_nip').val(button.data('nip'));
        modal.find('#edit_nama').val(button.data('nama'));
        modal.find('#edit_mapel').val(button.data('mapel'));
        modal.find('#edit_alamat').val(button.data('alamat'));
        modal.find('#edit_hp').val(button.data('hp'));

        var fotoUrl = button.data('foto');
        if(fotoUrl && fotoUrl !== "") {
            modal.find('#prev_foto').attr('src', fotoUrl);
        } else {
            modal.find('#prev_foto').attr('src', 'https://ui-avatars.com/api/?name=' + button.data('nama') + '&background=f1f5f9&color=64748b');
        }
    });

    // 3. Preview Foto saat pilih file
    function readURL(input, target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { $(target).attr('src', e.target.result); }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#addFoto").change(function() { readURL(this, '#add_prev_foto'); });
    $("#editFotoInput").change(function() { readURL(this, '#prev_foto'); });
});
</script>