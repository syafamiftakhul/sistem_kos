<?php
session_start();
include "koneksi.php";

if (isset($_POST['konfirmasi'])) {
    // 1. Ambil semua data dari hidden input tadi
    $no_ktp    = $_POST['no_ktp'];
    $nama      = $_POST['nama'];
    $no_hp     = $_POST['no_hp'];
    $alamat    = $_POST['alamat'];
    $id_user   = $_SESSION['id_user'];
    $tgl_masuk = $_POST['tgl_masuk'];
    $total     = $_POST['total_bayar'];
    $periode   = $_POST['periode'] ?? 1;

    // 2. Urusan File Foto
    $nama_file = $_FILES['bukti_transfer']['name'];
    $tmp_file  = $_FILES['bukti_transfer']['tmp_name'];
    $ekstensi  = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_baru = "STRUK_" . time() . "_" . uniqid() . "." . $ekstensi;
    $tujuan    = "assets/uploads/" . $nama_baru;

    if (move_uploaded_file($tmp_file, $tujuan)) {

        // A. Simpan/Update ke tabel customer
        $query_cust = "INSERT INTO customer (no_ktp, id_user, nama, no_hp, alamat) 
                       VALUES ('$no_ktp', '$id_user', '$nama', '$no_hp', '$alamat')
                       ON DUPLICATE KEY UPDATE nama='$nama', no_hp='$no_hp'";
        mysqli_query($koneksi, $query_cust);

        // B. Cari kamar kosong (diambil dari form pembayaran, dengan fallback jika kosong)
        $id_kamar = $_POST['id_kamar'] ?? '';
        if (empty($id_kamar)) {
            $query_kamar = mysqli_query($koneksi, "SELECT id_kamar FROM kamar WHERE status_kamar='kosong' LIMIT 1");
            $row_kamar = mysqli_fetch_assoc($query_kamar);
            $id_kamar = $row_kamar ? $row_kamar['id_kamar'] : '';
        }

        if (empty($id_kamar)) {
            echo "<script>alert('Gagal: Kamar tidak tersedia atau sudah penuh!'); window.history.back();</script>";
            exit;
        }

        // C. Insert ke Pesanan
        $tgl_skrg = date('Y-m-d');
        $q_pesan = "INSERT INTO pesanan (no_ktp, id_kamar, tgl_pesan, status_pesanan) 
                    VALUES ('$no_ktp', '$id_kamar', '$tgl_skrg', 'pending')";
        mysqli_query($koneksi, $q_pesan);
        $id_pesanan = mysqli_insert_id($koneksi);

        // D. Insert ke Transaksi - Tambahin kolom tgl_masuk dan periode di sini
        $q_trans = "INSERT INTO transaksi (id_pesanan, no_ktp, tgl_transaksi, tgl_masuk, periode, jml_bayar, bukti_transaksi, status_transaksi) 
            VALUES ('$id_pesanan', '$no_ktp', '$tgl_skrg', '$tgl_masuk', '$periode', '$total', '$nama_baru', 'pending')";

        if (mysqli_query($koneksi, $q_trans)) {
            echo "<script>alert('Pembayaran Berhasil Dikirim!'); window.location='user/dashboard_private_user.php';</script>";
        }
    }
}
