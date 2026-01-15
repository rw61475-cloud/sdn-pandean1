<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Admin Dashboard'; ?> | SDN Pandean 1</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap">
  <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">

  <style>
    :root { --primary: #008080; --bg-light: #f8fafc; }
    body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); color: #334155; }
    .main-sidebar { background: #ffffff !important; border-right: 1px solid #e2e8f0 !important; }
    .brand-link { background: var(--primary) !important; color: #fff !important; text-align: center; border-bottom: none !important; padding: 1.2rem 0.5rem !important; }
    .nav-header { font-size: 0.75rem !important; font-weight: 700 !important; color: #94a3b8 !important; letter-spacing: 1px; padding: 1rem 1.5rem 0.5rem !important; text-transform: uppercase; }
    .nav-pills .nav-link { border-radius: 10px; margin-bottom: 4px; color: #64748b !important; padding: 0.8rem 1rem; }
    .nav-pills .nav-link.active { background: var(--primary) !important; color: #fff !important; box-shadow: 0 4px 12px rgba(0, 128, 128, 0.3) !important; }
    .nav-pills .nav-link:hover:not(.active) { background: #f1f5f9; color: var(--primary) !important; }
    .main-header { background: rgba(255, 255, 255, 0.8) !important; backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0 !important; }
    .text-teal { color: var(--primary) !important; }
    .small-box { border-radius: 20px; overflow: hidden; transition: 0.4s; border: none; }
    .small-box:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .card { border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .bg-teal-custom { background-color: var(--primary) !important; color: white !important; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
    <ul class="navbar-nav pl-3">
      <li class="nav-item">
        <a class="nav-link text-teal" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link text-teal font-weight-bold"><i class="fas fa-home mr-1"></i> Beranda Admin</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto pr-3">
       <li class="nav-item">
          <a href="../logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
            <i class="fas fa-sign-out-alt mr-1"></i> Keluar
          </a>
       </li>
    </ul>
  </nav>