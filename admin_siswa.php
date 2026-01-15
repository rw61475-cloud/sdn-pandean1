<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. PROSES UPDATE DATA (Jika Form Dikirim)
if (isset($_POST['update_siswa'])) {
    // Sanitasi input untuk keamanan
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul_sapaan'] ?? '');
    $subjudul = mysqli_real_escape_string($koneksi, $_POST['subjudul_sapaan'] ?? '');
    $pengumuman = mysqli_real_escape_string($koneksi, $_POST['pengumuman_teks'] ?? '');

    $update = mysqli_query($koneksi, "UPDATE halaman_siswa SET 
                judul_sapaan='$judul', 
                subjudul_sapaan='$subjudul', 
                pengumuman_teks='$pengumuman'
                WHERE id=1");

    if ($update) {
        $success = "Pengaturan dashboard siswa berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}

// 3. AMBIL DATA DARI DATABASE
$query = mysqli_query($koneksi, "SELECT * FROM halaman_siswa WHERE id = 1");
$data = mysqli_fetch_assoc($query);

// Proteksi jika data belum ada di tabel
if (!$data) {
    $data = [
        'judul_sapaan' => 'Halo, Siswa SDN Pandean 1! 👋',
        'subjudul_sapaan' => 'Selamat datang kembali di Panel Siswa.',
        'pengumuman_teks' => ''
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pengaturan Dashboard Siswa | SDN Pandean 1</title>
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
    .card { border-radius: 20px; border: 1px solid #e2e8f0; }
    .btn-teal { background-color: var(--primary); color: white; border-radius: 10px; font-weight: 600; transition: 0.3s; }
    .btn-teal:hover { background-color: #006666; color: white; transform: translateY(-2px); }
    .form-control { border-radius: 10px; padding: 10px 15px; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(0, 128, 128, 0.1); }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
    <ul class="navbar-nav pl-3">
      <li class="nav-item">
        <a class="nav-link text-teal" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-light-teal elevation-4">
    <a href="index.php" class="brand-link">
      <span class="brand-text font-weight-bold">SDN PANDEAN 1</span>
    </a>
    <div class="sidebar mt-3 px-3">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-header">PENGATURAN WEBSITE</li>
          <li class="nav-item">
            <a href="admin_kontak.php" class="nav-link">
              <i class="fas fa-phone-alt nav-icon"></i>
              <p>Kontak Sekolah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="admin_siswa.php" class="nav-link active">
              <i class="fas fa-user-graduate nav-icon"></i>
              <p>Dashboard Siswa</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper pt-4 px-3">
    <div class="content-header">
      <div class="container-fluid">
        <h2 class="m-0 font-weight-bold text-dark">Manajemen Dashboard Siswa</h2>
        <p class="text-muted">Atur teks sapaan dan pengumuman yang muncul di halaman siswa.</p>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        
        <?php if(isset($success)): ?>
          <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="icon fas fa-check mr-2"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
            <i class="icon fas fa-ban mr-2"></i> <?php echo $error; ?>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          </div>
        <?php endif; ?>

        <div class="card card-teal card-outline shadow-sm">
          <div class="card-body p-4">
            <form action="" method="POST">
              
              <div class="row border-bottom mb-4 pb-2">
                <div class="col-12">
                  <h5 class="text-teal font-weight-bold">
                    <i class="fas fa-comment-dots mr-2"></i>Pesan Sapaan (Header)
                  </h5>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mb-3">
                  <label class="font-weight-bold">Judul Sapaan Utama</label>
                  <input type="text" name="judul_sapaan" class="form-control" 
                         value="<?php echo htmlspecialchars($data['judul_sapaan']); ?>" 
                         placeholder="Contoh: Halo, Siswa SDN Pandean 1! 👋" required>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="font-weight-bold">Sub-judul / Pesan Selamat Datang</label>
                  <textarea name="subjudul_sapaan" class="form-control" rows="2" 
                            placeholder="Contoh: Selamat datang kembali di Panel Siswa." required><?php echo htmlspecialchars($data['subjudul_sapaan']); ?></textarea>
                </div>
              </div>

              <div class="row border-bottom mt-4 mb-4 pb-2">
                <div class="col-12">
                  <h5 class="text-teal font-weight-bold">
                    <i class="fas fa-bullhorn mr-2"></i>Pengumuman Penting
                  </h5>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mb-3">
                  <label class="font-weight-bold">Teks Pengumuman (Opsional)</label>
                  <textarea name="pengumuman_teks" class="form-control" rows="4" 
                            placeholder="Masukkan pengumuman yang akan tampil di dashboard siswa..."><?php echo htmlspecialchars($data['pengumuman_teks']); ?></textarea>
                  <small class="text-muted mt-2 d-block">
                    <i class="fas fa-info-circle mr-1"></i> Bagian ini dapat dikosongkan jika tidak ada pengumuman.
                  </small>
                </div>
              </div>

              <div class="text-right mt-4">
                <button type="submit" name="update_siswa" class="btn btn-teal px-5 py-2">
                  <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
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