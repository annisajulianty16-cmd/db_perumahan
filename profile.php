<?php
session_start();
include 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

// Deteksi format session (id langsung atau array data)
if (is_array($_SESSION['user'])) {
  $user_id = $_SESSION['user']['id'] ?? null;
} else {
  $user_id = $_SESSION['user'];
}

// Jika tidak ada ID user di session
if (!$user_id) {
  echo "Session tidak berisi ID pengguna yang valid.";
  exit;
}

// Ambil data user dari database
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
if (!$query) {
  die("Kesalahan query: " . mysqli_error($conn));
}

if (mysqli_num_rows($query) == 0) {
  echo "Data pengguna tidak ditemukan.";
  exit;
}

$user = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Pengguna | InfoPerumahan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f4f6fa;
    }
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .btn-primary {
      background: #0a1b3c;
      border: none;
    }
    .btn-primary:hover {
      background: #f1c40f;
      color: #000;
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
  <div class="card p-4" style="width:400px;">
    <h4 class="text-center fw-bold mb-3">Profil Pengguna</h4>

    <div class="mb-3">
      <label class="fw-semibold">Username:</label>
      <div class="form-control bg-light"><?= htmlspecialchars($user['username']) ?></div>
    </div>

    <div class="mb-3">
      <label class="fw-semibold">Email:</label>
      <div class="form-control bg-light"><?= htmlspecialchars($user['email']) ?></div>
    </div>

    <div class="mb-3">
      <label class="fw-semibold">Role:</label>
      <div class="form-control bg-light"><?= htmlspecialchars($user['role']) ?></div>
    </div>

    <div class="d-flex justify-content-between mt-4">
      <a href="index.php" class="btn btn-secondary">Kembali</a>
      <a href="logout.php" class="btn btn-primary">Logout</a>
    </div>
  </div>
</body>
</html>
