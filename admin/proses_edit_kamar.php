<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

if (isset($_POST['update'])) {

    $id_kamar     = mysqli_real_escape_string($koneksi, $_POST['id_kamar']); 
    $nomor_kamar  = mysqli_real_escape_string($koneksi, $_POST['nomor_kamar']);
    $id_tipe      = mysqli_real_escape_string($koneksi, $_POST['id_tipe']);
    
    $status_input = trim($_POST['status_kamar'] ?? 'kosong');
    $status_kamar = mysqli_real_escape_string($koneksi, strtolower($status_input));

    // FIX KUNCI 1: Jika status kamar diganti ke kosong, paksa kolom no_ktp di tabel kamar jadi NULL / kosong!
    if ($status_kamar == 'kosong') {
        $query = "UPDATE kamar SET 
                    nomor_kamar = '$nomor_kamar', 
                    id_tipe = '$id_tipe', 
                    status_kamar = '$status_kamar',
                    no_ktp = NULL 
                  WHERE id_kamar = '$id_kamar'";
    } else {
        // Kalau statusnya terisi atau booking, biarkan no_ktp lamanya tetap aman
        $query = "UPDATE kamar SET 
                    nomor_kamar = '$nomor_kamar', 
                    id_tipe = '$id_tipe', 
                    status_kamar = '$status_kamar' 
                  WHERE id_kamar = '$id_kamar'";
    }

    // Eksekusi
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Data Kamar $nomor_kamar berhasil diperbarui!');
                window.location='kamar.php';
              </script>";
    } else {
        echo "Error updating record: " . mysqli_error($koneksi);
    }

} else {
    header("Location: kamar.php");
    exit;
}
?>