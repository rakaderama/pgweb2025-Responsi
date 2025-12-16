<?php
include 'config/koneksi.php';

mysqli_query($koneksi,"INSERT INTO fasilitas_kesehatan
(nama, jenis, alamat, latitude, longitude)
VALUES (
'".$_POST['nama']."',
'".$_POST['jenis']."',
'".$_POST['alamat']."',
'".$_POST['latitude']."',
'".$_POST['longitude']."'
)");

header("Location: data.php");
