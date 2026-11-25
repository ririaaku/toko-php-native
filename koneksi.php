<?php
$host = "127.0.0.1"; 
$user = "root";
$pass = "";
$db   = "db_toko"; // Database untuk modul 8
$port = 3307; 

// Pastikan nama variabelnya $koneksi agar file CRUD lain tidak error
$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>