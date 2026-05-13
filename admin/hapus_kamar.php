<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

// 1. Ambil ID (no_kamar) dari URL
// Pastiin di link hapusnya lu pake parameter 'id'
$id = $_GET['id'] ?? '';

if ($id != '') {
    // 2. Query Hapus berdasarkan no_kamar
    // Sesuaikan nama kolomnya kalau di DB lu bukan 'no_kamar'
    $query = "DELETE FROM kamar WHERE no_kamar = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        // Berhasil hapus, munculin notif dan balik ke halaman kamar
        echo "<script>
                alert('Data Kamar $id Berhasil Dihapus!');
                window.location='kamar.php';
              </script>";
    } else {
        // Gagal hapus
        echo "<script>
                alert('Gagal menghapus data kamar: " . mysqli_error($koneksi) . "');
                window.location='kamar.php';
              </script>";
    }
} else {
    // Kalau gak ada ID, langsung tendang balik
    header("Location: kamar.php");
    exit;
}
?>