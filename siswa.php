<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. IDENTITAS HALAMAN (Agar Sidebar Otomatis Mendeteksi Link Aktif)
$page = 'siswa';
$title = 'Data Siswa Per Kelas | SDN Pandean 1';

// Ambil keyword pencarian jika ada
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

// 3. PANGGIL HEADER & SIDEBAR (Sama seperti di index.php)
include "includes/header.php";
include "includes/sidebar.php";
?>

<style>
    :root { 
      --primary: #008080; 
      --primary-dark: #006666;
    }
    .text-teal { color: var(--primary) !important; }
    .btn-tambah-pro {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white !important; border: none; border-radius: 10px; padding: 10px 20px;
        font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0, 128, 128, 0.2);
        display: inline-flex; align-items: center;
    }
    .btn-tambah-pro:hover { transform: translateY(-2px); filter: brightness(1.1); }
    .btn-print-outline {
        border: 2px solid #e2e8f0; background: white; color: #64748b;
        border-radius: 10px; padding: 10px 18px; font-weight: 600; transition: 0.3s;
    }
    .card-kelas { border-radius: 20px; border: 1px solid #e2e8f0; margin-bottom: 20px; background: #fff; overflow: hidden; transition: 0.3s; }
    .card-kelas:hover { border-color: var(--primary); }
    .header-kelas { padding: 20px; background: #fff; cursor: pointer; }
    .walas-info { font-size: 0.85rem; color: #008080; background: #e6fffa; padding: 4px 14px; border-radius: 20px; display: inline-block; border: 1px solid #b2f2bb; font-weight: 500; margin-top: 5px; }
    .btn-lihat-siswa { background: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 600; font-size: 0.8rem; padding: 8px 15px; border: none; transition: 0.3s; }
    .collapsed .btn-lihat-siswa { background: var(--primary); color: white; }
    .btn-action { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; }

    @media print {
        .main-sidebar, .main-header, .btn-print-global, .btn-action-col, .main-footer, .btn-print-local, .no-print, .btn-lihat-siswa { display: none !important; }
        .collapse { display: block !important; }
        .content-wrapper { margin-left: 0 !important; padding: 0 !important; }
    }
</style>

<div class="content-wrapper pt-4 px-3">
    <div class="container-fluid mb-4 btn-print-global">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1 class="font-weight-bold text-dark mb-1">Daftar Siswa Per Kelas</h1>
          <p class="text-muted">Klik pada kartu kelas untuk melihat rincian siswa.</p>
        </div>
        <div class="col-md-6 text-md-right mt-3 mt-md-0">
          <button onclick="window.print()" class="btn btn-print-outline mr-2 shadow-sm"><i class="fas fa-print mr-2"></i> Cetak Semua</button>
          <a href="tambah_siswa.php" class="btn btn-tambah-pro shadow-sm"><i class="fas fa-plus-circle mr-2"></i> Tambah Murid</a>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div id="accordionSiswa">
      <?php 
      for ($i = 1; $i <= 6; $i++) {
          $query_walas = mysqli_query($koneksi, "SELECT nama_lengkap FROM guru WHERE mata_pelajaran LIKE '%Wali Kelas $i%' LIMIT 1");
          $data_walas = mysqli_fetch_assoc($query_walas);
          $nama_walas = ($data_walas) ? $data_walas['nama_lengkap'] : "Belum ditentukan";

          $sql_siswa = "SELECT * FROM siswa WHERE kelas = '$i' OR kelas = $i";
          if ($search != '') {
              $sql_siswa .= " AND (nama_lengkap LIKE '%$search%' OR nisn LIKE '%$search%')";
          }
          $sql_siswa .= " ORDER BY nama_lengkap ASC";
          $res_siswa = mysqli_query($koneksi, $sql_siswa);
          $count_siswa = mysqli_num_rows($res_siswa);
          
          if ($count_siswa > 0 || $search == '') {
      ?>
        <div class="card card-kelas shadow-sm">
          <div class="header-kelas d-flex justify-content-between align-items-center collapsed" 
               data-toggle="collapse" 
               data-target="#collapseKelas<?= $i ?>" 
               aria-expanded="false">
            <div class="d-flex align-items-center">
                <div class="bg-teal p-3 rounded-circle mr-3 shadow-sm" style="width:50px; height:50px; display:flex; align-items:center; justify-content:center; background-color: #008080;">
                    <h4 class="m-0 font-weight-bold text-white"><?= $i ?></h4>
                </div>
                <div>
                    <h5 class="font-weight-bold text-dark m-0">KELAS <?= $i ?></h5>
                    <div class="walas-info">
                        <i class="fas fa-chalkboard-teacher mr-1"></i> Wali Kelas: <?= $nama_walas ?>
                    </div>
                </div>
            </div>
            
            <div class="d-flex align-items-center">
                <span class="badge badge-light border px-3 py-2 mr-3 d-none d-md-inline"><?= $count_siswa ?> Murid</span>
                <div class="btn-lihat-siswa">
                    <span class="mr-2">Lihat Daftar Siswa</span>
                    <i class="fas fa-chevron-up transition"></i>
                </div>
            </div>
          </div>
          
          <div id="collapseKelas<?= $i ?>" class="collapse" data-parent="#accordionSiswa">
            <div class="card-body p-0 border-top">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th width="60" class="pl-4">No</th>
                      <th>NISN</th>
                      <th>Nama Lengkap</th>
                      <th width="150" class="text-center btn-action-col no-print">Opsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $no = 1;
                    if($count_siswa > 0) {
                        while($row = mysqli_fetch_assoc($res_siswa)) {
                    ?>
                      <tr>
                        <td class="pl-4 text-muted"><?= $no++ ?></td>
                        <td class="font-weight-bold text-teal"><?= $row['nisn']; ?></td>
                        <td class="font-weight-600 text-dark"><?= $row['nama_lengkap']; ?></td>
                        <td class="text-center btn-action-col no-print">
                          <a href="edit_siswa.php?nisn=<?= $row['nisn']; ?>" class="btn-action bg-light text-warning shadow-sm" title="Edit"><i class="fas fa-edit"></i></a>
                          <a href="hapus_siswa.php?nisn=<?= $row['nisn']; ?>" class="btn-action bg-light text-danger shadow-sm ml-1" onclick="return confirm('Hapus data ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
                        </td>
                      </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center py-5 text-muted'>Belum ada data siswa di kelas ini.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="p-3 bg-light text-right">
                 <button onclick="printDiv('collapseKelas<?= $i ?>')" class="btn btn-sm btn-outline-secondary no-print"><i class="fas fa-print mr-1"></i> Cetak Daftar Kelas <?= $i ?></button>
              </div>
            </div>
          </div>
        </div>
      <?php 
          } 
      } 
      ?>
      </div> 
    </div>
</div>

<script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = "<html><head><title>Print Daftar Siswa</title><link rel='stylesheet' href='../assets/adminlte/dist/css/adminlte.min.css'><style>.no-print, .btn-action-col, .btn-lihat-siswa { display:none !important; } body{padding:40px; font-family: sans-serif;}</style></head><body><h2 style='text-align:center'>DAFTAR SISWA SDN PANDEAN 1</h2>" + printContents + "</body></html>";
     window.print();
     document.body.innerHTML = originalContents;
     window.location.reload(); 
}
</script>

<?php 
// 4. PANGGIL FOOTER (Sama seperti di index.php)
include "includes/footer.php"; 
?>