<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

// Cek apakah tombol simpan udah diklik
if (isset($_POST['simpan'])) {
    
    // Ambil ID Kamar (sesuai nama kolom di DB lu)
$id_kamar     = mysqli_real_escape_string($koneksi, $_POST['no_kamar']); 
$id_tipe      = mysqli_real_escape_string($koneksi, $_POST['id_tipe']);
$status_kamar = mysqli_real_escape_string($koneksi, $_POST['status_kamar']);

$query = "INSERT INTO kamar (id_kamar, id_tipe, status_kamar, no_ktp) 
          VALUES ('$id_kamar', '$id_tipe', '$status_kamar', NULL)";
    // 3. Jalankan query
    if (mysqli_query($koneksi, $query)) {
        // Kalau sukses, kasih notif terus balik ke halaman daftar kamar
        echo "<script>
                alert('Kamar Baru Berhasil Ditambahkan!');
                window.location='kamar.php';
              </script>";
    } else {
        // Kalau gagal (misal: No. Kamar kembar), kasih tau errornya
        echo "<script>
                alert('Gagal nambah kamar: " . mysqli_error($koneksi) . "');
                window.history.back();
              </script>";
    }

} else {
    // Kalau ada yang coba akses file ini tanpa submit form, tendang balik
    header("Location: kamar.php");
    exit;
}
?>