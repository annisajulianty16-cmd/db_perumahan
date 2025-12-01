<?php
include '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin | InfoPerumahan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {font-family:'Poppins',sans-serif;background:#f5f7fa;color:#333;}
.navbar {background:#0a1b3c;color:white;}
.navbar-brand {color:#f1c40f!important;font-weight:600;}
.sidebar {
  background:#0a1b3c;
  height:100vh;
  color:white;
  padding:20px;
  position:fixed;
  top:0;left:0;width:240px;
}
.sidebar a {
  display:block;color:white;text-decoration:none;padding:10px 0;
  transition:all .3s ease;border-radius:6px;
}
.sidebar a:hover {background:#f1c40f;color:#000;}
.content {margin-left:260px;padding:30px;}
.card {
  border:none;
  border-radius:15px;
  box-shadow:0 4px 20px rgba(0,0,0,0.1);
}
.card h5 {color:#0a1b3c;font-weight:600;}
</style>
</head>
<body>

<div class="sidebar">
  <h4 class="fw-bold text-center mb-4"><i class="fa-solid fa-house-chimney"></i> Admin Panel</h4>
  <a href="index.php"><i class="fa fa-dashboard me-2"></i> Dashboard</a>
  <a href="tambah_perumahan.php"><i class="fa fa-plus-circle me-2"></i> Tambah Perumahan</a>
  <a href="data_perumahan.php"><i class="fa fa-list me-2"></i> Data Perumahan</a>
  <a href="data_developer.php"><i class="fa fa-user-tie me-2"></i> Developer</a>
  <a href="data_lokasi.php"><i class="fa fa-map-marker-alt me-2"></i> Lokasi</a>
  <a href="data_fasilitas.php"><i class="fa fa-building me-2"></i> Fasilitas</a>
  <a href="logs.php"><i class="fa fa-clipboard-list me-2"></i> Logs</a>
  <hr>
  <a href="../logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
</div>

<div class="content">
  <h2 class="fw-bold mb-4">Dashboard Admin</h2>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card p-4 text-center">
        <h5>Total Perumahan</h5>
        <h3 class="fw-bold">
          <?php
          $c=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS jml FROM housing"));
          echo $c['jml'];
          ?>
        </h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-4 text-center">
        <h5>Total Developer</h5>
        <h3 class="fw-bold">
          <?php
          $c=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS jml FROM developers"));
          echo $c['jml'];
          ?>
        </h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-4 text-center">
        <h5>Total Lokasi</h5>
        <h3 class="fw-bold">
          <?php
          $c=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS jml FROM locations"));
          echo $c['jml'];
          ?>
        </h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-4 text-center">
        <h5>Total Pengguna</h5>
        <h3 class="fw-bold">
          <?php
          $c=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS jml FROM users"));
          echo $c['jml'];
          ?>
        </h3>
      </div>
    </div>
  </div>
</div>

</body>
</html>
