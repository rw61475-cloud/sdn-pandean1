<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 
$page = 'siswa'; // Variabel untuk mengaktifkan menu 'Data Siswa' di sidebar

// 2. LOGIKA SIMPAN DATA
if (isset($_POST['simpan'])) {
    $nisn         = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $kelas        = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $alamat       = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_hp_ortu   = mysqli_real_escape_string($koneksi, $_POST['no_hp_ortu']);
    
    // Akun default untuk login siswa
    $password_raw = "12345";
    $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);

    // --- VALIDASI: CEK APAKAH NISN SUDAH ADA DI TABEL SISWA ---
    $cek_data = mysqli_query($koneksi, "SELECT nisn FROM siswa WHERE nisn = '$nisn'");
    
    if (mysqli_num_rows($cek_data) > 0) {
        echo "<script>alert('Gagal! NISN $nisn sudah terdaftar dalam sistem.'); window.history.back();</script>";
    } else {
        // A. Masukkan ke tabel users (Hanya kolom yang ada: identitas, nama_lengkap, password, role)
        // Kolom 'email' dibiarkan NULL atau kosong sesuai struktur database Anda
        $query_user = mysqli_query($koneksi, "INSERT INTO users (identitas, nama_lengkap, password, role) 
                                              VALUES ('$nisn', '$nama_lengkap', '$password_hash', 'siswa')");

        if ($query_user) {
            // Ambil ID user yang baru saja dibuat
            $id_user = mysqli_insert_id($koneksi);
            
            // B. Masukkan ke tabel siswa dengan menghubungkan id_user
            $query_siswa = mysqli_query($koneksi, "INSERT INTO siswa (nisn, nama_lengkap, kelas, alamat, no_hp_ortu, id_user) 
                                                  VALUES ('$nisn', '$nama_lengkap', '$kelas', '$alamat', '$no_hp_ortu', '$id_user')");
            
            if ($query_siswa) {
                echo "<script>alert('Data siswa berhasil ditambahkan!'); window.location='siswa.php';</script>";
            } else {
                // Jika tabel siswa gagal, hapus user yang sempat dibuat agar tidak menjadi data sampah
                mysqli_query($koneksi, "DELETE FROM users WHERE id_user = '$id_user'");
                echo "<script>alert('Gagal menambahkan data ke tabel siswa.');</script>";
            }
        } else {
            echo "<script>alert('Gagal membuat akun login user. Pastikan data identitas belum digunakan.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Siswa | SDN Pandean 1</title>

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
    .nav-pills .nav-link { border-radius: 10px; margin-bottom: 4px; color: #64748b !important; padding: 0.8rem 1rem; }
    .nav-pills .nav-link.active { background: var(--primary) !important; color: #fff !important; box-shadow: 0 4px 12px rgba(0, 128, 128, 0.2); }
    .nav-pills .nav-link:hover:not(.active) { background: #f1f5f9; color: var(--primary) !important; }

    .main-header { background: rgba(255, 255, 255, 0.8) !important; backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0 !important; }
    .text-teal { color: var(--primary) !important; }

    .card { border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-teal.card-outline { border-top: 4px solid var(--primary); }
    .form-control { border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; }
    .form-control:focus { border-color: var(--primary); box-shadow: none; }
    .btn-teal { background-color: var(--primary); color: white; border-radius: 10px; padding: 0.6rem 2rem; font-weight: 600; transition: 0.3s; }
    .btn-teal:hover { background-color: #006666; color: white; transform: translateY(-2px); }
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
        <a href="siswa.php" class="nav-link text-teal font-weight-bold"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Data Siswa</a>
      </li>
    </ul>
  </nav>

  <?php include "sidebar.php"; ?>

  <div class="content-wrapper pt-4 px-3">
    <div class="content-header">
      <div class="container-fluid">
        <h2 class="m-0 font-weight-bold text-dark">Tambah Siswa Baru</h2>
        <p class="text-muted small">Silakan lengkapi formulir di bawah ini dengan benar.</p>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-8">
            <div class="card card-teal card-outline shadow-sm">
              <div class="card-body mt-2">
                <form action="" method="POST">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>NISN <span class="text-danger">*</span></label>
                        <input type="text" name="nisn" class="form-control" placeholder="Contoh: 00123456" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Kelas <span class="text-danger">*</span></label>
                        <select name="kelas" class="form-control" required>
                          <option value="">-- Pilih Kelas --</option>
                          <option value="1">Kelas 1</option>
                          <option value="2">Kelas 2</option>
                          <option value="3">Kelas 3</option>
                          <option value="4">Kelas 4</option>
                          <option value="5">Kelas 5</option>
                          <option value="6">Kelas 6</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap siswa" required>
                  </div>

                  <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat tempat tinggal saat ini"></textarea>
                  </div>

                  <div class="form-group">
                    <label>No. HP Orang Tua / Wali</label>
                    <input type="text" name="no_hp_ortu" class="form-control" placeholder="Contoh: 08123456789">
                    <small class="text-muted">Gunakan format angka tanpa spasi.</small>
                  </div>

                  <div class="card-footer bg-white px-0 pb-0 mt-4 d-flex justify-content-end">
                    <button type="reset" class="btn btn-light rounded-pill mr-2 px-4">Reset</button>
                    <button type="submit" name="simpan" class="btn btn-teal shadow-sm">
                      <i class="fas fa-save mr-1"></i> Simpan Data Siswa
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="card shadow-sm">
              <div class="card-body text-center py-4">
                <i class="fas fa-user-shield text-teal mb-3" style="font-size: 3rem;"></i>
                <h5 class="font-weight-bold">Keamanan Data</h5>
                <p class="text-sm text-muted">Data NISN akan digunakan sebagai identitas login siswa ke dalam sistem informasi.</p>
                <hr>
                <p class="text-xs text-left mb-1"><b>ID Login:</b> Gunakan NISN</p>
                <p class="text-xs text-left mb-1"><b>Password Default:</b> 12345</p>
                <p class="text-xs text-left"><b>Status Akun:</b> Aktif</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer bg-white text-xs text-center py-3 mt-4">
    <strong>Copyright &copy; 2025 <span class="text-teal">SDN Pandean 1</span>.</strong>
  </footer>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
</body>
</html>