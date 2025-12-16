<?php
include 'config/koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek apakah ada data yang dipilih
if (!isset($_POST['id']) || empty($_POST['id'])) {
    header("Location: data.php");
    exit;
}

// Hapus data satu per satu
foreach ($_POST['id'] as $id) {
    $id = intval($id); // keamanan dasar
    mysqli_query($koneksi, "DELETE FROM fasilitas_kesehatan WHERE id=$id");
}

// Kembali ke halaman data
header("Location: data.php");
exit;
