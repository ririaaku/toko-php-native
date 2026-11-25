<?php
require "cek_session.php";
cek_akses(['owner']);
require "koneksi.php";
 
$kategori = $_GET['kategori'] ?? '';
 
$daftar_kategori = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM produk ORDER BY kategori");
 
if ($kategori !== '') {
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE kategori = ? ORDER BY stok ASC");
    mysqli_stmt_bind_param($stmt, "s", $kategori);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
} else {
    $data = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY stok ASC");
}
 
$batas_stok_menipis = 20;
$total_nilai_stok   = 0;
$daftar_produk      = [];
while ($row = mysqli_fetch_assoc($data)) {
    $daftar_produk[] = $row;
    $total_nilai_stok += $row['harga'] * $row['stok'];
}

// Export CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=laporan_stok.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Produk', 'Kategori', 'Harga', 'Stok']);
    foreach ($daftar_produk as $row) {
        fputcsv($out, [
            $row['nama_produk'],
            $row['kategori'],
            $row['harga'],
            $row['stok'],
        ]);
    }
    fclose($out);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Stok Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @media print { .no-print { display: none; } }
  </style>
</head>
<body>
<div class="container mt-4">
  <h3>Laporan Stok Produk</h3>
 
  <form method="GET" class="row g-2 align-items-end mb-4 no-print">
    <div class="col-auto">
      <label>Kategori</label>
      <select name="kategori" class="form-control">
        <option value="">Semua Kategori</option>
        <?php while ($kt = mysqli_fetch_assoc($daftar_kategori)) { ?>
        <option value="<?= $kt['kategori'] ?>" <?= $kategori === $kt['kategori'] ? 'selected' : '' ?>>
          <?= $kt['kategori'] ?>
        </option>
        <?php } ?>
      </select>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary">Tampilkan</button>
    </div>
  </form>
 
  <div class="mb-3 no-print">
    <button onclick="window.print()" class="btn btn-outline-secondary">Print</button>
    <a class="btn btn-outline-success" href="?<?= http_build_query($_GET) ?>&export=csv">Export CSV</a>
  </div>
 
  <table class="table table-bordered">
    <tr>
      <th>Produk</th>
      <th>Kategori</th>
      <th>Harga</th>
      <th>Stok</th>
      <th>Status</th>
    </tr>
    <?php foreach ($daftar_produk as $row) { ?>
    <tr class="<?= $row['stok'] < $batas_stok_menipis ? 'table-danger' : '' ?>">
      <td><?= $row['nama_produk'] ?></td>
      <td><?= $row['kategori'] ?></td>
      <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
      <td><?= $row['stok'] ?></td>
      <td>
        <?= $row['stok'] < $batas_stok_menipis ? 'Stok Menipis' : 'Aman' ?>
      </td>
    </tr>
    <?php } ?>
    <tr>
      <th colspan="4" class="text-end">Total Nilai Stok</th>
      <th>Rp<?= number_format($total_nilai_stok, 0, ',', '.') ?></th>
    </tr>
  </table>
 
  <a href="index.php" class="btn btn-secondary no-print mb-4">Kembali</a>
</div>
</body>
</html>