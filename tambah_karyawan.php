<?php
require "cek_session.php";
cek_akses(['admin']);
require "koneksi.php";

if (isset($_POST['simpan'])) {
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_karyawan']);
    $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
    $no_hp   = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    $query = mysqli_prepare($koneksi, "INSERT INTO karyawan (nama_karyawan, jabatan, no_hp) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($query, "sss", $nama, $jabatan, $no_hp);
    mysqli_stmt_execute($query);
    require "log_helper.php";
    catat_log('CREATE', 'Karyawan', "Menambah: $nama");
    
    header("Location: karyawan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Karyawan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Tambah Karyawan</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Nama Karyawan</label>
      <input type="text" name="nama_karyawan" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Jabatan</label>
      <input type="text" name="jabatan" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>No. HP</label>
      <input type="text" name="no_hp" class="form-control" required>
    </div>
    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
    <a href="karyawan.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>