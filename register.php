<?php
include 'config.php';
session_start();

if (isset($_POST['register'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  // Cek apakah username sudah ada
  $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
  if (mysqli_num_rows($check) > 0) {
    $error = "Username sudah digunakan!";
  } else {
    $query = mysqli_query($conn, "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");
    if ($query) {
      $_SESSION['success'] = "Registrasi berhasil, silakan login.";
      header("Location: login.php");
      exit;
    } else {
      $error = "Gagal mendaftar. " . mysqli_error($conn);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | InfoPerumahan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body {
  font-family:'Poppins',sans-serif;
  background: linear-gradient(135deg, #0a1b3c, #182b63);
  height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
}
.card {
  background:#fff;
  border:none;
  border-radius:15px;
  box-shadow:0 6px 20px rgba(0,0,0,0.15);
  width:380px;
  padding:30px;
}
.btn-primary {
  background:#0a1b3c;
  border:none;
  transition:all .3s ease;
}
.btn-primary:hover {
  background:#f1c40f;
  color:#000;
  transform:translateY(-2px);
}
a {text-decoration:none;}
</style>
</head>
<body>

<div class="card">
  <h4 class="text-center fw-bold mb-3">Daftar Akun Baru</h4>
  <?php if(isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" name="register" class="btn btn-primary w-100">Daftar Sekarang</button>
  </form>
  <p class="text-center mt-3">Sudah punya akun? <a href="login.php" class="text-decoration-none fw-semibold text-dark">Login</a></p>
</div>

</body>
</html>
