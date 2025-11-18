<?php
include 'config.php';
session_start();

// 🔒 Cek apakah parameter ID tersedia
if (!isset($_GET['id'])) {
  die("❌ ID perumahan tidak ditemukan!");
}

$id = intval($_GET['id']);

// 🔍 Ambil data detail perumahan
$sql = "
  SELECT 
    h.*, 
    d.name AS developer_name, 
    l.name AS location_name
  FROM housing h
  LEFT JOIN developers d ON h.developer_id = d.id
  LEFT JOIN locations l ON h.location_id = l.id
  WHERE h.id = $id
";
$result = mysqli_query($conn, $sql);
if (!$result) die("Query gagal: " . mysqli_error($conn));
$data = mysqli_fetch_assoc($result);
if (!$data) die("❌ Data tidak ditemukan!");

// ❤️ Cek apakah user sudah login dan sudah memfavoritkan
$isFavorited = false;
$user_id = 0;

if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];

  // Jika $_SESSION['user'] adalah array, ambil id-nya
  if (is_array($user)) {
    $user_id = $user['id'];
  } else {
    // Jika hanya email yang tersimpan
    $cekUser = mysqli_query($conn, "SELECT id FROM users WHERE email='$user' LIMIT 1");
    $u = mysqli_fetch_assoc($cekUser);
    $user_id = $u ? $u['id'] : 0;
  }

  // Cek apakah user sudah menambahkan favorit
  $cekFav = mysqli_query($conn, "SELECT * FROM favorites WHERE user_id='$user_id' AND housing_id='$id'");
  $isFavorited = mysqli_num_rows($cekFav) > 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($data['name']) ?> | InfoPerumahan</title>

<!-- Bootstrap & Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body {
  font-family:'Poppins',sans-serif;
  background:#f5f7fa;
  color:#333;
}
header {
  background:#0a1b3c;
  color:white;
  padding:80px 0;
  text-align:center;
}
header h1 {
  font-family:'Playfair Display',serif;
  font-size:2.5rem;
  font-weight:700;
}
.property-img {
  border-radius:15px;
  overflow:hidden;
  box-shadow:0 5px 15px rgba(0,0,0,0.2);
}
.price {
  color:#f1c40f;
  font-size:1.5rem;
  font-weight:700;
}
.btn-fav {
  border:none;
  border-radius:10px;
  padding:10px 20px;
}
.btn-fav i {
  margin-right:6px;
}
footer {
  background:#0a1b3c;
  color:white;
  text-align:center;
  padding:40px 0;
  margin-top:60px;
}
</style>
</head>
<body>

<!-- 🔹 Header -->
<header>
  <h1><?= htmlspecialchars($data['name']) ?></h1>
  <p><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($data['location_name']) ?></p>
</header>

<!-- 🔹 Konten -->
<div class="container my-5">
  <div class="row g-4">
    <div class="col-md-6">
      <div class="property-img">
        <img src="assets/img/<?= !empty($data['image']) ? htmlspecialchars($data['image']) : 'default-house.jpg' ?>" class="img-fluid" alt="<?= htmlspecialchars($data['name']) ?>">
      </div>
    </div>
    <div class="col-md-6">
      <h3 class="fw-bold">Tentang Perumahan</h3>
      <p><?= nl2br(htmlspecialchars($data['description'])) ?></p>
      <hr>
      <p><strong>Developer:</strong> <?= htmlspecialchars($data['developer_name']) ?></p>
      <p class="price">Harga: Rp <?= number_format($data['price'], 0, ',', '.') ?></p>

      <!-- 🔹 Tombol Aksi -->
      <div class="mt-3">
        <a href="index.php" class="btn btn-secondary me-2">
          <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>

        <?php if (isset($_SESSION['user'])): ?>
          <?php if ($isFavorited): ?>
            <button class="btn btn-success btn-fav" disabled>
              <i class="fa-solid fa-heart"></i> Sudah di Favorit
            </button>
          <?php else: ?>
            <form action="user/add_favorite.php" method="POST" class="d-inline">
              <input type="hidden" name="housing_id" value="<?= $data['id'] ?>">
              <button type="submit" class="btn btn-dark btn-fav">
                <i class="fa-regular fa-heart"></i> Tambah ke Favorit
              </button>
            </form>
          <?php endif; ?>
        <?php else: ?>
          <form action="user/add_favorite.php" method="POST" class="d-inline">
  <input type="hidden" name="housing_id" value="<?= $data['id'] ?>">
  <button type="submit" class="btn btn-dark btn-fav">
    <i class="fa-regular fa-heart"></i> Tambah ke Favorit
  </button>
</form>

        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- 🔹 Footer -->
<footer>
  <p>&copy; <?= date('Y') ?> InfoPerumahan | Hunian Nyaman, Hidup Bahagia</p>
</footer>

<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({duration:1000, once:true});
</script>
</body>
</html>
