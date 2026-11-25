<?php
require "cek_session.php";
cek_akses(['kasir', 'owner']);
require "koneksi.php";
$id = (int) $_GET['id'];
 
$stmt = mysqli_prepare($koneksi,
    "SELECT t.*, p.nama_pelanggan, k.nama_karyawan
     FROM transaksi_penjualan t
     JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
     JOIN karyawan k ON t.id_karyawan = k.id_karyawan
     WHERE t.id_transaksi = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$transaksi = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
 
$stmt2 = mysqli_prepare($koneksi,
    "SELECT d.*, pr.nama_produk
     FROM detail_transaksi d
     JOIN produk pr ON d.id_produk = pr.id_produk
     WHERE d.id_transaksi = ?");
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);
$detail = mysqli_stmt_get_result($stmt2);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Detail Transaksi #<?= $transaksi['id_transaksi'] ?></h3>
  <p>
    Tanggal: <?= date('d-m-Y H:i', strtotime($transaksi['tanggal'])) ?><br>
    Pelanggan: <?= $transaksi['nama_pelanggan'] ?><br>
    Kasir: <?= $transaksi['nama_karyawan'] ?>
  </p>
  <table class="table table-bordered">
    <tr><th>Produk</th><th>Qty</th><th>Subtotal</th></tr>
    <?php while ($d = mysqli_fetch_assoc($detail)) { ?>
    <tr>
      <td><?= $d['nama_produk'] ?></td>
      <td><?= $d['qty'] ?></td>
      <td>Rp<?= number_format($d['subtotal'], 0, ',', '.') ?></td>
    </tr>
    <?php } ?>
    <tr>
      <th colspan="2" class="text-end">Total</th>
      <th>Rp<?= number_format($transaksi['total'], 0, ',', '.') ?></th>
    </tr>
  </table>
  <a href="transaksi.php" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>