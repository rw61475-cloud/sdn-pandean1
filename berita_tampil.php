<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. IDENTITAS HALAMAN
$page  = 'berita'; 
$title = 'Manajemen Berita | SDN Pandean 1';

// 3. QUERY DATA
$query_berita = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM berita");
$data_count = mysqli_fetch_assoc($query_berita);
$total_berita = $data_count['total'] ?? 0;

// 4. PANGGIL HEADER & SIDEBAR
include "includes/header.php";
include "includes/sidebar.php";
?>

<style>
    :root { 
      --primary: #008080; 
      --bg-light: #f8fafc;
    }
    
    .text-teal { color: var(--primary) !important; }

    /* Card & Table Styling */
    .card { border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-teal.card-outline { border-top: 4px solid var(--primary); }
    
    .table thead th { 
        border: none; 
        color: #94a3b8; 
        font-size: 11px; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        padding: 1.5rem 1rem; 
        background-color: #fcfdfe;
    }
    
    .table td { 
        vertical-align: middle !important; 
        padding: 1.2rem 1rem !important; 
        border-top: 1px solid #f1f5f9; 
    }
    
    .bg-teal-custom { background-color: var(--primary) !important; color: white !important; transition: 0.3s; }
    .bg-teal-custom:hover { background-color: #006666 !important; transform: translateY(-2px); color: white !important; }
    
    /* Image Styling */
    .img-berita { 
        width: 70px; 
        height: 45px; 
        object-fit: cover; 
        border-radius: 8px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .badge-date {
        background: #e6fffa;
        color: var(--primary);
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 8px;
    }
</style>

<div class="content-wrapper pt-4 px-3">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h2 class="m-0 font-weight-bold text-dark"><i class="fas fa-newspaper mr-2 text-teal"></i> Manajemen Berita</h2>
                    <p class="text-muted small mb-0">Kelola artikel dan pengumuman untuk website sekolah.</p>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="berita_tambah.php" class="btn bg-teal-custom shadow-sm px-4" style="border-radius: 12px;">
                        <i class="fas fa-plus-circle mr-1"></i> Buat Berita Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-teal card-outline shadow-sm">
                        <div class="card-header bg-white border-0 pt-3">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-list mr-1 text-teal"></i> Daftar Berita Terbit
                            </h3>
                        </div>
                        
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0 table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">No</th>
                                            <th width="120">Gambar</th>
                                            <th>Informasi Berita</th>
                                            <th>Penulis</th>
                                            <th class="text-center">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query_tabel = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY id_berita DESC");
                                        if ($query_tabel && mysqli_num_rows($query_tabel) > 0) {
                                            while ($row = mysqli_fetch_assoc($query_tabel)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td>
                                                <img src="../assets/img/berita/<?php echo $row['gambar']; ?>" 
                                                     class="img-berita" 
                                                     onerror="this.src='../assets/img/berita/default.jpg'">
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark mb-1"><?php echo $row['judul']; ?></div>
                                                <span class="badge-date small">
                                                    <i class="far fa-calendar-alt mr-1"></i> <?php echo date('d M Y', strtotime($row['tanggal'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted small"><i class="fas fa-user-circle mr-1"></i> <?php echo $row['penulis']; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="berita_edit.php?id=<?php echo $row['id_berita']; ?>" class="btn btn-sm btn-outline-info mr-1" style="border-radius: 8px;" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="berita_hapus.php?id=<?php echo $row['id_berita']; ?>" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php 
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' class='text-center py-5 text-muted'>Belum ada data berita yang diterbitkan.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                            <p class="text-muted small mb-0">Menampilkan daftar berita terbaru dari database.</p>
                            <span class="badge badge-light p-2 text-teal" style="border-radius: 8px;">
                                Total: <b><?php echo $total_berita; ?></b> Berita
                            </span>
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