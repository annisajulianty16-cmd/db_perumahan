<?php
include '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT h.*, d.name AS developer_name, l.name AS location_name
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
<style>
.table img { width: 70px; height: 50px; object-fit: cover; border-radius:6px; }
</style>
</head>
<body>

<div class="content" style="margin-left:260px;padding:30px;">
    <h3 class="fw-bold mb-4">Data Perumahan</h3>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Developer</th>
                <th>Lokasi</th>
                <th>Harga</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

        <?php $no = 1; while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['developer_name']) ?></td>
                <td><?= htmlspecialchars($row['location_name']) ?></td>
                <td>Rp <?= number_format($row['price'],0,',','.') ?></td>
                <td>
                    <img src="../assets/img/<?= $row['image'] ?: 'default-house.jpg' ?>" alt="">
                </td>
                <td>
                    <a href="edit_perumahan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="hapus_perumahan.php?id=<?= $row['id'] ?>"
                       onclick="return confirm('Yakin ingin menghapus data ini?')"
                       class="btn btn-sm btn-danger">
                       Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>

        </tbody>
    </table>
</div>

</body>
</html>
