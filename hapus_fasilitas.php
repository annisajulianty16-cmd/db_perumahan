<?php
include '../config.php';
session_start();

// Cek login admin
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

// Pastikan ada ID
if (!isset($_GET['id'])) {
  header("Location: data_fasilitas.php");
  exit;
}

$id = intval($_GET['id']);

// Hapus data dari tabel
$q = mysqli_query($conn, "DELETE FROM facilities WHERE id = $id");

if ($q) {
  echo "<script>
    alert('✅ Data fasilitas berhasil dihapus!');
    window.location='data_fasilitas.php';
  </script>";
} else {
  echo "<script>
    alert('❌ Gagal menghapus data: " . mysqli_error($conn) . "');
    window.location='data_fasilitas.php';
  </script>";
}
?>
