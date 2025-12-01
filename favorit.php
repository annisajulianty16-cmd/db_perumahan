<?php
include 'config.php';
session_start();

// 🔒 Cek login
if (!isset($_SESSION['user'])) {
  echo "<script>alert('Silakan login terlebih dahulu!'); window.location='login.php';</script>";
  exit;
}

$user_id = intval($_SESSION['user']);

// 🔍 Ambil data favorit user
$sql = "
  SELECT f.id AS fav_id, h.id AS housing_id, h.name, h.price, h.image, l.name AS location_name
  FROM favorites f
  JOIN housing h ON f.housing_id = h.id
  LEFT JOIN locations l ON h.location_id = l.id
  WHERE f.user_id = '$user_id'
  ORDER BY f.created_at DESC
";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Favorit | InfoPerumahan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {font-family:'Poppins',sans-serif;background:#f5f7fa;}
.card {border:none;box-shadow:0 3px 10px rgba(0,0,0,0.1);border-radius:15px;overflow:hidden;}
.price {color:#f1c40f;font-weight:600;}
</style>
</head>
<body>

<div class="container py-5">
  <h2 class="mb-4 text-center fw-bold">🏠 Daftar Favorit Anda</h2>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="row g-4">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-4">
          <div class="card">
            <img src="assets/img/<?= htmlspecialchars($row['image'] ?: 'default-house.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
              <p><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($row['location_name'] ?: 'Tidak diketahui') ?></p>
              <p class="price">Rp <?= number_format($row['price'], 0, ',', '.') ?></p>
              <a href="detail.php?id=<?= $row['housing_id'] ?>" class="btn btn-dark w-100">
                <i class="fa-solid fa-circle-info"></i> Detail
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">Belum ada perumahan yang ditambahkan ke favorit.</div>
  <?php endif; ?>
</div>

</body>
</html>
