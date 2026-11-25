<?php
require "cek_session.php";
cek_akses(['admin', 'owner']);
require "koneksi.php";
 
$keyword   = $_GET['keyword']   ?? '';
$aktivitas = $_GET['aktivitas'] ?? '';
 
$sql   = "SELECT * FROM activity_logs WHERE 1=1";
$tipe  = "";
$param = [];
 
if ($keyword !== '') {
    $sql .= " AND (username LIKE ? OR module LIKE ? OR record_info LIKE ?)";
    $like = "%$keyword%";
    $tipe .= "sss";
    $param[] = $like;
    $param[] = $like;
    $param[] = $like;
}
 
if ($aktivitas !== '') {
    $sql  .= " AND activity = ?";
    $tipe .= "s";
    $param[] = $aktivitas;
}
 
$sql .= " ORDER BY waktu DESC LIMIT 100";
 
if ($param) {
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, $tipe, ...$param);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
} else {
    $hasil = mysqli_query($koneksi, $sql);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Log Aktivitas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Log Aktivitas Sistem</h3>
 
  <form method="GET" class="row g-2 align-items-end mb-4">
    <div class="col-auto">
      <label>Cari</label>
      <input type="text" name="keyword" class="form-control" placeholder="user, modul, keterangan..." value="<?= htmlspecialchars($keyword) ?>">
    </div>
    <div class="col-auto">
      <label>Jenis Aktivitas</label>
      <select name="aktivitas" class="form-control">
        <option value="">Semua</option>
        <?php foreach (['CREATE','UPDATE','DELETE','LOGIN','LOGOUT'] as $a) { ?>
        <option value="<?= $a ?>" <?= $aktivitas === $a ? 'selected' : '' ?>>
          <?= $a ?>
        </option>
        <?php } ?>
      </select>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary">Cari</button>
    </div>
    <?php if ($keyword || $aktivitas) { ?>
    <div class="col-auto">
      <a href="log_aktivitas.php" class="btn btn-outline-secondary">Reset</a>
    </div>
    <?php } ?>
  </form>
 
  <table class="table table-bordered table-sm">
    <tr>
      <th>Waktu</th><th>User</th><th>Aktivitas</th><th>Modul</th><th>Keterangan</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
    <tr>
      <td><?= date('d-m-Y H:i', strtotime($row['waktu'])) ?></td>
      <td><?= $row['username'] ?></td>
      <td>
        <span class="badge bg-secondary"><?= $row['activity'] ?></span>
      </td>
      <td><?= $row['module'] ?></td>
      <td><?= $row['record_info'] ?></td>
    </tr>
    <?php } ?>
  </table>
 
  <a href="index.php" class="btn btn-secondary mb-4">Kembali</a>
</div>
</body>
</html>