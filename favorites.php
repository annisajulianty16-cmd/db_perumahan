<?php
include '../config.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user'])) {
  header("Location: ../login.php");
  exit;
}

// Tangani kemungkinan $_SESSION['user'] adalah string (misal email)
if (is_array($_SESSION['user'])) {
  $user_id = $_SESSION['user']['id'];
} else {
  $email = $_SESSION['user'];
  $cekUser = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' LIMIT 1");
  $dataUser = mysqli_fetch_assoc($cekUser);
  $user_id = $dataUser ? $dataUser['id'] : 0;
}

// Ambil data favorit user dari tabel favorites
$sql = "
  SELECT 
    h.id AS housing_id, 
    h.name AS housing_name, 
    h.description, 
    h.price, 
    h.image, 
    l.name AS location_name
  FROM favorites f
  JOIN housing h ON f.housing_id = h.id
  LEFT JOIN locations l ON h.location_id = l.id
  WHERE f.user_id = '$user_id'
  ORDER BY f.id DESC
";
$query = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Favorit Saya | InfoPerumahan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body {
  font-family:'Poppins',sans-serif;
  background-color:#f5f7fa;
}
.navbar {
  background:#0a1b3c;
}
.property-card {
  background:#fff;
  border-radius:15px;
  box-shadow:0 4px 15px rgba(0,0,0,0.1);
  overflow:hidden;
  transition:.3s;
}
.property-card:hover {
  transform:translateY(-5px);
}
.property-card img {
  width:100%;
  height:200px;
  object-fit:cover;
}
.property-info {
  padding:15px;
}
.price {
  color:#f1c40f;
  font-weight:600;
}
.btn-dark {
  background:#0a1b3c;
  border:none;
}
.btn-dark:hover {
  background:#f1c40f;
  color:#000;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">
      <img src="../assets/img/logo.png" alt="Logo" height="40"> InfoPerumahan
    </a>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="../index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link active" href="favorites.php">Favorit</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">
  <h3 class="fw-bold text-center mb-4">Perumahan Favorit Saya</h3>
  <div class="row g-4">
  <?php if (mysqli_num_rows($query) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($query)): 
      $img = !empty($row['image']) ? '../assets/img/'.$row['image'] : '../assets/img/default-house.jpg'; ?>
      <div class="col-md-4">
        <div class="property-card">
          <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($row['housing_name']) ?>">
          <div class="property-info">
            <h5><?= htmlspecialchars($row['housing_name']) ?></h5>
            <p class="text-muted"><i class="fa-solid fa-location-dot text-danger"></i> <?= htmlspecialchars($row['location_name']) ?></p>
            <div class="price">Rp <?= number_format((int)$row['price'], 0, ',', '.') ?></div>
            <a href="../detail.php?id=<?= $row['housing_id'] ?>" class="btn btn-dark w-100 mt-2">Lihat Detail</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-center text-muted">Belum ada perumahan favorit yang disimpan.</p>
  <?php endif; ?>
  </div>
</div>

</body>
</html>
