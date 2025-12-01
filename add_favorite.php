<?php
session_start();
include '../config.php';

// 🔒 Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
  echo "<script>alert('Silakan login terlebih dahulu untuk menambahkan favorit!'); window.location='../login.php';</script>";
  exit;
}

// Ambil ID user dari session (karena di login.php: $_SESSION['user'] = $user['id'])
$user_id = intval($_SESSION['user']);

// 🔹 Ambil ID perumahan dari POST
if (!isset($_POST['housing_id'])) {
  echo "<script>alert('Data perumahan tidak ditemukan!'); window.history.back();</script>";
  exit;
}

$housing_id = intval($_POST['housing_id']);
if ($housing_id <= 0) {
  echo "<script>alert('ID perumahan tidak valid!'); window.history.back();</script>";
  exit;
}

// 🔍 Cek apakah sudah ada di tabel favorites
$cek = mysqli_query($conn, "SELECT id FROM favorites WHERE user_id='$user_id' AND housing_id='$housing_id' LIMIT 1");
if (mysqli_num_rows($cek) > 0) {
  echo "<script>alert('Perumahan ini sudah ada di daftar favorit kamu!'); window.location='../detail.php?id=$housing_id';</script>";
  exit;
}

// 💾 Simpan ke tabel favorites
$tanggal = date('Y-m-d H:i:s');
$simpan = mysqli_query($conn, "INSERT INTO favorites (user_id, housing_id, created_at) VALUES ('$user_id', '$housing_id', '$tanggal')");

// ✅ Cek hasil
if ($simpan) {
  echo "<script>alert('✅ Berhasil menambahkan ke favorit!'); window.location='../detail.php?id=$housing_id';</script>";
} else {
  echo "<script>alert('❌ Gagal menambahkan favorit: " . mysqli_error($conn) . "'); window.history.back();</script>";
}
?>
