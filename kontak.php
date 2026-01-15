<?php 
include '../includes/header.php'; 
include '../includes/db.php'; 

// 1. AMBIL DATA KONTAK DARI DATABASE
$query = mysqli_query($koneksi, "SELECT * FROM kontak_setting WHERE id = 1");
$d = mysqli_fetch_assoc($query);

// Fallback & Proteksi: Pastikan variabel tersedia meskipun data di DB kosong
$wa_db         = $d['whatsapp'] ?? '08812729632';
$email_db      = $d['email'] ?? 'info@sdnpandean1.sch.id';
$alamat_db     = $d['alamat'] ?? 'Pandean, Kec. Ngablak, Kab. Magelang, Jawa Tengah 56194';
$maps_db       = $d['maps_iframe'] ?? '';
$judul_hero    = $d['judul_hero'] ?? 'Kontak Resmi'; // Data dari Admin
$subjudul_hero = $d['subjudul_hero'] ?? 'SD Negeri Pandean 1 - Layanan Informasi & Pengaduan Terpadu'; // Data dari Admin

// Bersihkan nomor WA untuk link API (Hanya angka)
$no_wa_api = preg_replace('/[^0-9]/', '', $wa_db);
if (substr($no_wa_api, 0, 1) === '0') {
    $no_wa_api = '62' . substr($no_wa_api, 1);
}

// 2. LOGIKA PENANGANAN FORM (KIRIM KE WHATSAPP)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama   = htmlspecialchars(strip_tags(trim($_POST['nama'] ?? '')));
    $email  = htmlspecialchars(strip_tags(trim($_POST['email'] ?? '')));
    $subjek = htmlspecialchars(strip_tags(trim($_POST['subjek_layanan'] ?? 'Umum')));
    $pesan  = htmlspecialchars(strip_tags(trim($_POST['pesan'] ?? '')));
    
    $text_wa = "Halo Admin SDN Pandean 1,%0A%0A";
    $text_wa .= "*PESAN BARU DARI WEBSITE*%0A";
    $text_wa .= "----------------------------------%0A";
    $text_wa .= "*Nama:* " . urlencode($nama) . "%0A";
    $text_wa .= "*Email:* " . urlencode($email) . "%0A";
    $text_wa .= "*Keperluan:* " . urlencode($subjek) . "%0A";
    $text_wa .= "*Pesan:* " . urlencode($pesan) . "%0A";
    $text_wa .= "----------------------------------";

    echo "<script>window.location.href='https://api.whatsapp.com/send?phone=$no_wa_api&text=$text_wa';</script>";
    exit;
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --primary-color: #0f766e; 
    --secondary-color: #1e293b; 
    --accent-color: #fbbf24; 
    --bg-light: #f8fafc;
    --border-color: #e2e8f0;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--bg-light);
    color: var(--secondary-color);
}

/* HERO SECTION - Menggunakan data dari Admin */
.hero-section {
    background: linear-gradient(rgba(15, 118, 110, 0.95), rgba(15, 118, 110, 0.95)), 
                url('https://images.unsplash.com/photo-1523050853063-8802a6356263?auto=format&fit=crop&w=1500&q=80') center/cover;
    padding: 100px 0;
    color: #fff;
    text-align: center;
    border-bottom: 5px solid var(--accent-color);
}

.hero-section h1 {
    font-weight: 800;
    letter-spacing: -1px;
    margin-bottom: 15px;
    text-transform: capitalize;
}

.contact-container {
    max-width: 1100px;
    margin: -60px auto 80px;
    padding: 0 20px;
}

.main-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-wrap: wrap;
    overflow: hidden;
    border: 1px solid var(--border-color);
}

.info-column {
    flex: 1;
    min-width: 350px;
    background-color: #f1f5f9;
    padding: 50px;
    border-right: 1px solid var(--border-color);
}

.info-column h3 {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 30px;
    font-size: 1.5rem;
}

.contact-detail {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 25px;
}

