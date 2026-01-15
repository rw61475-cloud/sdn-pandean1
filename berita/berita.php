<?php 
// 1. Koneksi ke Database
include "../../includes/db.php"; 

// 2. Set base URL untuk header/navbar dan footer
$base_url = '/UAS/dashboard_publik/';

// 3. Memanggil Header otomatis
include "../../includes/header.php";
?>

<!-- Section Berita -->
<section class="section-berita py-5" style="background-color: #f4f7f6;">
    <div class="container">
        <h2 class="text-center mb-5 text-teal-custom fw-bold">Berita Sekolah</h2>
        <div class="row">

            <?php
            $query = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY tanggal DESC");

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $target_id = "detail" . $row['id_berita']; 
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if(!empty($row['gambar'])): ?>
                            <img src="../../assets/img/berita/<?php echo $row['gambar']; ?>" class="card-img-top" alt="Gambar Berita">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title font-weight-bold text-teal-custom"><?php echo $row['judul']; ?></h5>
                            <p class="text-muted small"><?php echo date('d F Y', strtotime($row['tanggal'])); ?></p>
                            <p class="card-text text-secondary">
                                <?php echo substr(strip_tags($row['isi']), 0, 100); ?>...
                            </p>
                            <button type="button" class="btn btn-teal mt-auto" data-bs-toggle="modal" data-bs-target="#<?php echo $target_id; ?>">
                                Baca Selengkapnya
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Berita -->
                <div class="modal fade" id="<?php echo $target_id; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Rincian Berita</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <?php if(!empty($row['gambar'])): ?>
                                    <img src="../../assets/img/berita/<?php echo $row['gambar']; ?>" class="img-fluid rounded mb-3 w-100" style="max-height: 400px; object-fit: cover;" alt="Gambar Berita">
                                <?php endif; ?>
                                <h3 class="font-weight-bold text-teal-custom"><?php echo $row['judul']; ?></h3>
                                <p class="text-muted">
                                    <i class="fas fa-calendar me-2"></i> <?php echo date('d F Y', strtotime($row['tanggal'])); ?> | 
                                    <i class="fas fa-user me-2"></i> Penulis: <?php echo $row['penulis']; ?>
                                </p>
                                <hr>
                                <div style="line-height: 1.8; color: #333; text-align: justify;">
                                    <?php echo nl2br($row['isi']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php 
                }
            } else {
                echo "<div class='col-12 text-center'><p class='text-muted'>Belum ada berita.</p></div>";
            }
            ?>

        </div>
    </div>
</section>

<style>
    .text-teal-custom { color: #0f766e; }
    .btn-teal { background-color: #0f766e; color: white; border-radius: 8px; font-weight: bold; transition: 0.3s; }
    .btn-teal:hover { background-color: #0d635d; color: white; transform: translateY(-2px); }
    .card { border-radius: 15px; border: none; transition: 0.3s; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .card-img-top { height: 200px; object-fit: cover; }
    .modal-content { border-radius: 20px; }
    .modal-header { background-color: #0f766e; color: white; border-radius: 20px 20px 0 0; }
</style>

<?php 
// Memanggil Footer otomatis
include "../../includes/footer.php"; 
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
