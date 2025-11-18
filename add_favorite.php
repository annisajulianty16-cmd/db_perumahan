<?php
session_start();
include '../config.php';

// 🔒 Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
  echo "<script>alert('Silakan login terlebih dahulu untuk menyimpan favorit!'); window.location='../login.php';</script>";
  exit;
}

// 🔹 Ambil ID user dari session
$user = $_SESSION['user'];
$user_id = 0;

if (is_array($user) && isset($user['id'])) {
  // Jika session menyimpan array user
  $user_id = intval($user['id']);
} else {
  // Jika hanya ID yang disimpan di session (seperti di login.php kamu)
  $user_id = intval($user);
}

// Cek validitas user_id
if ($user_id <= 0) {
  echo "<script>alert('Data pengguna tidak ditemukan. Silakan login ulang!'); window.location='../login.php';</script>";
  exit;
}

// 🔹 Pastikan ada housing_id yang dikirim dari form
if (!isset($_POST['housing_id'])) {
  echo "<script>alert('Data perumahan tidak ditemukan!'); window.history.back();</script>";
  exit;
}

$housing_id = intval($_POST['housing_id']);
if ($housing_id <= 0) {
  echo "<script>alert('ID perumahan tidak valid!'); window.history.back();</script>";
  exit;
}

// 🔍 Cek apakah perumahan sudah difavoritkan sebelumnya
$cekFav = mysqli_query($conn, "SELECT 1 FROM favorites WHERE user_id='$user_id' AND housing_id='$housing_id' LIMIT 1");

if (mysqli_num_rows($cekFav) > 0) {
  echo "<script>alert('Perumahan ini sudah ada di daftar favorit kamu!'); window.history.back();</script>";
  exit;
}

// 💾 Simpan ke tabel favorites
$tanggal = date('Y-m-d H:i:s');
$query = mysqli_query($conn, "INSERT INTO favorites (user_id, housing_id, created_at) VALUES ('$user_id', '$housing_id', '$tanggal')");

if ($query) {
  // 🧾 Catat ke tabel logs
  $action = "Menambahkan perumahan ID $housing_id ke favorit";
  mysqli_query($conn, "INSERT INTO logs (user_id, action, created_at) VALUES ('$user_id', '$action', '$tanggal')");

  echo "<script>alert('✅ Berhasil menambahkan ke favorit!'); window.location='../detail_perumahan.php?id=$housing_id';</script>";
} else {
  echo "<script>alert('❌ Gagal menambahkan favorit: " . mysqli_error($conn) . "'); window.history.back();</script>";
}
?>
