<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

$id_user = $_SESSION['id_user'] ?? '';

$query_cek_customer = mysqli_query($koneksi, "SELECT no_ktp FROM customer WHERE id_user = '$id_user'");
$data_customer = mysqli_fetch_assoc($query_cek_customer);

$no_ktp = $data_customer['no_ktp'] ?? null;
$no_telp = $data_customer['no_telp'] ?? '-'; // Ambil nomor telepon dinamis dari database

$booking = null;
$keluhan = null;

if ($no_ktp) {
    // Mengambil data pesanan aktif (status_pesanan = 2 artinya disetujui/berjalan)
    $query_booking = mysqli_query($koneksi, "SELECT pesanan.*, kamar.nomor_kamar, tipe_kamar.nama_tipe, tipe_kamar.harga 
        FROM pesanan 
        JOIN kamar ON pesanan.id_kamar = kamar.id_kamar 
        JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
        WHERE pesanan.no_ktp = '$no_ktp' AND pesanan.status_pesanan = 2 LIMIT 1");
    $booking = mysqli_fetch_assoc($query_booking);

    // Mengambil data keluhan terakhir
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Saya - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_private_user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header style="display: flex; align-items: center; padding: 16px 40px; border-bottom: 1px solid #E5E7EB; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
        <div style="background-color: #81A6C6; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
            <a href="../index.php" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                <img src="../assets/img/key.png" alt="Logo" style="width: 18px; filter: brightness(0) invert(1);">
            </a>
        </div>
        <h1 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #81A6C6; letter-spacing: -0.02em;">Kos Aqsya Residence</h1>
    </header>

    <div class="main-content">
        <a href="dashboard_user.php" style="color: #6B7280; text-decoration: none; display: inline-flex; align-items: center; margin-bottom: 24px; font-size: 14px; font-weight: 500;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali
        </a>
        <h2 class="dashboard-title">Dashboard Saya</h2>
        <p class="dashboard-subtitle">Pantau pesanan kamar dan sampaikan keluhan Anda di sini.</p>

        <h3>Pesanan Saya</h3>
        <?php if (!empty($no_ktp) && !empty($booking)) : ?>
            <div class="card-box">
                <div class="card-top">
                    <div>
                        <div class="booking-id">Booking #<?= htmlspecialchars($booking['id_pesanan']); ?></div>
                        <div class="booking-date">
                            <i class="far fa-calendar-alt"></i> 
                            <?= date('d M Y', strtotime($booking['tgl_masuk'])); ?> - <?= date('d M Y', strtotime($booking['tgl_keluar'])); ?>
                        </div>
                    </div>
                    <span class="badge-confirm">Terkonfirmasi</span>
                </div>

                <div class="grid-info">
                    <div>
                        <div class="info-label">Nama Penghuni</div>
                        <div class="info-value"><?= htmlspecialchars($_SESSION['nama'] ?? 'User'); ?></div>
                    </div>
                    <div>
                        <div class="info-label">Nomor Telepon</div>
                        <div class="info-value"><?= htmlspecialchars($no_telp); ?></div>
                    </div>
                    <div>
                        <div class="info-label">Kamar</div>
                        <div class="info-value"><?= htmlspecialchars($booking['nama_tipe'] . ' - ' . $booking['nomor_kamar']); ?></div>
                    </div>
                    <div>
                        <div class="info-label">Total Harga</div>
                        <div class="info-value price-value">Rp <?= number_format($booking['harga'], 0, ',', '.'); ?></div>
                    </div>
                </div>

                <a href="../pengaduan.php" class="btn-complaint">
                    <i class="fas fa-exclamation-circle"></i> Laporkan Keluhan Kamar
                </a>
            </div>
        <?php else : ?>
            <p class="empty-text" style="margin-top: -10px; margin-bottom: 30px;">Anda belum melakukan riwayat pesanan</p>
        <?php endif; ?>

        <h3>Keluhan Saya</h3>
        <?php if (!empty($no_ktp) && !empty($keluhan)) : ?>
            <div class="card-box">
                <div class="card-top" style="margin-bottom: 12px;">
                    <div class="keluhan-title"><?= htmlspecialchars($keluhan['subjek']); ?></div>
                    <span class="badge-proses">Sedang Diproses</span>
                </div>
                
                <div class="keluhan-room">
                    <i class="fas fa-door-open"></i> <?= htmlspecialchars($keluhan['nama_tipe'] . ' - Room ' . $keluhan['nomor_kamar']); ?>
                </div>
                
                <div class="keluhan-desc">
                    <?= nl2br(htmlspecialchars($keluhan['deskripsi'])); ?>
                </div>
                
                <div class="keluhan-date">
                    Dikirim pada <?= htmlspecialchars(date('d/n/Y', strtotime($keluhan['tgl_lapor']))); ?>
                </div>
            </div>
        <?php else : ?>
            <p class="empty-text" style="margin-top: -10px;">Lakukan pemesanan untuk bisa memberi pengaduan pada kamar anda</p>
        <?php endif; ?>
    </div>

</body>
</html>