<?php 
// 1. Memanggil Header dari folder includes yang sejajar dengan dashboard_publik
$base_path = $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/';
include $base_path . 'header.php';
?>

<style>
    /* Style khusus untuk halaman detail berita ini */
    body {
        background-color: #f8fafc;
    }
    .judul-berita {
        color: #0f766e;
        font-weight: 700;
    }
    /* Memastikan layout tetap rapi jika konten sedikit */
    html, body {
        height: 100%;
    }
    body {
        display: flex;
        flex-direction: column;
    }
</style>

<script>
    // Fixer Link Navbar agar tidak 'Not Found' karena berada di subfolder
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
    <div class="container rounded shadow p-4"
         style="max-width:900px; background-color:#e9f5f1;">

        <h2 class="judul-berita mb-2">
            Pelaksanaan Upacara Bendera Hari Senin
        </h2>

        <p class="text-muted mb-4">
            📅 4 Agustus 2025 • Oleh Admin Sekolah
        </p>

        <div class="row align-items-start">
            <div class="col-md-5 mb-4">
                <img src="../../assets/img/upacara.jpg"
                     class="img-fluid rounded shadow"
                     style="max-width: 100%;"
                     alt="Upacara bendera di SD Pandean 1">
            </div>

            <div class="col-md-7">
                <p>
                    SD Negeri 1 melaksanakan upacara bendera secara rutin
                    setiap hari Senin di halaman sekolah. Kegiatan ini diikuti oleh
                    seluruh siswa, guru, dan tenaga kependidikan dengan tertib.
                </p>

                <p>
                    Upacara bendera bertujuan untuk menanamkan nilai disiplin,
                    tanggung jawab, serta menumbuhkan rasa cinta tanah air kepada
                    peserta didik sejak usia dini.
                </p>

                <p>
                    Kepala Sekolah menyampaikan amanat mengenai pentingnya sikap
                    disiplin dalam kehidupan sehari-hari serta mengajak siswa
                    untuk terus menjaga sikap sopan santun di lingkungan sekolah.
                </p>

                <p>
                    Melalui kegiatan upacara bendera ini, diharapkan siswa dapat
                    memahami makna perjuangan para pahlawan dan menerapkan nilai-nilai
                    kebangsaan dalam kehidupan sehari-hari.
                </p>

                <a href="berita.php"
                   class="btn mt-3 text-white"
                   style="background-color:#0f766e;">
                    Kembali ke Berita
                </a>
            </div>
        </div>
    </div>
</section>

<?php 
// 2. Memanggil Footer dari folder includes
include $base_path . 'footer.php'; 
?>