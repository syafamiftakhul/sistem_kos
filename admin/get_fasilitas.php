<?php
include '../koneksi.php';
/** @var mysqli $koneksi */
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT fasilitas FROM tipe_kamar WHERE id_tipe = '$id'");
$data = mysqli_fetch_assoc($query);
echo $data['fasilitas'] ?? 'Data tidak ditemukan';
?>