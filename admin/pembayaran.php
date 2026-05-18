<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

// Ambil filter status dari URL (default 'Semua')
$filter = isset($_GET['status']) ? $_GET['status'] : 'Semua';

// Query dasar JOIN antara Transaksi dan Customer sesuai ERD
$query = "SELECT t.*, c.nama, p.id_kamar 
          FROM transaksi t 
          JOIN customer c ON t.no_ktp = c.no_ktp
          LEFT JOIN pesanan p ON t.id_pesanan = p.id_pesanan";

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
            <div class="sidebar-logo" style="display: flex; align-items: center; padding: 20px 25px;">
                <i class="fas fa-bars" id="btn-menu" style="cursor: pointer; font-size: 24px; color: #81A6C6; transition: 0.3s;"></i>
                <span class="logo-text" style="font-weight: bold; margin-left: 15px; color: #81A6C6; font-size: 18px;">Aqsya Kos</span>
            </div>

            <nav class="nav-icons">
                <a href="dashboard_admin.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'dashboard_admin.php') ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
                <a href="kamar.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kamar.php') ? 'active' : '' ?>">
                    <i class="fas fa-key"></i>
                    <span class="menu-text">Kamar</span>
                </a>
                <a href="penghuni.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'penghuni.php') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span class="menu-text">Penghuni</span>
                </a>
                <a href="pembayaran.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'pembayaran.php') ? 'active' : '' ?>">
                    <i class="fas fa-credit-card"></i>
                    <span class="menu-text">Pembayaran</span>
                </a>
                <a href="pesanan.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'pesanan.php') ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="menu-text">Pesanan</span>
                </a>
                <a href="pengaduan.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'pengaduan.php') ? 'active' : '' ?>">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="menu-text">Pengaduan</span>
                </a>
                <a href="laporan.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'laporan.php') ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-text">Laporan</span>
                </a>
                <a href="tipe_kamar.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'tipe_kamar.php') ? 'active' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span class="menu-text">Tipe Kamar</span>
                </a>
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </nav>
        </aside>

        <div class="main-content" style="flex: 1; min-height: 100vh; padding: 40px; box-sizing: border-box;">
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
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0) : ?>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                                        <td><span class="badge-kamar">Kamar <?= $row['id_kamar'] ?></span></td>
                                        <td><?= !empty($row['periode']) ? htmlspecialchars($row['periode']) . ' Bulan' : '1 Bulan' ?></td>
                                        <td>Rp <?= number_format($row['jml_bayar'], 0, ',', '.') ?></td>
                                        <td><?= ($row['tgl_transaksi'] && $row['tgl_transaksi'] != '0000-00-00') ? date('d M Y', strtotime($row['tgl_transaksi'])) : '-' ?></td>
                                        <td>
                                            <?php if (!empty($row['bukti_transaksi'])) : ?>
                                                <a href="../assets/uploads/<?= htmlspecialchars($row['bukti_transaksi']) ?>" target="_blank" style="color: #81A6C6; text-decoration: underline; font-size: 13px;">
                                                    <i class="fas fa-image"></i> Lihat Struk
                                                </a>
                                            <?php else : ?>
                                                <span style="color: #999;">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge-status <?= strtolower($row['status_transaksi']) ?>">
                                                <?= $row['status_transaksi'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (strtolower($row['status_transaksi']) == 'pending') : ?>
                                                <div class="action-icons" style="display: flex; gap: 10px; align-items: center;">
                                                    <a href="proses_pembayaran.php?id=<?= $row['id_transaksi'] ?>&aksi=setujui" class="edit" title="Setujui Pembayaran" onclick="return confirm('Yakin ingin menyetujui pembayaran ini?')">
                                                        <i class="fas fa-check-circle" style="color: #2ecc71; font-size: 20px;"></i>
                                                    </a>
                                                    <a href="proses_pembayaran.php?id=<?= $row['id_transaksi'] ?>&aksi=tolak" class="delete" title="Tolak Pembayaran" onclick="return confirm('Yakin ingin menolak pembayaran ini?')">
                                                        <i class="fas fa-times-circle" style="color: #e74c3c; font-size: 20px;"></i>
                                                    </a>
                                                </div>
                                            <?php elseif (strtolower($row['status_transaksi']) == 'lunas') : ?>
                                                <a href="cetak_bukti.php?id=<?= $row['id_transaksi'] ?>" target="_blank" style="color: #81A6C6; text-decoration: none; font-size: 13px; font-weight: bold; display: inline-flex; align-items: center; gap: 5px;">
                                                    <i class="fas fa-print"></i> Cetak Struk
                                                </a>
                                            <?php else : ?>
                                                <span style="color: #999; font-size: 13px;">Selesai</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 50px; color: #999;">
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
                        <h3>Rp <?= number_format($total_lunas, 0, ',', '.') ?></h3>
                    </div>
                    <div class="income-card pending">
                        <span class="label">Total Pending</span>
                        <h3>Rp <?= number_format($total_pending, 0, ',', '.') ?></h3>
                    </div>
                    <div class="income-card terlambat">
                        <span class="label">Total Terlambat</span>
                        <h3>Rp <?= number_format($total_terlambat, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </section>
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