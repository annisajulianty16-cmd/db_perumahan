<?php
include '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

// 🟢 Tambah developer baru
if (isset($_POST['tambah'])) {
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  if (!empty($nama)) {
    mysqli_query($conn, "INSERT INTO developers (name) VALUES ('$nama')");
  }
  header("Location: data_developer.php");
  exit;
}

// 🟡 Update data developer
if (isset($_POST['update'])) {
  $id = intval($_POST['id']);
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  mysqli_query($conn, "UPDATE developers SET name='$nama' WHERE id=$id");
  header("Location: data_developer.php");
  exit;
}

// 🔴 Hapus data developer
if (isset($_GET['hapus'])) {
  $id = intval($_GET['hapus']);

  // Cek apakah developer masih digunakan di tabel housing
  $cek = mysqli_query($conn, "SELECT * FROM housing WHERE developer_id=$id");
  if (mysqli_num_rows($cek) > 0) {
    $error = "❌ Tidak dapat menghapus developer karena masih digunakan di data perumahan.";
  } else {
    mysqli_query($conn, "DELETE FROM developers WHERE id=$id");
    $success = "✅ Developer berhasil dihapus.";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Developer</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
  <h3 class="fw-bold mb-3">👷 Data Developer</h3>
  <a href="index.php" class="btn btn-secondary mb-3">← Kembali</a>

  <?php if(isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php elseif(isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <!-- Form Tambah Developer -->
  <form method="post" class="d-flex mb-3">
    <input type="text" name="nama" class="form-control me-2" placeholder="Nama developer baru..." required>
    <button type="submit" name="tambah" class="btn btn-dark">Tambah</button>
  </form>

  <!-- Tabel Data Developer -->
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th width="60">ID</th>
        <th>Nama Developer</th>
        <th width="200">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $q = mysqli_query($conn, "SELECT * FROM developers ORDER BY id DESC");
      if (mysqli_num_rows($q) == 0) {
        echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada data developer.</td></tr>";
      }
      while ($r = mysqli_fetch_assoc($q)) {
        echo "
        <tr>
          <td>{$r['id']}</td>
          <td>{$r['name']}</td>
          <td>
            <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$r['id']}'>Edit</button>
            <a href='?hapus={$r['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus developer ini?\")'>Hapus</a>
          </td>
        </tr>

        <!-- Modal Edit -->
        <div class='modal fade' id='editModal{$r['id']}' tabindex='-1'>
          <div class='modal-dialog'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h5 class='modal-title'>Edit Developer</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
              </div>
              <form method='post'>
                <div class='modal-body'>
                  <input type='hidden' name='id' value='{$r['id']}'>
                  <div class='mb-3'>
                    <label>Nama Developer</label>
                    <input type='text' name='nama' value='" . htmlspecialchars($r['name'], ENT_QUOTES) . "' class='form-control' required>
                  </div>
                </div>
                <div class='modal-footer'>
                  <button type='submit' name='update' class='btn btn-primary'>Simpan Perubahan</button>
                  <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        ";
      }
      ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
