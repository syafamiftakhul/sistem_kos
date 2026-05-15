<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<script>alert('Keluhan berhasil dikirim!'); window.location.href='user/dashboard_private_user.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kirim Keluhan - Kos Aqsya Residence</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            margin: 0;
            color: #111827;
            -webkit-font-smoothing: antialiased;
        }

        /* Header */
        header {
            display: flex; align-items: center; padding: 20px 40px; border-bottom: 1px solid #e2e8f0; background: #fff;
        }

        /* Layout */
        .main-content {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .btn-back {
            color: #6B7280;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 30px;
            font-weight: 500;
        }

        /* Card */
        .dash-card {
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            padding: 30px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .form-icon-wrapper {
            background: #E0F2FE;
            color: #0284C7;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-title h2 { margin: 0; font-size: 18px; font-weight: 700; color: #111827; }
        .form-title p { margin: 4px 0 0 0; font-size: 13px; color: #6B7280; }

        /* Form Controls */
        .form-group-vertical {
            margin-bottom: 20px;
        }
        .form-group-vertical label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #111827;
        }
        .form-group-vertical input,
        .form-group-vertical textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-family: inherit;
            font-size: 14px;
            color: #111827;
            box-sizing: border-box;
        }
        .form-group-vertical textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn-outline {
            background: white;
            border: 1px solid #D1D5DB;
            padding: 10px 30px;
            border-radius: 6px;
            color: #6B7280;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            text-align: center;
            width: 45%;
        }
        .btn-solid {
            background: #81A6C6;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
            width: 45%;
        }
    </style>
</head>
<body>

    <header>
        <div style="background-color: #81A6C6; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
            <a href="index.php" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                <img src="assets/img/key.png" alt="Logo" style="width: 20px; filter: brightness(0) invert(1);">
            </a>
        </div>
        <h1 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #81A6C6;">Kos Aqsya Residence</h1>
    </header>

    <div class="main-content">
        <a href="user/dashboard_private_user.php" class="btn-back">
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

            <form action="pengaduan.php" method="POST">
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
                    <a href="user/dashboard_private_user.php" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-solid">Kirim</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
