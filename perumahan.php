<?php
include '../config.php';
session_start();

// Cek akses admin
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

// Ambil data perumahan
$query = mysqli_query($conn, "
  SELECT h.*, d.name AS developer, l.name AS lokasi 
  FROM housing h
  LEFT JOIN developers d ON h.developer_id = d.id
  LEFT JOIN locations l ON h.location_id = l.id
  ORDER BY h.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Perumahan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">
  <h3 class="mb-4">📋 Data Perumahan Masuk</h3>

  <a href="tambah_perumahan.php" class="btn btn-primary mb-3">
    + Tambah Perumahan
  </a>

  <div class="card shadow-sm">
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Nama Perumahan</th>
            <th>Developer</th>
            <th>Lokasi</th>
            <th>Harga</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php 
        $no = 1;
        while ($row = mysqli_fetch_assoc($query)) : ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['developer']) ?></td>
            <td><?= htmlspecialchars($row['lokasi']) ?></td>
            <td>Rp <?= number_format($row['price'],0,',','.') ?></td>
            <td>
              <img src="../assets/img/<?= $row['image'] ?>" width="90" class="rounded">
            </td>
            <td>
              <a href="edit_perumahan.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="hapus_perumahan.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
