<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil no_ktp & id_kamar dari data customer yang login
$query_user = mysqli_query($koneksi, "
    SELECT c.no_ktp, p.id_kamar 
    FROM customer c
    LEFT JOIN pesanan p ON c.no_ktp = p.no_ktp
    WHERE c.id_user = '$id_user' 
    ORDER BY p.id_pesanan DESC LIMIT 1
");
$data_user = mysqli_fetch_assoc($query_user);

$no_ktp   = $data_user['no_ktp'] ?? null;
$id_kamar = $data_user['id_kamar'] ?? null;

// Proses simpan data apa adanya ke DB
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $tanggal   = mysqli_real_escape_string($koneksi, $_POST['tanggal']);

    if ($no_ktp && $id_kamar) {
        // Query bersih murni sesuai screenshot DB lu rekk
        $query_insert = "INSERT INTO pengaduan (no_ktp, id_kamar, deskripsi, tgl_lapor, status_pengaduan) 
                         VALUES ('$no_ktp', '$id_kamar', '$deskripsi', '$tanggal', 'menunggu')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            echo "<script>alert('Keluhan berhasil dikirim!'); window.location.href='dashboard_user.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal mengirim keluhan: " . mysqli_error($koneksi) . "');</script>";
        }
    } else {
        echo "<script>alert('Gagal! Anda belum memiliki kamar aktif untuk dilaporkan.'); window.location.href='dashboard_user.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kirim Keluhan - Kos Aqsya Residence</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="assets/css/pengaduan.css">
</head>
<body>

    <header>
        <div style="background-color: #81A6C6; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
            <a href="../index.php" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                <img src="../assets/img/key.png" alt="Logo" style="width: 20px; filter: brightness(0) invert(1);">
            </a>
        </div>
        <h1 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #81A6C6;">Kos Aqsya Residence</h1>
    </header>

    <div class="main-content">
        <a href="dashboard_user.php" class="btn-back">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>

        <div class="dash-card">
            <div class="form-header">
                <div class="form-icon-wrapper">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

            <form action="" method="POST">
                <div class="form-group-vertical">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" required>
                </div>
                <div class="form-group-vertical">
                    <label>Subjek</label>
                    <input type="text" name="subjek" placeholder="Masukkan Subjek . . ." required>
                </div>
                <div class="form-group-vertical">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" placeholder="Tambahkan Deskripsi . . ." required></textarea>
                </div>
                
                <div class="form-actions">
                    <a href="dashboard_user.php" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-solid">Kirim</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>