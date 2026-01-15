<?php 
// 1. KONEKSI KE DATABASE (Mengikuti path siswa.php)
include "../includes/db.php"; 

// 2. IDENTITAS HALAMAN
$page = 'rekap_presensi_siswa';
$title = 'Rekap Presensi Siswa | SDN Pandean 1';

// Ambil keyword pencarian jika ada
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

// 3. PANGGIL HEADER & SIDEBAR (Mengikuti path siswa.php)
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
    .btn-lihat-rekap { background: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 600; font-size: 0.8rem; padding: 8px 15px; border: none; transition: 0.3s; }
    .collapsed .btn-lihat-rekap { background: var(--primary); color: white; }
    
    /* Styling Badge Status */
    .badge-status { width: 30px; display: inline-block; font-weight: bold; }

    @media print {
        .main-sidebar, .main-header, .btn-print-global, .main-footer, .btn-print-local, .no-print, .btn-lihat-rekap { display: none !important; }
        .collapse { display: block !important; }
        .content-wrapper { margin-left: 0 !important; padding: 0 !important; }
    }
</style>

<div class="content-wrapper pt-4 px-3">
    <div class="container-fluid mb-4 btn-print-global">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1 class="font-weight-bold text-dark mb-1">Rekap Presensi Siswa</h1>
          <p class="text-muted">Pantau data kehadiran siswa di setiap kelas secara berkala.</p>
        </div>
        <div class="col-md-6 text-md-right mt-3 mt-md-0">
          <button onclick="window.print()" class="btn btn-print-outline shadow-sm"><i class="fas fa-print mr-2"></i> Cetak Laporan</button>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div id="accordionRekap">
      <?php 
      for ($i = 1; $i <= 6; $i++) {
          // Ambil Wali Kelas
          $query_walas = mysqli_query($koneksi, "SELECT nama_lengkap FROM guru WHERE mata_pelajaran LIKE '%Wali Kelas $i%' LIMIT 1");
          $data_walas = mysqli_fetch_assoc($query_walas);
          $nama_walas = ($data_walas) ? $data_walas['nama_lengkap'] : "Belum ditentukan";

          // Query Rekap Menggunakan Nama_Lengkap Sesuai Database
          $sql_rekap = "SELECT 
                            s.nisn, 
                            s.nama_lengkap,
                            COUNT(CASE WHEN p.status = 'Hadir' THEN 1 END) as Hadir,
                            COUNT(CASE WHEN p.status = 'Izin' THEN 1 END) as Izin,
                            COUNT(CASE WHEN p.status = 'Sakit' THEN 1 END) as Sakit,
                            COUNT(CASE WHEN p.status = 'Alpha' THEN 1 END) as Alpa
                        FROM siswa s
                        LEFT JOIN presensi_siswa p ON s.nisn = p.nisn
                        WHERE s.kelas = '$i'";
          
          if ($search != '') {
              $sql_rekap .= " AND (s.nama_lengkap LIKE '%$search%' OR s.nisn LIKE '%$search%')";
          }
          
          $sql_rekap .= " GROUP BY s.nisn, s.nama_lengkap ORDER BY s.nama_lengkap ASC";
          $res_rekap = mysqli_query($koneksi, $sql_rekap);
          $count_siswa = mysqli_num_rows($res_rekap);
          
          if ($count_siswa > 0 || $search == '') {
      ?>
        <div class="card card-kelas shadow-sm">
          <div class="header-kelas d-flex justify-content-between align-items-center collapsed" 
               data-toggle="collapse" 
               data-target="#collapseRekap<?= $i ?>" 
               aria-expanded="false">
            <div class="d-flex align-items-center">
                <div class="bg-teal p-3 rounded-circle mr-3 shadow-sm" style="width:50px; height:50px; display:flex; align-items:center; justify-content:center; background-color: #008080;">
                    <h4 class="m-0 font-weight-bold text-white"><?= $i ?></h4>
                </div>
                <div>
                    <h5 class="font-weight-bold text-dark m-0">REKAP KELAS <?= $i ?></h5>
                    <div class="walas-info">
                        <i class="fas fa-chalkboard-teacher mr-1"></i> Walas: <?= $nama_walas ?>
                    </div>
                </div>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="btn-lihat-rekap">
                    <span class="mr-2">Lihat Statistik</span>
                    <i class="fas fa-chevron-up transition"></i>
                </div>
            </div>
          </div>
          
          <div id="collapseRekap<?= $i ?>" class="collapse" data-parent="#accordionRekap">
            <div class="card-body p-0 border-top">
              <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                  <thead class="bg-light">
                    <tr>
                      <th width="60" class="pl-4">No</th>
                      <th>Nama Lengkap</th>
                      <th class="text-center">Hadir</th>
                      <th class="text-center">Izin</th>
                      <th class="text-center">Sakit</th>
                      <th class="text-center">Alpa</th>
                      <th class="text-center">Total Absen</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $no = 1;
                    if($count_siswa > 0) {
                        while($row = mysqli_fetch_assoc($res_rekap)) {
                            $total_absen = $row['Izin'] + $row['Sakit'] + $row['Alpa'];
                    ?>
                      <tr>
                        <td class="pl-4 text-muted"><?= $no++ ?></td>
                        <td>
                            <div class="font-weight-bold text-dark"><?= $row['nama_lengkap']; ?></div>
                            <small class="text-muted">NISN: <?= $row['nisn']; ?></small>
                        </td>
                        <td class="text-center"><span class="badge badge-success px-2"><?= $row['Hadir']; ?></span></td>
                        <td class="text-center font-weight-bold text-primary"><?= $row['Izin']; ?></td>
                        <td class="text-center font-weight-bold text-warning"><?= $row['Sakit']; ?></td>
                        <td class="text-center font-weight-bold text-danger"><?= $row['Alpa']; ?></td>
                        <td class="text-center">
                            <span class="badge <?= ($total_absen > 3) ? 'badge-danger' : 'badge-light border' ?> px-3 py-1">
                                <?= $total_absen ?> Hari
                            </span>
                        </td>
                      </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-5 text-muted'>Belum ada data presensi di kelas ini.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="p-3 bg-light text-right">
                 <button onclick="printDiv('collapseRekap<?= $i ?>')" class="btn btn-sm btn-outline-secondary no-print"><i class="fas fa-print mr-1"></i> Cetak Rekap Kelas <?= $i ?></button>
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
     document.body.innerHTML = "<html><head><title>Cetak Rekap Presensi</title><link rel='stylesheet' href='../assets/adminlte/dist/css/adminlte.min.css'><style>.no-print { display:none !important; } body{padding:40px;}</style></head><body><h2 style='text-align:center'>REKAP PRESENSI SISWA - SDN PANDEAN 1</h2>" + printContents + "</body></html>";
     window.print();
     document.body.innerHTML = originalContents;
     window.location.reload(); 
}
</script>

<?php 
// 4. PANGGIL FOOTER
include "includes/footer.php"; 
?>