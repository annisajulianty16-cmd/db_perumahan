<?php
include '../config.php';
session_start();

// Cek login admin
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

// Cek apakah ada ID di URL
if (!isset($_GET['id'])) {
  header("Location: data_fasilitas.php");
  exit;
}

$id = intval($_GET['id']);
$q  = mysqli_query($conn, "SELECT * FROM facilities WHERE id = $id");
$data = mysqli_fetch_assoc($q);

if (!$data) {
  echo "<script>
    alert('❌ Data fasilitas tidak ditemukan!');
    window.location='data_fasilitas.php';
  </script>";
  exit;
}

// Proses update data
if (isset($_POST['update'])) {
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $update = mysqli_query($conn, "UPDATE facilities SET name='$nama' WHERE id=$id");

  if ($update) {
    echo "<script>
      alert('✅ Data fasilitas berhasil diperbarui!');
      window.location='data_fasilitas.php';
    </script>";
  } else {
    echo "<script>
      alert('❌ Gagal memperbarui data: " . mysqli_error($conn) . "');
    </script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Fasilitas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container" style="max-width:600px;">
    <h3 class="fw-bold mb-3">Edit Data Fasilitas</h3>
    <a href="data_fasilitas.php" class="btn btn-secondary mb-3">← Kembali</a>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nama Fasilitas</label>
        <input type="text" name="nama" class="form-control"
               value="<?= htmlspecialchars($data['name']) ?>" required>
      </div>
      <button type="submit" name="update" class="btn btn-dark w-100">Simpan Perubahan</button>
    </form>
  </div>
</body>
</html>
