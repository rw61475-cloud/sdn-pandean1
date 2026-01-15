<?php
include '../includes/header_guru.php';
include '../includes/db.php';

// Pastikan session sudah dimulai di header, jika tidak tambahkan session_start()
$id_user = $_SESSION['id_user'] ?? 0;

// 1. Perbaikan Query: Menggunakan id_guru sesuai struktur database Anda
$stmt = $koneksi->prepare("SELECT * FROM guru WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$guru = $result->fetch_assoc();
$stmt->close();

// 2. Jika belum ada data guru, siapkan default agar tidak error saat memanggil array
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

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header fw-bold text-white" style="background-color:#0f766e; padding: 15px;">
                <i class="fas fa-user-edit me-2"></i> Edit Biodata Guru
            </div>

            <div class="card-body p-4">
                <form action="proses_biodata.php?redirect=profil" method="POST" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            <label class="form-label d-block fw-bold">Foto Profil</label>
                            <?php 
                            // Cek apakah file foto ada, jika tidak gunakan default
                            $path_foto = "foto/" . ($guru['foto'] ?: 'default.png');
                            if (!file_exists($path_foto)) { $path_foto = "foto/default.png"; }
                            ?>
                            <img src="<?= $path_foto; ?>"
                                 id="imgPreview"
                                 class="img-thumbnail mb-3 shadow-sm"
                                 style="width:150px; height:150px; object-fit:cover; border-radius: 10px;">

                            <input type="file" name="foto" id="inputFoto" class="form-control form-control-sm">
                            <small class="text-muted d-block mt-2">Format: JPG/PNG, Maks 2MB</small>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-bold">NIP</label>
                                <input type="text"
                                       class="form-control bg-light"
                                       value="<?= htmlspecialchars($guru['nip']); ?>"
                                       readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text"
                                       name="nama_lengkap"
                                       class="form-control"
                                       placeholder="Nama Lengkap & Gelar"
                                       value="<?= htmlspecialchars($guru['nama_lengkap']); ?>"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mata Pelajaran</label>
                                <input type="text"
                                       name="mata_pelajaran"
                                       class="form-control"
                                       placeholder="Contoh: Guru Kelas (Wali Kelas IV)"
                                       value="<?= htmlspecialchars($guru['mata_pelajaran'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">No HP / WhatsApp</label>
                                <input type="text"
                                       name="no_hp"
                                       class="form-control"
                                       placeholder="0812xxxx"
                                       value="<?= htmlspecialchars($guru['no_hp']); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <textarea name="alamat"
                                          class="form-control"
                                          placeholder="Alamat Lengkap Domisili"
                                          rows="3"><?= htmlspecialchars($guru['alamat']); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4 border-top pt-3">
                        <a href="index.php" class="btn btn-light me-2">Batal</a>
                        <button type="submit" class="btn text-white px-4" style="background-color:#0f766e; border-radius: 8px;">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
    // Preview gambar saat memilih file
    const inputFoto = document.getElementById('inputFoto');
    const imgPreview = document.getElementById('imgPreview');
    
    inputFoto.onchange = evt => {
        const [file] = inputFoto.files
        if (file) {
            imgPreview.src = URL.createObjectURL(file)
        }
    }
</script>

<?php include '../includes/footer.php'; ?>