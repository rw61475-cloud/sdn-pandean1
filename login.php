<?php include '../includes/header.php'; ?>

<style>
    /* Konsisten dengan warna Teal Anda */
    :root {
        --teal-utama: #0f766e;
        --teal-gelap: #0d5a54;
    }

    /* Reset Body agar tidak berantakan */
    body {
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Container utama agar box login berada di tengah */
    .main-login-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-wrapper {
        display: flex;
        max-width: 900px;
        width: 100%;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    /* Kotak Kiri (Teal) */
    .login-side-info {
        flex: 1;
        background-color: var(--teal-utama);
        color: white;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    /* Kotak Kanan (Form) */
    .login-side-form {
        flex: 1.2;
        padding: 50px;
        background: white;
    }

    .form-control {
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-bottom: 15px;
    }

    .btn-masuk {
        background-color: var(--teal-utama);
        color: white;
        padding: 12px;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        width: 100%;
        cursor: pointer;
    }

    /* MEMPERBAIKI TOMBOL KEMBALI */
    .back-to-home {
        display: inline-block;
        margin-top: 30px;
        text-decoration: none;
        color: #666;
        font-size: 14px;
        font-weight: 500;
        transition: 0.3s;
        /* Pastikan area klik bersih */
        position: relative;
        z-index: 100;
    }

    .back-to-home:hover {
        color: var(--teal-utama);
    }

    /* Responsif untuk HP */
    @media (max-width: 768px) {
        .login-wrapper { flex-direction: column; }
        .login-side-info { padding: 30px; }
    }
</style>

<div class="main-login-container">
    <div class="login-wrapper">
        
        <div class="login-side-info">
            <i class="bi bi-mortarboard-fill" style="font-size: 70px; margin-bottom: 20px;"></i>
            <h2 class="fw-bold">SDN PANDEAN 1</h2>
            <p style="opacity: 0.8;">Sistem Informasi Akademik Siswa dan Guru Terpadu</p>
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2); width: 80%;">
                <small>Tahun Pelajaran 2025/2026</small>
            </div>
        </div>

        <div class="login-side-form text-start">
            <h4 class="fw-bold mb-1" style="color: var(--teal-utama);">Selamat Datang</h4>
            <p class="text-muted small mb-4">Silakan login untuk mengakses akun Anda</p>

            <form action="proses_login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold">NIP / NISN</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan ID Anda" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn-masuk">
                    MASUK SEKARANG
                </button>
            </form>

            <div class="text-center">
                <a href="../dashboard_publik/index.php" class="back-to-home">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>