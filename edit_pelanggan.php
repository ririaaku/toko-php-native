<?php
require "cek_session.php";
cek_akses(['admin']);
require "koneksi.php";

$id  = $_GET['id'];
$cek = mysqli_prepare($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = ?");
mysqli_stmt_bind_param($cek, "i", $id);
mysqli_stmt_execute($cek);
$hasil = mysqli_stmt_get_result($cek);
$row   = mysqli_fetch_assoc($hasil);

if (isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $no_hp  = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $query = mysqli_prepare($koneksi, "UPDATE pelanggan SET nama_pelanggan=?, no_hp=?, alamat=? WHERE id_pelanggan=?");
    mysqli_stmt_bind_param($query, "sssi", $nama, $no_hp, $alamat, $id);
    mysqli_stmt_execute($query);
    require "log_helper.php";
    catat_log('UPDATE', 'Pelanggan', "Mengubah: $nama");

    header("Location: pelanggan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Edit Pelanggan</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Nama Pelanggan</label>
      <input type="text" name="nama_pelanggan" class="form-control" value="<?= $row['nama_pelanggan'] ?>" required>
    </div>
    <div class="mb-3">
      <label>No. HP</label>
      <input type="text" name="no_hp" class="form-control" value="<?= $row['no_hp'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Alamat</label>
      <textarea name="alamat" class="form-control" required><?= $row['alamat'] ?></textarea>
    </div>
    <button type="submit" name="update" class="btn btn-warning">Update</button>
    <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>