<?php
require "cek_session.php";
cek_akses(['admin', 'gudang']);
require "koneksi.php";
$data = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id_produk DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Data Produk</h3>
  <a href="tambah_produk.php" class="btn btn-primary mb-3">+ Tambah Produk</a>
  <table class="table table-bordered table-striped">
    <tr>
      <th>No</th><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th>
    </tr>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row['nama_produk'] ?></td>
      <td><?= $row['kategori'] ?></td>
      <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
      <td><?= $row['stok'] ?></td>
      <td>
        <a href="edit_produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="hapus_produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus produk ini?')">Hapus</a>
      </td>
    </tr>
    <?php } ?>
  </table>
  <a href="index.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil') { ?>
  <script>
    alert("Produk tersebut berhasil di hapus!");
  </script>
<?php } ?>
</body>
</html>