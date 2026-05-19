<?php
session_start();
include "koneksi.php";
/** @var mysqli $koneksi */

if (isset($_POST['konfirmasi'])) {
    $no_ktp          = $_POST['no_ktp'] ?? '';
    $nama            = $_POST['nama'] ?? '';
    $no_hp           = $_POST['no_hp'] ?? '';
    $alamat          = $_POST['alamat'] ?? '';
    $jenis_kelamin   = $_POST['jenis_kelamin'] ?? '';
    $kontak_keluarga = $_POST['kontak_keluarga'] ?? '';
    $id_user         = $_SESSION['id_user'] ?? '';
    $tgl_masuk       = $_POST['tgl_masuk'] ?? date('Y-m-d');
    $total           = $_POST['total_bayar'] ?? 0;
    $periode         = $_POST['periode'] ?? 1;
    $id_kamar        = $_POST['id_kamar'] ?? '';

    $nama_file = $_FILES['bukti_transfer']['name'] ?? '';
    $tmp_file  = $_FILES['bukti_transfer']['tmp_name'] ?? '';
    $ekstensi  = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_baru = "STRUK_" . time() . "_" . uniqid() . "." . $ekstensi;

    $folder_tujuan = "assets/uploads/"; 
    
    if (!is_dir($folder_tujuan)) {
        mkdir($folder_tujuan, 0777, true);
    }

    $tujuan = $folder_tujuan . $nama_baru;

    if (move_uploaded_file($tmp_file, $tujuan)) {

        $query_cust = "INSERT INTO customer (no_ktp, id_user, nama, no_hp, alamat, jenis_kelamin, kontak_keluarga) 
                       VALUES ('$no_ktp', '$id_user', '$nama', '$no_hp', '$alamat', '$jenis_kelamin', '$kontak_keluarga')
                       ON DUPLICATE KEY UPDATE nama='$nama', no_hp='$no_hp', alamat='$alamat', jenis_kelamin='$jenis_kelamin', kontak_keluarga='$kontak_keluarga'";
        mysqli_query($koneksi, $query_cust);

        if (empty($id_kamar)) {
            $query_kamar = mysqli_query($koneksi, "SELECT id_kamar FROM kamar WHERE status_kamar='kosong' LIMIT 1");
            $row_kamar = mysqli_fetch_assoc($query_kamar);
            $id_kamar = $row_kamar ? $row_kamar['id_kamar'] : '';
        }

        if (empty($id_kamar)) {
            echo "<script>alert('Gagal: Kamar tidak tersedia atau sudah penuh!'); window.history.back();</script>";
            exit;
        }

        $tgl_skrg = date('Y-m-d');
        $q_pesan = "INSERT INTO pesanan (no_ktp, id_kamar, tgl_pesan, status_pesanan) 
                    VALUES ('$no_ktp', '$id_kamar', '$tgl_skrg', 'pending')";
        mysqli_query($koneksi, $q_pesan);
        $id_pesanan = mysqli_insert_id($koneksi);

        $q_trans = "INSERT INTO transaksi (id_pesanan, no_ktp, tgl_transaksi, tgl_masuk, periode, jml_bayar, bukti_transaksi, status_transaksi) 
                    VALUES ('$id_pesanan', '$no_ktp', '$tgl_skrg', '$tgl_masuk', '$periode', '$total', '$nama_baru', 'Pending')";

        if (mysqli_query($koneksi, $q_trans)) {
            echo "<script>alert('Pembayaran Berhasil Dikirim! Menunggu konfirmasi admin.'); window.location='user/dashboard_private_user.php';</script>";
        } else {
            echo "Eror SQL Transaksi: " . mysqli_error($koneksi);
        }
    } else {
        echo "<script>alert('Gagal mengupload bukti transfer! Periksa direktori target.'); window.history.back();</script>";
    }
}
?>