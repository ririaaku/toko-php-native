<?php
function catat_log($aktivitas, $modul, $keterangan) {
    global $koneksi;
 
    $username = $_SESSION['username'] ?? 'system';
 
    $stmt = mysqli_prepare($koneksi,
        "INSERT INTO activity_logs (username, activity, module, record_info) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $username, $aktivitas, $modul, $keterangan);
    mysqli_stmt_execute($stmt);
}
?>