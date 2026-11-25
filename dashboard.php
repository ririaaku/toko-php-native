<?php
require "cek_session.php";
cek_akses(['owner']);
require "koneksi.php";

// ---- KPI 1: Total Produk ----
$sql1 = "SELECT COUNT(*) AS total FROM produk";
$total_produk = mysqli_fetch_assoc(mysqli_query($koneksi, $sql1))['total'];

// ---- KPI 2: Transaksi Bulan Ini ----
$sql2 = "SELECT COUNT(*) AS total
    FROM transaksi_penjualan
    WHERE MONTH(tanggal) = MONTH(CURDATE())
      AND YEAR(tanggal) = YEAR(CURDATE())";
$transaksi_bulan_ini = mysqli_fetch_assoc(mysqli_query($koneksi, $sql2))['total'];

// ---- KPI 3: Pendapatan Bulan Ini ----
$sql3 = "SELECT COALESCE(SUM(total), 0) AS total
    FROM transaksi_penjualan
    WHERE MONTH(tanggal) = MONTH(CURDATE())
      AND YEAR(tanggal) = YEAR(CURDATE())";
$pendapatan_bulan_ini = mysqli_fetch_assoc(mysqli_query($koneksi, $sql3))['total'];

// ---- KPI 4: Produk Terjual Bulan Ini ----
$sql4 = "SELECT COALESCE(SUM(dt.qty), 0) AS total
    FROM detail_transaksi dt
    JOIN transaksi_penjualan t ON dt.id_transaksi = t.id_transaksi
    WHERE MONTH(t.tanggal) = MONTH(CURDATE())
      AND YEAR(t.tanggal) = YEAR(CURDATE())";
$produk_terjual_bulan_ini = mysqli_fetch_assoc(mysqli_query($koneksi, $sql4))['total'];

// ---- Grafik 1: Jumlah Transaksi per Bulan ----
$data_bulanan = array_fill(1, 12, 0);
$sql5 = "SELECT MONTH(tanggal) AS bulan, COUNT(*) AS jumlah
    FROM transaksi_penjualan
    WHERE YEAR(tanggal) = YEAR(CURDATE())
    GROUP BY MONTH(tanggal)";
$hasil5 = mysqli_query($koneksi, $sql5);
while ($row = mysqli_fetch_assoc($hasil5)) {
    $data_bulanan[(int) $row['bulan']] = (int) $row['jumlah'];
}
$nama_bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

// ---- Grafik 2: Produk Terlaris (Top 5) ----
$sql6 = "SELECT pr.nama_produk, SUM(dt.qty) AS total_terjual
    FROM detail_transaksi dt
    JOIN produk pr ON dt.id_produk = pr.id_produk
    GROUP BY dt.id_produk
    ORDER BY total_terjual DESC
    LIMIT 5";
$hasil6 = mysqli_query($koneksi, $sql6);
$label_terlaris = [];
$data_terlaris  = [];
while ($row = mysqli_fetch_assoc($hasil6)) {
    $label_terlaris[] = $row['nama_produk'];
    $data_terlaris[]  = (int) $row['total_terjual'];
}

// ---- Tabel: 5 Transaksi Terbaru ----
$sql7 = "SELECT t.id_transaksi, t.tanggal, t.total, p.nama_pelanggan, k.nama_karyawan
    FROM transaksi_penjualan t
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    JOIN karyawan k ON t.id_karyawan = k.id_karyawan
    ORDER BY t.id_transaksi DESC
    LIMIT 5";
$transaksi_terbaru = mysqli_query($koneksi, $sql7);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Sistem Informasi Toko</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
</head>
<body>
<div class="container mt-4">
  <h3 class="mb-4">Dashboard Sistem Informasi Toko</h3>
 
  <!-- KPI Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card text-white bg-primary">
        <div class="card-body">
          <div class="small">TOTAL PRODUK</div>
          <div class="fs-3 fw-bold"><?= $total_produk ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-success">
        <div class="card-body">
          <div class="small">TRANSAKSI BULAN INI</div>
          <div class="fs-3 fw-bold"><?= $transaksi_bulan_ini ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-warning">
        <div class="card-body">
          <div class="small">PENDAPATAN BULAN INI</div>
          <div class="fs-5 fw-bold">Rp<?= number_format($pendapatan_bulan_ini, 0, ',', '.') ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-info">
        <div class="card-body">
          <div class="small">PRODUK TERJUAL BULAN INI</div>
          <div class="fs-3 fw-bold"><?= $produk_terjual_bulan_ini ?></div>
        </div>
      </div>
    </div>
  </div>
 
  <!-- Charts -->
  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <div class="card p-3">
        <h6>Jumlah Transaksi per Bulan</h6>
        <canvas id="chartBulanan"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3">
        <h6>Produk Terlaris (Top 5)</h6>
        <canvas id="chartTerlaris"></canvas>
      </div>
    </div>
  </div>
 
  <!-- Tabel Transaksi Terbaru -->
  <div class="card p-3 mb-4">
    <h6>5 Transaksi Terbaru</h6>
    <table class="table table-sm table-bordered mb-0">
      <tr>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Kasir</th>
        <th>Total</th>
      </tr>
      <?php while ($row = mysqli_fetch_assoc($transaksi_terbaru)) { ?>
      <tr>
        <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
        <td><?= $row['nama_pelanggan'] ?></td>
        <td><?= $row['nama_karyawan'] ?></td>
        <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
 
  <a href="index.php" class="btn btn-secondary mb-4">Kembali</a>
</div>
 
<script>
const chartBulanan = new Chart(
    document.getElementById('chartBulanan'), {
    type: 'line',
    data: {
        labels: <?= json_encode($nama_bulan) ?>,
        datasets: [{
            label: 'Jumlah Transaksi',
            data: <?= json_encode(array_values($data_bulanan)) ?>,
            borderColor: '#0d6efd',
            tension: 0.3
        }]
    }
});
 
const chartTerlaris = new Chart(
    document.getElementById('chartTerlaris'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($label_terlaris) ?>,
        datasets: [{
            label: 'Qty Terjual',
            data: <?= json_encode($data_terlaris) ?>,
            backgroundColor: '#198754'
        }]
    },
    options: { indexAxis: 'y' }
});
</script>
</body>
</html>