<?php
include 'config.php';
session_start();

/* =========================
   LOGIKA PENCARIAN
   ========================= */
$where = "";
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
  $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
  $where = "WHERE h.name LIKE '%$keyword%'";
}

/* =========================
   AMBIL DATA PERUMAHAN
   ========================= */
$sql = "
  SELECT 
    h.id AS housing_id,
    h.name AS housing_name,
    h.description,
    h.price,
    h.image,
    d.name AS developer_name,
    l.name AS location_name
  FROM housing h
  LEFT JOIN developers d ON h.developer_id = d.id
  LEFT JOIN locations l ON h.location_id = l.id
  $where
  ORDER BY h.id DESC
  LIMIT 6
";
$query = mysqli_query($conn, $sql);

if (!$query) {
  die('❌ Query gagal dijalankan: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>InfoPerumahan | Hunian Premium Impian Anda</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
:root {
  --primary: #0a1b3c;
  --accent: #f1c40f;
  --light: #f8f9fa;
  --transition: all 0.4s ease-in-out;
}
body {
  font-family: 'Poppins', sans-serif;
  background-color: #f5f7fa;
  color: #333;
  scroll-behavior: smooth;
}

/* Navbar */
.navbar {
  background: rgba(10, 27, 60, 0.95);
  backdrop-filter: blur(10px);
  transition: var(--transition);
}
.navbar-brand img {
  height: 48px;
  width: auto;
  border-radius: 50%;
  filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.3));
}
.navbar-nav .nav-link {
  color: #fff !important;
  font-weight: 500;
  margin: 0 10px;
  transition: var(--transition);
}
.navbar-nav .nav-link:hover {
  color: var(--accent) !important;
  transform: scale(1.05);
}

/* Hero */
.hero {
  position: relative;
  height: 100vh;
  background: url('assets/img/banner.jpg') center/cover fixed no-repeat;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  text-align: center;
}
.hero::after {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom right, rgba(10, 27, 60, 0.85), rgba(0,0,0,0.3));
}
.hero-content {
  position: relative;
  z-index: 2;
  max-width: 720px;
  padding: 20px;
  animation: fadeUp 1.5s ease-in-out;
}
.hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: 3.5rem;
  font-weight: 700;
}
.hero p {
  color: #ddd;
  font-size: 1.2rem;
  margin: 20px 0 35px;
}
.search-box {
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(10px);
  border-radius: 50px;
  padding: 12px 18px;
  display: flex;
  gap: 10px;
  justify-content: center;
  flex-wrap: wrap;
  box-shadow: 0 8px 20px rgba(0,0,0,0.25);
}
.search-box input {
  border: none;
  outline: none;
  padding: 12px 18px;
  border-radius: 30px;
  flex: 1;
  min-width: 180px;
}
.search-box button {
  background: var(--accent);
  border: none;
  color: #000;
  font-weight: 600;
  padding: 12px 30px;
  border-radius: 30px;
  transition: var(--transition);
}
.search-box button:hover {
  background: #d4af37;
  transform: translateY(-2px);
}

/* Property Cards */
.property-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
  transition: var(--transition);
}
.property-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.25);
}
.property-card img {
  height: 240px;
  width: 100%;
  object-fit: cover;
}
.property-info {
  padding: 20px;
}
.price {
  color: var(--accent);
  font-weight: 700;
  font-size: 1.2rem;
}

/* CTA */
.parallax-section {
  background: url('assets/img/house1.jpg') center/cover fixed no-repeat;
  color: white;
  text-align: center;
  padding: 130px 20px;
  position: relative;
}
.parallax-section::after {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.55);
}
.parallax-section .content {
  position: relative;
  z-index: 2;
}

