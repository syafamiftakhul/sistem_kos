<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['keluhan'])) {
        $_SESSION['keluhan'] = [];
    }
    $_SESSION['keluhan'][] = [
        'tanggal' => $_POST['tanggal'],
        'subjek' => $_POST['subjek'],
        'deskripsi' => $_POST['deskripsi']
    ];
    echo "<script>alert('Keluhan berhasil dikirim!'); window.location.href='dashboard.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kirim Keluhan - Kos Aqsya Residence</title>
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
      <a href="dashboard.php" class="back-link" style="display:inline-flex; align-items:center; text-decoration:none; color:#555; margin-bottom: 24px; font-weight:500;">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali
      </a>

      <div class="dash-card" style="max-width: 800px; margin: 0 auto;">
        <div class="form-header">
          <div class="form-icon-wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
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
