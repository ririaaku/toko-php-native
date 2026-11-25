<?php
require "cek_session.php";
require "koneksi.php";
require "log_helper.php";

catat_log('LOGOUT', 'Auth', 'User logout');

session_destroy();
header("Location: login.php");
exit;
?>