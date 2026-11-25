<?php
require "cek_session.php";
cek_akses(['admin']);
require "koneksi.php";

$id  = $_GET['id'];
$cek = mysqli_prepare($koneksi, "SELECT * FROM karyawan WHERE id_karyawan = ?");
mysqli_stmt_bind_param($cek, "i", $id);
mysqli_stmt_execute($cek);
$hasil = mysqli_stmt_get_result($cek);
$row   = mysqli_fetch_assoc($hasil);

if (isset($_POST['update'])) {
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_karyawan']);
    $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
    $no_hp   = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    $query = mysqli_prepare($koneksi, "UPDATE karyawan SET nama_karyawan=?, jabatan=?, no_hp=? WHERE id_karyawan=?");
    mysqli_stmt_bind_param($query, "sssi", $nama, $jabatan, $no_hp, $id);
    mysqli_stmt_execute($query);
    require "log_helper.php";
    catat_log('UPDATE', 'Karyawan', "Mengubah: $nama");

    header("Location: karyawan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Karyawan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Edit Karyawan</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Nama Karyawan</label>
      <input type="text" name="nama_karyawan" class="form-control" value="<?= $row['nama_karyawan'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Jabatan</label>
      <input type="text" name="jabatan" class="form-control" value="<?= $row['jabatan'] ?>" required>
    </div>
    <div class="mb-3">
      <label>No. HP</label>
      <input type="text" name="no_hp" class="form-control" value="<?= $row['no_hp'] ?>" required>
    </div>
    <button type="submit" name="update" class="btn btn-warning">Update</button>
    <a href="karyawan.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>