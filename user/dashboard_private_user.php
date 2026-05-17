<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

$id_user = $_SESSION['id_user'] ?? '';

$query_cek_customer = mysqli_query($koneksi, "SELECT no_ktp FROM customer WHERE id_user = '$id_user'");
$data_customer = mysqli_fetch_assoc($query_cek_customer);

$no_ktp = $data_customer['no_ktp'] ?? null;

$booking = null;
$keluhan = null;

if ($no_ktp) {
    $query_booking = mysqli_query($koneksi, "SELECT pesanan.*, kamar.nomor_kamar, tipe_kamar.nama_tipe, tipe_kamar.harga 
        FROM pesanan 
        JOIN kamar ON pesanan.id_kamar = kamar.id_kamar 
        JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
        WHERE pesanan.no_ktp = '$no_ktp' AND pesanan.status_pesanan = 'lunas' LIMIT 1");
    $booking = mysqli_fetch_assoc($query_booking);

    $query_keluhan = mysqli_query($koneksi, "SELECT pengaduan.*, kamar.nomor_kamar, tipe_kamar.nama_tipe 
        FROM pengaduan 
        JOIN kamar ON pengaduan.id_kamar = kamar.id_kamar 
        JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
        WHERE pengaduan.no_ktp = '$no_ktp' 
        ORDER BY tgl_lapor DESC LIMIT 1");
    $keluhan = mysqli_fetch_assoc($query_keluhan);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Saya - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_private_user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <nav class="navbar-custom">
        <div class="logo-icon"><i class="fas fa-key"></i></div>
        <div class="logo-name">Kos Aqsya Residence</div>
    </nav>

    <div class="main-content">
        <a href="index.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <h2 class="dashboard-title">Dashboard Saya</h2>
        <p class="dashboard-subtitle">Kelola pemesanan dan keluhan Anda</p>

        <h3>Pesanan Saya</h3>
        <?php if ($no_ktp && $booking) : ?>
            <div class="card-booking">
                <div class="card-top">
                    <div>
                        <span class="booking-id">Booking #<?= $booking['id_pesanan']; ?></span>
                        <span class="booking-date">
                            <i class="far fa-calendar"></i> <?= date('d M Y', strtotime($booking['tgl_pesan'])); ?>
                        </span>

                    </div>
                    <button class="btn-confirm">Confirm</button>
                </div>

                <div class="grid-info">
                    <div class="info-box">
                        <label>Nama Penghuni</label>
                        <span><?= $_SESSION['nama'] ?? 'User'; ?></span>
                    </div>
                    <div class="info-box">
                        <label>Nomor Telepon</label>
                        <span>08123456789</span>
                    </div>
                    <div class="info-box">
                        <label>Kamar</label>
                        <span><?= $booking['nama_tipe']; ?> Room <?= $booking['nomor_kamar']; ?></span>
                    </div>
                    <div class="info-box">
                        <label>Total Price</label>
                        <span style="color: var(--primary);">Rp <?= number_format($booking['harga'], 0, ',', '.'); ?></span>
                    </div>
                </div>

                <a href="pengaduan.php?id_kamar=<?= $booking['id_kamar']; ?>" class="btn-complaint" style="text-decoration: none; display: inline-block; text-align: center;">
                    <i class="fas fa-exclamation-circle"></i> Tambahkan Pengaduan
                </a>
            </div>
        <?php else : ?>
            <p class="empty-text">Anda belum melakukan riwayat pesanan</p>
        <?php endif; ?>

        <h3>Keluhan Saya</h3>
        <?php if ($no_ktp && $keluhan) : ?>
        <?php else : ?>
            <p class="empty-text">Lakukan pemesanan untuk bisa memberi pengaduan pada kamar anda</p>
        <?php endif; ?>
    </div>

</body>

</html>