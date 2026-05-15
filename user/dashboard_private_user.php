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
        WHERE pesanan.no_ktp = '$no_ktp' AND pesanan.status_pesanan = 2 LIMIT 1");
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

    <header style="display: flex; align-items: center; padding: 20px 40px; border-bottom: 1px solid #e2e8f0; background: #fff;">
        <div style="background-color: #81A6C6; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
            <a href="../index.php" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                <img src="../assets/img/key.png" alt="Logo" style="width: 20px; filter: brightness(0) invert(1);">
            </a>
        </div>
        <h1 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #81A6C6;">Kos Aqsya Residence</h1>
    </header>

    <div class="main-content">
        <h2 class="dashboard-title">Dashboard Saya</h2>
        <p class="dashboard-subtitle">Kelola pemesanan dan keluhan Anda</p>

        <h3>Pesanan Saya</h3>
        <?php if ($no_ktp && $booking) : ?>
            <div class="card-box">
                <div class="card-top">
                    <div>
                        <div class="booking-id">Booking #<?= $booking['id_pesanan']; ?></div>
                        <div class="booking-date"><i class="far fa-calendar"></i> 14/08/2025 - 15-11-2025</div>
                    </div>
                    <div class="badge-confirm">Confirm</div>
                </div>

                <div class="grid-info">
                    <div>
                        <div class="info-label">Nama Penghuni</div>
                        <div class="info-value"><?= htmlspecialchars($_SESSION['nama'] ?? 'User'); ?></div>
                    </div>
                    <div>
                        <div class="info-label">Nomor Telepon</div>
                        <div class="info-value">0273127394194</div>
                    </div>
                    <div>
                        <div class="info-label">Kamar</div>
                        <div class="info-value"><?= htmlspecialchars($booking['nama_tipe'] . ' Room ' . $booking['nomor_kamar']); ?></div>
                    </div>
                    <div>
                        <div class="info-label">Total Price</div>
                        <div class="info-value price-value">Rp<?= number_format($booking['harga'], 0, ',', '.'); ?></div>
                    </div>
                </div>

                <a href="../pengaduan.php" class="btn-complaint">
                    <i class="fas fa-exclamation-circle"></i> Tambahkan Pengaduan
                </a>
            </div>
        <?php else : ?>
            <p class="empty-text">Anda belum melakukan riwayat pesanan</p>
        <?php endif; ?>

        <h3>Keluhan Saya</h3>
        <?php if ($no_ktp && $keluhan) : ?>
            <div class="card-box">
                <div class="card-top" style="margin-bottom: 12px;">
                    <div class="keluhan-title"><?= htmlspecialchars($keluhan['subjek']); ?></div>
                    <div class="badge-proses">Sedang Diproses</div>
                </div>
                
                <div class="keluhan-room">
                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($keluhan['nama_tipe'] . ' Room ' . $keluhan['nomor_kamar']); ?>
                </div>
                
                <div class="keluhan-desc">
                    <?= htmlspecialchars($keluhan['deskripsi']); ?>
                </div>
                
                <div class="keluhan-date">
                    Dikirim pada <?= htmlspecialchars(date('j/n/Y', strtotime($keluhan['tgl_lapor']))); ?>
                </div>
            </div>
        <?php else : ?>
            <p class="empty-text">Lakukan pemesanan untuk bisa memberi pengaduan pada kamar anda</p>
        <?php endif; ?>
    </div>

</body>
</html>