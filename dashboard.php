<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Saya - Kos Aqsya Residence</title>
  <link rel="stylesheet" href="./global.css" />
  <link rel="stylesheet" href="./detail.css" />
  <link rel="stylesheet" href="./dashboard.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
</head>
<body>
  <div class="dashboard-user1">
    <header class="detail-header">
      <div class="logo-area">
        <div class="key" style="cursor:pointer;" onclick="window.location.href='index.php'">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
          </svg>
        </div>
        <h1 class="logo-text" style="cursor:pointer;" onclick="window.location.href='index.php'">Kos Aqsya Residence</h1>
      </div>
    </header>

    <main class="dashboard-container">
      <h2 class="page-title">Dashboard Saya</h2>
      <p class="page-subtitle">Kelola pemesanan dan keluhan Anda</p>

      <h3 class="section-title">Pesanan Saya</h3>
      <?php if (isset($_SESSION['pesanan'])): ?>
      <div class="dash-card">
        <div class="card-header">
          <h3>Booking #1</h3>
          <span class="badge badge-green">Confirm</span>
        </div>
        <div class="booking-date">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
          <?php echo $_SESSION['pesanan']['checkin'] . ' - ' . $_SESSION['pesanan']['checkout']; ?>
        </div>
        <div class="booking-info-grid">
          <div class="info-col">
            <div class="label">Nama Penghuni</div>
            <div class="val"><?php echo htmlspecialchars($user_name); ?></div>
          </div>
          <div class="info-col">
            <div class="label">Nomor Telepon</div>
            <div class="val"><?php echo htmlspecialchars($_SESSION['pesanan']['telepon']); ?></div>
          </div>
          <div class="info-col">
            <div class="label">Kamar</div>
            <div class="val"><?php echo htmlspecialchars($_SESSION['pesanan']['kamar']); ?></div>
          </div>
          <div class="info-col">
            <div class="label">Total Price</div>
            <div class="val price"><?php echo htmlspecialchars($_SESSION['pesanan']['harga']); ?></div>
          </div>
        </div>
        <a href="pengaduan.php" class="btn-pengaduan">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
          </svg>
          Tambahkan Pengaduan
        </a>
      </div>
      <?php else: ?>
      <div class="dash-card" style="text-align: center; color: #888; padding: 40px 24px;">
        <p>Belum ada pesanan kamar.</p>
        <a href="index.php" style="color: #83A6C4; text-decoration: none; font-weight: 600; display: inline-block; margin-top: 12px;">Cari Kamar Sekarang</a>
      </div>
      <?php endif; ?>

      <h3 class="section-title">Keluhan Saya</h3>
      <?php if (isset($_SESSION['keluhan']) && count($_SESSION['keluhan']) > 0): ?>
        <?php foreach ($_SESSION['keluhan'] as $keluhan): ?>
        <div class="dash-card">
          <div class="card-header">
            <h3><?php echo htmlspecialchars($keluhan['subjek']); ?></h3>
            <span class="badge badge-orange">Sedang Diproses</span>
          </div>
          <div class="complaint-room">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            Deluxe Room A1
          </div>
          <p class="complaint-desc">
            <?php echo nl2br(htmlspecialchars($keluhan['deskripsi'])); ?>
          </p>
          <div class="complaint-date">Dikirim pada <?php echo htmlspecialchars($keluhan['tanggal']); ?></div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
      <div class="dash-card" style="text-align: center; color: #888; padding: 40px 24px;">
        <p>Belum ada keluhan yang diajukan.</p>
      </div>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
