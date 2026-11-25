<?php
session_start();
 
function cek_login() {
    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }
}
 
function cek_akses($role_diizinkan) {
    cek_login();
    if (!in_array($_SESSION['role'], $role_diizinkan)) {
        echo "<div style='font-family:sans-serif; padding:40px;'>";
        echo "<h3>Akses ditolak</h3>";
        echo "<p>Halaman ini bukan untuk role Anda.</p>";
        echo "<a href='index.php'>Kembali ke Menu</a>";
        echo "</div>";
        exit;
    }
}
?>