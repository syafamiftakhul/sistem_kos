<?php
session_start();
include '../koneksi.php'; // Hubungkan ke database
/** @var mysqli $koneksi */

// 1. Cek login pake id_user biar sinkron
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// 2. Ambil data no_ktp si customer yang login
$query_cust = mysqli_query($koneksi, "SELECT no_ktp FROM customer WHERE id_user = '$id_user'");
$data_cust = mysqli_fetch_assoc($query_cust);
$no_ktp = $data_cust['no_ktp'] ?? '';

// 3. Tangkap id_kamar dari lemparan URL (GET)
$id_kamar = $_GET['id_kamar'] ?? '';

// 4. Proses pas form-nya di-submit (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subjek     = mysqli_real_escape_string($koneksi, $_POST['subjek']);
    $deskripsi  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $tgl_lapor  = date('Y-m-d'); // Tanggal otomatis hari ini sesuai database

    // Validasi dasar biar data gak kosong
    if (!empty($no_ktp) && !empty($id_kamar) && !empty($subjek) && !empty($deskripsi)) {
        
        // SINKRONISASI ERD: Langsung hajar INSERT ke tabel pengaduan
        $query_insert = "INSERT INTO pengaduan (id_kamar, no_ktp, subjek, deskripsi, tgl_lapor) 
                         VALUES ('$id_kamar', '$no_ktp', '$subjek', '$deskripsi', '$tgl_lapor')";
        
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

  <link rel="stylesheet" href="./assets/css/global.css" />
  <link rel="stylesheet" href="./assets/css/detail.css" />
  <link rel="stylesheet" href="./assets/css/dashboard.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
</head>
<body>
  <div class="dashboard-user1">
    <header class="detail-header">
      <div class="logo-area">
        <div class="key" style="cursor:pointer; display: flex; align-items: center; justify-content: center; background-color: #81A6C6; width: 36px; height: 36px; border-radius: 8px;" onclick="window.location.href='index.php'">
          <img src="../assets/img/key.png" alt="Logo" style="width: 20px; filter: brightness(0) invert(1);">
        </div>
        <h1 class="logo-text" style="cursor:pointer;" onclick="window.location.href='index.php'">Kos Aqsya Residence</h1>
      </div>
    </header>

    <main class="dashboard-container">
      <a href="dashboard_private_user.php" class="back-link" style="display:inline-flex; align-items:center; text-decoration:none; color:#555; margin-bottom: 24px; font-weight:500;">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali
      </a>

      <div class="dash-card" style="max-width: 800px; margin: 0 auto;">
        <div class="form-header">
          <div class="form-icon-wrapper" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-exclamation-circle" style="font-size: 24px; color: #fff;"></i>
          </div>
          <div class="form-title">
            <h2>Kirim Keluhan</h2>
            <p>Ceritakan masalah yang sedang kamu alami</p>
          </div>
        </div>

        <form action="pengaduan.php" method="POST">
          <div class="form-group-vertical">
            <label>Tanggal</label>
            <input type="date" name="tanggal" required>
          </div>
          <div class="form-group-vertical">
            <label>Subjek</label>
            <input type="text" name="subjek" placeholder="Masukkan Subjek. . ." required>
          </div>
          <div class="form-group-vertical">
            <label>Deskripsi</label>
            <textarea name="deskripsi" placeholder="Tambahkan Deskripsi. . ." required></textarea>
          </div>
          
          <div class="form-actions">
            <div></div> <!-- Spacer -->
            <div class="btn-group-right">
              <button type="button" class="btn-outline" onclick="window.location.href='dashboard.php'">Batal</button>
              <button type="submit" class="btn-solid">Kirim</button>
            </div>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
