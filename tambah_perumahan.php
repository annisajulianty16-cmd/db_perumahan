<?php
include '../config.php';
session_start();

// Cek login admin
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

// Ambil data dari database
$dev_query   = mysqli_query($conn, "SELECT * FROM developers ORDER BY name ASC");
$loc_query   = mysqli_query($conn, "SELECT * FROM locations ORDER BY name ASC");

// Proses simpan data
if (isset($_POST['simpan'])) {
  $nama        = mysqli_real_escape_string($conn, $_POST['nama']);
  $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);
  $developer   = intval($_POST['developer']);
  $lokasi      = intval($_POST['lokasi']);

  // Hilangkan karakter selain angka dari input harga
  $harga_input = preg_replace('/[^0-9]/', '', $_POST['harga']);
  $harga       = (int)$harga_input;

  // ============================
  // UPLOAD GAMBAR (VERSI LAMA)
  // ============================
  $gambar = $_FILES['gambar']['name'];
  $tmp    = $_FILES['gambar']['tmp_name'];

  if (!empty($gambar)) {
    $upload_path = "../assets/img/" . $gambar;
    move_uploaded_file($tmp, $upload_path);
  } else {
    $gambar = "default-house.jpg";
  }

  // ================================
  //  INSERT SESUAI STRUKTUR TABEL
  // ================================
  $sql = "INSERT INTO housing (name, description, price, image, developer_id, location_id)
          VALUES ('$nama', '$deskripsi', '$harga', '$gambar', '$developer', '$lokasi')";

  $q = mysqli_query($conn, $sql);

  if ($q) {
    echo "<script>alert('✅ Data perumahan berhasil disimpan!');window.location='index.php';</script>";
  } else {
    echo "<script>alert('❌ Gagal menyimpan data: " . mysqli_error($conn) . "');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Perumahan | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
  <h3 class="mb-3">Tambah Perumahan</h3>

  <form method="post" enctype="multipart/form-data">

    <!-- Nama -->
    <div class="mb-3">
      <label>Nama Perumahan</label>
      <input type="text" name="nama" class="form-control" required>
    </div>

    <!-- Deskripsi -->
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
    </div>

    <!-- Harga -->
    <div class="mb-3">
      <label>Harga</label>
      <input type="text" id="harga" name="harga" class="form-control" required>
    </div>

    <!-- Developer -->
    <div class="mb-3">
      <label>Developer</label>
      <select name="developer" class="form-control" required>
        <option value="">-- Pilih Developer --</option>
        <?php while ($d = mysqli_fetch_assoc($dev_query)): ?>
          <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <!-- Lokasi -->
    <div class="mb-3">
      <label>Lokasi</label>
      <select name="lokasi" class="form-control" required>
        <option value="">-- Pilih Lokasi --</option>
        <?php while ($l = mysqli_fetch_assoc($loc_query)): ?>
          <option value="<?= $l['id'] ?>"><?= $l['name'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <!-- Gambar -->
    <div class="mb-3">
      <label>Gambar</label>
      <input type="file" name="gambar" class="form-control" accept="image/*">
    </div>

    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>

  </form>
</div>

<!-- Script Format Rupiah -->
<script>
document.getElementById('harga').addEventListener('keyup', function(e) {
  var angka = this.value.replace(/[^,\d]/g, '').toString();
  var split = angka.split(',');
  var sisa  = split[0].length % 3;
  var rupiah = split[0].substr(0, sisa);
  var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

  if (ribuan) {
    var separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }

  rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
  this.value = 'Rp ' + rupiah;
});
</script>

</body>
</html>
