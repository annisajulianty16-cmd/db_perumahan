<?php
include 'config.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user'];

// Ambil data user dari database berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id' LIMIT 1");

if (!$query) {
  die("Query Error: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($query);

// Jika user tidak ditemukan (misalnya dihapus)
if (!$user) {
  echo "<script>alert('Akun tidak ditemukan, silakan login kembali.'); window.location='logout.php';</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Saya | InfoPerumahan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background-color: #f5f7fa;
}
.navbar {
  background: #0a1b3c;
}
.card {
  border: none;
  border-radius: 20px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.btn-dark {
  background: #0a1b3c;
  border: none;
}
.btn-dark:hover {
  background: #f1c40f;
  color: #000;
}
.avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: #ccc url('assets/img/logo.png') center/contain no-repeat;
  margin: auto;
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      <img src="assets/img/logo.png" alt="Logo" height="40" class="me-2"> InfoPerumahan
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="favorites.php">Favorit</a></li>
        <li class="nav-item"><a class="nav-link active" href="profile.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Konten Profil -->
<div class="container py-5">
  <div class="card p-4 mx-auto" style="max-width:500px;">
    <div class="avatar mb-3"></div>
    <h4 class="fw-bold text-center mb-1"><?= htmlspecialchars($user['username']) ?></h4>
    <p class="text-center text-muted mb-4"><?= htmlspecialchars($user['email']) ?></p>
    <hr>
    <h6 class="fw-bold">Informasi Akun</h6>
    <ul class="list-group list-group-flush mb-3">
      <li class="list-group-item"><i class="fa fa-user me-2 text-secondary"></i> Username: <?= htmlspecialchars($user['username']) ?></li>
      <li class="list-group-item"><i class="fa fa-envelope me-2 text-secondary"></i> Email: <?= htmlspecialchars($user['email']) ?></li>
      <?php if (!empty($user['role'])): ?>
      <li class="list-group-item"><i class="fa fa-id-badge me-2 text-secondary"></i> Role: <?= htmlspecialchars($user['role']) ?></li>
      <?php endif; ?>
      <?php if (!empty($user['created_at'])): ?>
      <li class="list-group-item"><i class="fa fa-calendar me-2 text-secondary"></i> Terdaftar: <?= htmlspecialchars($user['created_at']) ?></li>
      <?php endif; ?>
    </ul>
    <div class="d-grid">
      <a href="logout.php" class="btn btn-dark">Logout</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
