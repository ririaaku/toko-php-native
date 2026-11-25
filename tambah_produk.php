<?php
require "cek_session.php";
cek_akses(['admin', 'gudang']);
require "koneksi.php";
 
if (isset($_POST['simpan'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga    = (int) $_POST['harga'];
    $stok     = (int) $_POST['stok'];
 
    $query = mysqli_prepare($koneksi,
        "INSERT INTO produk (nama_produk, kategori, harga, stok) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($query, "ssii", $nama, $kategori, $harga, $stok);
    mysqli_stmt_execute($query);
    require "log_helper.php";
    catat_log('CREATE', 'Produk', "Menambah: $nama");
    
    header("Location: produk.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Tambah Produk</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Kategori</label>
      <input type="text" name="kategori" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Harga</label>
      <input type="number" name="harga" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Stok</label>
      <input type="number" name="stok" class="form-control" required>
    </div>
    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
    <a href="produk.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>