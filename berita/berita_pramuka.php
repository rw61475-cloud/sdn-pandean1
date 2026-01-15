<?php 
// 1. Tentukan path ke folder includes
$base_path = $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/';
include $base_path . 'header.php';
?>

<script>
    // Agar link di navbar berfungsi dari dalam subfolder
    window.onload = function() {
        const navLinks = document.querySelectorAll('.nav-link, .navbar-brand');
        navLinks.forEach(link => {
            let currentHref = link.getAttribute('href');
            if (currentHref && !currentHref.startsWith('http') && !currentHref.startsWith('/') && !currentHref.startsWith('#')) {
                link.href = '../' + currentHref;
            }
        });
    }
</script>

<section class="py-5">
    <div class="container">
        <div class="box-berita shadow-sm">
            <h2 class="judul-berita mb-2">
                Kegiatan Pramuka Rutin SD Negeri Pandean 1
            </h2>
            <p class="text-muted mb-4">
                📅 18 September 2025 • Oleh Admin Sekolah
            </p>

            <div class="row align-items-start">
                <div class="col-md-4 mb-3">
                    <img src="../../assets/img/pramuka.jpg"
                         class="img-fluid rounded shadow"
                         style="max-width:100%;"
                         alt="Kegiatan Pramuka">
                </div>

                <div class="col-md-8">
                    <p>Sekolah Dasar Pandean 1 secara rutin melaksanakan kegiatan ekstrakurikuler Pramuka sebagai sarana pembentukan karakter peserta didik.</p>
                    <p>Melalui kegiatan Pramuka, siswa dilatih untuk bersikap disiplin, mandiri, bertanggung jawab, serta mampu bekerja sama dalam kelompok.</p>
                    <p>Pembina Pramuka menyampaikan bahwa kegiatan ini bertujuan untuk menanamkan nilai-nilai kedisiplinan serta cinta tanah air sejak dini.</p>
                </div>
            </div>

            <a href="berita.php" class="btn mt-3 text-white" style="background-color:#0f766e;">
                Kembali ke Berita
            </a>
        </div>
    </div>
</section>

<?php 
// 2. Panggil Footer
include $base_path . 'footer.php'; 
?>