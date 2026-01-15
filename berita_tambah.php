<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. IDENTITAS HALAMAN (Agar sidebar otomatis menyala di menu berita)
$page  = 'berita';
$title = 'Tambah Berita | SDN Pandean 1';

// 3. LOGIKA SIMPAN DATA
if(isset($_POST['simpan'])){
    $judul   = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi     = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $tanggal = $_POST['tanggal'];
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);

    $nama_gambar = $_FILES['gambar']['name'];
    $sumber_tmp  = $_FILES['gambar']['tmp_name'];
    $folder_tujuan = "../assets/img/berita/";

    // Validasi Folder (Buat folder jika belum ada)
    if (!file_exists($folder_tujuan)) {
        mkdir($folder_tujuan, 0777, true);
    }

    if(!empty($nama_gambar)) {
        // Beri nama unik pada gambar untuk menghindari nama file yang sama
        $ekstensi = pathinfo($nama_gambar, PATHINFO_EXTENSION);
        $nama_baru = date('YmdHis') . "." . $ekstensi;

        if(move_uploaded_file($sumber_tmp, $folder_tujuan . $nama_baru)) {
            $query = mysqli_query($koneksi, "INSERT INTO berita (judul, isi, gambar, tanggal, penulis) 
                                 VALUES ('$judul', '$isi', '$nama_baru', '$tanggal', '$penulis')");
            
            if($query){
                echo "<script>alert('Berita Berhasil Ditambahkan!'); window.location='berita_tampil.php';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan ke database.');</script>";
            }
        }
    } else {
        echo "<script>alert('Mohon pilih foto sampul terlebih dahulu.');</script>";
    }
}

// 4. PANGGIL HEADER & SIDEBAR (PERSIS INDEX)
include "includes/header.php";
include "includes/sidebar.php";
?>

<style>
    :root { 
      --primary: #008080; 
      --bg-light: #f8fafc;
    }
    
    .text-teal { color: var(--primary) !important; }
    .judul-berwarna { color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    
    /* Card & Button Styling */
    .card { border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .bg-teal-custom { background-color: var(--primary) !important; color: white !important; border-radius: 12px; transition: 0.3s; }
    .bg-teal-custom:hover { background-color: #006666 !important; transform: translateY(-2px); }
    .form-control { border-radius: 10px; padding: 10px 15px; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(0, 128, 128, 0.1); }
</style>

<div class="content-wrapper pt-4 px-3">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h2 class="judul-berwarna m-0"><i class="fas fa-edit mr-2"></i> Tambah Berita Sekolah</h2>
                    <p class="text-muted small">Publikasikan informasi terbaru ke website publik.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php" class="text-teal">Admin</a></li>
                        <li class="breadcrumb-item"><a href="berita_tampil.php" class="text-teal">Berita</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 col-md-12">
                    <div class="card">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                
                                <div class="form-group mb-4">
                                    <label class="text-teal font-weight-bold"><i class="fas fa-heading mr-1"></i> Judul Berita</label>
                                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Kegiatan Upacara Hari Senin di SDN Pandean 1" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="text-teal font-weight-bold"><i class="fas fa-calendar-alt mr-1"></i> Tanggal Terbit</label>
                                            <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="text-teal font-weight-bold"><i class="fas fa-user-edit mr-1"></i> Penulis</label>
                                            <input type="text" name="penulis" class="form-control" value="Admin SDN Pandean 1" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="text-teal font-weight-bold"><i class="fas fa-align-left mr-1"></i> Konten / Isi Berita</label>
                                    <textarea name="isi" class="form-control" rows="10" placeholder="Tuliskan isi berita secara detail di sini..." required></textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="text-teal font-weight-bold"><i class="fas fa-image mr-1"></i> Foto Sampul (Thumbnail)</label>
                                    <div class="p-4 bg-light border" style="border-radius:15px; border-style: dashed !important;">
                                        <input type="file" name="gambar" class="form-control-file" accept="image/*" required>
                                        <small class="text-muted d-block mt-2">Format yang didukung: JPG, PNG, JPEG. Rekomendasi ukuran: 1280x720 pixel.</small>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer bg-white border-top-0 pb-4">
                                <div class="d-flex justify-content-between">
                                    <a href="berita_tampil.php" class="btn btn-light px-4" style="border-radius:12px;">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                                    </a>
                                    <button type="submit" name="simpan" class="btn bg-teal-custom px-5 shadow-sm">
                                        <i class="fas fa-paper-plane mr-1"></i> Terbitkan Berita Sekarang
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php 
// 5. PANGGIL FOOTER
include "includes/footer.php"; 
?>