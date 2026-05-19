<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

if (isset($_POST['update'])) {

    $id_kamar     = mysqli_real_escape_string($koneksi, $_POST['id_kamar']); 
    $nomor_kamar  = mysqli_real_escape_string($koneksi, $_POST['nomor_kamar']);
    $id_tipe      = mysqli_real_escape_string($koneksi, $_POST['id_tipe']);
    
    // Ambil input status, bersihkan spasi, dan paksa huruf kecil
    $status_input = trim($_POST['status_kamar'] ?? 'kosong');
    $status_kamar = mysqli_real_escape_string($koneksi, strtolower($status_input));

    // JURUS FIX: Jika status diubah jadi kosong (penghuni pindah), cukup kosongkan KTP di tabel kamar!
    if ($status_kamar == 'kosong' || $status_kamar == 'tersedia') {
        $status_kamar = 'kosong'; 
        
        // Kita timpa no_ktp pakai string kosong ('') biar lolos dari proteksi NOT NULL database lu rekk
       // QUERY BARU UNTUK HALAMAN PENGHUNI (Taruh di admin/penghuni.php)
$query = "SELECT customer.*, 
                 CASE 
                    WHEN kamar.status_kamar = 'kosong' OR kamar.status_kamar IS NULL THEN '-'
                    ELSE kamar.nomor_kamar 
                 END AS nomor_kamar
          FROM customer
          LEFT JOIN kamar ON customer.no_ktp = kamar.no_ktp";
    } else {
        // Kalau statusnya diubah jadi 'terisi' atau 'booking', jalankan update biasa tanpa ganggu no_ktp
        $query = "UPDATE kamar SET 
                    nomor_kamar = '$nomor_kamar', 
                    id_tipe = '$id_tipe', 
                    status_kamar = '$status_kamar' 
                  WHERE id_kamar = '$id_kamar'";
    }

    // Eksekusi query ke database
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