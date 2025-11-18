<?php
include '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

$id = $_GET['id'];
$q = mysqli_query($conn, "DELETE FROM housing WHERE id='$id'");

if ($q) {
  echo "<script>alert('Data berhasil dihapus!');window.location='index.php';</script>";
} else {
  echo "<script>alert('Gagal menghapus data!');window.location='index.php';</script>";
}
?>
