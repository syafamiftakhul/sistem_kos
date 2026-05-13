<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

if (isset($_POST['update'])) {
    $id_tipe   = $_POST['id_tipe'];
    $nama_tipe = $_POST['nama_tipe'];
    $harga     = $_POST['harga'];
    $fasilitas = $_POST['fasilitas'];

    $sql = "UPDATE tipe_kamar SET 
            nama_tipe = '$nama_tipe', 
            harga = '$harga', 
            fasilitas = '$fasilitas' 
            WHERE id_tipe = '$id_tipe'";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
                alert('Data Tipe Kamar Berhasil Diperbarui!');
                window.location='tipe_kamar.php';
              </script>";
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
} else {
    header("Location: tipe_kamar.php");
    exit;
}
?>