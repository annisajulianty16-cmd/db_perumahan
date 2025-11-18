<?php
// Konfigurasi koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_perumahan"; // pastikan nama database sesuai di phpMyAdmin

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

// =============================
// 🧾 Fungsi untuk mencatat log aktivitas
// =============================
function catat_log($conn, $user_id, $action) {
  $user_id = mysqli_real_escape_string($conn, $user_id);
  $action  = mysqli_real_escape_string($conn, $action);

  // Insert ke tabel logs
  $sql = "INSERT INTO logs (user_id, action, created_at) VALUES ('$user_id', '$action', NOW())";
  mysqli_query($conn, $sql);
}
?>
