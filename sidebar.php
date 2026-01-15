<aside class="main-sidebar sidebar-light-teal">
  <a href="index.php" class="brand-link">
    <span class="brand-text font-weight-bold">SDN PANDEAN 1</span>
  </a>

  <div class="sidebar mt-3 px-3">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-header">NAVIGASI UTAMA</li>
        
        <li class="nav-item">
          <a href="index.php" class="nav-link <?= ($page == 'dashboard') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard Overview</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="siswa.php" class="nav-link <?= ($page == 'siswa') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-user-graduate"></i>
            <p>Data Siswa</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="guru.php" class="nav-link <?= ($page == 'guru') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-chalkboard-teacher"></i>
            <p>Data Guru</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="ruang_kelas.php" class="nav-link <?= ($page == 'kelas') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-school"></i>
            <p>Ruang Kelas</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="pengaturan_publik.php" class="nav-link <?= ($page == 'pengaturan_publik') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-desktop"></i>
            <p>Tampilan Publik</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <p>Mata Pelajaran</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>