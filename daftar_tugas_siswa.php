<?php 
include '../includes/header_siswa.php'; 
include '../includes/db.php';

if (!isset($_GET['id_jadwal'])) {
    echo "<script>location.href='jadwal_siswa.php';</script>";
    exit;
}

$id_jadwal = $_GET['id_jadwal'];
$id_siswa = $_SESSION['id_user'];

// Ambil info mata pelajaran
$info_query = mysqli_query($koneksi, "SELECT * FROM jadwal WHERE id_jadwal = '$id_jadwal'");
$info = mysqli_fetch_assoc($info_query);

// Ambil semua tugas untuk jadwal ini
$tugas_query = mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_jadwal = '$id_jadwal' ORDER BY deadline ASC");
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-teal mb-0"><?= $info['mapel']; ?></h3>
            <p class="text-muted">Daftar tugas untuk kelas <?= $info['kelas']; ?></p>
        </div>
        <a href="jadwal_siswa.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Kembali</a>
    </div>

    <?php if (mysqli_num_rows($tugas_query) > 0) : ?>
        <?php while ($t = mysqli_fetch_assoc($tugas_query)) : 
            $id_tugas = $t['id_tugas'];
            $deadline = $t['deadline'];
            $sekarang = date('Y-m-d H:i:s');
            $is_expired = strtotime($sekarang) > strtotime($deadline);

            // Cek apakah siswa sudah mengumpulkan tugas ini
            $cek_kumpul = mysqli_query($koneksi, "SELECT * FROM tugas_siswa WHERE id_tugas = '$id_tugas' AND id_user = '$id_siswa'");
            $sudah_kumpul = mysqli_num_rows($cek_kumpul) > 0;
        ?>
        <div class="card shadow-sm border-0 rounded-4 mb-3 overflow-hidden">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-2"><?= nl2br($t['deskripsi_tugas']); ?></h5>
                        <div class="d-flex gap-3">
                            <span class="small text-muted">
                                <i class="bi bi-calendar-event me-1"></i> Batas: <b><?= date('d M Y, H:i', strtotime($deadline)); ?></b>
                            </span>
                            <?php if($t['file_tugas']): ?>
                            <a href="../dashboard_guru/uploads_tugas/<?= $t['file_tugas']; ?>" target="_blank" class="small text-decoration-none text-primary fw-bold">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Unduh Materi
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <?php if ($sudah_kumpul) : ?>
                            <button class="btn btn-success btn-sm rounded-pill px-4 disabled">
                                <i class="bi bi-check-circle me-1"></i> Sudah Dikirim
                            </button>
                        <?php elseif ($is_expired) : ?>
                            <button class="btn btn-secondary btn-sm rounded-pill px-4 disabled">Deadline Berakhir</button>
                        <?php else : ?>
                            <button class="btn btn-teal btn-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#uploadModal<?= $id_tugas; ?>">
                                <i class="bi bi-upload me-1"></i> Kirim Tugas
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if($is_expired && !$sudah_kumpul): ?>
                <div class="bg-danger py-1 text-center text-white small" style="font-size: 10px; opacity: 0.8;">PENGUMPULAN DITUTUP</div>
            <?php endif; ?>
        </div>

        <div class="modal fade" id="uploadModal<?= $id_tugas; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="proses_upload_tugas.php" method="POST" enctype="multipart/form-data" class="modal-content" style="border-radius: 20px;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Kirim Jawaban</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_tugas" value="<?= $id_tugas; ?>">
                        <input type="hidden" name="id_jadwal" value="<?= $id_jadwal; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Pilih File (Gambar/PDF/Doc)</label>
                            <input type="file" name="file_jawaban" class="form-control" required>
                        </div>
                        <div class="alert alert-info py-2" style="font-size: 11px;">
                            <i class="bi bi-info-circle me-1"></i> Pastikan tugas sudah benar sebelum dikirim.
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" name="submit_tugas" class="btn btn-teal w-100 rounded-pill fw-bold">Unggah Tugas Sekarang</button>
                    </div>
                </form>
            </div>
        </div>

        <?php endwhile; ?>
    <?php else : ?>
        <div class="text-center py-5">
            <p class="text-muted mt-3">Belum ada tugas untuk mata pelajaran ini.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>