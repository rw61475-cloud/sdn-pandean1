<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. IDENTITAS HALAMAN (Agar Sidebar Menyorot Menu yang Benar)
$page  = 'pengaturan_publik';
$title = 'Kelola Tampilan Publik';

// 3. LOGIKA SIMPAN PERUBAHAN
if(isset($_POST['update'])){
    $judul_hero = mysqli_real_escape_string($koneksi, $_POST['judul_hero']);
    $subjudul_hero = mysqli_real_escape_string($koneksi, $_POST['subjudul_hero']);
    $nama_kepsek = mysqli_real_escape_string($koneksi, $_POST['nama_kepsek']);
    $jabatan_kepsek = mysqli_real_escape_string($koneksi, $_POST['jabatan_kepsek']);
    $sambutan_teks = mysqli_real_escape_string($koneksi, $_POST['sambutan_teks']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $kontak = mysqli_real_escape_string($koneksi, $_POST['kontak']);

    // Logika Upload Foto Kepsek
    $foto_nama = $_FILES['foto_kepsek']['name'];
    $foto_tmp  = $_FILES['foto_kepsek']['tmp_name'];

    if(!empty($foto_nama)){
        $ekstensi_boleh = array('png', 'jpg', 'jpeg');
        $x = explode('.', $foto_nama);
        $ekstensi = strtolower(end($x));
        $nama_baru = "kepsek_" . time() . "." . $ekstensi;

        if(in_array($ekstensi, $ekstensi_boleh) === true){
            move_uploaded_file($foto_tmp, '../assets/img/' . $nama_baru);
            $sql = "UPDATE beranda_publik SET 
                    judul_hero = '$judul_hero', subjudul_hero = '$subjudul_hero', 
                    nama_kepsek = '$nama_kepsek', jabatan_kepsek = '$jabatan_kepsek', 
                    sambutan_teks = '$sambutan_teks', foto_kepsek = '$nama_baru',
                    alamat = '$alamat', kontak = '$kontak' WHERE id = 1";
        }
    } else {
        $sql = "UPDATE beranda_publik SET 
                judul_hero = '$judul_hero', subjudul_hero = '$subjudul_hero', 
                nama_kepsek = '$nama_kepsek', jabatan_kepsek = '$jabatan_kepsek', 
                sambutan_teks = '$sambutan_teks', alamat = '$alamat', 
                kontak = '$kontak' WHERE id = 1";
    }

    if(mysqli_query($koneksi, $sql)){
        echo "<script>alert('Tampilan publik berhasil diperbarui!'); window.location='pengaturan_publik.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($koneksi) . "');</script>";
    }
}

// 4. AMBIL DATA DARI DATABASE
$query = mysqli_query($koneksi, "SELECT * FROM beranda_publik WHERE id = 1");
$pub = mysqli_fetch_assoc($query);

// 5. PANGGIL HEADER & SIDEBAR (Sama seperti index.php)
include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper pt-4 px-3">
    <div class="content-header">
        <div class="container-fluid">
            <h2 class="m-0 font-weight-bold text-dark">Pengaturan Tampilan Beranda Publik</h2>
            <p class="text-muted text-sm">Sesuaikan konten yang tampil di halaman depan website sekolah.</p>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-teal card-outline shadow-sm">
                            <div class="card-body">
                                <h5 class="text-teal font-weight-bold mb-3"><i class="fas fa-desktop mr-2"></i> Bagian Hero Banner</h5>
                                <div class="form-group">
                                    <label>Judul Utama</label>
                                    <input type="text" name="judul_hero" class="form-control" value="<?= htmlspecialchars($pub['judul_hero'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Sub-Judul / Slogan</label>
                                    <textarea name="subjudul_hero" class="form-control" rows="2"><?= htmlspecialchars($pub['subjudul_hero'] ?? '') ?></textarea>
                                </div>

                                <hr>
                                <h5 class="text-teal font-weight-bold mb-3"><i class="fas fa-user-tie mr-2"></i> Bagian Sambutan</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Kepala Sekolah</label>
                                            <input type="text" name="nama_kepsek" class="form-control" value="<?= htmlspecialchars($pub['nama_kepsek'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jabatan</label>
                                            <input type="text" name="jabatan_kepsek" class="form-control" value="<?= htmlspecialchars($pub['jabatan_kepsek'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Isi Teks Sambutan</label>
                                    <textarea name="sambutan_teks" class="form-control" rows="5" required><?= htmlspecialchars($pub['sambutan_teks'] ?? '') ?></textarea>
                                </div>

                                <hr>
                                <h5 class="text-teal font-weight-bold mb-3"><i class="fas fa-map-marked-alt mr-2"></i> Footer & Kontak</h5>
                                <div class="form-group">
                                    <label>Alamat Sekolah</label>
                                    <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($pub['alamat'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Kontak (Telp/Email)</label>
                                    <input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($pub['kontak'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="card-footer bg-white text-right">
                                <button type="submit" name="update" class="btn btn-teal shadow-sm px-4">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-teal">
                                <h3 class="card-title font-weight-bold"><i class="fas fa-image mr-1"></i> Foto Kepala Sekolah</h3>
                            </div>
                            <div class="card-body text-center">
                                <?php 
                                    $foto_tampil = (!empty($pub['foto_kepsek'])) ? $pub['foto_kepsek'] : 'default_kepsek.jpg';
                                ?>
                                <img src="../assets/img/<?= $foto_tampil ?>" class="img-thumbnail shadow-sm mb-3" style="max-height: 250px; width: 100%; object-fit: cover;">
                                <div class="form-group text-left">
                                    <label class="text-sm text-muted">Ganti Foto:</label>
                                    <input type="file" name="foto_kepsek" class="form-control-file border p-1 rounded">
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-left border-info">
                            <div class="card-body p-3">
                                <h6 class="font-weight-bold text-info"><i class="fas fa-info-circle mr-1"></i> Akses Cepat</h6>
                                <p class="text-xs text-muted mb-0">Klik tombol di bawah untuk melihat hasil perubahan pada halaman utama website publik.</p>
                                <hr class="my-2">
                                <a href="http://localhost/UAS/dashboard_publik/index.php" target="_blank" class="btn btn-sm btn-info btn-block shadow-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i> Lihat Halaman Publik
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<?php 
// 6. PANGGIL FOOTER
include "includes/footer.php"; 
?>