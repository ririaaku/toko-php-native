<?php
require "cek_session.php";
cek_akses(['admin']);
require "koneksi.php";
$data = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Data Pelanggan</h3>
  <a href="tambah_pelanggan.php" class="btn btn-primary mb-3">+ Tambah Pelanggan</a>
  <table class="table table-bordered table-striped">
    <tr>
      <th>No</th><th>Nama Pelanggan</th><th>No. HP</th><th>Alamat</th><th>Aksi</th>
    </tr>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row['nama_pelanggan'] ?></td>
      <td><?= $row['no_hp'] ?></td>
      <td><?= $row['alamat'] ?></td>
      <td>
        <a href="edit_pelanggan.php?id=<?= $row['id_pelanggan'] ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="hapus_pelanggan.php?id=<?= $row['id_pelanggan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus pelanggan ini?')">Hapus</a>
      </td>
    </tr>
    <?php } ?>
  </table>
  <a href="index.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil') { ?>
  <script>
    alert("Pelanggan tersebut berhasil di hapus!");
  </script>
<?php } ?>
</body>
</html>