<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

if (isset($_POST['update'])) {
    $id_kamar     = mysqli_real_escape_string($koneksi, $_POST['id_kamar']); 
    $nomor_kamar  = mysqli_real_escape_string($koneksi, $_POST['nomor_kamar']);
    $id_tipe      = mysqli_real_escape_string($koneksi, $_POST['id_tipe']);
    
    $status_input = trim($_POST['status_kamar'] ?? 'kosong');
    $status_kamar = mysqli_real_escape_string($koneksi, strtolower($status_input));

    // 1. Proses Update Kamar
    if ($status_kamar == 'kosong' || $status_kamar == 'tersedia') {
        // Jika jadi kosong, set no_ktp jadi NULL
        $query = "UPDATE kamar SET 
                    nomor_kamar = '$nomor_kamar', 
                    id_tipe = '$id_tipe', 
                    status_kamar = 'kosong', 
                    no_ktp = NULL 
                  WHERE id_kamar = '$id_kamar'";
    } else {
        // Jika terisi/lainnya
        $query = "UPDATE kamar SET 
                    nomor_kamar = '$nomor_kamar', 
                    id_tipe = '$id_tipe', 
                    status_kamar = '$status_kamar' 
                  WHERE id_kamar = '$id_kamar'";
    }

    if (mysqli_query($koneksi, $query)) {
        
        // 2. JURUS ARSIP: Kalau status jadi kosong, otomatis ubah status pesanan jadi 'selesai'
        if ($status_kamar == 'kosong') {
            mysqli_query($koneksi, "UPDATE pesanan SET status_pesanan = 'selesai' 
                                    WHERE id_kamar = '$id_kamar' 
                                    AND status_pesanan IN ('lunas', 'pending', 'proses')");
        }

        echo "<script>
                alert('Data Kamar $nomor_kamar berhasil diperbarui!');
                window.location='kamar.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }

} else {
    header("Location: kamar.php");
    exit;
}
?>