<?php
session_start();

if (!isset($_SESSION['akses']) || $_SESSION['akses'] != 1) {
    
    header("Location: ../login.php"); 
    exit(); // Penting agar sisa kode di bawah tidak dijalankan
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kos Aqsya</title>
    <link rel="stylesheet" href="../assets/css/dashboard_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="dashboard-wrapper">
        <aside class="sidebar-admin">
            <div class="sidebar-logo">
                <img src="../assets/img/logo-menu.png" alt="Menu">
            </div>
            <nav class="nav-icons">
                <a href="#" class="nav-link active"><img src="../assets/img/home-icon.png" alt="Home"></a>
                <a href="#" class="nav-link"><img src="../assets/img/key-icon.png" alt="Rooms"></a>
                <a href="#" class="nav-link"><img src="../assets/img/user-icon.png" alt="Tenants"></a>
                <a href="#" class="nav-link"><img src="../assets/img/wallet-icon.png" alt="Finance"></a>
                <a href="#" class="nav-link"><img src="../assets/img/cart-icon.png" alt="Orders"></a>
                <a href="#" class="nav-link"><img src="../assets/img/chat-icon.png" alt="Reports"></a>
                <a href="#" class="nav-link"><img src="../assets/img/doc-icon.png" alt="Logs"></a>
                <a href="#" class="nav-link"><img src="../assets/img/grid-icon.png" alt="Apps"></a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="content-header">
                <h1>DASHBOARD</h1>
                <p>Ringkasan Manajemen Kos Anda</p>
            </header>

            <section class="stats-row">
                <div class="card-stat">
                    <div class="stat-text">
                        <span>Total Kamar</span>
                        <h3>24</h3>
                        <small>24 Terisi, 4 Kosong</small>
                    </div>
                    <div class="stat-icon-box blue"><img src="../assets/img/key-small.png" alt=""></div>
                </div>
                <div class="card-stat">
                    <div class="stat-text">
                        <span>Total Penghuni</span>
                        <h3>24</h3>
                        <small>2 baru bulan ini</small>
                    </div>
                    <div class="stat-icon-box light-blue"><img src="../assets/img/user-small.png" alt=""></div>
                </div>
                <div class="card-stat">
                    <div class="stat-text">
                        <span>Total Pembayaran</span>
                        <h3>Rp 18.5 jt</h3>
                        <small>18 dari 20 lunas</small>
                    </div>
                    <div class="stat-icon-box beige">$</div>
                </div>
                <div class="card-stat">
                    <div class="stat-text">
                        <span>Total Tunggakan</span>
                        <h3>Rp 1.5 jt</h3>
                        <small>2 Penghuni</small>
                    </div>
                    <div class="stat-icon-box orange">!</div>
                </div>
            </section>

            <section class="data-grid">
                <div class="info-panel">
                    <h4>Pembayaran Terbaru</h4>
                    <div class="data-item">
                        <div class="item-info">
                            <strong>Aulia Khanza</strong>
                            <span>Kamar A-101 • 5 Apr 2026</span>
                        </div>
                        <div class="item-status">
                            <strong>Rp. 1.600.000</strong>
                            <span class="badge success">Lunas</span>
                        </div>
                    </div>
                    <div class="data-item">
                        <div class="item-info">
                            <strong>Aulia Khanza</strong>
                            <span>Kamar A-101 • 5 Apr 2026</span>
                        </div>
                        <div class="item-status">
                            <strong>Rp. 1.600.000</strong>
                            <span class="badge warning">Pending</span>
                        </div>
                    </div>
                </div>

                <div class="info-panel">
                    <h4>Kontrak Akan Berakhir</h4>
                    <div class="data-item">
                        <div class="item-info">
                            <strong>Queensha Imola</strong>
                            <span>Kamar A-101</span>
                        </div>
                        <div class="item-status">
                            <span>30 April 2026</span>
                            <span class="badge info">30 Hari Lagi</span>
                        </div>
                    </div>
                </div>

                <div class="info-panel">
                    <h4>Pengaduan Terbaru</h4>
                    <div class="data-item">
                        <div class="item-info">
                            <strong>AC Rusak</strong>
                            <span>Kamar A-101 - Queensha Imola</span>
                        </div>
                        <div class="item-status">
                            <span>30 April 2026</span>
                            <span class="badge success">Selesai</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

</body>
</html>