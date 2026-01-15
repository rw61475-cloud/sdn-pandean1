<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. AMBIL PARAMETER FILTER
$filter_guru = isset($_GET['filter_guru']) ? mysqli_real_escape_string($koneksi, $_GET['filter_guru']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rekap Nilai Siswa | SDN Pandean 1</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap">
  <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">

  <style>
    :root { 
      --primary: #008080; 
      --bg-light: #f8fafc;
    }
    
    body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); color: #334155; }
    
    .main-sidebar { background: #ffffff !important; border-right: 1px solid #e2e8f0 !important; }
    .brand-link { background: var(--primary) !important; color: #fff !important; text-align: center; border-bottom: none !important; padding: 1.2rem 0.5rem !important; }
    
    .nav-header { 
      font-size: 0.75rem !important; 
      font-weight: 700 !important; 
      color: #94a3b8 !important; 
      letter-spacing: 1px; 
      padding: 1rem 1.5rem 0.5rem !important; 
      text-transform: uppercase;
    }

    .nav-pills .nav-link { border-radius: 10px; margin-bottom: 4px; color: #64748b !important; padding: 0.8rem 1rem; }
    .nav-pills .nav-link.active { 
      background: var(--primary) !important; 
      color: #fff !important; 
      box-shadow: 0 4px 12px rgba(0, 128, 128, 0.3) !important; 
    }
    .nav-pills .nav-link:hover:not(.active) { background: #f1f5f9; color: var(--primary) !important; }

    .main-header { background: rgba(255, 255, 255, 0.8) !important; backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0 !important; }
    .text-teal { color: var(--primary) !important; }
    .btn-teal { background-color: var(--primary); color: white; border-radius: 10px; font-weight: 600; transition: 0.3s; }
    
    .card { border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .table thead th { border: none; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; padding: 1.5rem 1rem; }
    
    @media print {
      .main-sidebar, .main-header, form, .btn-teal, .nav-header { display: none !important; }
      .content-wrapper { margin-left: 0 !important; padding: 0 !important; }
      .card { border: none !important; box-shadow: none !important; }
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
    <ul class="navbar-nav pl-3">
      <li class="nav-item">
        <a class="nav-link text-teal" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link text-teal font-weight-bold"><i class="fas fa-home mr-1"></i> Beranda Admin</a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-light-teal elevation-4">
    <a href="index.php" class="brand-link">
      <span class="brand-text font-weight-bold">SDN PANDEAN 1</span>
    </a>

    <div class="sidebar mt-3 px-3">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-header">NAVIGASI UTAMA</li>
          
          <li class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard Overview</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="rekap_nilai.php" class="nav-link active">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>Rekap Nilai</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="siswa.php" class="nav-link">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>Data Siswa</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="guru.php" class="nav-link">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>Data Guru</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="ruang_kelas.php" class="nav-link">
              <i class="nav-icon fas fa-school"></i>
              <p>Ruang Kelas</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="mata_pelajaran.php" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>Mata Pelajaran</p>
            </a>
          </li>

          <li class="nav-header">PENGATURAN WEBSITE</li>
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-globe"></i>
              <p>Tampilan Publik <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="galeri.php" class="nav-link"><i class="far fa-image nav-icon"></i><p>Galeri Sekolah</p></a></li>
              <li class="nav-item"><a href="berita.php" class="nav-link"><i class="far fa-newspaper nav-icon"></i><p>Berita & Artikel</p></a></li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper pt-4 px-3">
    <div class="content-header">
      <div class="container-fluid d-flex justify-content-between align-items-center">
        <h2 class="m-0 font-weight-bold text-dark"><i class="fas fa-graduation-cap text-teal mr-2"></i> Rekap Nilai Siswa</h2>
        <?php if($filter_guru): ?>
          <button onclick="window.print()" class="btn btn-teal shadow-sm"><i class="fas fa-print mr-2"></i> Cetak Laporan</button>
        <?php endif; ?>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        
        <div class="card mb-4 shadow-sm border-0">
          <div class="card-body">
            <form method="GET" id="filterForm" class="row align-items-end">
              <div class="col-md-8">
                <label class="small font-weight-bold text-muted">CARI BERDASARKAN GURU / WALI KELAS</label>
                <select name="filter_guru" class="form-control" style="border-radius: 10px;" onchange="this.form.submit()" required>
                  <option value="">-- Pilih Nama Guru untuk Mencari Data --</option>
                  <?php
                  $gurus = mysqli_query($koneksi, "SELECT id_user, nama_lengkap, mata_pelajaran FROM guru ORDER BY nama_lengkap ASC");
                  while($g = mysqli_fetch_assoc($gurus)) {
                    $selected = ($filter_guru == $g['id_user']) ? 'selected' : '';
                    echo "<option value='".$g['id_user']."' $selected>".$g['nama_lengkap']." (".$g['mata_pelajaran'].")</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-4">
                <?php if($filter_guru): ?>
                  <a href="rekap_nilai.php" class="btn btn-light btn-block border" style="border-radius: 10px;">Reset Pencarian</a>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>

        <?php
        if ($filter_guru) {
            // Mengambil mapel unik menggunakan id_user yang sudah Anda perbaiki
            $sql_mapel = "SELECT DISTINCT mapel FROM nilai WHERE id_user = '$filter_guru' AND mapel IS NOT NULL";
            $res_mapel = mysqli_query($koneksi, $sql_mapel);

            if (mysqli_num_rows($res_mapel) > 0) {
                while($m = mysqli_fetch_assoc($res_mapel)) {
                    $current_mapel = $m['mapel'];
        ?>
            <div class="card shadow-sm border-0 mb-4">
              <div class="card-header bg-white py-3">
                <h5 class="m-0 font-weight-bold text-teal"><i class="fas fa-book mr-2"></i> Mata Pelajaran: <?= $current_mapel ?></h5>
              </div>
              <div class="card-body p-0 text-sm">
                <div class="table-responsive">
                  <table class="table table-hover m-0">
                    <thead class="bg-light">
                      <tr>
                        <th class="pl-4">NISN</th>
                        <th>Nama Siswa</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Rata-Rata</th>
                        <th class="text-center">Semester</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql_nilai = "SELECT n.*, s.nama_lengkap as nama_siswa 
                                    FROM nilai n
                                    JOIN siswa s ON n.nisn = s.nisn
                                    WHERE n.id_user = '$filter_guru' AND n.mapel = '$current_mapel'
                                    ORDER BY s.nama_lengkap ASC";
                      $res_nilai = mysqli_query($koneksi, $sql_nilai);
                      while($row = mysqli_fetch_assoc($res_nilai)):
                        $rata = ($row['nilai_tugas'] + $row['nilai_uts'] + $row['nilai_uas']) / 3;
                      ?>
                        <tr>
                          <td class="pl-4 font-weight-bold text-teal"><?= $row['nisn'] ?></td>
                          <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                          <td class="text-center"><?= $row['nilai_tugas'] ?></td>
                          <td class="text-center"><?= $row['nilai_uts'] ?></td>
                          <td class="text-center"><?= $row['nilai_uas'] ?></td>
                          <td class="text-center">
                            <b class="<?= $rata >= 75 ? 'text-success' : 'text-danger' ?>">
                              <?= number_format($rata, 1) ?>
                            </b>
                          </td>
                          <td class="text-center"><?= $row['semester'] ?></td>
                        </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        <?php 
                }
            } else {
                echo "<div class='text-center py-5'><img src='../assets/img/empty.svg' style='width:150px;'><p class='mt-3 text-muted'>Tidak ada data nilai yang ditemukan untuk guru ini.</p></div>";
            }
        } else {
            echo "<div class='card shadow-sm border-0'><div class='card-body text-center py-5 text-muted'><i class='fas fa-search fa-3x mb-3 text-light'></i><br>Silakan pilih Nama Wali Kelas pada dropdown di atas untuk menampilkan rekap nilai.</div></div>";
        }
        ?>
      </div>
    </section>
  </div>

  <footer class="main-footer bg-white text-xs text-center py-3">
    <strong>Copyright &copy; 2025 <span class="text-teal">SDN Pandean 1</span>.</strong>
  </footer>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
</body>
</html>