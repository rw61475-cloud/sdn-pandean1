<?php 
session_start(); 
include '../includes/db.php'; 

// Proteksi Halaman & Ambil Session
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Gunakan identitas (NISN) untuk filter data
$siswa_nisn = $_SESSION['identitas'] ?? ''; 
include '../includes/header_siswa.php'; 

// Query sesuai Struktur Tabel di Gambar: id_presensi, nisn, id_guru, kelas, tanggal, status, jam_absen
$sql = "SELECT tanggal, status, jam_absen, kelas 
        FROM presensi_siswa 
        WHERE nisn = '$siswa_nisn' 
        ORDER BY tanggal DESC, id_presensi DESC";

$res = mysqli_query($koneksi, $sql);
?>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: #0d9488; letter-spacing: -1px;">Riwayat Kehadiran</h3>
            <p class="text-muted small mb-0">Laporan presensi untuk NISN: <span class="fw-bold text-dark"><?= $siswa_nisn ?></span></p>
        </div>
        <div class="d-none d-md-block">
            <button class="btn btn-outline-secondary btn-sm rounded-3" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Cetak Laporan
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                        <th class="ps-4 py-4 text-secondary small fw-bold" style="letter-spacing: 1px;">TANGGAL & HARI</th>
                        <th class="py-4 text-secondary small fw-bold text-center" style="letter-spacing: 1px;">UNIT KELAS</th>
                        <th class="py-4 text-secondary small fw-bold text-center" style="letter-spacing: 1px;">LOG WAKTU</th>
                        <th class="py-4 text-secondary small fw-bold text-center" style="letter-spacing: 1px;">STATUS ENTRY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)): 
                            $status = strtolower($row['status']);
                            
                            // Styling dinamis berdasarkan status
                            if($status == 'hadir') {
                                $color = '#059669'; $bg = '#ecfdf5'; $dot = '#10b981';
                            } elseif($status == 'alfa') {
                                $color = '#dc2626'; $bg = '#fef2f2'; $dot = '#ef4444';
                            } else {
                                $color = '#d97706'; $bg = '#fffbeb'; $dot = '#f59e0b';
                            }
                    ?>
                    <tr style="transition: all 0.2s;">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="date-box me-3 text-center shadow-sm">
                                    <span class="d-block fw-bold text-dark" style="font-size: 16px; line-height: 1;"><?= date('d', strtotime($row['tanggal'])) ?></span>
                                    <span class="text-uppercase text-muted" style="font-size: 9px; font-weight: 700;"><?= date('M', strtotime($row['tanggal'])) ?></span>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 14px;"><?= date('l', strtotime($row['tanggal'])) ?></div>
                                    <div class="text-muted" style="font-size: 11px;"><?= date('Y', strtotime($row['tanggal'])) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-2" style="background: #f1f5f9; color: #475569; font-weight: 600; font-size: 12px; border: 1px solid #e2e8f0;">
                                <?= $row['kelas'] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><i class="far fa-clock me-1 text-muted"></i> <?= $row['jam_absen'] ?></span>
                                <span class="text-muted" style="font-size: 10px; text-transform: uppercase;">WIB</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="status-pill d-inline-flex align-items-center px-3 py-1" style="background: <?= $bg ?>; color: <?= $color ?>;">
                                <span class="status-dot" style="background: <?= $dot ?>;"></span>
                                <?= strtoupper($status) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; } else { ?>
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-5">
                                <i class="fas fa-clipboard-list fa-3x mb-3" style="color: #e2e8f0;"></i>
                                <h5 class="text-secondary fw-bold">Tidak ada data presensi</h5>
                                <p class="text-muted small">Data kehadiran Anda untuk NISN <?= $siswa_nisn ?> belum terekam.</p>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; }
    .date-box {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 6px 10px;
        min-width: 48px;
    }
    .status-pill {
        border-radius: 30px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }
    .status-dot {
        height: 6px;
        width: 6px;
        border-radius: 50%;
        margin-right: 8px;
        display: inline-block;
    }
    .table tbody tr:hover {
        background-color: #f1f5f9 !important;
        transform: scale(1.002);
    }
    /* Sembunyikan cetak saat di layar */
    @media print {
        .btn, header, footer { display: none !important; }
        .card { shadow: none !important; border: 1px solid #000; }
    }
</style>

<?php include '../includes/footer.php'; ?>