<?php
// 1. KONEKSI KE DATABASE
include '../includes/db.php'; 

// 2. IDENTITAS HALAMAN (Penting untuk Sidebar Otomatis)
$page  = 'ruang_kelas';
$title = 'Monitoring Ruang Kelas | SDN Pandean 1';

// 3. QUERY AMBIL DATA RUANG KELAS
$query_ruang = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$total_ruang = mysqli_num_rows($query_ruang);

// 4. PANGGIL HEADER & SIDEBAR
include "includes/header.php";
include "includes/sidebar.php";
?>

<style>
    :root { 
      --primary: #008080; 
      --bg-light: #f8fafc;
    }
    
    /* Stats Card Styling - Warna Kuning sesuai Index */
    .stat-card { 
        background: #f1b921; 
        border-radius: 15px; 
        padding: 1.5rem; 
        position: relative; 
        overflow: hidden; 
        color: #fff; 
        border: none; 
        box-shadow: 0 4px 15px rgba(241, 185, 33, 0.2); 
    }
    .stat-card .icon-bg { 
        position: absolute; 
        right: 10px; 
        bottom: 5px; 
        font-size: 4rem; 
        opacity: 0.2; 
    }
    
    /* Tabel Styling Modern */
    .card-custom { 
        border-radius: 15px; 
        border: 1px solid #e2e8f0; 
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
        background: #ffffff; 
        overflow: hidden; 
    }
    .table thead th { 
        border-bottom: 1px solid #f1f5f9; 
        color: #94a3b8; 
        font-size: 11px; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        padding: 1.2rem 1rem; 
    }
    .table td { 
        vertical-align: middle !important; 
        border-top: 1px solid #f1f5f9; 
        padding: 1rem !important; 
    }
    
    .room-icon { 
        width: 40px; 
        height: 40px; 
        background: #e6f2f2; 
        color: var(--primary); 
        border-radius: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
    }
    
    .btn-teal { 
        background: var(--primary); 
        color: #fff; 
        border-radius: 8px; 
        padding: 6px 15px; 
        font-weight: 600; 
        font-size: 13px; 
        border: none; 
        transition: 0.3s; 
    }
    .btn-teal:hover { 
        background: #006666; 
        color: #fff; 
        box-shadow: 0 4px 12px rgba(0, 128, 128, 0.2); 
    }
    .text-teal { color: var(--primary) !important; }
</style>

<div class="content-wrapper pt-4 px-3">
    <section class="content">
      <div class="container-fluid">
        
        <div class="row mb-4">
          <div class="col-sm-12 pl-2">
            <h2 class="font-weight-bold text-dark mb-1">Monitoring Ruang Kelas</h2>
            <p class="text-muted">Kelola distribusi dan kapasitas ruang belajar secara real-time.</p>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-4">
            <div class="stat-card shadow-sm">
              <h1 class="font-weight-bold mb-0"><?php echo $total_ruang; ?></h1>
              <p class="mb-0 font-weight-600">Total Ruang Kelas Aktif</p>
              <i class="fas fa-school icon-bg"></i>
            </div>
          </div>
        </div>

        <div class="card card-custom">
          <div class="card-header border-0 bg-white pt-4 px-4">
            <h5 class="font-weight-bold mb-0 text-dark">
              <i class="fas fa-layer-group mr-2 text-warning"></i> Distribusi Kelas & Kapasitas Siswa
            </h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th class="pl-4 text-center" width="70">No</th>
                    <th>Nama Ruang Kelas</th>
                    <th class="text-center">Jumlah Siswa Terdaftar</th>
                    <th class="text-right pr-4">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  while($r = mysqli_fetch_assoc($query_ruang)) { 
                    $kls_name = mysqli_real_escape_string($koneksi, trim($r['nama_kelas']));
                    // Menghitung jumlah siswa per kelas
                    $query_siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE TRIM(kelas) = '$kls_name'");
                    $count_siswa = mysqli_num_rows($query_siswa);
                  ?>
                  <tr>
                    <td class="text-center text-muted font-weight-bold">
                        <?php echo str_pad($no++, 2, "0", STR_PAD_LEFT); ?>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="room-icon mr-3"><i class="fas fa-building"></i></div>
                        <div>
                          <span class="font-weight-bold text-dark d-block">Kelas <?php echo htmlspecialchars($r['nama_kelas']); ?></span>
                          <small class="text-muted">Unit: SDN Pandean 1</small>
                        </div>
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="badge badge-light px-3 py-2 border" style="border-radius: 8px; font-weight: 500;">
                        <i class="fas fa-users mr-1 text-muted"></i> <?php echo $count_siswa; ?> Siswa
                      </span>
                    </td>
                    <td class="text-right pr-4">
                      <a href="daftar_siswa.php?kelas=<?php echo urlencode($r['nama_kelas']); ?>" class="btn btn-teal">
                        <i class="fas fa-eye mr-1" style="font-size: 11px;"></i> Detail Siswa
                      </a>
                    </td>
                  </tr>
                  <?php } ?>
                  
                  <?php if($total_ruang == 0): ?>
                  <tr>
                    <td colspan="4" class="text-center py-5 text-muted">Belum ada data ruang kelas.</td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>
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