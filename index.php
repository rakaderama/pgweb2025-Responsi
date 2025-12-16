<?php
include 'config/koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$jenis_warna = [
  'RSUD'            => 'red',
  'RS Mata'         => 'green',
  'RS Jiwa'         => 'violet',
  'RS Bedah'        => 'black',
  'RS Gigi & Mulut' => 'orange',
  'RSIA'            => 'yellow',
  'RS'              => 'blue'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>WebGIS Fasilitas Kesehatan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
html, body { height: 100%; margin: 0; }
body { display: flex; flex-direction: column; }
main { flex: 1; }

#map { height: 600px; width: 100%; }

.table-wrapper {
  border: 1px solid #dee2e6;
  border-radius: 8px;
  padding: 15px;
  background: #ffffff;
}

.table-scroll {
  max-height: 350px;
  overflow-y: auto;
}

.legend {
  background: white;
  padding: 10px;
  border-radius: 5px;
  font-size: 13px;
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
}

.legend img { width: 18px; margin-right: 5px; }

table th {
  background-color: #198754;
  color: white;
}

footer {
  background-color: #212529;
  color: #ccc;
  font-size: 12px;
  padding: 6px;
  text-align: center;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-map-marked-alt"></i>
      WebGIS Fasilitas Kesehatan Kota Yogyakarta
    </a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
      <li class="nav-item"><a class="nav-link" href="data.php">Data</a></li>
    </ul>
  </div>
</nav>

<main>

<div class="container-fluid mt-3">
  <h5><i class="fas fa-map"></i> Peta Persebaran Fasilitas Kesehatan</h5>
  <div id="map"></div>
</div>

<div class="container mt-4">
  <h5><i class="fas fa-table"></i> Data Fasilitas Kesehatan</h5>

  <div class="table-wrapper">
    <div class="table-responsive table-scroll">
      <table class="table table-bordered table-striped mb-0">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Alamat</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $q = mysqli_query($koneksi, "SELECT * FROM fasilitas_kesehatan");
        while ($d = mysqli_fetch_assoc($q)) {
          echo "<tr>
            <td>
              <a href='javascript:void(0)'
                 onclick='zoomToMarker(".$d['id'].")'
                 class='fw-semibold text-decoration-none'>
                ".htmlspecialchars($d['nama'])."
              </a>
            </td>
            <td>".htmlspecialchars($d['jenis'])."</td>
            <td>".htmlspecialchars($d['alamat'])."</td>
          </tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</main>

<footer>
  &copy; 2025 WebGIS Fasilitas Kesehatan
</footer>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

var map = L.map('map', {
  center: [-7.8, 110.37],
  zoom: 13,
  layers: [osm]
});

map.createPane('paneWMS');
map.getPane('paneWMS').style.zIndex = 400;

map.createPane('paneMarker');
map.getPane('paneMarker').style.zIndex = 650;

var wmsLayer = L.tileLayer.wms("http://localhost:8080/geoserver/wms", {
  layers: 'kesehatan:ADMINISTRASIDESA_AR_25K',
  format: 'image/png',
  transparent: true,
  pane: 'paneWMS'
});

var iconCache = {};
function getIcon(color) {
  if (!iconCache[color]) {
    iconCache[color] = new L.Icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-' + color + '.png',
      shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34]
    });
  }
  return iconCache[color];
}

var markerLayer = L.layerGroup({ pane: 'paneMarker' });
var markerMap = {};

<?php
$q = mysqli_query($koneksi, "SELECT * FROM fasilitas_kesehatan");
while ($d = mysqli_fetch_assoc($q)) {

  if (!$d['latitude'] || !$d['longitude']) continue;

  $warna = 'grey';
  foreach ($jenis_warna as $key => $val) {
    if (stripos($d['jenis'], $key) !== false) {
      $warna = $val;
      break;
    }
  }

  echo "
  var marker = L.marker(
    [{$d['latitude']}, {$d['longitude']}],
    { icon: getIcon('$warna'), pane: 'paneMarker' }
  )
  .bindPopup('<b>".addslashes($d['nama'])."</b><br>".addslashes($d['jenis'])."<br>".addslashes($d['alamat'])."')
  .addTo(markerLayer);

  markerMap[".$d['id']."] = marker;
  ";
}
?>

markerLayer.addTo(map);
wmsLayer.addTo(map);

L.control.layers(
  { 'OpenStreetMap': osm },
  {
    'Batas Wilayah (WMS)': wmsLayer,
    'Fasilitas Kesehatan': markerLayer
  },
  { collapsed: false }
).addTo(map);

function zoomToMarker(id) {
  if (markerMap[id]) {
    map.setView(markerMap[id].getLatLng(), 17, { animate: true });
    markerMap[id].openPopup();
  }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
