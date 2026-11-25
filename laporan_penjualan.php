<?php
require "cek_session.php";
cek_akses(['owner']);
require "koneksi.php";

// Dropdown daftar kasir/karyawan
$karyawan = mysqli_query($koneksi, "SELECT * FROM karyawan ORDER BY nama_karyawan");

$tgl_awal    = $_GET['tgl_awal']    ?? '';
$tgl_akhir   = $_GET['tgl_akhir']   ?? '';
$id_karyawan = $_GET['id_karyawan'] ?? '';

$data = [];
$grand_total = 0;

if ($tgl_awal && $tgl_akhir) {
    $sql = "SELECT t.id_transaksi, t.tanggal, t.total, p.nama_pelanggan, k.nama_karyawan
        FROM transaksi_penjualan t
        JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
        JOIN karyawan k ON t.id_karyawan = k.id_karyawan
        WHERE DATE(t.tanggal) BETWEEN ? AND ?";
    $tipe  = "ss";
    $param = [$tgl_awal, $tgl_akhir];

    if ($id_karyawan !== '') {
        $sql   .= " AND t.id_karyawan = ?";
        $tipe  .= "i";
        $param[] = $id_karyawan;
    }
    $sql .= " ORDER BY t.tanggal ASC";

    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, $tipe, ...$param);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($hasil)) {
        $data[] = $row;
        $grand_total += $row['total'];
    }
}

// Export CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=laporan_penjualan.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Tanggal', 'Pelanggan', 'Kasir', 'Total']);
    foreach ($data as $row) {
        fputcsv($out, [
            $row['tanggal'],
            $row['nama_pelanggan'],
            $row['nama_karyawan'],
            $row['total'],
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
  <title>Laporan Penjualan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @media print { .no-print { display: none; } }
  </style>
</head>
<body>
<div class="container mt-4">
  <h3>Laporan Penjualan</h3>
 
  <form method="GET" class="row g-2 align-items-end mb-4 no-print">
    <div class="col-auto">
      <label>Tanggal Awal</label>
      <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal ?>" required>
    </div>
    <div class="col-auto">
      <label>Tanggal Akhir</label>
      <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir ?>" required>
    </div>
    <div class="col-auto">
      <label>Kasir</label>
      <select name="id_karyawan" class="form-control">
        <option value="">Semua Kasir</option>
        <?php mysqli_data_seek($karyawan, 0);
        while ($k = mysqli_fetch_assoc($karyawan)) { ?>
        <option value="<?= $k['id_karyawan'] ?>" <?= $id_karyawan == $k['id_karyawan'] ? 'selected' : '' ?>>
          <?= $k['nama_karyawan'] ?>
        </option>
        <?php } ?>
      </select>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary">Tampilkan</button>
    </div>
  </form>
 
  <?php if ($data) { ?>
  <div class="mb-3 no-print">
    <button onclick="window.print()" class="btn btn-outline-secondary">Print</button>
    <a class="btn btn-outline-success" href="?<?= http_build_query($_GET) ?>&export=csv">Export CSV</a>
  </div>
 
  <table class="table table-bordered">
    <tr>
      <th>Tanggal</th>
      <th>Pelanggan</th>
      <th>Kasir</th>
      <th>Total</th>
    </tr>
    <?php foreach ($data as $row) { ?>
    <tr>
      <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
      <td><?= $row['nama_pelanggan'] ?></td>
      <td><?= $row['nama_karyawan'] ?></td>
      <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
    </tr>
    <?php } ?>
    <tr>
      <th colspan="3" class="text-end">Grand Total</th>
      <th>Rp<?= number_format($grand_total, 0, ',', '.') ?></th>
    </tr>
  </table>
  <?php } elseif ($tgl_awal && $tgl_akhir) { ?>
  <div class="alert alert-warning">
    Tidak ada transaksi pada rentang tanggal ini.
  </div>
  <?php } ?>
 
  <a href="index.php" class="btn btn-secondary no-print mb-4">Kembali</a>
</div>
</body>
</html>