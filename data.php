<?php
include 'config/koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Fasilitas Kesehatan</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    main {
      flex: 1;
    }
    table th {
      background-color: #198754;
      color: white;
    }
    footer {
      background-color: #212529;
      color: #ccc;
      font-size: 12px;
      padding: 6px 0;
      text-align: center;
    }
  </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-map-marked-alt"></i> WebGIS Fasilitas Kesehatan
    </a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
      <li class="nav-item"><a class="nav-link active" href="data.php">Data</a></li>
    </ul>
  </div>
</nav>

<main>
<div class="container mt-4">

  <!-- HEADER + BUTTON -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">
      <i class="fas fa-table"></i> Data Fasilitas Kesehatan
    </h4>

    <div>
      <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus"></i> Tambah
      </button>

      <button class="btn btn-danger" onclick="hapusData()">
        <i class="fas fa-trash"></i> Hapus
      </button>
    </div>
  </div>

  <!-- TABEL -->
  <form id="formHapus" method="POST" action="hapus.php">
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr>
            <th width="40" class="text-center">
              <input type="checkbox" onclick="toggle(this)">
            </th>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Alamat</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $q = mysqli_query($koneksi, "SELECT * FROM fasilitas_kesehatan ORDER BY id DESC");
          while ($d = mysqli_fetch_assoc($q)) {
          ?>
          <tr>
            <td class="text-center">
              <input type="checkbox" name="id[]" value="<?= $d['id'] ?>">
            </td>
            <td><?= htmlspecialchars($d['nama']) ?></td>
            <td><?= htmlspecialchars($d['jenis']) ?></td>
            <td><?= htmlspecialchars($d['alamat']) ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </form>

</div>
</main>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="simpan.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Fasilitas Kesehatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input name="nama" class="form-control mb-2" placeholder="Nama" required>
        <input name="jenis" class="form-control mb-2" placeholder="Jenis" required>
        <textarea name="alamat" class="form-control mb-2" placeholder="Alamat"></textarea>
        <input name="latitude" class="form-control mb-2" placeholder="Latitude">
        <input name="longitude" class="form-control mb-2" placeholder="Longitude">
      </div>
      <div class="modal-footer">
        <button class="btn btn-success">
          <i class="fas fa-save"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- FOOTER -->
<footer>
  &copy; 2025 WebGIS Fasilitas Kesehatan
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggle(source) {
  const checkboxes = document.querySelectorAll('input[name="id[]"]');
  checkboxes.forEach(cb => cb.checked = source.checked);
}

function hapusData() {
  const checked = document.querySelectorAll('input[name="id[]"]:checked');
  if (checked.length === 0) {
    alert('Pilih minimal satu data untuk dihapus!');
    return;
  }
  if (confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')) {
    document.getElementById('formHapus').submit();
  }
}
</script>

</body>
</html>
