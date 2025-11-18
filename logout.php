<?php
session_start();
include 'config.php';

// 🔹 Jika user login biasa
if (isset($_SESSION['user'])) {
  $user_id = $_SESSION['user'];
  catat_log($conn, $user_id, "Logout dari sistem");
  unset($_SESSION['user']);
}

// 🔹 Jika admin login
if (isset($_SESSION['admin'])) {
  catat_log($conn, 0, "Admin logout dari sistem");
  unset($_SESSION['admin']);
}

// 🔒 Hapus semua session
session_destroy();

// 🔁 Kembali ke halaman login
header("Location: login.php");
exit;
?>