.icon-square {
    width: 45px;
    height: 45px;
    background-color: var(--primary-color);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.contact-text h6 {
    margin: 0;
    font-weight: 700;
    color: var(--secondary-color);
}

.contact-text p {
    margin: 0;
    font-size: 0.9rem;
    color: #64748b;
    word-break: break-all;
}

.map-box {
    margin-top: 30px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--border-color);
    height: 200px;
}

.form-column {
    flex: 1.5;
    min-width: 400px;
    padding: 50px;
}

.form-column h3 { font-weight: 700; margin-bottom: 10px; }
.form-column p { color: #64748b; margin-bottom: 35px; }

.form-label {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--secondary-color);
    margin-bottom: 8px;
    display: block;
    text-transform: uppercase;
}

.custom-input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    background-color: #fff;
    transition: all 0.2s;
    margin-bottom: 20px;
}

.custom-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
}

.btn-whatsapp-formal {
    background-color: #25D366;
    color: #fff;
    border: none;
    padding: 15px 30px;
    border-radius: 8px;
    font-weight: 700;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: background 0.2s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
}

.btn-whatsapp-formal:hover { background-color: #128C7E; }

@media (max-width: 768px) {
    .info-column, .form-column { padding: 30px; }
}
</style>

<section class="hero-section">
    <div class="container">
        <h1><?= htmlspecialchars($judul_hero) ?></h1>
        <p class="lead"><?= htmlspecialchars($subjudul_hero) ?></p>
    </div>
</section>

<div class="contact-container">
    <div class="main-card">
        
        <div class="info-column">
            <h3>Hubungi Kami</h3>
            
            <div class="contact-detail">
                <div class="icon-square"><i class="fab fa-whatsapp"></i></div>
                <div class="contact-text">
                    <h6>WhatsApp Admin</h6>
                    <p><?= htmlspecialchars($wa_db) ?></p>
                </div>
            </div>

            <div class="contact-detail">
                <div class="icon-square"><i class="fas fa-envelope"></i></div>
                <div class="contact-text">
                    <h6>Email Resmi</h6>
                    <p><?= htmlspecialchars($email_db) ?></p>
                </div>
            </div>

            <div class="contact-detail">
                <div class="icon-square"><i class="fas fa-map-marker-alt"></i></div>
                <div class="contact-text">
                    <h6>Alamat Sekolah</h6>
                    <p><?= htmlspecialchars($alamat_db) ?></p>
                </div>
            </div>

            <div class="map-box">
                <?php if(!empty($maps_db)): ?>
                <iframe 
                    src="<?= $maps_db ?>" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center h-100 text-muted">Peta belum diatur</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-column">
            <h3><?= htmlspecialchars($judul_hero) ?></h3>
            <p>Silakan lengkapi formulir di bawah ini untuk memulai percakapan WhatsApp otomatis ke nomor <?= htmlspecialchars($wa_db) ?>.</p>

            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="custom-input" placeholder="Masukkan nama Anda" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="custom-input" placeholder="contoh@email.com" required>
                    </div>
                </div>

                <label class="form-label">Keperluan / Subjek</label>
                <select name="subjek_layanan" class="custom-input" required>
                    <option value="" disabled selected>Pilih Keperluan</option>
                    <option value="Pendaftaran Siswa Baru">Pendaftaran Siswa Baru (PPDB)</option>
                    <option value="Layanan Akademik">Layanan Akademik & Data Siswa</option>
                    <option value="Kritik & Saran">Kritik & Saran</option>
                    <option value="Lainnya">Lainnya</option>
                </select>

                <label class="form-label">Isi Pesan</label>
                <textarea name="pesan" class="custom-input" rows="5" placeholder="Tuliskan pesan atau pertanyaan Anda secara mendetail..." required></textarea>

                <button type="submit" class="btn-whatsapp-formal">
                    <i class="fab fa-whatsapp fa-lg"></i> KIRIM SEKARANG
                </button>
            </form>
        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>