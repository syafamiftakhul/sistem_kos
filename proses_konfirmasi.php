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
    $id_kamar = $_POST['id_kamar'];

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

        if (empty($id_kamar)) {
            die("ID kamar tidak ditemukan");
        }

        // C. Insert ke Pesanan
        $tgl_skrg = date('Y-m-d');
        $q_pesan = "INSERT INTO pesanan (no_ktp, id_kamar, tgl_pesan, status_pesanan) 
                    VALUES ('$no_ktp', '$id_kamar', '$tgl_skrg', 'pending')";
        mysqli_query($koneksi, $q_pesan);
        $id_pesanan = mysqli_insert_id($koneksi);

        // D. Insert ke Transaksi
        // D. Insert ke Transaksi - Tambahin kolom tgl_masuk di sini
        $q_trans = "INSERT INTO transaksi (id_pesanan, no_ktp, tgl_transaksi, tgl_masuk, jml_bayar, bukti_transaksi, status_transaksi) 
            VALUES ('$id_pesanan', '$no_ktp', '$tgl_skrg', '$tgl_masuk', '$total', '$nama_baru', 'pending')";

        if (mysqli_query($koneksi, $q_trans)) {
            echo "<script>alert('Pembayaran Berhasil Dikirim!'); window.location='user/dashboard_private_user.php';</script>";
        }
    }
}
