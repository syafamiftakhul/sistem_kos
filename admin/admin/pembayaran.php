<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

// Ambil filter status dari URL (default 'Semua')
$filter = isset($_GET['status']) ? $_GET['status'] : 'Semua';

// Query dasar JOIN antara Transaksi dan Customer sesuai ERD
$query = "SELECT t.*, c.nama 
          FROM transaksi t 
          JOIN customer c ON t.no_ktp = c.no_ktp";

if ($filter != 'Semua') {
    $query .= " WHERE t.status_transaksi = '$filter'";
}

$result = mysqli_query($koneksi, $query);

// Hitung Ringkasan Income (Berdasarkan status di DB lu)
$total_lunas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(jml_bayar) as total FROM transaksi WHERE status_transaksi = 'Lunas'"))['total'] ?? 0;
$total_pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(jml_bayar) as total FROM transaksi WHERE status_transaksi = 'Pending'"))['total'] ?? 0;
$total_terlambat = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(jml_bayar) as total FROM transaksi WHERE status_transaksi = 'Terlambat'"))['total'] ?? 0;
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
        </aside> <div class="main-content">


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
                    <a href="?status=Semua" class="cat-item <?= $filter == 'Semua' ? 'active' : '' ?>">Semua</a>
                    <a href="?status=Lunas" class="cat-item <?= $filter == 'Lunas' ? 'active' : '' ?>">Lunas</a>
                    <a href="?status=Pending" class="cat-item <?= $filter == 'Pending' ? 'active' : '' ?>">Pending</a>
                    <a href="?status=Terlambat" class="cat-item <?= $filter == 'Terlambat' ? 'active' : '' ?>">Terlambat</a>
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
                            <?php if (mysqli_num_rows($result) > 0) : ?>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                                        <td><span class="badge-kamar">Kamar <?= $row['id_kamar'] ?></span></td>
                                        <td><?= $row['periode'] ?></td>
                                        <td>Rp <?= number_format($row['jml_bayar'], 0, ',', '.') ?></td>
                                        <td><?= ($row['tgl_transaksi'] && $row['tgl_transaksi'] != '0000-00-00') ? date('d M Y', strtotime($row['tgl_transaksi'])) : '-' ?></td>
                                        <td>
                                            <span class="badge-status <?= strtolower($row['status_transaksi']) ?>">
                                                <?= $row['status_transaksi'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 50px; color: #999;">
                                        <i class="fas fa-receipt" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                                        Belum ada data pembayaran untuk kategori <strong><?= $filter ?></strong>.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="income-summary">
                    <div class="income-card lunas">
                        <span class="label">Total Lunas</span>
                        <h3>Rp <?= number_format($total_lunas / 1000000, 1) ?> jt</h3>
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