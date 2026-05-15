<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

// 1. Ambil filter status dari URL
$filter = isset($_GET['status']) ? $_GET['status'] : 'semua';

// QUERY JOIN: Kita ambil data dari Pesanan sebagai pusatnya
$query = "SELECT p.*, c.nama, k.nomor_kamar, 
                 p.status_pesanan as status_transaksi, 
                 tk.harga as jml_bayar
          FROM pesanan p
          JOIN customer c ON p.no_ktp = c.no_ktp
          JOIN kamar k ON p.id_kamar = k.id_kamar
          JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe";

if ($filter != 'semua') {
    $query .= " WHERE p.status_pesanan = '$filter'";
}

$result = mysqli_query($koneksi, $query);

// Ambil filter status dan paksa jadi huruf kecil
$filter = isset($_GET['status']) ? strtolower($_GET['status']) : 'semua';



// 3. Hitung Ringkasan Income dari tabel PESANAN (karena data transaksi lu masih kosong)
// Kita ambil harga dari tipe_kamar berdasarkan pesanan yang lunas
$q_lunas = "SELECT SUM(tk.harga) as total 
            FROM pesanan p
            JOIN kamar k ON p.id_kamar = k.id_kamar
            JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
            WHERE p.status_pesanan = 'lunas'";

$res_lunas = mysqli_query($koneksi, $q_lunas);
$total_lunas = mysqli_fetch_assoc($res_lunas)['total'] ?? 0;

// Hitung Pending
$q_pending = "SELECT SUM(tk.harga) as total FROM pesanan p 
              JOIN kamar k ON p.id_kamar = k.id_kamar 
              JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe 
              WHERE p.status_pesanan = 'pending'";
$total_pending = mysqli_fetch_assoc(mysqli_query($koneksi, $q_pending))['total'] ?? 0;

$total_terlambat = 0; // Set default dulu
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Pembayaran - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_admin.css">
    <link rel="stylesheet" href="../assets/css/pembayaran.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar-admin" id="sidebar">
            <div class="sidebar-logo">
                <img src="../assets/img/logo-menu.png" alt="Menu" id="btn-menu" style="cursor: pointer;">
                <span class="logo-text" style="font-weight: bold; margin-left: 10px;">Aqsya Kos</span>
            </div>

            <nav class="nav-icons">
                <a href="dashboard_admin.php" class="nav-link active">
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
                <a href="tipe_kamar.php" class='nav-link'>
                    <img src='../assets/img/type-icon.png' alt='Type'>
                    <span class='menu-text'>Tipe Kamar</span>
                </a>
                <a href="logout.php" class="nav-link">
                    <img src="../assets/img/logout-icon.png" alt="Logout">
                    <span class="menu-text">Logout</span>
                </a>
            </nav>
        </aside>
        <div class="main-content">


            <main class="main-content">
                <header>
                    <div class="header-title">
                        <h1>Manajemen Pembayaran</h1>
                        <p>Kelola Data Pembayaran Kos-kosan</p>
                    </div>
                </header>

                <section class="data-section">
                    <div class="action-bar">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Masukkan nama, kamar, atau telepon..">
                        </div>
                    </div>

                    <div class="category-filter">
                        <a href="pembayaran.php?status=semua" class="cat-item <?= ($filter == 'semua') ? 'active' : '' ?>">Semua</a>
                        <a href="pembayaran.php?status=lunas" class="cat-item <?= ($filter == 'lunas') ? 'active' : '' ?>">Lunas</a>
                        <a href="pembayaran.php?status=pending" class="cat-item <?= ($filter == 'pending') ? 'active' : '' ?>">Pending</a>
                        <a href="pembayaran.php?status=terlambat" class="cat-item <?= ($filter == 'terlambat') ? 'active' : '' ?>">Terlambat</a>
                    </div>

                    <div class="table-container">
                        <table class="pembayaran-table">
                            <thead>
                                <tr>
                                    <th>Penghuni</th>
                                    <th>Kamar</th>
                                    <th>Periode</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && mysqli_num_rows($result) > 0) : ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                                            <td><span class="badge-kamar">Kamar <?= htmlspecialchars($row['nomor_kamar']) ?></span></td>
                                            <td><?= htmlspecialchars($row['periode'] ?? 'Bulan Ini') ?></td>
                                            <td>Rp <?= number_format($row['jml_bayar'], 0, ',', '.') ?></td>
                                            <td>-</td>
                                            <td>
                                                <span class="badge-status <?= strtolower($row['status_transaksi']) ?>">
                                                    <?= htmlspecialchars($row['status_transaksi']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 50px; color: #999;">
                                            <i class="fas fa-receipt" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                                            Belum ada data pembayaran untuk kategori <strong><?= htmlspecialchars($filter) ?></strong>.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="income-summary">
                        <div class="income-card lunas">
                            <span class="label">Total Lunas</span>
                            <h3>Rp <?= number_format($total_lunas, 0, ',', '.') ?></h3>
                        </div>
                        <div class="income-card pending">
                            <span class="label">Total Pending</span>
                            <h3>Rp <?= number_format($total_pending / 1000000, 1) ?> jt</h3>
                        </div>
                        <div class="income-card terlambat">
                            <span class="label">Total Terlambat</span>
                            <h3>Rp <?= number_format($total_terlambat / 1000000, 1) ?> jt</h3>
                        </div>
                    </div>
                </section>
            </main>
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