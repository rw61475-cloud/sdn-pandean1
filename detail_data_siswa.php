<?php 
include "../includes/db.php"; 

$nisn = $_GET['nisn'] ?? '';
$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nisn = '$nisn'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Siswa | SDN Pandean 1</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700&display=fallback">
  <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">

  <style>
    :root { --primary: #008080; --bg-sidebar: #ffffff; }
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    
    /* Sidebar Brand Area */
    .brand-link { 
      background-color: var(--primary) !important; 
      color: #ffffff !important; 
      border-bottom: none !important;
      padding: 1rem 0.5rem !important;
      text-align: center;
    }
    .brand-text { font-weight: 700 !important; letter-spacing: 0.5px; }

    /* Sidebar Menu Styling */
    .main-sidebar { background-color: var(--bg-sidebar) !important; border-right: 1px solid #e2e8f0; }
    .nav-header { 
      font-size: 0.7rem !important; 
      font-weight: 700 !important; 
      color: #94a3b8 !important; 
      padding: 1rem 1rem 0.5rem 1.5rem !important;
    }
    .nav-pills .nav-link { color: #475569; font-weight: 500; border-radius: 8px; margin: 2px 10px; }
    .nav-pills .nav-link:hover { background-color: #f1f5f9; color: var(--primary); }
    .nav-pills .nav-link.active { 
      background-color: var(--primary) !important; 
      color: #ffffff !important; 
      box-shadow: 0 4px 6px -1px rgba(0, 128, 128, 0.2); 
    }
    .nav-icon { font-size: 0.9rem !important; margin-right: 10px !important; }

    /* Main Content Card */
    .card-pro { border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; }
    .profile-banner { height: 100px; background: linear-gradient(135deg, var(--primary) 0%, #005f5f 100%); }
    .profile-avatar { width: 110px; height: 110px; border: 5px solid #fff; margin-top: -55px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link text-teal font-weight-bold"><i class="fas fa-th-large mr-2"></i>Beranda Admin</a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-light-teal elevation-0">
    <a href="index.php" class="brand-link">
      <span class="brand-text">SDN PANDEAN 1</span>
    </a>

    <div class="sidebar mt-2">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-header">NAVIGASI UTAMA</li>
          
          <li class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard Overview</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="siswa.php" class="nav-link active">
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
            <a href="mapel.php" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>Mata Pelajaran</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper pt-4 px-3">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          
          <div class="col-lg-4">
            <div class="card card-pro text-center bg-white pb-4 mb-4">
              <div class="profile-banner"></div>
              <div class="text-center">
                <img src="../assets/adminlte/dist/img/avatar5.png" class="profile-avatar img-circle" alt="Siswa">
              </div>
              <div class="px-4 mt-3 text-center">
                <h4 class="font-weight-bold mb-1"><?php echo $data['nama_lengkap']; ?></h4>
                <p class="text-muted text-sm mb-4">Siswa Aktif • Kelas <?php echo $data['kelas']; ?></p>
                
                <div class="bg-light rounded p-3 mb-4 text-left">
                  <span class="text-xs font-weight-bold text-muted d-block mb-1">NISN</span>
                  <span class="h6 font-weight-bold text-teal"><?php echo $data['nisn']; ?></span>
                </div>

                <a href="edit_siswa.php?nisn=<?php echo $data['nisn']; ?>" class="btn btn-block py-2 text-white font-weight-bold" style="background: var(--primary); border-radius: 10px;">
                  <i class="fas fa-user-edit mr-2"></i> Edit Data
                </a>
              </div>
            </div>
          </div>

          <div class="col-lg-8">
            <div class="card card-pro bg-white p-4">
              <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h5 class="font-weight-bold mb-0 text-dark">Informasi Detail Peserta Didik</h5>
                <span class="badge badge-success px-3 py-2" style="border-radius: 6px;">AKTIF</span>
              </div>

              <div class="row">
                <div class="col-12 mb-4">
                  <label class="text-muted text-xs font-weight-bold mb-1 uppercase"><i class="fas fa-map-marker-alt mr-1"></i> Alamat</label>
                  <p class="h6 font-weight-600 text-dark bg-light p-3 rounded"><?php echo $data['alamat']; ?></p>
                </div>
                
                <div class="col-md-6 mb-4">
                  <label class="text-muted text-xs font-weight-bold mb-1 uppercase"><i class="fas fa-phone-alt mr-1"></i> No. HP Orang Tua</label>
                  <p class="h6 font-weight-600 text-dark bg-light p-3 rounded"><?php echo $data['no_hp_ortu']; ?></p>
                </div>

                <div class="col-md-6 mb-4">
                  <label class="text-muted text-xs font-weight-bold mb-1 uppercase"><i class="fas fa-door-open mr-1"></i> Ruang Kelas</label>
                  <p class="h6 font-weight-600 text-dark bg-light p-3 rounded">Kelas <?php echo $data['kelas']; ?></p>
                </div>
              </div>

              <div class="mt-4 pt-4 border-top text-right">
                <button onclick="window.print()" class="btn btn-outline-secondary px-4">
                  <i class="fas fa-print mr-2"></i> Cetak Profil
                </button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer bg-white text-xs text-center border-0">
    <strong>Copyright &copy; 2025 <span class="text-teal">SDN Pandean 1</span>.</strong>
  </footer>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
</body>
</html>