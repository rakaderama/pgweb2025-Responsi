Nama Produk: WebGIS Fasilitas Kesehatan Kota Yogyakarta

Deskripsi: WebGIS ini adalah aplikasi berbasis web yang menampilkan peta interaktif persebaran fasilitas kesehatan di Kota Yogyakarta. Pengguna dapat melihat lokasi Rumah Sakit, Puskesmas, Klinik, dan fasilitas kesehatan lain secara visual pada peta, serta mengakses data detail melalui tabel. WebGIS ini memudahkan masyarakat dan pemangku kebijakan untuk mengetahui distribusi fasilitas kesehatan secara spasial dan atributif.

Teknologi Pembangun Produk:

Frontend:
HTML5, CSS3 (Bootstrap 5), JavaScript
Leaflet.js untuk peta interaktif
Font Awesome untuk ikon

Backend:
PHP (Native, tanpa framework)
MySQL/MariaDB untuk database

Lainnya:
XAMPP sebagai web server lokal
GeoServer (opsional, untuk layer WMS batas wilayah)

Sumber Data:

Database:
Data fasilitas kesehatan diambil dari tabel fasilitas_kesehatan pada database MySQL, yang berisi kolom: id, nama, jenis, alamat, latitude, dan longitude.

Peta Dasar:
OpenStreetMap (melalui tile server Leaflet)

Batas Wilayah:
Layer WMS dari GeoServer (opsional, jika tersedia)

<img width="1919" height="1151" alt="image" src="https://github.com/user-attachments/assets/246ec469-3721-4b47-b45b-677c79b9a528" />

<img width="1919" height="1090" alt="image" src="https://github.com/user-attachments/assets/7fcd157a-6577-488c-bccb-e94c2410dcf3" />

<img width="1918" height="1092" alt="image" src="https://github.com/user-attachments/assets/0b2eb653-e903-45df-826a-6771989c9a57" />

<img width="1916" height="1092" alt="image" src="https://github.com/user-attachments/assets/367d57d1-5683-4552-a990-5f40f2d8b3c1" />
