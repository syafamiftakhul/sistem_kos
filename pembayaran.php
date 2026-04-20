<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['konfirmasi'])) {
    $_SESSION['pesanan'] = [
        'kamar' => 'Deluxe Room A1',
        'checkin' => '14-08-2025',
        'checkout' => '15-11-2025',
        'harga' => 'Rp 3.000.000',
        'telepon' => '081234567890'
    ];
    echo "<script>alert('Pembayaran Berhasil!'); window.location.href='dashboard.php';</script>";
    exit;
}
$user_name = $_SESSION['user_name'] ?? 'Ricky Naila';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran - Kos Aqsya Residence</title>
  <link rel="stylesheet" href="./global.css" />
  <link rel="stylesheet" href="./detail.css" />
  <link rel="stylesheet" href="./booking.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
</head>
<body>
  <div class="dashboard-user1">
    <header class="detail-header">
      <div class="logo-area">
        <div class="key">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
          </svg>
        </div>
        <h1 class="logo-text">Kos Aqsya Residence</h1>
      </div>
    </header>

    <main class="booking-container">
      <a href="booking.php" class="back-link">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali
      </a>

      <div class="payment-grid">
        <div class="booking-card" style="margin-top:0;">
          <h2 class="booking-card-title">Pembayaran</h2>
          <p class="booking-card-subtitle">Selesaikan pembayaran Anda untuk mengkonfirmasi pemesanan</p>

          <div class="section-heading" style="font-size: 16px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="5" width="20" height="14" rx="2" ry="2"></rect>
              <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
            Pilih Metode Pembayaran
          </div>

          <div class="payment-method active">
            <input type="radio" name="payment" checked>
            <div class="payment-method-info">
              <h4>Bank Transfer</h4>
              <p>BCA, Mandiri, BNI, BRI</p>
            </div>
          </div>
          <div class="payment-method">
            <input type="radio" name="payment">
            <div class="payment-method-info">
              <h4>E-Wallet</h4>
              <p>GoPay, Dana, OVO, ShopeePay</p>
            </div>
          </div>

          <div class="bank-details-box">
            <h4>Detail Bank Account</h4>
            <div class="bank-grid">
              <div class="bank-item">
                <div class="bank-name">BCA - Queensha</div>
                <div class="bank-acc">1234567890</div>
              </div>
              <div class="bank-item">
                <div class="bank-name">BNI - Syifa</div>
                <div class="bank-acc">1234567890</div>
              </div>
              <div class="bank-item">
                <div class="bank-name">Mandiri - Aulia Khanza</div>
                <div class="bank-acc">1234567890</div>
              </div>
              <div class="bank-item">
                <div class="bank-name">BRI - Rum</div>
                <div class="bank-acc">1234567890</div>
              </div>
            </div>
          </div>

          <div class="section-heading" style="font-size: 16px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="17 8 12 3 7 8"></polyline>
              <line x1="12" y1="3" x2="12" y2="15"></line>
            </svg>
            Upload Bukti Pembayaran
          </div>

          <div class="upload-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="17 8 12 3 7 8"></polyline>
              <line x1="12" y1="3" x2="12" y2="15"></line>
            </svg>
            <p><span>Pilih file</span> atau seret dan lepas</p>
            <div class="hint">PNG, JPG, JPEG dibawah 2MB</div>
          </div>

          <form action="" method="POST">
            <input type="hidden" name="konfirmasi" value="1">
            <button type="submit" class="btn-primary">Konfirmasi Pembayaran</button>
          </form>
        </div>

        <div class="summary-card">
          <h3>Rincian Pesanan</h3>
          
          <div class="summary-item">
            <div class="label">Kamar</div>
            <div class="value">Deluxe Room A1</div>
          </div>
          <div class="summary-item">
            <div class="label">Nama Penghuni</div>
            <div class="value"><?php echo htmlspecialchars($user_name); ?></div>
          </div>
          <div class="summary-item">
            <div class="label">Check-in</div>
            <div class="value">14-08-2025</div>
          </div>
          <div class="summary-item">
            <div class="label">Check-out</div>
            <div class="value">14-11-2025</div>
          </div>
          <div class="summary-item">
            <div class="label">Durasi</div>
            <div class="value">3 Bulan</div>
          </div>

          <div class="summary-divider"></div>

          <div class="summary-row">
            <span>Tarif Bulanan</span>
            <span>Rp. 1.000.000</span>
          </div>
          <div class="summary-row">
            <span>Durasi</span>
            <span>3 Bulan</span>
          </div>
          
          <div class="summary-divider"></div>

          <div class="summary-row total">
            <span>Total</span>
            <span class="val">Rp 3.000.000</span>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
