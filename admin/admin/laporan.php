<?php
session_start();
// Uncomment jika butuh proteksi session:
// if (!isset($_SESSION['login'])) {
//     header("Location: ../login.php");
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/laporan_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar-admin" id="sidebar">
            <div class="sidebar-logo">
                <img src="../assets/img/logo-menu.png" alt="Menu" id="btn-menu" style="cursor: pointer;">
                <div class="logo-text" style="margin-left: 10px; display: none;">
                    <div style="font-weight: bold; font-size: 16px; color: #333;">Aqsya Kos</div>
                    <div style="font-size: 11px; color: #888;">Manajemen Kos</div>
                </div>
            </div>

            <nav class="nav-icons">
                <a href="dashboard_admin.php" class="nav-link">
                    <img src="../assets/img/home-icon.png" alt="Home">
                    <span class="menu-text">Dashboard</span>
                </a>
                <a href="kamar.php" class="nav-link">
                    <img src="../assets/img/key-icon.png" alt="Rooms">
                    <span class="menu-text">Kamar</span>
                </a>
                <a href="penghuni.php" class="nav-link">
                    <img src="../assets/img/user-icon.png" alt="Tenants">
                    <span class="menu-text">Penghuni</span>
                </a>
                <a href="pembayaran.php" class="nav-link">
                    <img src="../assets/img/payment-icon.png" alt="Payments">
                    <span class="menu-text">Pembayaran</span>
                </a>
                <a href="pesanan.php" class="nav-link">
                    <img src="../assets/img/order-icon.png" alt="Orders">
                    <span class="menu-text">Pesanan</span>
                </a>
                <a href="pengaduan.php" class="nav-link">
                    <img src="../assets/img/complaint-icon.png" alt="Complaints">
                    <span class="menu-text">Pengaduan</span>
                </a>
                <a href="laporan.php" class="nav-link active">
                    <img src="../assets/img/report-icon.png" alt="Reports">
                    <span class="menu-text">Laporan</span>
                </a>
                <a href="tipe_kamar.php" class="nav-link">
                    <img src="../assets/img/type-icon.png" alt="Type">
                    <span class="menu-text">Tipe Kamar</span>
                </a>
                <a href="logout.php" class="nav-link">
                    <img src="../assets/img/logout-icon.png" alt="Logout">
                    <span class="menu-text">Logout</span>
                </a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="header-top">
                <div class="header-title">
                    <h1>Laporan</h1>
                    <p>Analisis dan laporan keuangan kos-kosan</p>
                </div>
                <div class="header-actions">
                    <div class="periode-filter">
                        <span>Periode:</span>
                        <select>
                            <option>6 Bulan</option>
                            <option>1 Tahun</option>
                            <option>Keseluruhan</option>
                        </select>
                    </div>
                    <a href="#" class="btn-export">
                        <i class="fas fa-download"></i> Export PDF
                    </a>
                </div>
            </header>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>pendapatan bulan ini</h3>
                    <div class="value">Rp 0</div>
                    <div class="indicator">
                        <i class="fas fa-minus" style="color: #888;"></i>
                        <span style="color: #888; font-weight: 600;">0%</span> vs bulan lalu
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3>Tingkat Hunian</h3>
                    <div class="value">0%</div>
                    <div class="indicator">
                        <i class="fas fa-minus" style="color: #888;"></i>
                        <span style="color: #888; font-weight: 600;">0%</span> vs bulan lalu
                    </div>
                </div>

                <div class="stat-card">
                    <h3>Rata-Rata Sewa</h3>
                    <div class="value">Rp 0</div>
                    <div class="indicator">
                        <i class="fas fa-minus" style="color: #888;"></i>
                        <span style="color: #888; font-weight: 600;">0%</span> vs bulan lalu
                    </div>
                </div>

                <div class="stat-card">
                    <h3>Tunggakan</h3>
                    <div class="value">Rp 0</div>
                    <div class="indicator">
                        <i class="fas fa-minus" style="color: #888;"></i>
                        <span style="color: #888; font-weight: 600;">0%</span> vs bulan lalu
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="laporan-table">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Pendapatan</th>
                            <th>Target</th>
                            <th>Kamar Terisi</th>
                            <th>Tingkat Hunian</th>
                            <th>Pencapaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #888;">Belum ada data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const btnMenu = document.getElementById('btn-menu');
        const sidebar = document.getElementById('sidebar');

        btnMenu.onclick = function() {
            sidebar.classList.toggle('expand');
        }
    </script>
</body>
</html>
