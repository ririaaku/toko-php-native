<?php
require "cek_session.php";
cek_akses(['kasir', 'owner']);
require "koneksi.php";
 
$sql = "SELECT t.id_transaksi, t.tanggal, t.total, p.nama_pelanggan, k.nama_karyawan
        FROM transaksi_penjualan t
        JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
        JOIN karyawan k ON t.id_karyawan = k.id_karyawan
        ORDER BY t.id_transaksi DESC";
$data = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Daftar Transaksi Penjualan</h3>
  <a href="tambah_transaksi.php" class="btn btn-primary mb-3">+ Transaksi Baru</a>
  <table class="table table-bordered table-striped">
    <tr><th>No</th><th>Tanggal</th><th>Pelanggan</th><th>Kasir</th><th>Total</th><th>Aksi</th></tr>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
      <td><?= $row['nama_pelanggan'] ?></td>
      <td><?= $row['nama_karyawan'] ?></td>
      <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
      <td>
        <a href="detail_transaksi.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-info btn-sm">Detail</a>
      </td>
    </tr>
    <?php } ?>
  </table>
  <a href="index.php" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>