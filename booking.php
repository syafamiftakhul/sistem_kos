<?php
session_start();

// Cek apakah ada ID User di session. 
// Kalau kosong, berarti dia belum login atau belum daftar.
if (!isset($_SESSION['id_user'])) {
    // Arahkan paksa ke login.php
    echo "<script>
            alert('Akses Ditolak! Kamu harus login dulu untuk bisa booking kamar.');
            window.location.href='login.php?pesan=wajib_login';
          </script>";
    exit(); // Hentikan semua proses di bawahnya
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Form - Kos Aqsya Residence</title>
  <link rel="stylesheet" href="assets/css/global.css" />
  <link rel="stylesheet" href="assets/css/detail.css" />
  <link rel="stylesheet" href="assets/css/booking.css" />
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
      <a href="detail.php" class="back-link">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali
      </a>

      <div class="booking-card">
        <h2 class="booking-card-title">Booking Form</h2>
        <p class="booking-card-subtitle">Isi Detail untuk melanjutkan proses</p>

        <div class="room-info-box">
          <div class="room-info-left">
            <h3>Deluxe Room A1</h3>
            <p>Kaliwungu</p>
          </div>
          <div class="room-info-right">
            <h3>Rp 1.000.000</h3>
            <p>Per Bulan</p>
          </div>
        </div>

        <form action="pembayaran.php" method="POST">
          <div class="section-heading">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Informasi Personal
          </div>
          <div class="form-grid">
            <div class="input-group">
              <label>Nama Lengkap *</label>
              <input type="text" name="nama" value="<?php echo htmlspecialchars($user_name); ?>" required>
            </div>
            <div class="input-group">
              <label>NIK (Nomor id) *</label>
              <input type="text" name="nik" required>
            </div>
            <div class="input-group">
              <label>Nomor Telepon</label>
              <input type="text" name="telepon">
            </div>
            <div class="input-group">
              <label>Alamat Rumah Sekarang</label>
              <input type="text" name="alamat">
            </div>
            <div class="input-group">
              <label>Nomor Telepon Keluarga</label>
              <input type="text" name="telepon_keluarga">
            </div>
            <div class="input-group">
              <label>Jenis Kelamin *</label>
              <select name="gender" required>
                <option value="">Pilih...</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
              </select>
            </div>
          </div>

          <div class="section-heading">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            Periode Pemesanan
          </div>
          <div class="form-grid">
            <div class="input-group">
              <label>Check-in Date *</label>
              <input type="date" name="checkin" value="2025-08-14" required>
            </div>
            <div class="input-group">
              <label>Check-out Date *</label>
              <input type="date" name="checkout" value="2025-11-14" required>
            </div>
          </div>

          <button type="submit" class="btn-primary">Proses Pembayaran</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
