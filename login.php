<?php
require "cek_session.php";
require "koneksi.php";
require "log_helper.php";

if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
    $user  = mysqli_fetch_assoc($hasil);

    if ($user && password_verify($password, $user['password'])) {
        // Simpan session terlebih dahulu agar $_SESSION['username'] siap dibaca catat_log()
        $_SESSION['id_user']  = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        // Catat aktivitas login ke tabel activity_logs
        catat_log('LOGIN', 'Auth', 'Login sebagai ' . $user['role']);

        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Sistem Informasi Toko</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:400px;">
  <h3 class="mb-3 text-center">Login</h3>

  <?php if ($error) { ?>
  <div class="alert alert-danger"><?= $error ?></div>
  <?php } ?>

  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required autofocus>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
  </form>
</div>
</body>
</html>