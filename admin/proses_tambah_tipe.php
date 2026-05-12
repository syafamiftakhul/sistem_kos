<?php
include '../koneksi.php'; 
/** @var mysqli $koneksi */

if (isset($_POST['simpan'])) {
    $id_tipe   = $_POST['id_tipe'];
    $nama_tipe = $_POST['nama_tipe'];
    $harga     = $_POST['harga'];
    $fasilitas = $_POST['fasilitas'];

    $query = "INSERT INTO tipe_kamar (id_tipe, nama_tipe, harga, fasilitas) 
              VALUES ('$id_tipe', '$nama_tipe', '$harga', '$fasilitas')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Tipe Kamar Berhasil Ditambahkan!');
                window.location='tipe_kamar.php'; 
              </script>";
    } else {
        echo "Gagal simpan data: " . mysqli_error($koneksi);
    }
} else {
    header("Location: tipe_kamar.php");
}
?>