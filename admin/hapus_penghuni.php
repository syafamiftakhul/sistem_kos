<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

// 1. Tangkap id_kamar dari lemparan URL
$id_kamar = $_GET['id'] ?? '';

if (!empty($id_kamar)) {
    // Amankan variabel dari SQL Injection
    $id_kamar = mysqli_real_escape_string($koneksi, $id_kamar);
    
    $query_hapus = "UPDATE pesanan SET status_pesanan = 'batal' WHERE id_kamar = '$id_kamar' AND status_pesanan = 'lunas'";

    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>
                alert('Penghuni berhasil dihapus (Status pesanan diubah menjadi Batal)!'); 
                window.location.href='penghuni.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal menghapus penghuni: " . mysqli_error($koneksi) . "'); 
                window.location.href='penghuni.php';
              </script>";
        exit;
    }
} else {
    // Kalau id kosong, langsung tendang balik ke halaman utama penghuni
    header("Location: penghuni.php");
    exit;
}
?>