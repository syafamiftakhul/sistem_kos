<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

$tahun_sekarang = date('Y');
$target_bulanan = 5000000;

// Query yang sudah diperbaiki untuk MySQL strict mode
$sql = "SELECT 
            MONTH(t.tgl_transaksi) as bulan_num,
            DATE_FORMAT(t.tgl_transaksi, '%M') as bulan_nama, 
            SUM(t.jml_bayar) as total_pendapatan,
            COUNT(DISTINCT p.id_kamar) as kamar_terisi
        FROM transaksi t 
        LEFT JOIN pesanan p ON t.id_pesanan = p.id_pesanan
        WHERE YEAR(t.tgl_transaksi) = '$tahun_sekarang'
        GROUP BY MONTH(t.tgl_transaksi), DATE_FORMAT(t.tgl_transaksi, '%M')
        ORDER BY bulan_num ASC";

$result = mysqli_query($koneksi, $sql);

$bulan_ini = date('m');
$tahun_ini = date('Y');
$query_pendapatan = mysqli_query($koneksi, "SELECT SUM(jml_bayar) as total FROM transaksi WHERE MONTH(tgl_transaksi) = '$bulan_ini' AND YEAR(tgl_transaksi) = '$tahun_ini'");
$data_pendapatan = mysqli_fetch_assoc($query_pendapatan);
$total_pendapatan_bulan_ini = $data_pendapatan['total'] ?? 0;

$query_total_kamar = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kamar");
$total_kamar = mysqli_fetch_assoc($query_total_kamar)['total'] ?? 0;

$query_terisi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kamar WHERE status_kamar = 'terisi'");
$total_terisi = mysqli_fetch_assoc($query_terisi)['total'] ?? 0;

$persen_hunian_skrg = ($total_kamar > 0) ? ($total_terisi / $total_kamar) * 100 : 0;

$query_avg = mysqli_query($koneksi, "SELECT AVG(tipe_kamar.harga) as rata_rata 
                                    FROM kamar 
                                    JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
                                    WHERE kamar.status_kamar = 'terisi'");
$rata_rata_sewa = mysqli_fetch_assoc($query_avg)['rata_rata'] ?? 0;

$query_tunggakan = mysqli_query($koneksi, "SELECT SUM(jml_bayar) as total FROM transaksi WHERE status_transaksi = 'Pending'"); 
$total_tunggakan = mysqli_fetch_assoc($query_tunggakan)['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_admin.css">
    <link rel="stylesheet" href="../assets/css/laporan_admin.css">
    <!-- Font Awesome untuk icon edit & hapus -->
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
                <!-- ... icon menu lainnya ... -->
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
                    <h3>Pendapatan Bulan Ini</h3>
                    <div class="value">Rp <?= number_format($total_pendapatan_bulan_ini, 0, ',', '.'); ?></div>
                    <div class="indicator">
                        <i class="fas fa-arrow-up" style="color: #2ecc71;"></i>
                        <span style="color: #2ecc71; font-weight: 600;">Aktual</span> bulan ini
                    </div>
                </div>

                <div class="stat-card">
                    <h3>Tingkat Hunian</h3>
                    <div class="value"><?= round($persen_hunian_skrg); ?>%</div>
                    <div class="indicator">
                        <i class="fas fa-door-open" style="color: #81A6C6;"></i>
                        <span><?= $total_terisi; ?> / <?= $total_kamar; ?> Kamar</span>
                    </div>
                </div>

                <div class="stat-card">
                    <h3>Rata-Rata Sewa</h3>
                    <div class="value">Rp <?= number_format($rata_rata_sewa, 0, ',', '.'); ?></div>
                    <div class="indicator">
                        <i class="fas fa-chart-line" style="color: #81A6C6;"></i>
                        <span>Per kamar terisi</span>
                    </div>
                </div>

                <div class="stat-card">
                    <h3>Tunggakan / Pending</h3>
                    <div class="value">Rp <?= number_format($total_tunggakan, 0, ',', '.'); ?></div>
                    <div class="indicator">
                        <i class="fas fa-exclamation-circle" style="color: <?= ($total_tunggakan > 0) ? '#e74c3c' : '#888'; ?>;"></i>
                        <span>Perlu konfirmasi</span>
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
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)):
                            // Logic Perhitungan
                            $pendapatan = $row['total_pendapatan'];
                            $persen_pencapaian = ($pendapatan / $target_bulanan) * 100;
                            $query_kamar = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kamar");
                            $data_kamar = mysqli_fetch_assoc($query_kamar);
                            $total_kamar_tersedia = $data_kamar['total'];

                            if ($total_kamar_tersedia > 0) {
                                $persen_hunian = ($row['kamar_terisi'] / $total_kamar_tersedia) * 100;
                            } else {
                                $persen_hunian = 0; // Biar nggak error kalau tabel kamar kosong
                            }

                            // Warna status pencapaian
                            $status_color = ($persen_pencapaian >= 100) ? '#2ecc71' : '#f39c12';
                        ?>
                            <tr>
                                <td><strong><?= $row['bulan_nama']; ?></strong></td>
                                <td>Rp <?= number_format($pendapatan, 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($target_bulanan, 0, ',', '.'); ?></td>
                                <td><?= $row['kamar_terisi']; ?> Kamar</td>
                                <td>
                                    <div style="font-size: 12px;"><?= round($persen_hunian); ?>%</div>
                                    <div style="width: 100%; background: #eee; height: 5px; border-radius: 5px;">
                                        <div style="width: <?= $persen_hunian; ?>%; background: #81A6C6; height: 100%; border-radius: 5px;"></div>
                                    </div>
                                </td>
                                <td>
                                    <span style="color: <?= $status_color; ?>; font-weight: bold;">
                                        <?= round($persen_pencapaian, 1); ?>%
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #888; padding: 20px;">
                                Belum ada data transaksi di tahun <?= $tahun_sekarang; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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