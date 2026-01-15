<?php
include '../includes/db.php'; // Pastikan koneksi disertakan
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengumuman Guru | SD Negeri Pandean 1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <style>
        body { background-color: #f1f5f9; }
        .judul { color: #0f766e; font-weight: 700; }
        .card-pengumuman { background-color: #ffffff; border-radius: 15px; padding: 25px; margin-bottom: 20px; border-left: 5px solid #0f766e; }
        .btn-download { background-color: #0f766e; color: white; border-radius: 8px; font-size: 0.9rem; }
        .btn-download:hover { background-color: #0d5e58; color: white; }
    </style>
</head>
<body>

<?php include '../includes/header_guru.php'; ?>

<div class="container py-5">
    <h3 class="judul mb-5 text-center">📢 Pengumuman Guru & Staf</h3>

    <div class="row justify-content-center">
        <div class="col-md-9">
            <?php
            // Ambil data dari database
            $query = mysqli_query($koneksi, "SELECT * FROM pengumuman ORDER BY tanggal_dibuat DESC");
            
            if (mysqli_num_rows($query) > 0) {
                while($data = mysqli_fetch_assoc($query)):
            ?>
                <div class="card-pengumuman shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($data['judul']); ?></h5>
                            <small class="text-muted d-block mb-3">
                                <i class="far fa-calendar-alt me-1"></i> 
                                <?= date('d F Y - H:i', strtotime($data['tanggal_dibuat'])); ?> WIB
                            </small>
                        </div>
                    </div>
                    
                    <p class="text-dark" style="white-space: pre-line;"><?= htmlspecialchars($data['isi']); ?></p>

                    <?php if (!empty($data['file_surat'])): ?>
                        <div class="mt-3 p-3 bg-light rounded d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                <div>
                                    <span class="d-block small fw-bold">Lampiran Surat Digital</span>
                                    <small class="text-muted"><?= $data['file_surat']; ?></small>
                                </div>
                            </div>
                            <a href="../assets/files/pengumuman/<?= $data['file_surat']; ?>" class="btn btn-download px-3" target="_blank">
                                <i class="fas fa-download me-1"></i> Buka / Download
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php 
                endwhile; 
            } else { 
                echo "<div class='text-center py-5 text-muted'>Belum ada pengumuman masuk.</div>";
            } 
            ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>