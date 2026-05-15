<?php
session_start();
include "koneksi.php";

$id_tipe = $_GET['id_tipe'] ?? $_GET['id'] ?? '';

$query = mysqli_query($koneksi, "SELECT * FROM tipe_kamar WHERE id_tipe = '$id_tipe'");
$data_tipe = mysqli_fetch_array($query);

if ($data_tipe) {
    $tampil_tipe = $data_tipe['nama_tipe'];
    $tampil_harga = $data_tipe['harga'];
} else {
    // Hardcode fallback jika tabel kosong
    if ($id_tipe == 2) {
        $tampil_tipe = "Deluxe Room A2";
        $tampil_harga = 700000;
    } elseif ($id_tipe == 3) {
        $tampil_tipe = "Standard Room";
        $tampil_harga = 400000;
    } elseif ($id_tipe == 1) {
        $tampil_tipe = "Deluxe Room A1";
        $tampil_harga = 1000000;
    } else {
        $res = mysqli_query($koneksi, "SELECT * FROM tipe_kamar LIMIT 1");
        $d = mysqli_fetch_array($res);
        $tampil_tipe = $d['nama_tipe'] ?? "Database Kosong";
        $tampil_harga = $d['harga'] ?? 0;
        if ($d) {
            $id_tipe = $d['id_tipe']; // Update id_tipe agar tombol kembali berfungsi jika tidak ada di URL
        }
    }
}

$back_link = "index.php";

// Ambil ID kamar pertama dari database untuk detail_kamar1 (tanpa ORDER BY, persis seperti di detail_kamar1.php)
$q1 = mysqli_query($koneksi, "SELECT id_tipe FROM tipe_kamar LIMIT 1");
$row1 = mysqli_fetch_assoc($q1);
$id_kamar1 = $row1 ? $row1['id_tipe'] : 1;

// Ambil ID kamar kedua dari database untuk detail_kamar2
$q2 = mysqli_query($koneksi, "SELECT id_tipe FROM tipe_kamar LIMIT 1 OFFSET 1");
$row2 = mysqli_fetch_assoc($q2);
$id_kamar2 = $row2 ? $row2['id_tipe'] : 2;

if ($id_tipe == $id_kamar1) {
    $back_link = "detail_kamar1.php";
} elseif ($id_tipe == $id_kamar2 || $id_tipe == 2) {
    $back_link = "detail_kamar2.php";
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
      <a href="<?php echo $back_link; ?>" class="back-link">
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
            <h3><?php echo htmlspecialchars($tampil_tipe); ?></h3>
            <p>Kaliwungu</p>
          </div>
          <div class="room-info-right">
            <h3>Rp <?php echo number_format((float)$tampil_harga, 0, ',', '.'); ?></h3>
            <p>Per Bulan</p>
          </div>
        </div>

        <form action="pembayaran.php" method="POST">
          <input type="hidden" name="id_tipe" value="<?php echo $id_tipe; ?>">
          <input type="hidden" name="harga_satuan" value="<?php echo $tampil_harga; ?>">

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
              <input type="text" name="nama" required>
            </div>
            <div class="input-group">
              <label>NIK (no_ktp) *</label>
              <input type="text" name="no_ktp" required>
            </div>
            <div class="input-group">
              <label>Nomor Telepon (no_hp) *</label>
              <input type="text" name="no_hp" required>
            </div>
            <div class="input-group">
              <label>Alamat *</label>
              <input type="text" name="alamat" required>
            </div>
            <div class="input-group">
              <label>Nomor Telepon Keluarga *</label>
              <input type="text" name="kontak_keluarga" required>
            </div>
            <div class="input-group">
              <label>Jenis Kelamin *</label>
              <select name="jenis_kelamin" required>
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
              <label>Tanggal Masuk *</label>
              <input type="date" name="tgl_masuk" required>
            </div>
            <div class="input-group">
              <label>Durasi (Bulan) *</label>
              <select name="periode" required>
                <option value="1">1 Bulan</option>
                <option value="3">3 Bulan</option>
                <option value="6">6 Bulan</option>
              </select>
            </div>
          </div>

          <button type="submit" class="btn-primary">Proses Pembayaran</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>