<?php 
// 1. Memanggil Header dari folder includes yang sejajar dengan dashboard_publik
$base_path = $_SERVER['DOCUMENT_ROOT'] . '/UAS/includes/';
include $base_path . 'header.php';
?>

<style>
    /* Style tetap dipertahankan sesuai permintaan */
    body {
        background-color: #f1f5f9;
    }
    .judul-berita {
        color: #0f766e;
        font-weight: 700;
    }
    .box-berita {
        background-color: #e2e8f0;
        border-radius: 12px;
        padding: 20px;
    }
    html, body {
        height: 100%;
    }
    body {
        display: flex;
        flex-direction: column;
    }
</style>

<script>
    // Karena posisi file ini di dalam folder 'berita', 
    // kita paksa semua link di navbar naik satu tingkat agar tidak 'Not Found'
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
                Pelaksanaan ANBK di SD Negeri Pandean 1 Berjalan Lancar
            </h2>

            <p class="text-muted mb-4">
                📅 10 September 2025 • Oleh Admin Sekolah
            </p>

            <div class="row align-items-start">
                <div class="col-md-4 mb-3">
                    <img src="../../assets/img/anbk.jpg"
                         class="img-fluid rounded shadow"
                         style="max-width:360px;"
                         alt="Pelaksanaan ANBK SD Pandean 1">
                </div>

                <div class="col-md-8">
                    <p>
                        Sekolah Dasar Pandean 1 telah melaksanakan Asesmen Nasional Berbasis Komputer (ANBK)
                        tahun pelajaran 2025 dengan lancar dan tertib.
                        Kegiatan ini diikuti oleh siswa kelas V sesuai dengan jadwal yang telah ditetapkan.
                    </p>

                    <p>
                        Pelaksanaan ANBK bertujuan untuk mengukur mutu pendidikan sekolah melalui
                        asesmen literasi, numerasi, dan survei karakter.
                        Sebelum pelaksanaan, sekolah telah melakukan berbagai persiapan,
                        mulai dari simulasi hingga pengecekan perangkat komputer.
                    </p>

                    <p>
                        Kepala Sekolah menyampaikan apresiasi kepada seluruh pihak yang telah
                        mendukung pelaksanaan ANBK, baik guru, siswa, maupun orang tua.
                        Diharapkan hasil ANBK dapat menjadi bahan evaluasi
                        untuk meningkatkan kualitas pembelajaran di sekolah.
                    </p>
                </div>
            </div>

            <a href="berita.php"
               class="btn mt-3 text-white"
               style="background-color:#0f766e;">
                Kembali ke Berita
            </a>

        </div>
    </div>
</section>

<?php 
// 2. Memanggil Footer dari folder includes
include $base_path . 'footer.php'; 
?>