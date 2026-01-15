<?php include '../includes/header.php'; ?>

<!-- REGISTER SECTION -->
<div class="container d-flex justify-content-center align-items-center"
     style="min-height:80vh;">

    <div class="card shadow border-0"
         style="max-width:520px; width:100%;">

        <div class="card-body p-4">

            <!-- Judul -->
            <h4 class="text-center fw-bold mb-4"
                style="color:#0f766e;">
                Registrasi Siswa
            </h4>

            <!-- FORM REGISTER -->
            <form action="proses_register.php" method="POST">

                <div class="mb-3">
                    <label class="form-label fw-semibold">NISN</label>
                    <input type="text"
                           name="nisn"
                           class="form-control"
                           placeholder="Masukkan NISN"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text"
                           name="nama_lengkap"
                           class="form-control"
                           placeholder="Masukkan nama lengkap"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kelas</label>
                    <input type="text"
                           name="kelas"
                           class="form-control"
                           placeholder="Contoh: 5A"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat"
                              class="form-control"
                              rows="2"
                              placeholder="Alamat lengkap"
                              required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        No. Telepon Orang Tua
                    </label>
                    <input type="text"
                           name="no_hp_ortu"
                           class="form-control"
                           placeholder="08xxxxxxxxxx"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Buat password"
                           required>
                </div>

                <!-- BUTTON -->
                <button type="submit"
                        class="btn w-100 fw-bold text-white"
                        style="background-color:#0f766e;">
                    Daftar
                </button>

            </form>

            <!-- LINK LOGIN -->
            <div class="text-center mt-3">
                <small class="text-muted">
                    Sudah punya akun?
                    <a href="login.php"
                       class="fw-semibold"
                       style="color:#0f766e;">
                        Login di sini
                    </a>
                </small>
            </div>

        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
