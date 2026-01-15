<?php 
// 1. Memulai Session & Koneksi (Penting jika ingin mengambil data dinamis nanti)
session_start();
include '../includes/db.php'; 

// 2. Perbaikan Jalur Header
// Menggunakan '../' karena folder includes berada satu tingkat di atas folder dashboard_siswa
include '../includes/header_siswa.php'; 
?>

<style>
    .catatan-body { background-color: #f4f7f6; min-height: 100vh; }
    .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: #fff; }
    .text-teal { color: #0d6d62 !important; }
    .bg-teal { background-color: #0d6d62 !important; }
    
    /* Style Timeline untuk Feedback Guru */
    .timeline { border-left: 3px solid #0d6d62; position: relative; list-style: none; padding-left: 20px; }
    .timeline-item { margin-bottom: 25px; position: relative; }
    .timeline-item::before {
        content: ""; background: #0d6d62; width: 12px; height: 12px;
        border-radius: 50%; position: absolute; left: -27.5px; top: 5px;
    }
    .progress { height: 10px; border-radius: 5px; background-color: #e9ecef; }
</style>

<div class="container-fluid py-4 catatan-body">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-teal">Perkembangan Belajarku 🚀</h3>
            <p class="text-muted small">Halo, pantau terus prestasimu di SDN Pandean 1</p>
        </div>
        <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card card-custom p-4 text-center border-top border-4 border-success">
                <h6 class="text-muted small fw-bold text-uppercase">Rata-Rata Nilai</h6>
                <h2 class="fw-bold text-teal">88.5</h2>
                <small class="text-success"><i class="fas fa-arrow-up"></i> Naik 2%</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card card-custom p-4 text-center border-top border-4 border-primary">
                <h6 class="text-muted small fw-bold text-uppercase">Kehadiran</h6>
                <h2 class="fw-bold text-teal">100%</h2>
                <small class="text-muted">Sangat Disiplin</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card card-custom p-4 text-center border-top border-4 border-warning">
                <h6 class="text-muted small fw-bold text-uppercase">Target Capaian</h6>
                <h2 class="fw-bold text-teal">4/5</h2>
                <small class="text-muted">Materi Selesai</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold mb-4 text-teal"><i class="fas fa-comments me-2"></i>Catatan Guru</h5>
                <ul class="timeline mb-0">
                    <li class="timeline-item">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold mb-0">Bpk. Ahmad Fauzi (B. Indo)</h6>
                            <small class="text-muted">20 Des</small>
                        </div>
                        <p class="text-secondary small mt-2">"Anjani sangat aktif dalam diskusi kelompok. Kemampuan menulis narasinya sudah sangat meningkat."</p>
                    </li>
                    <li class="timeline-item">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold mb-0">Ibu Siti Nurhaliza (MTK)</h6>
                            <small class="text-muted">15 Des</small>
                        </div>
                        <p class="text-secondary small mt-2">"Sudah memahami konsep perkalian pecahan dengan baik. Perbanyak latihan soal cerita."</p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold mb-4 text-teal"><i class="fas fa-chart-line me-2"></i>Penguasaan Materi</h5>
                <div class="mb-4 small">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Matematika</span>
                        <span class="fw-bold text-teal">80%</span>
                    </div>
                    <div class="progress"><div class="progress-bar bg-teal" style="width: 80%"></div></div>
                </div>
                <div class="mb-4 small">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Bahasa Indonesia</span>
                        <span class="fw-bold text-teal">95%</span>
                    </div>
                    <div class="progress"><div class="progress-bar bg-teal" style="width: 95%"></div></div>
                </div>
                <div class="mb-4 small">
                    <div class="d-flex justify-content-between mb-1">
                        <span>IPA</span>
                        <span class="fw-bold text-warning">75%</span>
                    </div>
                    <div class="progress"><div class="progress-bar bg-warning" style="width: 75%"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Perbaikan Jalur Footer
include '../includes/footer.php'; 
?>