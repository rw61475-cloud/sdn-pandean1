<?php
include '../includes/header_guru.php';
include '../includes/db.php';

$id_user = $_SESSION['id_user'] ?? 0;

// PERBAIKAN: Mengganti id_user menjadi id_guru sesuai kolom di database Anda
$stmt = $koneksi->prepare("SELECT * FROM guru WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$guru = $result->fetch_assoc();
$stmt->close();

if (!$guru) {
    $guru = [
        'nip'            => $_SESSION['identitas'] ?? '',
        'nama_lengkap'   => '',
        'mata_pelajaran' => '',
        'alamat'         => '',
        'no_hp'          => '',
        'foto'           => 'default.png'
    ];
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
:root { 
    --primary-color: #0f766e; 
    --hover-color: #0d645d; 
    --bg-light: #f1f5f9;
}
body { background-color: var(--bg-light); }
.settings-sidebar .list-group-item {
    border: none;
    padding: 14px 20px;
    transition: all 0.3s ease;
    margin-bottom: 4px;
    font-weight: 500;
    color: #64748b;
}
.settings-sidebar .list-group-item.active {
    background-color: var(--primary-color) !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
}
.settings-sidebar .list-group-item:hover:not(.active) {
    background-color: #f0fdfa;
    color: var(--primary-color);
    transform: translateX(8px);
}
.profile-img-container { position: relative; display: inline-block; }
.profile-img-container img {
    width: 160px; height: 160px; object-fit: cover;
    border: 5px solid white;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
}
.card { border: none; border-radius: 16px; overflow: hidden; }
.form-label { color: #475569; }
.form-control { padding: 10px 15px; border-radius: 8px; border: 1px solid #e2e8f0; }
.form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(15,118,110,0.1); }

.btn-save {
    background-color: var(--primary-color);
    border: none; padding: 12px 30px;
    border-radius: 10px; font-weight: 600;
    transition: 0.3s cubic-bezier(0.4,0,0.2,1);
}
.btn-save:hover { 
    background-color: var(--hover-color);
    transform: translateY(-3px);
    box-shadow: 0 10px 15px -3px rgba(15,118,110,0.3);
}
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card shadow-sm settings-sidebar">
                <div class="card-body p-3">
                    <div class="text-center mb-4 mt-2">
                        <h6 class="fw-bold text-uppercase small text-muted">Pengaturan Akun</h6>
                    </div>
                    <div class="list-group list-group-flush" id="settingsTabs">
                        <a href="#profil" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                            <i class="fa-solid fa-circle-user me-3"></i>Profil & Akun
                        </a>
                        <a href="#password" class="list-group-item list-group-item-action" data-bs-toggle="list">
                            <i class="fa-solid fa-shield-halved me-3"></i>Ubah Password
                        </a>
                        <a href="#notifikasi" class="list-group-item list-group-item-action" data-bs-toggle="list">
                            <i class="fa-solid fa-bell me-3"></i>Notifikasi
                        </a>
                        <a href="#keamanan" class="list-group-item list-group-item-action" data-bs-toggle="list">
                            <i class="fa-solid fa-fingerprint me-3"></i>Keamanan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="tab-content shadow-sm rounded-4">

                <div class="tab-pane fade show active" id="profil">
                    <div class="card">
                        <div class="card-header fw-bold text-white py-4 px-4" style="background-color: var(--primary-color);">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fa-solid fa-user-edit me-2"></i> Detail Informasi Guru</span>
                                <span class="badge bg-white text-dark small fw-normal">ID: #<?= $id_user ?></span>
                            </div>
                        </div>
                        <div class="card-body p-4 p-lg-5">
                            <form action="proses_pengaturan.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-4 text-center mb-4">
                                        <div class="profile-img-container mb-3">
                                            <img src="foto/<?= htmlspecialchars($guru['foto']); ?>" class="rounded-circle" id="previewFoto">
                                        </div>
                                        <div class="px-3">
                                            <label for="fotoInput" class="btn btn-outline-secondary btn-sm w-100 py-2">
                                                <i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload Foto Baru
                                            </label>
                                            <input type="file" name="foto" id="fotoInput" class="d-none" onchange="previewImage(this)">
                                            <p class="text-muted small mt-3">Gunakan foto formal (JPG/PNG).</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-uppercase">NIP / Identitas</label>
                                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($guru['nip']); ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-uppercase">Nama Lengkap</label>
                                                <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($guru['nama_lengkap']); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-uppercase">Mata Pelajaran</label>
                                                <input type="text" name="mata_pelajaran" class="form-control" value="<?= htmlspecialchars($guru['mata_pelajaran']); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-uppercase">No. WhatsApp</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-brands fa-whatsapp"></i></span>
                                                    <input type="text" name="no_hp" class="form-control border-start-0" value="<?= htmlspecialchars($guru['no_hp']); ?>">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-bold small text-uppercase">Alamat Rumah</label>
                                                <textarea name="alamat" class="form-control" rows="4"><?= htmlspecialchars($guru['alamat']); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-5">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-save text-white px-5">
                                        <i class="fa-solid fa-check-circle me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="password">
                    <div class="card h-100">
                        <div class="card-header fw-bold text-white py-4 px-4" style="background-color: var(--primary-color);">
                            <i class="fa-solid fa-lock me-2"></i>Ubah Password
                        </div>
                        <div class="card-body p-4 p-lg-5">
                            <form action="proses_password.php" method="POST" style="max-width: 550px;">
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase">Password Saat Ini</label>
                                    <input type="password" name="password_lama" class="form-control" placeholder="Masukkan password lama" required>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase">Password Baru</label>
                                        <input type="password" name="password_baru" class="form-control" placeholder="Min. 8 Karakter" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase">Konfirmasi Baru</label>
                                        <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password" required>
                                    </div>
                                </div>
                                <div class="mt-5">
                                    <button type="submit" class="btn btn-save text-white">
                                        <i class="fa-solid fa-key me-2"></i>Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="notifikasi">
                    <div class="card h-100">
                        <div class="card-header fw-bold text-white py-4 px-4" style="background-color: var(--primary-color);">
                            <i class="fa-solid fa-bell me-2"></i>Pusat Notifikasi
                        </div>
                        <div class="card-body p-4 p-lg-5">
                            <div class="text-center py-5">
                                <i class="fa-solid fa-bell-slash fa-3x text-light mb-3"></i>
                                <p class="text-muted">Belum ada notifikasi baru untuk Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="keamanan">
                    <div class="card h-100">
                        <div class="card-header fw-bold text-white py-4 px-4" style="background-color: var(--primary-color);">
                            <i class="fa-solid fa-shield-halved me-2"></i>Keamanan Akun
                        </div>
                        <div class="card-body p-4 p-lg-5 text-center">
                            <div class="mb-4">
                                <i class="fa-solid fa-user-shield fa-4x text-muted mb-3"></i>
                                <h4>Proteksi Akun</h4>
                                <p class="text-muted">Klik tombol di bawah untuk mengakhiri semua sesi login yang sedang aktif.</p>
                            </div>
                            <div class="mt-5">
                                <a href="keluar_semua_perangkat.php" class="btn btn-danger btn-lg px-5 shadow-sm">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>Keluar dari Seluruh Perangkat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('previewFoto');
            preview.style.opacity = '0';
            setTimeout(() => {
                preview.src = e.target.result;
                preview.style.opacity = '1';
            }, 300);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../includes/footer.php'; ?>