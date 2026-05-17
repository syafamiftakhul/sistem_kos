<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

if (isset($_POST['update_penghuni'])) {
    $no_ktp       = $_POST['no_ktp'];
    $id_transaksi = $_POST['id_transaksi'];
    $no_hp        = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $tgl_masuk    = mysqli_real_escape_string($koneksi, $_POST['tgl_masuk']);
    $periode      = (int)$_POST['periode'];

    // 1. Update No HP di tabel customer
    $update_customer = mysqli_query($koneksi, "UPDATE customer SET no_hp = '$no_hp' WHERE no_ktp = '$no_ktp'");

    // 2. Update tanggal masuk & periode di tabel transaksi jika transaksinya ada
    if (!empty($id_transaksi)) {
        $update_transaksi = mysqli_query($koneksi, "UPDATE transaksi SET tgl_masuk = '$tgl_masuk', periode = '$periode' WHERE id_transaksi = '$id_transaksi'");
    } else {
        // Fallback aman kalau ternyata blm ada record transaksi tapi status pesanan sudah lunas
        $update_transaksi = true; 
    }

    if ($update_customer && $update_transaksi) {
        echo "<script>alert('Data penghuni berhasil diperbarui!'); window.location.href='penghuni.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "'); window.location.href='penghuni.php';</script>";
    }
} else {
    header("Location: penghuni.php");
    exit;
}
?>