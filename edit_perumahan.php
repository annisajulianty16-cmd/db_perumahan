<?php
include '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM housing WHERE id='$id'");
$data = mysqli_fetch_assoc($q);

if (isset($_POST['update'])) {
  $nama = $_POST['nama'];
  $deskripsi = $_POST['deskripsi'];
  $harga = $_POST['harga'];
  $developer = $_POST['developer'];
  $lokasi = $_POST['lokasi'];
  $gambar = $_FILES['gambar']['name'];
  $tmp = $_FILES['gambar']['tmp_name'];

  if ($gambar != '') {
    move_uploaded_file($tmp, "../assets/img/".$gambar);
    $update = ", image='$gambar'";
  } else {
    $update = "";
  }

  $sql = "UPDATE housing SET 
            name='$nama',
            description='$deskripsi',
            price='$harga',
            developer_id='$developer',
            location_id='$lokasi' 
            $update 
          WHERE id='$id'";
  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Data berhasil diperbarui!');window.location='index.php';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui data');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Perumahan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:'Poppins',sans-serif;background:#f5f7fa;}
.card{border:none;border-radius:15px;box-shadow:0 4px 20px rgba(0,0,0,0.1);}
.btn-dark{background:#0a1b3c;border:none;}
.btn-dark:hover{background:#f1c40f;color:#000;}
</style>
</head>
<body>
<div class="container mt-5">
  <div class="card p-4">
    <h4 class="fw-bold mb-3">Edit Data Perumahan</h4>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Nama Perumahan</label>
        <input type="text" name="nama" value="<?= $data['name'] ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="4" required><?= $data['description'] ?></textarea>
      </div>
      <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="harga" value="<?= $data['price'] ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Developer</label>
        <select name="developer" class="form-control" required>
          <option value="">--Pilih Developer--</option>
          <?php
          $dev=mysqli_query($conn,"SELECT * FROM developers");
          while($d=mysqli_fetch_assoc($dev)){
            $sel = ($d['id']==$data['developer_id'])?'selected':'';
            echo "<option value='{$d['id']}' $sel>{$d['name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="mb-3">
        <label>Lokasi</label>
        <select name="lokasi" class="form-control" required>
          <option value="">--Pilih Lokasi--</option>
          <?php
          $loc=mysqli_query($conn,"SELECT * FROM locations");
          while($l=mysqli_fetch_assoc($loc)){
            $sel = ($l['id']==$data['location_id'])?'selected':'';
            echo "<option value='{$l['id']}' $sel>{$l['name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="mb-3">
        <label>Gambar (Kosongkan jika tidak diubah)</label>
        <input type="file" name="gambar" class="form-control">
        <img src="../assets/img/<?= $data['image'] ?>" alt="" class="img-thumbnail mt-2" width="150">
      </div>
      <button type="submit" name="update" class="btn btn-dark w-100">Perbarui</button>
    </form>
  </div>
</div>
</body>
</html>
