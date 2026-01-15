<?php
include '../includes/header_guru.php';
include '../includes/db.php';

$id_user = $_SESSION['id_user'] ?? 0;

/* Ambil data guru dari database */
// PERBAIKAN: Mengganti id_user menjadi id_guru sesuai kolom di database
$stmt = $koneksi->prepare("SELECT * FROM guru WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$guru = $result->fetch_assoc();
$stmt->close();

/* Jika belum ada data guru, siapkan default agar tidak error */
if (!$guru) {
    $guru = [
        'nip'            => $_SESSION['identitas'] ?? '-',
        'nama_lengkap'   => 'Belum diisi',
        'mata_pelajaran' => 'Belum diisi', // Tambahan field
        'alamat'         => 'Belum diisi',
        'no_hp'          => 'Belum diisi',
        'foto'           => 'default.png'
    ];
}
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-8">

        <div class="card shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header fw-bold text-white" style="background-color:#0f766e; padding: 15px;">
                <i class="fas fa-id-card me-2"></i> Profil Lengkap Guru
            </div>

            <div class="card-body p-4">
                <div class="row">

                    <div class="col-md-4 text-center border-end">
                        <img src="foto/<?= htmlspecialchars($guru['foto']); ?>"
                             class="img-thumbnail mb-3 shadow-sm"
                             style="width:180px; height:180px; object-fit:cover; border-radius: 15px;">
                        
                        <div class="mt-2">
                            <span class="badge bg-success">Status: Aktif</span>
                        </div>
                    </div>

                    <div class="col-md-8 px-4">

                        <div class="mb-3 border-bottom pb-2">
                            <label class="text-muted small d-block">NIP / Identitas</label>
                            <p class="fw-bold mb-0 text-dark"><?= htmlspecialchars($guru['nip']); ?></p>
                        </div>

                        <div class="mb-3 border-bottom pb-2">
                            <label class="text-muted small d-block">Nama Lengkap</label>
                            <p class="fw-bold mb-0 text-dark"><?= htmlspecialchars($guru['nama_lengkap']); ?></p>
                        </div>

                        <div class="mb-3 border-bottom pb-2">
                            <label class="text-muted small d-block">Mata Pelajaran / Tugas</label>
                            <p class="fw-bold mb-0 text-dark"><?= htmlspecialchars($guru['mata_pelajaran'] ?? '-'); ?></p>
                        </div>

                        <div class="mb-3 border-bottom pb-2">
                            <label class="text-muted small d-block">Nomor HP / WhatsApp</label>
                            <p class="fw-bold mb-0 text-dark"><?= htmlspecialchars($guru['no_hp']); ?></p>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted small d-block">Alamat Lengkap</label>
                            <p class="fw-bold mb-0 text-dark"><?= htmlspecialchars($guru['alamat']); ?></p>
                        </div>

                        <div class="text-end mt-4">
                            <a href="index.php" class="btn btn-light border me-2" style="border-radius: 10px;">
                                <i class="fas fa-arrow-left me-1"></i> Dashboard
                            </a>
                            <a href="biodata.php" class="btn text-white px-4" style="background-color:#0f766e; border-radius: 10px;">
                                <i class="fas fa-edit me-1"></i> Edit Data
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>