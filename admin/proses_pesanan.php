<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id_pesanan = $_GET['id'];
    $aksi       = $_GET['aksi'];

    if ($aksi == 'setujui') {
        // 1. Ambil data no_ktp dan id_kamar dari pesanan ini dulu
        $query_p = mysqli_query($koneksi, "SELECT no_ktp, id_kamar FROM pesanan WHERE id_pesanan = '$id_pesanan'");
        $data_p  = mysqli_fetch_assoc($query_p);
        
        if ($data_p) {
            $no_ktp   = $data_p['no_ktp'];
            $id_kamar = $data_p['id_kamar'];

            // 2. UPDATE PESANAN: Status jadi 'lunas'
            mysqli_query($koneksi, "UPDATE pesanan SET status_pesanan = 'lunas' WHERE id_pesanan = '$id_pesanan'");

            // 3. UPDATE KAMAR: Status jadi 'terisi' dan catat no_ktp penghuninya
            mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'terisi', no_ktp = '$no_ktp' WHERE id_kamar = '$id_kamar'");

            // FIX KUNCI 1: UPDATE TRANSAKSI ikut jadi 'Lunas' agar muncul di kas admin!
            mysqli_query($koneksi, "UPDATE transaksi SET status_transaksi = 'Lunas' WHERE id_pesanan = '$id_pesanan'");

            echo "<script>alert('Pesanan disetujui, kamar otomatis terisi dan transaksi diperbarui!'); window.location='pesanan.php';</script>";
        } else {
            echo "<script>alert('Data pesanan tidak ditemukan!'); window.location='pesanan.php';</script>";
        }

    } elseif ($aksi == 'tolak') {
        // 4. UPDATE PESANAN: Status jadi 'batal'
        mysqli_query($koneksi, "UPDATE pesanan SET status_pesanan = 'batal' WHERE id_pesanan = '$id_pesanan'");

        // FIX KUNCI 2: UPDATE TRANSAKSI ikut jadi 'Batal' saat pesanan ditolak
        mysqli_query($koneksi, "UPDATE transaksi SET status_transaksi = 'Batal' WHERE id_pesanan = '$id_pesanan'");

        echo "<script>alert('Pesanan telah ditolak!'); window.location='pesanan.php';</script>";
    }
} else {
    header("Location: pesanan.php");
    exit;
}
?>