<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit;
}

$id_log = $_SESSION['id_user']; // Ini isinya no_ktp lu kan?

// Ambil data transaksi buat dapet id_kamar si user
$query = "SELECT t.*, k.id_kamar 
          FROM transaksi t 
          JOIN kamar k ON t.id_kamar = k.id_kamar 
          WHERE t.no_ktp = '$id_log' 
          ORDER BY t.id_transaksi DESC LIMIT 1";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

// Ambil data pengaduan BERDASARKAN id_kamar yang lagi disewa user
if ($data) {
  $kamar_user = $data['id_kamar'];
  $query_adu = "SELECT * FROM pengaduan WHERE id_kamar = '$kamar_user' ORDER BY id_pengaduan DESC";
  $result_adu = mysqli_query($koneksi, $query_adu);
} else {
  $result_adu = false;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Dashboard Saya - Kos Aqsya</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/dashboard_user.css">
</head>

<body>

  <header class="header">
    <div class="logo-box">
      <img src="../assets/img/key.png" alt="Logo" class="icon" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
    </div>
    <span class="brand-name">Kos Aqsya Residence</span>
  </header>

  <main class="dashboard-container">
    <a href="../index.php" class="back-link" style="text-decoration: none; color: #6c757d; font-size: 14px; display: flex; align-items: center; gap: 5px; margin-bottom: 20px;">
      <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
      Kembali
    </a>

    <div class="section-title">
      <h2>Dashboard Saya</h2>
      <p>Kelola pemesanan dan keluhan Anda dengan mudah</p>
    </div>

    <h3 style="margin-bottom: 15px; font-size: 18px; margin-top: 20px;">Pesanan Saya</h3>

    <?php if ($data): ?>
      <div class="card">
        <div class="card-header">
          <div>
            <p class="booking-id">Booking #<?php echo $data['id_transaksi']; ?></p>
            <p class="booking-date">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>
              Periode: <?php echo $data['periode'] ?: 'Baru saja diinput'; ?>
            </p>
          </div>
          <span class="status-badge status-<?php echo strtolower($data['status_transaksi'] ?: 'pending'); ?>">
            <?php echo $data['status_transaksi'] ?: 'Menunggu Konfirmasi'; ?>
          </span>
        </div>

        <div class="detail-grid">
          <div class="detail-item">
            <span>Nama Penghuni</span>
            <p><?php echo htmlspecialchars($user_name); ?></p>
          </div>
          <div class="detail-item">
            <span>Nomor Telepon</span>
            <p><?php echo $_SESSION['no_hp'] ?? '08xxxxxxx'; ?></p>
          </div>
          <div class="detail-item">
            <span>Kamar</span>
            <p><?php echo $data['nama_kamar'] ?: 'Kamar Pilihan'; ?></p>
          </div>
          <div class="detail-item">
            <span>Total Harga</span>
            <p class="price">Rp <?php echo number_format($data['jml_bayar'], 0, ',', '.'); ?></p>
          </div>
        </div>

        <button onclick="window.location.href='pengaduan.php'" style="margin-top: 20px; padding: 10px 20px; border: none; border-radius: 8px; background: #3b5998; font-size: 13px; font-weight: 600; cursor: pointer; color: white; display: flex; align-items: center; gap: 8px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
          </svg>
          Tambahkan Pengaduan
        </button>
      </div>

    <?php else: ?>
      <div class="card" style="text-align: center; padding: 40px; border: 2px dashed #eee; background: none; box-shadow: none;">
        <p style="color: #6c757d; margin-bottom: 15px;">Belum ada pesanan aktif nih bre.</p>
        <a href="../index.php" style="background: #3b5998; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600;">Booking Kamar Sekarang</a>
      </div>
    <?php endif; ?>

    <h3 style="margin-bottom: 15px; font-size: 18px; margin-top: 40px;">Keluhan Saya</h3>

    <?php if ($result_adu && mysqli_num_rows($result_adu) > 0): ?>
      <?php while ($adu = mysqli_fetch_assoc($result_adu)): ?>
        <div class="card" style="margin-bottom: 15px;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h4 style="font-size: 15px;">Laporan #<?php echo $adu['id_pengaduan']; ?></h4>

            <span class="status-badge status-<?php echo strtolower($adu['status_pengaduan'] ?: 'pending'); ?>">
              <?php echo $adu['status_pengaduan'] ?: 'Pending'; ?>
            </span>
          </div>

          <p style="font-size: 13px; line-height: 1.6;">
            <?php echo nl2br(htmlspecialchars($adu['deskripsi'])); ?>
          </p>

          <p style="font-size: 11px; color: #adb5bd; margin-top: 15px;">
            Dilaporkan pada: <?php echo date('d/m/Y', strtotime($adu['tgl_lapor'])); ?>
          </p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="card" style="text-align: center; padding: 30px; border: 1px dashed #ccc; background: none; box-shadow: none;">
        <p style="color: #999; font-size: 13px;">Belum ada riwayat keluhan yang Anda ajukan.</p>
      </div>
    <?php endif; ?>

  </main>
</body>

</html>