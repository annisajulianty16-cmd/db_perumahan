<?php
session_start();
include 'config.php';

// Jika form login dikirim
if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  // 🔑 Login default untuk admin
  if ($username === "admin" && $password === "admin123") {
    $_SESSION['admin'] = true;

    // Catat log untuk admin login
    catat_log($conn, 0, "Admin login ke sistem");

    header("Location: admin/index.php");
    exit;
  }

  // 🔍 Cek login user biasa dari tabel users
  $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");

  if ($query && mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);
    $_SESSION['user'] = $user['id'];

    // Catat log user login
    catat_log($conn, $user['id'], "Login ke sistem");

    header("Location: index.php");
    exit;
  } else {
    $error = "❌ Username atau password salah!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | InfoPerumahan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body {
  font-family:'Poppins',sans-serif;
  background:#f4f6fa;
}
.card {
  border:none;
  border-radius:15px;
  box-shadow:0 4px 20px rgba(0,0,0,0.1);
}
.btn-primary {
  background:#0a1b3c;
  border:none;
}
.btn-primary:hover {
  background:#f1c40f;
  color:#000;
}
</style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
<div class="card p-4" style="width:360px;">
  <h4 class="text-center fw-bold mb-3">Login ke InfoPerumahan</h4>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger text-center"><?= $error ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
  </form>

  <p class="text-center mt-3">
    Belum punya akun? <a href="register.php">Daftar</a>
  </p>
</div>
</body>
</html>
