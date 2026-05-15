<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

if (isset($_POST['simpan'])) {
    // Ambil data dari form
    $nomor_kamar  = mysqli_real_escape_string($koneksi, $_POST['nomor_kamar']); 
    $id_tipe      = mysqli_real_escape_string($koneksi, $_POST['id_tipe']);
    $status_kamar = mysqli_real_escape_string($koneksi, $_POST['status_kamar']);

    // QUERY BERSIH: Hapus no_ktp dan NULL-nya
    $query = "INSERT INTO kamar (nomor_kamar, id_tipe, status_kamar) 
              VALUES ('$nomor_kamar', '$id_tipe', '$status_kamar')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Kamar $nomor_kamar berhasil ditambah!'); window.location='kamar.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    header("Location: kamar.php");
    exit;
}
?>