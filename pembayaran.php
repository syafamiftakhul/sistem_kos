<?php
session_start();
include "koneksi.php";

// Tangkap data dari form booking
$nama           = $_POST['nama'] ?? 'Guest';
$no_ktp         = $_POST['no_ktp'] ?? '';
$no_hp          = $_POST['no_hp'] ?? '';
$alamat         = $_POST['alamat'] ?? '';
$tgl_masuk      = $_POST['tgl_masuk'] ?? date('Y-m-d');
$periode        = (int)($_POST['periode'] ?? 1);
$harga_satuan   = (int)($_POST['harga_satuan'] ?? 0);

// Hitung total
$total_bayar    = $harga_satuan * $periode;

// Hitung tanggal check-out otomatis (tambah bulan sesuai periode)
$date           = new DateTime($tgl_masuk);
$date->modify("+$periode month");
$tgl_keluar     = $date->format('d-m-Y');

// Ambil detail kamar buat ditampilin di ringkasan
$query_detail = mysqli_query($koneksi, "SELECT kamar.nomor_kamar, tipe_kamar.nama_tipe 
                                        FROM kamar 
                                        JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
                                        WHERE kamar.id_kamar = '$id_kamar'");

// Berikan nilai default jika query gagal atau id_kamar tidak ditemukan / bernilai null
$detail = mysqli_fetch_assoc($query_detail) ?? ['nama_tipe' => 'Tidak Diketahui', 'nomor_kamar' => '-'];

// Gabungin nama tipe dan nomor kamarnya
$nama_kamar_lengkap = $detail['nama_tipe'] . " - " . $detail['nomor_kamar'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran - Kos Aqsya Residence</title>
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
          <img src="assets/img/key.png" alt="Logo" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
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

          <div class="payment-method active" onclick="showPayment('bank')">
            <input type="radio" name="payment" id="radio-bank" checked>
            <div class="payment-method-info">
              <h4>Bank Transfer</h4>
              <p>BCA, Mandiri, BNI, BRI</p>
            </div>
          </div>

          <div class="payment-method" onclick="showPayment('wallet')">
            <input type="radio" name="payment" id="radio-wallet">
            <div class="payment-method-info">
              <h4>E-Wallet</h4>
              <p>GoPay, Dana, OVO, ShopeePay</p>
            </div>
          </div>

          <div id="detail-bank" class="bank-details-box">
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
            </div>
          </div>

          <div id="detail-wallet" class="bank-details-box" style="display: none;">
            <h4>Detail E-Wallet Account</h4>
            <div class="bank-grid">
              <div class="bank-item">
                <div class="bank-name">GoPay - Admin Kos</div>
                <div class="bank-acc">08123456789</div>
              </div>
              <div class="bank-item">
                <div class="bank-name">Dana - Aqsya Res</div>
                <div class="bank-acc">08123456789</div>
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

          <form action="proses_konfirmasi.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nama" value="<?php echo $nama; ?>">
            <input type="hidden" name="no_ktp" value="<?php echo $no_ktp; ?>">
            <input type="hidden" name="no_hp" value="<?php echo $no_hp; ?>">
            <input type="hidden" name="alamat" value="<?php echo $alamat; ?>">
            <input type="hidden" name="tgl_masuk" value="<?php echo $tgl_masuk; ?>">
            <input type="hidden" name="periode" value="<?php echo $periode; ?>">
            <input type="hidden" name="total_bayar" value="<?php echo $total_bayar; ?>">

            <div class="upload-box" id="drop-zone">
              <input type="file" name="bukti_transfer" id="file-upload" accept="image/*" style="display:none;" onchange="previewImage()" required>
              <label for="file-upload" style="cursor:pointer; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">

                <div id="pre-upload">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #6c757d; margin-bottom: 10px;">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                  </svg>
                  <p style="color: #495057; font-weight: 500;">Pilih file bukti transfer</p>
                </div>

                <div id="post-upload" style="display: none; width: 100%; height: 200px; overflow: hidden; border-radius: 8px;">
                  <img id="image-preview" src="#" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
              </label>
            </div>

            <input type="hidden" name="konfirmasi" value="1">
            <button type="submit" class="btn-primary">Konfirmasi Pembayaran</button>
          </form>
        </div>

        <div class="summary-card">
          <h3>Rincian Pesanan</h3>
          <div class="summary-item">
            <div class="label">Kamar</div>
            <div class="value"><?php echo htmlspecialchars($nama_kamar_lengkap); ?></div>
          </div>
          <div class="summary-item">
            <div class="label">Nama Penghuni</div>
            <div class="value"><?php echo htmlspecialchars($nama); ?></div>
          </div>
          <div class="summary-item">
            <div class="label">Check-in</div>
            <div class="value"><?php echo date('d-m-Y', strtotime($tgl_masuk)); ?></div>
          </div>
          <div class="summary-item">
            <div class="label">Check-out</div>
            <div class="value"><?php echo $tgl_keluar; ?></div>
          </div>
          <div class="summary-item">
            <div class="label">Durasi</div>
            <div class="value"><?php echo $periode; ?> Bulan</div>
          </div>

          <div class="summary-divider"></div>

          <div class="summary-row">
            <span>Tarif Bulanan</span>
            <span>Rp <?php echo number_format($harga_satuan, 0, ',', '.'); ?></span>
          </div>
          <div class="summary-row">
            <span>Durasi</span>
            <span>x <?php echo $periode; ?></span>
          </div>

          <div class="summary-divider"></div>

          <div class="summary-row total">
            <span>Total</span>
            <span class="val">Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></span>
          </div>
        </div>
      </div>
    </main>
  </div>
  <script>
    function previewImage() {
      const input = document.getElementById('file-upload');
      const preUpload = document.getElementById('pre-upload');
      const postUpload = document.getElementById('post-upload');
      const imagePreview = document.getElementById('image-preview');

      if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileSize = file.size / 1024 / 1024; // Hitung ke MB

        // 1. Validasi Maksimal 2MB
        if (fileSize > 2) {
          alert("Waduh bre, filenya kegedean! Maksimal 2MB ya.");
          input.value = ""; // Reset input biar gak jadi ke-upload
          return;
        }

        // 2. Kalau aman, lanjut nampilin preview
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          preUpload.style.display = 'none';
          postUpload.style.display = 'block';
        }
        reader.readAsDataURL(file);
      }
    }

    function showPayment(type) {
      const bankBox = document.getElementById('detail-bank');
      const walletBox = document.getElementById('detail-wallet');
      const methods = document.querySelectorAll('.payment-method');

      methods.forEach(m => m.classList.remove('active'));

      if (type === 'bank') {
        bankBox.style.display = 'block';
        walletBox.style.display = 'none';
        methods[0].classList.add('active');
        document.getElementById('radio-bank').checked = true;
      } else {
        bankBox.style.display = 'none';
        walletBox.style.display = 'block';
        methods[1].classList.add('active');
        document.getElementById('radio-wallet').checked = true;
      }
    }
  </script>
</body>

</html>