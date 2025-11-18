<?php
session_start();
include '../config.php';

// Cek login admin
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

// fungsi bantu: cek jumlah data terkait di tabel lain (housing, perumahan)
function count_related($conn, $location_id) {
  $total = 0;

  // Cek tabel housing jika ada
  $q1 = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM housing WHERE location_id = " . (int)$location_id);
  if ($q1 && mysqli_num_rows($q1) > 0) {
    $r1 = mysqli_fetch_assoc($q1);
    $total += (int)$r1['c'];
  }

  // Cek tabel perumahan jika ada (beberapa project pakai nama ini)
  $q2 = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM perumahan WHERE location_id = " . (int)$location_id);
  if ($q2 && mysqli_num_rows($q2) > 0) {
    $r2 = mysqli_fetch_assoc($q2);
    $total += (int)$r2['c'];
  }

  return $total;
}

// Tambah lokasi
if (isset($_POST['tambah'])) {
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $insert = mysqli_query($conn, "INSERT INTO locations (name) VALUES ('$nama')");
  if ($insert) {
    $msg = "✅ Lokasi berhasil ditambahkan.";
    if (function_exists('catat_log')) catat_log($conn, 0, "Admin menambahkan lokasi: $nama");
  } else {
    $msg = "❌ Gagal menambah lokasi: " . mysqli_error($conn);
  }
}

// Edit lokasi
if (isset($_POST['edit'])) {
  $id = (int)$_POST['id'];
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $update = mysqli_query($conn, "UPDATE locations SET name='$nama' WHERE id=$id");
  if ($update) {
    $msg = "✅ Lokasi berhasil diperbarui.";
    if (function_exists('catat_log')) catat_log($conn, 0, "Admin mengubah lokasi ID $id menjadi: $nama");
  } else {
    $msg = "❌ Gagal memperbarui lokasi: " . mysqli_error($conn);
  }
}

// Hapus lokasi (aman) — cek terkait dulu
if (isset($_GET['hapus']) && !isset($_GET['force'])) {
  $id = (int)$_GET['hapus'];
  $related = count_related($conn, $id);

  if ($related > 0) {
    // Jika ada data terkait, jangan hapus langsung — beri opsi force
    $msg = "❗ Lokasi ID $id tidak bisa dihapus karena terdapat $related data terkait. "
         . "Gunakan tombol 'Hapus beserta data terkait' jika memang ingin menghapus semua data terkait.";
    // tampilkan informasi detail nanti di UI
    $show_force = true;
    $force_id = $id;
  } else {
    // tidak ada terkait — lakukan hapus normal
    $delete = mysqli_query($conn, "DELETE FROM locations WHERE id=$id");
    if ($delete) {
      $msg = "✅ Lokasi berhasil dihapus.";
      if (function_exists('catat_log')) catat_log($conn, 0, "Admin menghapus lokasi ID $id (tanpa data terkait).");
    } else {
      $msg = "❌ Gagal menghapus lokasi: " . mysqli_error($conn);
    }
  }
}

