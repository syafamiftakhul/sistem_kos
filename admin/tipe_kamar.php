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
    <title>Kelola Tipe Kamar - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/tipe_kamar_admin.css">
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
                <a href="laporan.php" class="nav-link">
                    <img src="../assets/img/report-icon.png" alt="Reports">
                    <span class="menu-text">Laporan</span>
                </a>
                <a href="tipe_kamar.php" class="nav-link active">
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
            <header class="header-title">
                <h1>Kelola Tipe Kamar</h1>
                <p>Pengelolaan Tipe Kamar dan Tambah Tipe Kamar</p>
            </header>

            <div class="action-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="cari nama, email atau role...">
                </div>
                <button class="btn-tambah" id="btn-tambah-tipe">
                    <i class="fas fa-plus"></i> Tambah Tipe Kamar
                </button>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="tipe-table">
                    <thead>
                        <tr>
                            <th>Id Tipe</th>
                            <th>Nama Tipe</th>
                            <th>Fasilitas</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #888;">Belum ada data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Modal Overlay -->
            <div class="modal-overlay" id="modal-tipe">
                <div class="modal-box">
                    <i class="fas fa-times close-btn" id="close-modal"></i>
                    <h2>Tambah Tipe</h2>
                    <form action="proses_tambah_tipe.php" method="POST">
                        <div class="form-group">
                            <label>Id Tipe</label>
                            <input type="text" name="id_tipe" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Tipe</label>
                            <input type="text" name="nama_tipe" required>
                        </div>
                        <div class="form-group">
                            <label>Fasilitas</label>
                            <input type="text" name="fasilitas" required>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" name="harga" required>
                        </div>
                        <button type="submit" class="btn-submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sidebar Toggle
        const btnMenu = document.getElementById('btn-menu');
        const sidebar = document.getElementById('sidebar');

        btnMenu.onclick = function() {
            sidebar.classList.toggle('expand');
        }

        // Modal Logic
        const btnTambah = document.getElementById('btn-tambah-tipe');
        const modalTipe = document.getElementById('modal-tipe');
        const closeModal = document.getElementById('close-modal');

        btnTambah.onclick = function() {
            modalTipe.classList.add('active');
        }

        closeModal.onclick = function() {
            modalTipe.classList.remove('active');
        }

        // Tutup modal kalau klik di luar box
        window.onclick = function(event) {
            if (event.target == modalTipe) {
                modalTipe.classList.remove('active');
            }
        }
    </script>
</body>
</html>
