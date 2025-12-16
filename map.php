<?php
include 'config/koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peta Fasilitas Kesehatan</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
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
    footer {
      margin-top: auto;
    }
    #map {
      height: 600px;
      width: 100%;
    }
    .legend {
      background: white;
      padding: 10px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      font-size: 14px;
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
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="data.php">Data</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container-fluid mt-3">
  <h4 class="mb-3">
    <i class="fas fa-map"></i> Peta Persebaran Fasilitas Kesehatan
  </h4>
  <div id="map"></div>
</div>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-3 mt-4">
  &copy; 2025 WebGIS Fasilitas Kesehatan
</footer>

<!-- JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
/* =====================
   INISIALISASI MAP
===================== */
var map = L.map('map').setView([-7.8, 110.37], 11);

/* BASEMAP */
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap'
}).addTo(map);

/* WMS GEOSERVER */
L.tileLayer.wms('http://localhost:8080/geoserver/wms', {
  layers: 'kesehatan:batas_admin',
  format: 'image/png',
  transparent: true
}).addTo(map);

/* =====================
   ICON MARKER
===================== */
function createIcon(color) {
  return new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-' + color + '.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34]
  });
}

var iconRS        = createIcon('red');
var iconPuskesmas = createIcon('blue');
var iconKlinik    = createIcon('green');
var iconApotek    = createIcon('violet');
var iconDefault   = createIcon('grey');

/* MARKER DARI DATABASE */
<?php
$jenis_list = [];
$q = mysqli_query($koneksi, "SELECT DISTINCT jenis FROM fasilitas_kesehatan");
while ($row = mysqli_fetch_assoc($q)) {
  $jenis_list[] = $row['jenis'];
}

$data = mysqli_query($koneksi, "SELECT * FROM fasilitas_kesehatan");
while ($d = mysqli_fetch_assoc($data)) {

  if (!empty($d['latitude']) && !empty($d['longitude'])) {

    $icon = "iconDefault";

    if (stripos($d['jenis'], 'Rumah Sakit') !== false) {
      $icon = "iconRS";
    } elseif (stripos($d['jenis'], 'Puskesmas') !== false) {
      $icon = "iconPuskesmas";
    } elseif (stripos($d['jenis'], 'Klinik') !== false) {
      $icon = "iconKlinik";
    } elseif (stripos($d['jenis'], 'Apotek') !== false) {
      $icon = "iconApotek";
    }

    echo "L.marker(
      [{$d['latitude']}, {$d['longitude']}],
      {icon: $icon}
    ).addTo(map)
     .bindPopup('<b>" . addslashes($d['nama']) . "</b><br>"
     . addslashes($d['jenis']) . "<br>"
     . addslashes($d['alamat']) . "');";
  }
}
?>

/* LEGENDA */
var legend = L.control({position: 'bottomright'});
legend.onAdd = function () {
  var div = L.DomUtil.create('div', 'legend');
  div.innerHTML = '<b>Legenda</b><br>';
  <?php
  foreach ($jenis_list as $jenis) {
    $color = 'grey'; // default
    if (stripos($jenis, 'Rumah Sakit') !== false) $color = 'red';
    elseif (stripos($jenis, 'Puskesmas') !== false) $color = 'blue';
    elseif (stripos($jenis, 'Klinik') !== false) $color = 'green';
    elseif (stripos($jenis, 'Apotek') !== false) $color = 'violet';
    
    echo "div.innerHTML += '<img src=\"https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-$color.png\" /> $jenis<br>';";
  }
  ?>
  return div;
};
legend.addTo(map);

/* SKALA */
L.control.scale().addTo(map);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
