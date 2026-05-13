<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

$id = $_GET['id'] ?? '';

if ($id != '') {
    $query = "DELETE FROM tipe_kamar WHERE id_tipe = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Tipe Kamar Berhasil Dihapus!');
                window.location='tipe_kamar.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus! Tipe ini mungkin masih digunakan oleh data kamar.');
                window.location='tipe_kamar.php';
              </script>";
    }
} else {
    header("Location: tipe_kamar.php");
}
?>