// Hapus paksa (force) — hapus data terkait dulu lalu lokasi, gunakan transaction
if (isset($_GET['hapus']) && isset($_GET['force']) && $_GET['force'] == '1') {
  $id = (int)$_GET['hapus'];

  // mulai transaksi
  mysqli_begin_transaction($conn);

  $ok = true;
  $errors = [];

  // Hapus dari housing jika tabel ada
  $q = @mysqli_query($conn, "SHOW TABLES LIKE 'housing'");
  if ($q && mysqli_num_rows($q) > 0) {
    if (!mysqli_query($conn, "DELETE FROM housing WHERE location_id = $id")) {
      $ok = false;
      $errors[] = "Gagal menghapus data dari 'housing': " . mysqli_error($conn);
    }
  }

  // Hapus dari perumahan jika tabel ada
  $q2 = @mysqli_query($conn, "SHOW TABLES LIKE 'perumahan'");
  if ($q2 && mysqli_num_rows($q2) > 0) {
    if (!mysqli_query($conn, "DELETE FROM perumahan WHERE location_id = $id")) {
      $ok = false;
      $errors[] = "Gagal menghapus data dari 'perumahan': " . mysqli_error($conn);
    }
  }

  // Lalu hapus lokasi
  if ($ok) {
    if (!mysqli_query($conn, "DELETE FROM locations WHERE id = $id")) {
      $ok = false;
      $errors[] = "Gagal menghapus data di 'locations': " . mysqli_error($conn);
    }
  }

  if ($ok) {
    mysqli_commit($conn);
    $msg = "✅ Lokasi dan semua data terkait berhasil dihapus.";
    if (function_exists('catat_log')) catat_log($conn, 0, "Admin menghapus lokasi ID $id beserta data terkait (force).");
  } else {
    mysqli_rollback($conn);
    $msg = "❌ Gagal melakukan penghapusan paksa: " . implode(" | ", $errors);
  }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Data Lokasi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
  <h3 class="fw-bold mb-3">📍 Data Lokasi</h3>
  <a href="index.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

  <?php if (isset($msg)): ?>
    <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <!-- Jika ada data terkait dan admin belum pilih force, tampilkan tombol force -->
  <?php if (!empty($show_force) && !empty($force_id)): ?>
    <div class="alert alert-warning">
      Lokasi ID <?= $force_id ?> memiliki data terkait. Jika Anda yakin ingin menghapus semua data yang terkait (akan menghapus entry di tabel terkait), klik:
      <a href="?hapus=<?= $force_id ?>&force=1" class="btn btn-danger btn-sm ms-2" onclick="return confirm('Yakin menghapus lokasi beserta semua data terkait? Tindakan ini tidak dapat dibatalkan.')">Hapus beserta data terkait</a>
      <a href="data_lokasi.php" class="btn btn-secondary btn-sm ms-2">Batal</a>
    </div>
  <?php endif; ?>

  <!-- Form tambah -->
  <form method="post" class="d-flex mb-4">
    <input type="text" name="nama" class="form-control me-2" placeholder="Nama lokasi baru..." required>
    <button type="submit" name="tambah" class="btn btn-dark">Tambah</button>
  </form>

  <!-- Tabel lokasi -->
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th width="60">ID</th>
        <th>Nama Lokasi</th>
        <th width="220">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $q = mysqli_query($conn, "SELECT * FROM locations ORDER BY id DESC");
      if (!$q) {
        echo "<tr><td colspan='3' class='text-danger'>Error mengambil data: " . mysqli_error($conn) . "</td></tr>";
      } elseif (mysqli_num_rows($q) == 0) {
        echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada data lokasi.</td></tr>";
      } else {
        while ($r = mysqli_fetch_assoc($q)) {
          // hitung relasi untuk ditampilkan singkat
          $cnt = count_related($conn, $r['id']);
          $badge = $cnt > 0 ? " <span class='badge bg-warning text-dark ms-2'>$cnt terkait</span>" : "";
          echo "<tr>
                  <td>{$r['id']}</td>
                  <td>" . htmlspecialchars($r['name']) . "$badge</td>
                  <td>
                    <a href='?edit_id={$r['id']}' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='?hapus={$r['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus lokasi ID {$r['id']}?')\">Hapus</a>
                  </td>
                </tr>";
        }
      }
      ?>
    </tbody>
  </table>

  <!-- Form Edit -->
  <?php
  if (isset($_GET['edit_id'])):
    $id = (int)$_GET['edit_id'];
    $data = mysqli_query($conn, "SELECT * FROM locations WHERE id=$id");
    if ($data && mysqli_num_rows($data) > 0):
      $loc = mysqli_fetch_assoc($data);
  ?>
  <hr>
  <h5 class="mt-4 fw-bold">✏️ Edit Lokasi</h5>
  <form method="post" class="mt-3">
    <input type="hidden" name="id" value="<?= $loc['id'] ?>">
    <div class="mb-3">
      <label>Nama Lokasi</label>
      <input type="text" name="nama" value="<?= htmlspecialchars($loc['name']) ?>" class="form-control" required>
    </div>
    <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="data_lokasi.php" class="btn btn-secondary">Batal</a>
  </form>
  <?php endif; endif; ?>

</div>
</body>
</html>
