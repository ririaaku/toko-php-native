<?php
require "cek_session.php";
cek_akses(['admin']);
require "koneksi.php";
require "log_helper.php";

$id = $_GET['id'];
$cek = mysqli_prepare($koneksi, "SELECT nama_karyawan FROM karyawan WHERE id_karyawan = ?");
mysqli_stmt_bind_param($cek, "i", $id);
mysqli_stmt_execute($cek);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($cek));

$query = mysqli_prepare($koneksi, "DELETE FROM karyawan WHERE id_karyawan = ?");
mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

catat_log('DELETE', 'Karyawan', 'Menghapus karyawan: ' . $row['nama_karyawan']);

header("Location: karyawan.php?pesan=berhasil");
exit;
?>