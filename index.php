<?php
require "cek_session.php";
cek_login();
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sistem Informasi Toko</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Toko Makmur</a>
    <div class="navbar-nav me-auto">
      <?php if (in_array($role, ['admin','gudang'])) { ?>
      <a class="nav-link" href="produk.php">Data Produk</a>
      <?php } ?>
      <?php if ($role === 'admin') { ?>
      <a class="nav-link" href="pelanggan.php">Data Pelanggan</a>
      <a class="nav-link" href="karyawan.php">Data Karyawan</a>
      <?php } ?>
      <?php if (in_array($role, ['kasir','owner'])) { ?>
      <a class="nav-link" href="transaksi.php">Transaksi</a>
      <?php } ?>
      <?php if ($role === 'owner') { ?>
      <a class="nav-link" href="dashboard.php">Dashboard</a>
      <a class="nav-link" href="laporan_penjualan.php">Laporan Penjualan</a>
      <a class="nav-link" href="laporan_stok.php">Laporan Stok</a>
      <?php } ?>
      <?php if (in_array($role, ['admin','owner'])) { ?>
      <a class="nav-link" href="log_aktivitas.php">Log Aktivitas</a>
      <?php } ?>
    </div>
    <div class="navbar-nav">
      <span class="nav-link text-white">
        <?= $_SESSION['username'] ?> (<?= $role ?>)
      </span>
      <a class="nav-link" href="logout.php">Logout</a>
    </div>
  </div>
</nav>
<div class="container mt-4">
  <h3>Selamat Datang, <?= $_SESSION['username'] ?></h3>
  <p>Silakan pilih menu di atas sesuai role Anda (<?= $role ?>).</p>
</div>
</body>
</html>