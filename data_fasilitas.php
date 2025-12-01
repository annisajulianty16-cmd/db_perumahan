<?php
include '../config.php';
session_start();

// Cek login admin
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

// Proses tambah fasilitas baru
if (isset($_POST['tambah'])) {
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $query = mysqli_query($conn, "INSERT INTO facilities (name) VALUES ('$nama')");
  
  if ($query) {
    echo "<script>alert('✅ Fasilitas berhasil ditambahkan!');window.location='data_fasilitas.php';</script>";
  } else {
    echo "<script>alert('❌ Gagal menambahkan fasilitas: " . mysqli_error($conn) . "');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Fasilitas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h3 class="fw-bold mb-3">Data Fasilitas</h3>
    <a href="index.php" class="btn btn-secondary mb-3">← Kembali</a>

    <!-- Form Tambah Fasilitas -->
    <form method="post" class="d-flex mb-3">
      <input type="text" name="nama" class="form-control me-2" placeholder="Nama fasilitas baru..." required>
      <button type="submit" name="tambah" class="btn btn-dark">Tambah</button>
    </form>

    <!-- Tabel Data -->
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th width="10%">ID</th>
          <th>Nama Fasilitas</th>
          <th width="25%">Aksi</th>
        </tr>
      </thead>
      <tbody>

        <?php
        $q = mysqli_query($conn, "SELECT * FROM facilities ORDER BY id DESC");

        // 🔧 FIX ERROR: Jika query gagal, tampilkan pesan aman
        if (!$q) {
            echo "<tr><td colspan='3' class='text-danger text-center'>
                    Terjadi kesalahan query: " . htmlspecialchars(mysqli_error($conn)) . "
                  </td></tr>";
        } else if (mysqli_num_rows($q) > 0) {

          while ($r = mysqli_fetch_assoc($q)) {
            echo "
            <tr>
              <td>{$r['id']}</td>
              <td>" . htmlspecialchars($r['name']) . "</td>
              <td>
                <a href='edit_fasilitas.php?id={$r['id']}' class='btn btn-warning btn-sm me-1'>Edit</a>
                <a href='hapus_fasilitas.php?id={$r['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus fasilitas ini?');\">Hapus</a>
              </td>
            </tr>";
          }

        } else {
          echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada data fasilitas.</td></tr>";
        }
        ?>

      </tbody>
    </table>
  </div>
</body>
</html>
