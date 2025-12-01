<?php
session_start();
include '../config.php';

// 🔒 Cek login admin
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logs Aktivitas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
  <h3 class="fw-bold mb-4">📜 Riwayat Aktivitas Pengguna</h3>
  <a href="index.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th width="180">Tanggal</th>
        <th>Aktivitas</th>
        <th width="200">Pengguna</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // 🧾 Ambil data logs dan gabungkan dengan username dari users
      $q = mysqli_query($conn, "
        SELECT l.*, u.username AS user_name
        FROM logs l
        LEFT JOIN users u ON l.user_id = u.id
        ORDER BY l.created_at DESC
      ");

      if (!$q) {
        echo "<tr><td colspan='3' class='text-danger'>Gagal mengambil data logs: " . mysqli_error($conn) . "</td></tr>";
      } elseif (mysqli_num_rows($q) == 0) {
        echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada aktivitas tercatat.</td></tr>";
      } else {
        while ($r = mysqli_fetch_assoc($q)) {
          echo "<tr>
                  <td>{$r['created_at']}</td>
                  <td>{$r['action']}</td>
                  <td>" . htmlspecialchars($r['user_name'] ?? 'Tidak diketahui') . "</td>
                </tr>";
        }
      }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
