<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. LOGIKA DATA SISWA
$nisn = $_GET['nisn'] ?? '';
$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nisn = '$nisn'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: siswa.php");
    exit;
}

// 3. IDENTITAS HALAMAN
$page  = 'siswa';
$title = 'Detail Siswa | SDN Pandean 1';

// 4. PANGGIL HEADER & SIDEBAR
include "includes/header.php";
include "includes/sidebar.php";
?>

<style>
    :root { --primary: #008080; }
    .text-teal { color: var(--primary) !important; }
    .card-pro { border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; background: #fff; }
    .profile-banner { height: 100px; background: linear-gradient(135deg, var(--primary) 0%, #005f5f 100%); }
    .profile-avatar { width: 110px; height: 110px; border: 5px solid #fff; margin-top: -55px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .detail-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; font-weight: 700; margin-bottom: 4px; display: block; }
    .detail-value { font-weight: 600; color: #334155; background-color: #f1f5f9; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; }

    /* CSS KHUSUS PRINT */
    #print-header { display: none; } 

    @media print {
      .main-sidebar, .main-header, .main-footer, .btn, .edit-link { display: none !important; }
      .content-wrapper { margin-left: 0 !important; padding-top: 0 !important; width: 100% !important; background: white !important; }
      #print-header { 
        display: block !important; 
        text-align: center; 
        border-bottom: 3px double #000; 
        margin-bottom: 30px; 
        padding-bottom: 10px;
      }
      .card-pro { border: none !important; box-shadow: none !important; }
      .detail-value { background-color: #fff !important; border-bottom: 1px solid #ddd; border-radius: 0; padding: 5px 0; }
      .profile-avatar { margin-top: 0; }
    }
</style>

<div class="content-wrapper pt-4 px-3">
    <div id="print-header">
        <div class="row align-items-center">
            <div class="col-2 text-right">
                <img src="../assets/img/logo_sekolah.png" alt="Logo" style="width: 80px;" onerror="this.style.display='none'">
            </div>
            <div class="col-8 text-center">
                <h4 style="margin: 0; font-weight: 700;">PEMERINTAH KABUPATEN MAGELANG</h4>
                <h3 style="margin: 0; font-weight: 800; color: #008080;">SD NEGERI PANDEAN 1</h3>
                <p style="margin: 0; font-size: 12px;">Alamat: Grabag, Kec. Grabag, Kabupaten Magelang, Jawa Tengah 56196</p>
                <p style="margin: 0; font-size: 12px;">Email: info@sdnpandean1.sch.id | Website: www.sdnpandean1.sch.id</p>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card card-pro text-center pb-4 mb-4">
                        <div class="profile-banner"></div>
                        <div class="text-center">
                            <img src="../assets/adminlte/dist/img/avatar5.png" class="profile-avatar img-circle" alt="Siswa">
                        </div>
                        <div class="px-4 mt-3">
                            <h4 class="font-weight-bold mb-1"><?php echo htmlspecialchars($data['nama_lengkap']); ?></h4>
                            <p class="text-muted text-sm mb-4">Siswa Aktif • Kelas <?php echo htmlspecialchars($data['kelas']); ?></p>
                            
                            <div class="bg-light rounded p-3 mb-4 text-left border shadow-sm">
                                <span class="detail-label mb-1">NISN Peserta Didik</span>
                                <span class="h6 font-weight-bold text-teal mb-0"><?php echo htmlspecialchars($data['nisn']); ?></span>
                            </div>

                            <a href="edit_siswa.php?nisn=<?php echo $data['nisn']; ?>" class="btn btn-block py-2 text-white font-weight-bold shadow-sm edit-link" style="background: var(--primary); border-radius: 10px;">
                                <i class="fas fa-user-edit mr-2"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card card-pro p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h5 class="font-weight-bold mb-0 text-dark">Informasi Detail Peserta Didik</h5>
                            <span class="badge badge-success px-3 py-2" style="border-radius: 8px;">STATUS: AKTIF</span>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="detail-label"><i class="fas fa-map-marker-alt mr-1"></i> Alamat Domisili</label>
                                <p class="detail-value"><?php echo htmlspecialchars($data['alamat']); ?></p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="detail-label"><i class="fas fa-phone-alt mr-1"></i> No. HP Orang Tua / Wali</label>
                                <p class="detail-value"><?php echo htmlspecialchars($data['no_hp_ortu'] ?: '-'); ?></p>
                            </div>

                            <div class="col-md-6">
                                <label class="detail-label"><i class="fas fa-door-open mr-1"></i> Penempatan Kelas</label>
                                <p class="detail-value">Kelas <?php echo htmlspecialchars($data['kelas']); ?></p>
                            </div>
                        </div>

                        <div class="mt-2 pt-4 border-top d-flex justify-content-between no-print">
                            <a href="siswa.php" class="btn btn-light px-4 border" style="border-radius: 8px;">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-secondary px-4 shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-print mr-2"></i> Cetak Profil
                            </button>
                        </div>
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