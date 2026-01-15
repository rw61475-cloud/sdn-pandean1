<?php
// 1. KONEKSI KE DATABASE
include '../includes/db.php';

// Logika Hapus Pengumuman
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    
    // Ambil nama file sebelum data dihapus untuk menghapus file fisik
    $cek_file = mysqli_query($koneksi, "SELECT file_surat FROM pengumuman WHERE id_pengumuman = '$id'");
    $data_file = mysqli_fetch_assoc($cek_file);
    if ($data_file['file_surat']) {
        $path = "../assets/files/pengumuman/" . $data_file['file_surat'];
        if (file_exists($path)) unlink($path);
    }

    mysqli_query($koneksi, "DELETE FROM pengumuman WHERE id_pengumuman = '$id'");
    header("Location: pengumuman_guru.php");
    exit;
}

// Logika Tambah Pengumuman
if (isset($_POST['tambah'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    
    // Mengambil tanggal dari input kalender, jika kosong gunakan waktu sekarang
    $tanggal_input = $_POST['tanggal_pengumuman'];
    $jam_sekarang = date('H:i:s');
    $tanggal_final = (!empty($tanggal_input)) ? $tanggal_input . " " . $jam_sekarang : date('Y-m-d H:i:s');
    
    $nama_file = null;

    // Proses Upload File jika ada
    if (!empty($_FILES['file_surat']['name'])) {
        $target_dir = "../assets/files/pengumuman/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $ekstensi = pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION);
        $nama_file = "SURAT_" . time() . "." . $ekstensi;
        move_uploaded_file($_FILES["file_surat"]["tmp_name"], $target_dir . $nama_file);
    }
    
    $query = "INSERT INTO pengumuman (judul, isi, tanggal_dibuat, file_surat) VALUES ('$judul', '$isi', '$tanggal_final', '$nama_file')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Pengumuman berhasil diterbitkan!'); window.location='pengumuman_guru.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelola Pengumuman | SDN Pandean 1</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap">
  <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">

  <style>
    :root { --primary: #008080; --bg-light: #f8fafc; }
    body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); color: #334155; }
    .main-sidebar { background: #ffffff !important; border-right: 1px solid #e2e8f0 !important; }
    .brand-link { background: var(--primary) !important; color: #fff !important; text-align: center; padding: 1.2rem 0.5rem !important; border-bottom: none !important; }
    .nav-pills .nav-link { border-radius: 10px; margin-bottom: 4px; color: #64748b !important; }
    .nav-pills .nav-link.active { background: var(--primary) !important; color: #fff !important; box-shadow: 0 4px 12px rgba(0, 128, 128, 0.2); }
    .card { border-radius: 20px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .border-left-teal { border-left: 5px solid var(--primary) !important; }
    .btn-teal { background: var(--primary); color: white; border-radius: 10px; font-weight: 600; }
    .btn-teal:hover { background: #006666; color: white; }
    .text-teal { color: var(--primary) !important; }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
    <ul class="navbar-nav pl-3">
      <li class="nav-item"><a class="nav-link text-teal" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link text-teal font-weight-bold"><i class="fas fa-home mr-1"></i> Beranda Admin</a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-light-teal">
    <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-bold">SDN PANDEAN 1</span>
    </a>
    <div class="sidebar mt-3 px-3">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">NAVIGASI UTAMA</li>
          <li class="nav-item"><a href="index.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard Overview</p></a></li>
          <li class="nav-header">PENGATURAN KHUSUS</li>
          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link active"><i class="nav-icon fas fa-user-shield"></i><p>Dashboard Guru <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="rekap_presensi.php" class="nav-link"><i class="fas fa-calendar-check nav-icon"></i><p>Rekap Presensi</p></a></li>
              <li class="nav-item"><a href="pengumuman_guru.php" class="nav-link active"><i class="fas fa-bullhorn nav-icon"></i><p>Isi Pengumuman</p></a></li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper pt-4 px-3">
    <div class="content-header">
      <div class="container-fluid d-flex justify-content-between align-items-center">
        <h2 class="font-weight-bold text-dark">Kelola Pengumuman Guru</h2>
        <button class="btn btn-teal shadow-sm px-4" data-toggle="modal" data-target="#modalTambah">
          <i class="fas fa-plus-circle mr-2"></i> Buat Pengumuman
        </button>
      </div>
    </div>

    <section class="content mt-3">
      <div class="container-fluid">
        <div class="row">
          <?php
          $query = mysqli_query($koneksi, "SELECT * FROM pengumuman ORDER BY tanggal_dibuat DESC");
          if (mysqli_num_rows($query) > 0) {
            while($p = mysqli_fetch_assoc($query)):
          ?>
          <div class="col-md-6">
            <div class="card mb-4 shadow-sm border-left-teal">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                  <h5 class="font-weight-bold text-teal"><?= htmlspecialchars($p['judul']); ?></h5>
                  <a href="pengumuman_guru.php?hapus=<?= $p['id_pengumuman']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus?')"><i class="fas fa-trash-alt"></i></a>
                </div>
                <p class="text-muted small mb-3"><i class="far fa-calendar-alt mr-1"></i> <?= date('d M Y', strtotime($p['tanggal_dibuat'])); ?> | <i class="far fa-clock mr-1"></i> <?= date('H:i', strtotime($p['tanggal_dibuat'])); ?> WIB</p>
                <p class="text-dark mb-3"><?= nl2br(htmlspecialchars($p['isi'])); ?></p>
                
                <?php if ($p['file_surat']): ?>
                <div class="p-2 bg-light rounded border d-flex align-items-center">
                    <i class="fas fa-file-pdf text-danger mr-2 fa-lg"></i>
                    <small class="text-truncate mr-auto"><?= $p['file_surat']; ?></small>
                    <a href="../assets/files/pengumuman/<?= $p['file_surat']; ?>" target="_blank" class="btn btn-xs btn-primary">Lihat File</a>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endwhile; } else { ?>
            <div class="col-12 text-center py-5"><p class="text-muted">Belum ada pengumuman.</p></div>
          <?php } ?>
        </div>
      </div>
    </section>
  </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form action="" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header border-0 bg-teal text-white" style="border-radius: 20px 20px 0 0;">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-paper-plane mr-2"></i> Pengumuman Baru</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body p-4">
        <div class="form-group mb-3">
          <label class="small font-weight-bold">Judul Pengumuman</label>
          <input type="text" name="judul" class="form-control" placeholder="Tulis judul..." required>
        </div>
        
        <div class="form-group mb-3">
          <label class="small font-weight-bold">Tanggal Pengumuman (Kalender)</label>
          <input type="date" name="tanggal_pengumuman" class="form-control" value="<?= date('Y-m-d'); ?>" required>
          <small class="text-muted">Pilih tanggal pengumuman ini diterbitkan.</small>
        </div>

        <div class="form-group mb-3">
          <label class="small font-weight-bold">Isi Pesan</label>
          <textarea name="isi" class="form-control" rows="4" placeholder="Tulis pesan..." required></textarea>
        </div>
        
        <div class="form-group mb-0">
          <label class="small font-weight-bold">Lampiran Surat (PDF/Gambar)</label>
          <input type="file" name="file_surat" class="form-control-file d-block p-2 border rounded bg-light" accept=".pdf,.jpg,.jpeg,.png">
        </div>
      </div>
      <div class="modal-footer border-0 p-4">
        <button type="button" class="btn btn-light px-4" data-dismiss="modal">Batal</button>
        <button type="submit" name="tambah" class="btn btn-teal px-4 shadow-sm">Terbitkan</button>
      </div>
    </form>
  </div>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
</body>
</html>