/* Footer */
footer {
  background: var(--primary);
  color: white;
  text-align: center;
  padding: 40px 0;
}
@keyframes fadeUp {
  from {opacity: 0; transform: translateY(30px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="assets/img/logo.png" alt="InfoPerumahan">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="favorit.php">Favorit</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profil</a></li>
        <?php if(isset($_SESSION['user']) || isset($_SESSION['admin'])): ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h1 data-aos="fade-up">Temukan Hunian Premium Impian Anda</h1>
    <p data-aos="fade-up" data-aos-delay="200">Eksplorasi berbagai perumahan eksklusif dengan lokasi strategis dan desain elegan.</p>
    <form class="search-box mx-auto" method="GET" action="index.php" data-aos="zoom-in" data-aos-delay="400">
      <input type="text" name="keyword" placeholder="Cari nama perumahan..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
      <button type="submit"><i class="fa fa-search"></i> Cari</button>
    </form>
  </div>
</section>

<!-- Daftar Perumahan -->
<div class="container my-5" id="daftar">
  <h2 class="section-title text-center mb-4" data-aos="fade-up">
    <?= isset($_GET['keyword']) && $_GET['keyword'] != '' ? "Hasil pencarian: " . htmlspecialchars($_GET['keyword']) : "Perumahan Terbaru" ?>
  </h2>
  <div class="row g-4">
    <?php if(mysqli_num_rows($query) > 0): ?>
      <?php while($row = mysqli_fetch_assoc($query)): 
        $img = !empty($row['image']) ? 'assets/img/'.$row['image'] : 'assets/img/default-house.jpg'; ?>
        <div class="col-md-4" data-aos="zoom-in">
          <div class="property-card">
            <img src="<?= $img ?>" alt="<?= htmlspecialchars($row['housing_name']) ?>">
            <div class="property-info">
              <h5><?= htmlspecialchars($row['housing_name']) ?></h5>
              <p><i class="fa-solid fa-location-dot text-danger"></i> <?= htmlspecialchars($row['location_name']) ?></p>
              <small class="text-muted"><i class="fa-solid fa-user-tie"></i> <?= htmlspecialchars($row['developer_name']) ?></small>
              <div class="price mt-2">Rp <?= number_format($row['price'],0,',','.') ?></div>
              <a href="detail.php?id=<?= $row['housing_id'] ?>" class="btn btn-sm btn-outline-dark mt-3">Lihat Detail</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center text-muted">Belum ada data perumahan.</p>
    <?php endif; ?>
  </div>
</div>

<!-- CTA -->
<section class="parallax-section">
  <div class="content" data-aos="fade-up">
    <h2 class="fw-bold mb-3">Temukan Hunian Dengan Nilai Investasi Terbaik</h2>
    <p class="mb-4">Kami membantu Anda menemukan perumahan ideal dengan kemudahan pencarian dan informasi terpercaya.</p>
    <a href="register.php" class="btn btn-light btn-lg fw-semibold"><i class="fa fa-home me-2"></i>Mulai Sekarang</a>
  </div>
</section>

<!-- Footer -->
<footer>
 <footer class="mt-5" style="background:#0a1b3c; color:white; padding:50px 0;">
  <div class="container">
    <div class="row">

      <!-- Tentang -->
      <div class="col-md-4 mb-4">
        <h5 class="fw-bold mb-3">Tentang InfoPerumahan</h5>
        <p style="line-height:1.7;">
          InfoPerumahan adalah platform pencarian hunian modern yang membantu Anda
          menemukan perumahan terbaik dengan informasi lengkap, akurat, dan terpercaya.
        </p>
      </div>

      <!-- Kontak -->
      <div class="col-md-4 mb-4">
        <h5 class="fw-bold mb-3">Kontak Kami</h5>
        <p><i class="fa-solid fa-phone me-2"></i> 0831-7035-1783</p>
        <p><i class="fa-solid fa-envelope me-2"></i> support@infoperumahan.com</p>
        <p><i class="fa-solid fa-location-dot me-2"></i> Bangka Belitung, Indonesia</p>
      </div>

      <!-- Sosial Media -->
      <div class="col-md-4 mb-4">
        <h5 class="fw-bold mb-3">Ikuti Kami</h5>
        <a href="https://www.instagram.com/infoperumahan01?igsh=OTNmNGt2dWF5b3Yx&utm_source=qr" class="text-white d-block mb-2">
          <i class="fa-brands fa-instagram me-2"></i>@infoperumahan01
        </a>
        <a href="https://www.tiktok.com/@ga_sukabubur?_r=1&_t=ZS-91fDXkBiKcp" class="text-white d-block mb-2">
          <i class="fa-brands fa-tiktok me-2"></i> TikTok
        </a>
      </div>

    </div>

    <hr style="border-color:rgba(255,255,255,0.2);">

    <p class="text-center mt-3 mb-0" style="font-size:14px;">
      &copy; <?= date('Y') ?> InfoPerumahan — Hunian Nyaman, Hidup Bahagia
    </p>
  </div>
</footer>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({duration:1000, once:true});</script>
</body>
</html>