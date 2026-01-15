<?php 
// 1. KONEKSI KE DATABASE
include "../includes/db.php"; 

// 2. IDENTITAS HALAMAN
$page  = 'rekap_presensi';
$title = 'Rekap Presensi Guru';

// 3. LOGIKA HAPUS DATA
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM presensi WHERE id = '$id'");
    header("Location: rekap_presensi.php?status=deleted");
    exit;
}

// 4. LOGIKA FILTER (PENGELOMPOKAN)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua';
$where_clause = "";

if ($filter == 'hari') {
    $where_clause = " WHERE p.tanggal = CURDATE()";
} elseif ($filter == 'minggu') {
    $where_clause = " WHERE YEARWEEK(p.tanggal, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($filter == 'bulan') {
    $where_clause = " WHERE MONTH(p.tanggal) = MONTH(CURDATE()) AND YEAR(p.tanggal) = YEAR(CURDATE())";
}

// Filter Tanggal Manual jika ada
if (isset($_GET['tgl']) && !empty($_GET['tgl'])) {
    $tgl = mysqli_real_escape_string($koneksi, $_GET['tgl']);
    $where_clause = " WHERE p.tanggal = '$tgl'";
}

// 5. PANGGIL HEADER & SIDEBAR
include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper pt-4 px-3">
    <div class="content-header no-print">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <h2 class="m-0 font-weight-bold text-dark"><i class="fas fa-clipboard-list text-teal mr-2"></i>Rekap Presensi Guru</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <button onclick="window.print()" class="btn btn-primary shadow-sm px-4">
                        <i class="fas fa-print mr-1"></i> Cetak Laporan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            <div class="row mb-4 no-print">
                <div class="col-12">
                    <div class="btn-group shadow-sm">
                        <a href="?filter=semua" class="btn btn-white <?= $filter == 'semua' ? 'active bg-teal text-white' : '' ?>">Semua Data</a>
                        <a href="?filter=hari" class="btn btn-white <?= $filter == 'hari' ? 'active bg-teal text-white' : '' ?>">Hari Ini</a>
                        <a href="?filter=minggu" class="btn btn-white <?= $filter == 'minggu' ? 'active bg-teal text-white' : '' ?>">Minggu Ini</a>
                        <a href="?filter=bulan" class="btn btn-white <?= $filter == 'bulan' ? 'active bg-teal text-white' : '' ?>">Bulan Ini</a>
                    </div>
                </div>
            </div>

            <div class="card card-teal card-outline shadow-sm">
                <div class="card-header border-transparent d-flex justify-content-between align-items-center no-print">
                    <h3 class="card-title font-weight-bold">
                        Daftar Kehadiran: <span class="text-capitalize text-teal"><?= $filter ?></span>
                    </h3>
                    
                    <form method="GET" class="ml-auto d-flex align-items-center">
                        <input type="date" name="tgl" class="form-control form-control-sm" value="<?= $_GET['tgl'] ?? '' ?>" style="width: 160px; border-radius: 8px;">
                        <button type="submit" class="btn btn-teal btn-sm ml-2 px-3 text-white" style="background:#008080;">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <div class="print-only text-center mb-4" style="display:none;">
                    <h3>LAPORAN KEHADIRAN GURU - SDN PANDEAN 1</h3>
                    <p>Periode: <?= strtoupper($filter) ?> (Dicetak pada: <?= date('d/m/Y H:i') ?>)</p>
                    <hr>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 table-hover table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50" class="text-center">NO</th>
                                    <th>TANGGAL</th>
                                    <th>NIP</th>
                                    <th>NAMA LENGKAP</th>
                                    <th>JAM MASUK</th>
                                    <th class="text-center">STATUS</th>
                                    <th width="80" class="text-center no-print">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query_str = "SELECT p.*, g.nama_lengkap 
                                              FROM presensi p 
                                              LEFT JOIN guru g ON p.nip = g.nip 
                                              $where_clause
                                              ORDER BY p.tanggal DESC, p.jam_masuk DESC";
                                
                                $sql = mysqli_query($koneksi, $query_str);

                                if ($sql && mysqli_num_rows($sql) > 0) {
                                    while($data = mysqli_fetch_assoc($sql)):
                                        $status = $data['status'];
                                        $badge_class = ($status == 'Hadir') ? 'badge-success' : 'badge-warning';
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><strong><?= date('d/m/Y', strtotime($data['tanggal'])); ?></strong></td>
                                    <td><code><?= $data['nip']; ?></code></td>
                                    <td><?= htmlspecialchars($data['nama_lengkap'] ?? 'Data Guru Terhapus'); ?></td>
                                    <td><i class="far fa-clock text-teal mr-1"></i><?= $data['jam_masuk']; ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= $badge_class; ?> px-2 py-1"><?= $status; ?></span>
                                    </td>
                                    <td class="text-center no-print">
                                        <a href="?hapus=<?= $data['id']; ?>" class="text-danger" onclick="return confirm('Hapus data ini?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; } else { ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        Data tidak ditemukan untuk periode ini.
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .bg-teal { background-color: #008080 !important; }
    .text-teal { color: #008080 !important; }
    .btn-white { background: #fff; border: 1px solid #dee2e6; color: #666; }
    
    @media print {
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        .content-wrapper { margin-left: 0 !important; padding-top: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table { width: 100% !important; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000 !important; padding: 8px !important; }
        .badge { border: 1px solid #000 !important; color: #000 !important; background: transparent !important; }
    }
</style>

<?php include "includes/footer.php"; ?>