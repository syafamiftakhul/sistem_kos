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
        
        $no_ktp   = $data_p['no_ktp'];
        $id_kamar = $data_p['id_kamar'];

        // 2. UPDATE PESANAN: Pakai 'lunas' (sesuai ENUM di screenshot lu)
        mysqli_query($koneksi, "UPDATE pesanan SET status_pesanan = 'lunas' WHERE id_pesanan = '$id_pesanan'");

        // 3. UPDATE KAMAR: Status jadi 'terisi' (sesuai ENUM tabel kamar lu)
        mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'terisi', no_ktp = '$no_ktp' WHERE id_kamar = '$id_kamar'");

        echo "<script>alert('Pesanan disetujui, kamar otomatis terisi!'); window.location='pesanan.php';</script>";

    } elseif ($aksi == 'tolak') {
        // 4. UPDATE PESANAN: Pakai 'batal' (sesuai ENUM di screenshot lu)
        mysqli_query($koneksi, "UPDATE pesanan SET status_pesanan = 'batal' WHERE id_pesanan = '$id_pesanan'");
        echo "<script>alert('Pesanan telah ditolak!'); window.location='pesanan.php';</script>";
    }
} else {
    header("Location: pesanan.php");
    exit;
}
?>