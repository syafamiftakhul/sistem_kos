<?php
session_start();
include '../koneksi.php'; // Hubungkan ke database
/** @var mysqli $koneksi */

// 1. Cek login pake id_user biar sinkron
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// 2. Ambil data no_ktp si customer yang login
$query_cust = mysqli_query($koneksi, "SELECT no_ktp FROM customer WHERE id_user = '$id_user'");
$data_cust = mysqli_fetch_assoc($query_cust);
$no_ktp = $data_cust['no_ktp'] ?? '';

// 3. Tangkap id_kamar dari lemparan URL (GET) atau body (POST)
$id_kamar = $_GET['id_kamar'] ?? $_POST['id_kamar'] ?? '';

// 4. Proses pas form-nya di-submit (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kita tetep ambil inputan 'subjek' dari HTML form di bawah
    $subjek     = mysqli_real_escape_string($koneksi, $_POST['subjek']);
    $deskripsi  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $tgl_lapor  = date('Y-m-d'); // Tanggal otomatis hari ini sesuai database

    // Validasi dasar biar data gak kosong (Variabel $subjek sekarang aman dicek)
    if (!empty($no_ktp) && !empty($id_kamar) && !empty($subjek) && !empty($deskripsi)) {
        
        // Trik pintar: Gabungkan subjek dan deskripsi menjadi satu teks panjang
        $deskripsi_lengkap = "[" . $subjek . "] " . $deskripsi;
        
        // FIX KUNCI: Query bersih tanpa kolom 'subjek' & tanda kutip SQL-nya sudah balance
        $query_insert = "INSERT INTO pengaduan (id_kamar, no_ktp, deskripsi, tgl_lapor) 
                         VALUES ('$id_kamar', '$no_ktp', '$deskripsi_lengkap', '$tgl_lapor')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            echo "<script>alert('Keluhan berhasil dikirim ke database!'); window.location.href='dashboard_private_user.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal mengirim keluhan: " . mysqli_error($koneksi) . "');</script>";
        }
    } else {
        echo "<script>alert('Gagal! Data kamar atau profil Anda belum lengkap.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kirim Keluhan - Kos Aqsya Residence</title>

  <link rel="stylesheet" href="../assets/css/global.css" />
  <link rel="stylesheet" href="../assets/css/detail.css" />
  <link rel="stylesheet" href="../assets/css/dashboard_private_user.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
</head>
<body>
  <div class="dashboard-user1">
    <header class="detail-header" style="display: flex; align-items: center; padding: 16px 40px; border-bottom: 1px solid #E5E7EB; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
      <div class="logo-area" style="display: flex; align-items: center;">
        <div class="key" style="cursor:pointer; display: flex; align-items: center; justify-content: center; background-color: #81A6C6; width: 36px; height: 36px; border-radius: 8px; margin-right: 12px;" onclick="window.location.href='../index.php'">
          <img src="../assets/img/key.png" alt="Logo" style="width: 18px; filter: brightness(0) invert(1);">
        </div>
        <h1 class="logo-text" style="cursor:pointer; margin: 0; font-size: 1.15rem; font-weight: 700; color: #81A6C6; letter-spacing: -0.02em;" onclick="window.location.href='../index.php'">Kos Aqsya Residence</h1>
      </div>
    </header>

    <main class="dashboard-container" style="padding: 40px; max-width: 1000px; margin: 0 auto;">
      <a href="dashboard_private_user.php" class="back-link" style="display:inline-flex; align-items:center; text-decoration:none; color:#6B7280; margin-bottom: 24px; font-weight:500; font-size: 14px;">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali
      </a>

      <div class="dash-card" style="max-width: 800px; margin: 0 auto; background: #fff; border: 1px solid #E5E7EB; border-radius: 12px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div class="form-header" style="display: flex; align-items: center; margin-bottom: 24px;">
          <div class="form-icon-wrapper" style="display: flex; align-items: center; justify-content: center; background: #EF4444; width: 48px; height: 48px; border-radius: 8px; margin-right: 16px;">
            <i class="fas fa-exclamation-circle" style="font-size: 24px; color: #fff;"></i>
          </div>
          <div class="form-title">
            <h2 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #1F2937;">Kirim Keluhan</h2>
            <p style="margin: 4px 0 0 0; font-size: 0.875rem; color: #6B7280;">Ceritakan masalah yang sedang kamu alami</p>
          </div>
        </div>

        <form action="pengaduan.php" method="POST">
          <input type="hidden" name="id_kamar" value="<?php echo htmlspecialchars($id_kamar); ?>">
          
          <div class="form-group-vertical" style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Tanggal</label>
            <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" readonly style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 6px; background-color: #F3F4F6; color: #9CA3AF; outline: none; box-sizing: border-box;">
          </div>
          <div class="form-group-vertical" style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Subjek</label>
            <input type="text" name="subjek" placeholder="Masukkan Subjek. . ." required style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 6px; outline: none; box-sizing: border-box; font-family: 'Inter', sans-serif;">
          </div>
          <div class="form-group-vertical" style="margin-bottom: 24px;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Deskripsi</label>
            <textarea name="deskripsi" placeholder="Tambahkan Deskripsi. . ." required style="width: 100%; min-height: 120px; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 6px; outline: none; box-sizing: border-box; font-family: 'Inter', sans-serif; resize: vertical;"></textarea>
          </div>
          
          <div class="form-actions" style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #E5E7EB; padding-top: 20px;">
            <div></div> <!-- Spacer -->
            <div class="btn-group-right" style="display: flex; gap: 12px;">
              <button type="button" class="btn-outline" onclick="window.location.href='dashboard_private_user.php'" style="padding: 10px 20px; border: 1px solid #D1D5DB; background: #fff; border-radius: 6px; font-weight: 500; color: #374151; cursor: pointer; transition: all 0.2s;">Batal</button>
              <button type="submit" class="btn-solid" style="padding: 10px 24px; border: none; background: #EF4444; color: #fff; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Kirim</button>
            </div>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
