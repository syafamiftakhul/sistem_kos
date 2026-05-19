<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

if (isset($_POST['update'])) {

    $id_kamar     = mysqli_real_escape_string($koneksi, $_POST['id_kamar']); 
    $nomor_kamar  = mysqli_real_escape_string($koneksi, $_POST['nomor_kamar']);
    $id_tipe      = mysqli_real_escape_string($koneksi, $_POST['id_tipe']);
    
    // Ambil input status, bersihkan spasi kanan-kiri, dan paksa jadi huruf kecil
    $status_input = trim($_POST['status_kamar'] ?? 'kosong');
    $status_kamar = mysqli_real_escape_string($koneksi, strtolower($status_input));

    // FIX KUNCI MUTLAK: Deteksi ketat, mau isinya 'kosong' atau 'tersedia', pokoknya kalau bukan terisi/booking, kosongkan KTP!
    if ($status_kamar == 'kosong' || $status_kamar == 'tersedia') {
        $status_kamar = 'kosong'; // samakan dengan ENUM DB lu
        $query = "UPDATE kamar SET 
                    nomor_kamar = '$nomor_kamar', 
                    id_tipe = '$id_tipe', 
                    status_kamar = '$status_kamar',
                    no_ktp = NULL 
                  WHERE id_kamar = '$id_kamar'";
    } else {
        // Kalau statusnya 'terisi' atau 'booking', biarkan no_ktp lamanya tetap nempel aman
        $query = "UPDATE kamar SET 
                    nomor_kamar = '$nomor_kamar', 
                    id_tipe = '$id_tipe', 
                    status_kamar = '$status_kamar' 
                  WHERE id_kamar = '$id_kamar'";
    }

    // Eksekusi ke database
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