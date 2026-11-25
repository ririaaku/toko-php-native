<?php
require "cek_session.php";
cek_akses(['admin']);
require "koneksi.php";

if (isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $no_hp  = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $query = mysqli_prepare($koneksi, "INSERT INTO pelanggan (nama_pelanggan, no_hp, alamat) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($query, "sss", $nama, $no_hp, $alamat);
    mysqli_stmt_execute($query);
    require "log_helper.php";
    catat_log('CREATE', 'Pelanggan', "Menambah: $nama");

    header("Location: pelanggan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Tambah Pelanggan</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Nama Pelanggan</label>
      <input type="text" name="nama_pelanggan" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>No. HP</label>
      <input type="text" name="no_hp" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Alamat</label>
      <textarea name="alamat" class="form-control" required></textarea>
    </div>
    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
    <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>