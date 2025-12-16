<?php
$koneksi = mysqli_connect("localhost","root","","db_kesehatan");
if(!$koneksi){
  die("Koneksi database gagal");
}
?>