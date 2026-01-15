<?php
// Mulai session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login dan role-nya admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); // arahkan ke halaman login
    exit;
}

// Deteksi halaman aktif untuk sidebar
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="main-sidebar sidebar-light-teal elevation-4">
    <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-bold ml-3 text-teal">SDN PANDEAN 1</span>
    </a>

    <div class="sidebar mt-3 px-3">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-header">NAVIGASI UTAMA</li>

                <li class="nav-item">
                    <a href="register.php" class="nav-link <?= ($page == 'register') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Kelola Pengguna</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php" class="nav-link <?= ($page == 'dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard Overview</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="siswa.php" class="nav-link <?= ($page == 'siswa') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Data Siswa</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="guru.php" class="nav-link <?= ($page == 'guru') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>Data Guru</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="ruang_kelas.php" class="nav-link <?= ($page == 'kelas') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-school"></i>
                        <p>Ruang Kelas</p>
                    </a>
                </li>

                <li class="nav-header">AKADEMIK & CATATAN</li>

                <li class="nav-item">
                    <a href="mata_pelajaran.php" class="nav-link <?= ($page == 'mapel') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Mata Pelajaran</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="jadwal.php" class="nav-link <?= ($page == 'jadwal') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Jadwal Pelajaran</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="rekap_presensi.php" class="nav-link <?= ($page == 'presensi') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-check"></i>
                        <p>Presensi Guru</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="rekap_presensi_siswa.php" class="nav-link <?= ($page == 'rekap_presensi_siswa') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Presensi Siswa</p>
                    </a>
                </li>

                <li class="nav-header">PENGATURAN WEBSITE</li>

                <li class="nav-item <?= (in_array($page, ['galeri', 'berita', 'slider', 'pengaturan_publik'])) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (in_array($page, ['galeri', 'berita', 'slider', 'pengaturan_publik'])) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>
                            Tampilan Publik
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pengaturan_publik.php" class="nav-link <?= ($page == 'slider') ? 'active' : '' ?>">
                                <i class="far fa-images nav-icon"></i>
                                <p>Beranda</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="galeri.php" class="nav-link <?= ($page == 'galeri') ? 'active' : '' ?>">
                                <i class="far fa-image nav-icon"></i>
                                <p>Galeri Sekolah</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="berita_tampil.php" class="nav-link <?= ($page == 'berita') ? 'active' : '' ?>">
                                <i class="far fa-newspaper nav-icon"></i>
                                <p>Berita & Artikel</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">SISTEM</li>

                <li class="nav-item">
                    <a href="../auth/logout.php" class="nav-link text-danger" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>