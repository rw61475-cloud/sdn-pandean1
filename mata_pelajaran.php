<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. IDENTITAS HALAMAN (Penting untuk Sidebar)
$page  = 'mata_pelajaran';
$title = 'Mata Pelajaran | SDN Pandean 1';

// 3. PANGGIL HEADER & SIDEBAR
include "includes/header.php";
include "includes/sidebar.php";
?>

<style>
    :root { 
      --primary: #008080; 
      --bg-light: #f8fafc;
    }
    
    .text-teal { color: var(--primary) !important; }

    /* Card Styling untuk Daftar Kelas */
    .class-card { 
      border-radius: 20px; 
      border: 1px solid #e2e8f0; 
      transition: all 0.3s ease; 
      background: #fff;
      overflow: hidden;
      margin-bottom: 25px;
    }
    .class-card:hover { 
      transform: translateY(-5px); 
      box-shadow: 0 12px 24px rgba(0, 128, 128, 0.08); 
      border-color: var(--primary);
    }
    .class-icon { 
      width: 50px; height: 50px; 
      background: #e6f2f2; color: var(--primary); 
      border-radius: 12px; display: flex; 
      align-items: center; justify-content: center; font-size: 20px;
    }

    .btn-rincian {
      background: var(--primary);
      color: white !important;
      border-radius: 10px;
      padding: 8px 18px;
      font-weight: 600;
      font-size: 13px;
      border: none;
      transition: 0.3s;
    }
    .btn-rincian:hover { 
      opacity: 0.9; 
      box-shadow: 0 4px 10px rgba(0, 128, 128, 0.2); 
    }
</style>

<div class="content-wrapper pt-4 px-3">
    <div class="content-header">
      <div class="container-fluid">
        <h2 class="m-0 font-weight-bold text-dark">Kurikulum Sekolah</h2>
        <p class="text-muted">Kelola mata pelajaran berdasarkan jenjang kelas dan kurikulum yang berlaku.</p>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <?php for($i=1; $i<=6; $i++): ?>
          <div class="col-lg-4 col-md-6">
            <div class="class-card p-4 shadow-sm">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <div class="class-icon mr-3">
                    <i class="fas fa-book-reader"></i>
                  </div>
                  <div>
                    <h5 class="mb-0 font-weight-bold text-dark">Kelas <?php echo $i; ?></h5>
                    <small class="text-muted">Kurikulum Merdeka</small>
                  </div>
                </div>
                <a href="detail_mapel.php?kelas=<?php echo $i; ?>" class="btn btn-rincian">
                  Rincian <i class="fas fa-chevron-right ml-1" style="font-size: 10px;"></i>
                </a>
              </div>
            </div>
          </div>
          <?php endfor; ?>
        </div>
      </div>
    </section>
</div>

<?php 
// 4. PANGGIL FOOTER
include "includes/footer.php"; 
?>