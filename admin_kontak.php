<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. PROSES UPDATE DATA (Jika Form Dikirim)
if (isset($_POST['update'])) {
    // Gunakan isset() atau null coalescing (??) untuk mencegah error Undefined Key
    $wa = mysqli_real_escape_string($koneksi, $_POST['whatsapp'] ?? '');
    $email = mysqli_real_escape_string($koneksi, $_POST['email'] ?? '');
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat'] ?? '');
    $maps = mysqli_real_escape_string($koneksi, $_POST['maps_iframe'] ?? '');
    $judul_hero = mysqli_real_escape_string($koneksi, $_POST['judul_hero'] ?? '');
    $subjudul_hero = mysqli_real_escape_string($koneksi, $_POST['subjudul_hero'] ?? '');

    $update = mysqli_query($koneksi, "UPDATE kontak_setting SET 
                whatsapp='$wa', 
                email='$email', 
                alamat='$alamat', 
                maps_iframe='$maps',
                judul_hero='$judul_hero',
                subjudul_hero='$subjudul_hero'
                WHERE id=1");

    if ($update) {
        $success = "Data kontak berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}

// 3. AMBIL DATA KONTAK
$query = mysqli_query($koneksi, "SELECT * FROM kontak_setting WHERE id = 1");
$data = mysqli_fetch_assoc($query);

// Proteksi jika data tidak ditemukan atau kolom baru masih kosong
if (!$data) {
    $data = [
        'whatsapp' => '',
        'email' => '',
        'alamat' => '',
        'maps_iframe' => '',
        'judul_hero' => '',
        'subjudul_hero' => ''
    ];
} else {
    // Memastikan setiap key ada nilainya meskipun kolom baru saja ditambahkan
    $data['judul_hero'] = $data['judul_hero'] ?? 'Kontak Resmi';
    $data['subjudul_hero'] = $data['subjudul_hero'] ?? 'Layanan Informasi Terpadu';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pengaturan Kontak | SDN Pandean 1</title>
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
    .btn-teal { background-color: var(--primary); color: white; border-radius: 10px; font-weight: 600; }
    .btn-teal:hover { background-color: #006666; color: white; }
    .form-control { border-radius: 10px; }
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
    <a href="index.php" class="brand-link"><span class="brand-text font-weight-bold">SDN PANDEAN 1</span></a>
    <div class="sidebar mt-3 px-3">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-header">PENGATURAN WEBSITE</li>
          <li class="nav-item"><a href="admin_kontak.php" class="nav-link active"><i class="fas fa-phone-alt nav-icon"></i><p>Kontak Sekolah</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper pt-4 px-3">
    <div class="content-header">
      <div class="container-fluid">
        <h2 class="m-0 font-weight-bold text-dark">Manajemen Kontak Publik</h2>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <?php if(isset($success)): ?>
          <div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
        <?php endif; ?>

        <div class="card card-teal card-outline shadow-sm">
          <div class="card-body p-4">
            <form action="" method="POST">
              <div class="row border-bottom mb-4 pb-2">
                <div class="col-12"><h5 class="text-teal font-weight-bold"><i class="fas fa-heading mr-2"></i>Pengaturan Judul Hero</h5></div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold">Judul Utama</label>
                  <input type="text" name="judul_hero" class="form-control" value="<?php echo htmlspecialchars($data['judul_hero']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold">Sub-judul</label>
                  <input type="text" name="subjudul_hero" class="form-control" value="<?php echo htmlspecialchars($data['subjudul_hero']); ?>" required>
                </div>
              </div>

              <div class="row border-bottom mt-4 mb-4 pb-2">
                <div class="col-12"><h5 class="text-teal font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Informasi Kontak & Peta</h5></div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold">WhatsApp Admin</label>
                  <input type="text" name="whatsapp" class="form-control" value="<?php echo htmlspecialchars($data['whatsapp']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold">Email Sekolah</label>
                  <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="font-weight-bold">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="3" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>
              </div>

              <div class="mb-4">
                <label class="font-weight-bold">Google Maps URL (src)</label>
                <input type="text" name="maps_iframe" class="form-control" value="<?php echo htmlspecialchars($data['maps_iframe']); ?>">
              </div>

              <div class="text-right mt-4">
                <button type="submit" name="update" class="btn btn-teal px-5 py-2"><i class="fas fa-save mr-2"></i> Simpan Perubahan</button>
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