<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. PROSES UPDATE DATA SAMBUTAN & FOTO KEPSEK
if (isset($_POST['update_sambutan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_kepsek'] ?? '');
    $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan_kepsek'] ?? '');
    $sambutan = mysqli_real_escape_string($koneksi, $_POST['sambutan_teks'] ?? '');

    // Ambil data lama untuk menghapus foto lama jika ada upload baru
    $old_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto_kepsek FROM beranda_publik WHERE id=1"));

    // Cek apakah ada upload foto kepsek baru
    if (!empty($_FILES['foto_kepsek']['name'])) {
        $foto_name = time() . "_" . $_FILES['foto_kepsek']['name']; // Tambah prefix time agar nama unik
        $tmp = $_FILES['foto_kepsek']['tmp_name'];
        $path = "../assets/img/" . $foto_name;

        if (move_uploaded_file($tmp, $path)) {
            // Hapus foto lama dari folder jika bukan file default
            if (!empty($old_data['foto_kepsek']) && file_exists("../assets/img/" . $old_data['foto_kepsek'])) {
                unlink("../assets/img/" . $old_data['foto_kepsek']);
            }
            
            $update = mysqli_query($koneksi, "UPDATE beranda_publik SET 
                        nama_kepsek='$nama', 
                        jabatan_kepsek='$jabatan', 
                        sambutan_teks='$sambutan',
                        foto_kepsek='$foto_name' 
                        WHERE id=1");
        }
    } else {
        // Update tanpa mengganti foto
        $update = mysqli_query($koneksi, "UPDATE beranda_publik SET 
                    nama_kepsek='$nama', 
                    jabatan_kepsek='$jabatan', 
                    sambutan_teks='$sambutan' 
                    WHERE id=1");
    }

    if ($update) { $success = "Data sambutan berhasil diperbarui!"; }
}

// 3. PROSES TAMBAH SLIDER
if (isset($_POST['tambah_slider'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul_slider']);
    $subjudul = mysqli_real_escape_string($koneksi, $_POST['subjudul_slider']);
    
    $foto = time() . "_" . $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $path = "../assets/img/" . $foto;

    if (move_uploaded_file($tmp, $path)) {
        mysqli_query($koneksi, "INSERT INTO beranda_slider (gambar, judul, subjudul) VALUES ('$foto', '$judul', '$subjudul')");
        $success = "Slider baru berhasil ditambahkan!";
    }
}

// 4. PROSES HAPUS SLIDER
if (isset($_GET['hapus_slider'])) {
    $id_hapus = (int)$_GET['hapus_slider'];
    
    // Hapus file fisik gambar slider
    $res = mysqli_query($koneksi, "SELECT gambar FROM beranda_slider WHERE id=$id_hapus");
    $row_del = mysqli_fetch_assoc($res);
    if (file_exists("../assets/img/" . $row_del['gambar'])) {
        unlink("../assets/img/" . $row_del['gambar']);
    }

    mysqli_query($koneksi, "DELETE FROM beranda_slider WHERE id=$id_hapus");
    header("Location: admin_dashboard.php?pesan=terhapus");
    exit;
}

// 5. AMBIL DATA UNTUK FORM
$data_beranda = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM beranda_publik WHERE id=1"));
$query_slider = mysqli_query($koneksi, "SELECT  * FROM beranda_slider ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Beranda | SDN Pandean 1</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap">
  <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">
  <style>
    :root { --primary: #008080; --bg-light: #f8fafc; }
    body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); color: #334155; }
    .main-sidebar { background: #ffffff !important; border-right: 1px solid #e2e8f0 !important; }
    .brand-link { background: var(--primary) !important; color: #fff !important; text-align: center; padding: 1.2rem 0.5rem !important; }
    .nav-pills .nav-link.active { background: var(--primary) !important; box-shadow: 0 4px 12px rgba(0, 128, 128, 0.3) !important; }
    .text-teal { color: var(--primary) !important; }
    .card { border-radius: 20px; border: 1px solid #e2e8f0; margin-bottom: 2rem; }
    .btn-teal { background-color: var(--primary); color: white; border-radius: 10px; font-weight: 600; }
    .btn-teal:hover { background-color: #006666; color: white; }
    .form-control { border-radius: 10px; }
    .img-preview { width: 80px; height: 50px; object-fit: cover; border-radius: 5px; }
    .img-kepse-preview { width: 100px; height: 120px; object-fit: cover; border-radius: 10px; border: 2px solid #e2e8f0; }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
    <ul class="navbar-nav pl-3">
      <li class="nav-item"><a class="nav-link text-teal" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-light-teal elevation-4">
    <a href="admin_dashboard.php" class="brand-link"><span class="brand-text font-weight-bold">SDN PANDEAN 1</span></a>
    <div class="sidebar mt-3 px-3">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-header">PENGATURAN WEBSITE</li>
          <li class="nav-item">
            <a href="admin_dashboard.php" class="nav-link active"><i class="fas fa-home nav-icon"></i><p>Beranda Publik</p></a>
          </li>
          <li class="nav-item">
            <a href="admin_kontak.php" class="nav-link"><i class="fas fa-phone-alt nav-icon"></i><p>Kontak Sekolah</p></a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper pt-4 px-3">
    <div class="content-header">
      <div class="container-fluid">
        <h2 class="m-0 font-weight-bold text-dark">Manajemen Beranda Publik</h2>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        
        <?php if(isset($success) || isset($_GET['pesan'])): ?>
          <div class="alert alert-success alert-dismissible fade show">
            <?php echo $success ?? ($_GET['pesan'] == 'terhapus' ? 'Data berhasil dihapus!' : 'Data berhasil diproses!'); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        <?php endif; ?>

        <div class="card card-teal card-outline shadow-sm">
          <div class="card-body p-4">
            <div class="row border-bottom mb-4 pb-2">
              <div class="col-12"><h5 class="text-teal font-weight-bold"><i class="fas fa-images mr-2"></i>Pengaturan Slider (Banner)</h5></div>
            </div>
            
            <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
              <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                  <label class="font-weight-bold small">Upload Gambar</label>
                  <input type="file" name="gambar" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                  <label class="font-weight-bold small">Judul Slide</label>
                  <input type="text" name="judul_slider" class="form-control" placeholder="Masukan judul...">
                </div>
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold small">Sub-judul Slide</label>
                  <input type="text" name="subjudul_slider" class="form-control" placeholder="Masukan deskripsi singkat...">
                </div>
                <div class="col-md-2 mb-3">
                  <button type="submit" name="tambah_slider" class="btn btn-teal btn-block"><i class="fas fa-plus mr-1"></i> Tambah</button>
                </div>
              </div>
            </form>

            <div class="table-responsive">
              <table class="table table-hover border">
                <thead class="bg-light">
                  <tr>
                    <th>Gambar</th>
                    <th>Informasi Teks</th>
                    <th width="100" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = mysqli_fetch_assoc($query_slider)): ?>
                  <tr>
                    <td><img src="../assets/img/<?php echo $row['gambar']; ?>" class="img-preview shadow-sm"></td>
                    <td>
                      <div class="font-weight-bold"><?php echo htmlspecialchars($row['judul']); ?></div>
                      <small class="text-muted"><?php echo htmlspecialchars($row['subjudul']); ?></small>
                    </td>
                    <td class="text-center">
                      <a href="?hapus_slider=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus slide ini?')">
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

        <div class="card card-teal card-outline shadow-sm">
          <div class="card-body p-4">
            <form action="" method="POST" enctype="multipart/form-data">
              <div class="row border-bottom mb-4 pb-2">
                <div class="col-12"><h5 class="text-teal font-weight-bold"><i class="fas fa-user-tie mr-2"></i>Sambutan Kepala Sekolah</h5></div>
              </div>

              <div class="row">
                <div class="col-md-3 text-center mb-3">
                  <label class="d-block font-weight-bold">Foto Saat Ini</label>
                  <?php 
                    $foto_now = (!empty($data_beranda['foto_kepsek'])) ? $data_beranda['foto_kepsek'] : 'default_kepsek.jpg';
                  ?>
                  <img src="../assets/img/<?php echo $foto_now; ?>" class="img-kepse-preview mb-2 shadow-sm">
                  <input type="file" name="foto_kepsek" class="form-control form-control-sm">
                  <small class="text-muted">Pilih file jika ingin ganti foto</small>
                </div>
                <div class="col-md-9">
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="font-weight-bold">Nama Kepala Sekolah</label>
                      <input type="text" name="nama_kepsek" class="form-control" value="<?php echo htmlspecialchars($data_beranda['nama_kepsek'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="font-weight-bold">Jabatan</label>
                      <input type="text" name="jabatan_kepsek" class="form-control" value="<?php echo htmlspecialchars($data_beranda['jabatan_kepsek'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="font-weight-bold">Teks Sambutan</label>
                    <textarea name="sambutan_teks" class="form-control" rows="6" required><?php echo htmlspecialchars($data_beranda['sambutan_teks'] ?? ''); ?></textarea>
                  </div>
                </div>
              </div>

              <div class="text-right mt-4">
                <button type="submit" name="update_sambutan" class="btn btn-teal px-5 py-2"><i class="fas fa-save mr-2"></i> Simpan Sambutan</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </section>
  </div>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
</body>
</html>