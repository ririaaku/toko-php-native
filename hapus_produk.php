<?php
require "cek_session.php";
cek_akses(['admin', 'gudang']);
require "koneksi.php";
require "log_helper.php";

$id = $_GET['id'];

$cek = mysqli_prepare($koneksi, "SELECT nama_produk FROM produk WHERE id_produk = ?");
mysqli_stmt_bind_param($cek, "i", $id);
mysqli_stmt_execute($cek);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($cek));

$query = mysqli_prepare($koneksi, "DELETE FROM produk WHERE id_produk = ?");
mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

catat_log('DELETE', 'Produk', 'Menghapus produk: ' . $row['nama_produk']);

header("Location: produk.php?pesan=berhasil");
exit;
?>