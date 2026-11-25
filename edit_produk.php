<?php
require "cek_session.php";
cek_akses(['admin', 'gudang']);
require "koneksi.php";
 
$id   = $_GET['id'];
$cek  = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE id_produk = ?");
mysqli_stmt_bind_param($cek, "i", $id);
mysqli_stmt_execute($cek);
$hasil = mysqli_stmt_get_result($cek);
$row   = mysqli_fetch_assoc($hasil);
 
if (isset($_POST['update'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga    = (int) $_POST['harga'];
    $stok     = (int) $_POST['stok'];
 
    $query = mysqli_prepare($koneksi,
        "UPDATE produk SET nama_produk=?, kategori=?, harga=?, stok=? WHERE id_produk=?");
    mysqli_stmt_bind_param($query, "ssiii", $nama, $kategori, $harga, $stok, $id);
    mysqli_stmt_execute($query);
    require "log_helper.php";
    catat_log('UPDATE', 'Produk', "Mengubah: $nama");
 
    header("Location: produk.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Edit Produk</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" value="<?= $row['nama_produk'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Kategori</label>
      <input type="text" name="kategori" class="form-control" value="<?= $row['kategori'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Harga</label>
      <input type="number" name="harga" class="form-control" value="<?= $row['harga'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Stok</label>
      <input type="number" name="stok" class="form-control" value="<?= $row['stok'] ?>" required>
    </div>
    <button type="submit" name="update" class="btn btn-warning">Update</button>
    <a href="produk.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>