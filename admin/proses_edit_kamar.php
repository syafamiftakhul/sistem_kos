<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

if (isset($_POST['update'])) {

    $id_kamar     = mysqli_real_escape_string($koneksi, $_POST['id_kamar']); // ID asli buat patokan
    $nomor_kamar  = mysqli_real_escape_string($koneksi, $_POST['nomor_kamar']);
    $id_tipe      = mysqli_real_escape_string($koneksi, $_POST['id_tipe']);
    $status_kamar = mysqli_real_escape_string($koneksi, $_POST['status_kamar']);

    // 2. Query UPDATE
    // Kita update nomor, tipe, dan statusnya berdasarkan id_kamar
    $query = "UPDATE kamar SET 
                nomor_kamar = '$nomor_kamar', 
                id_tipe = '$id_tipe', 
                status_kamar = '$status_kamar' 
              WHERE id_kamar = '$id_kamar'";

    // 3. Eksekusi
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Data Kamar $nomor_kamar berhasil diperbarui!');
                window.location='kamar.php';
              </script>";
    } else {
        // Kalau error, tampilin pesannya biar gampang debug
        echo "Error updating record: " . mysqli_error($koneksi);
    }

} else {
    // Kalau coba akses file ini tanpa klik tombol update, tendang balik
    header("Location: kamar.php");
    exit;
}
?>