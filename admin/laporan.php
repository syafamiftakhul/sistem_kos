<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

$tahun_sekarang = date('Y');
$target_bulanan = 5000000;

// Menangkap tanggal kustom, jika kosong set default ke tanggal contoh gambar Anda
$tgl_awal_pilihan  = $_GET['tgl_awal'] ?? '2026-02-01';
$tgl_akhir_pilihan = $_GET['tgl_akhir'] ?? '2026-03-11';

// Mengubah logika pencarian menggunakan BETWEEN (rentang tanggal)
$kondisi_where_transaksi = "WHERE t.tgl_transaksi BETWEEN '$tgl_awal_pilihan' AND '$tgl_akhir_pilihan'";
$kondisi_where_pesanan   = "AND p.tgl_pesan BETWEEN '$tgl_awal_pilihan' AND '$tgl_akhir_pilihan'";
$kondisi_where_riwayat   = "WHERE p.tgl_pesan BETWEEN '$tgl_awal_pilihan' AND '$tgl_akhir_pilihan'";

// Query utama untuk ringkasan bulanan
$sql = "SELECT 
            bulan_num,
            bulan_nama,
            SUM(jml_bayar) as total_pendapatan,
            COUNT(DISTINCT id_kamar) as kamar_terisi
        FROM (
            SELECT 
                MONTH(t.tgl_transaksi) as bulan_num,
                DATE_FORMAT(t.tgl_transaksi, '%M') as bulan_nama, 
                t.jml_bayar,
                p.id_kamar
            FROM transaksi t 
            LEFT JOIN pesanan p ON t.id_pesanan = p.id_pesanan
            $kondisi_where_transaksi

            UNION ALL

            SELECT 
                MONTH(p.tgl_pesan) as bulan_num,
                DATE_FORMAT(p.tgl_pesan, '%M') as bulan_nama,
                1000000 as jml_bayar,
                p.id_kamar
            FROM pesanan p
            LEFT JOIN transaksi t ON p.id_pesanan = t.id_pesanan
            WHERE p.status_pesanan = 'lunas' 
              AND t.id_transaksi IS NULL 
              $kondisi_where_pesanan
        ) as gabungan
        GROUP BY bulan_num, bulan_nama
        ORDER BY bulan_num ASC";

$result = mysqli_query($koneksi, $sql);

// Query untuk mengambil riwayat sewa kamar dan sisa harinya
$sql_riwayat = "SELECT 
                    k.nomor_kamar,
                    tk.nama_tipe,
                    c.nama, 
                    p.tgl_pesan as tgl_mulai,
                    DATE_ADD(p.tgl_pesan, INTERVAL t.periode MONTH) as tgl_habis,
                    DATEDIFF(DATE_ADD(p.tgl_pesan, INTERVAL t.periode MONTH), CURRENT_DATE()) as sisa_hari,
                    p.status_pesanan
                FROM pesanan p
                JOIN transaksi t ON p.id_pesanan = t.id_pesanan
                JOIN kamar k ON p.id_kamar = k.id_kamar
                JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
                JOIN customer c ON p.no_ktp = c.no_ktp 
                $kondisi_where_riwayat 
                ORDER BY p.tgl_pesan DESC";

$result_riwayat = mysqli_query($koneksi, $sql_riwayat);

$bulan_ini = date('m');
$tahun_ini = date('Y');

$query_pendapatan = mysqli_query($koneksi, "
    SELECT SUM(total) as total FROM (
        SELECT IFNULL(t.jml_bayar, 1000000) as total
        FROM pesanan p 
        LEFT JOIN transaksi t ON p.id_pesanan = t.id_pesanan
        WHERE (p.status_pesanan = 'lunas' OR t.status_transaksi = 'lunas')
          AND MONTH(IFNULL(t.tgl_transaksi, p.tgl_pesan)) = '$bulan_ini'
          AND YEAR(IFNULL(t.tgl_transaksi, p.tgl_pesan)) = '$tahun_ini'
    ) as x");
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
                <a href="dashboard_admin.php" class="nav-link"><i class="fas fa-chart-line"></i><span class="menu-text">Dashboard</span></a>
                <a href="kamar.php" class="nav-link"><i class="fas fa-key"></i><span class="menu-text">Kamar</span></a>
                <a href="penghuni.php" class="nav-link"><i class="fas fa-users"></i><span class="menu-text">Penghuni</span></a>
                <a href="pembayaran.php" class="nav-link"><i class="fas fa-credit-card"></i><span class="menu-text">Pembayaran</span></a>
                <a href="pesanan.php" class="nav-link"><i class="fas fa-shopping-cart"></i><span class="menu-text">Pesanan</span></a>
                <a href="pengaduan.php" class="nav-link"><i class="fas fa-exclamation-circle"></i><span class="menu-text">Pengaduan</span></a>
                <a href="laporan.php" class="nav-link"><i class="fas fa-file-alt"></i><span class="menu-text">Laporan</span></a>
                <a href="tipe_kamar.php" class="nav-link"><i class="fas fa-tags"></i><span class="menu-text">Tipe Kamar</span></a>
                <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i><span class="menu-text">Logout</span></a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="header-top">
                <div class="header-title">
                    <h1>Laporan</h1>
                    <p>Analisis dan laporan keuangan kos-kosan</p>
                </div>
                <div class="header-actions" style="display: flex; align-items: center; gap: 15px;">
                    <form method="GET" action="" id="form-filter" style="display: flex; align-items: center; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); gap: 10px; margin: 0;">
                        <input type="date" name="tgl_awal" id="tgl_awal" value="<?= $tgl_awal_pilihan; ?>" onchange="submitFilter()" style="border: none; outline: none; color: #334155; font-size: 14px; font-family: inherit; cursor: pointer;">
                        <span style="color: #64748b; font-size: 13px; font-weight: 500;">s.d</span>
                        <input type="date" name="tgl_akhir" id="tgl_akhir" value="<?= $tgl_akhir_pilihan; ?>" onchange="submitFilter()" style="border: none; outline: none; color: #334155; font-size: 14px; font-family: inherit; cursor: pointer;">
                    </form>

                    <a href="export_pdf.php?tgl_awal=<?= $tgl_awal_pilihan; ?>&tgl_akhir=<?= $tgl_akhir_pilihan; ?>" class="btn-export" target="_blank" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-download"></i> Export PDF
                    </a>
                </div>
            </header>

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
                                $pendapatan = $row['total_pendapatan'];
                                $persen_pencapaian = ($pendapatan / $target_bulanan) * 100;
                                $query_kamar = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kamar");
                                $data_kamar = mysqli_fetch_assoc($query_kamar);
                                $total_kamar_tersedia = $data_kamar['total'];

                                $persen_hunian = ($total_kamar_tersedia > 0) ? ($row['kamar_terisi'] / $total_kamar_tersedia) * 100 : 0;
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
                                    Belum ada data transaksi pada rentang <?= date('d M Y', strtotime($tgl_awal_pilihan)); ?> s.d <?= date('d M Y', strtotime($tgl_akhir_pilihan)); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <br><br>

            <div class="table-container mt-4">
                <div style="padding: 20px 20px 0 20px;">
                    <h3 style="color: #333; font-size: 18px; margin-bottom: 5px;">Laporan Riwayat Hunian & Batas Kontrak Kamar</h3>
                    <p style="color: #777; font-size: 13px;">Daftar aktivitas penggunaan kamar dan estimasi jatuh tempo pembayaran pada periode terpilih.</p>
                </div>
                <table class="laporan-table">
                    <thead>
                        <tr>
                            <th>No. Kamar</th>
                            <th>Tipe Kamar</th>
                            <th>Nama Penghuni</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Jatuh Tempo</th>
                            <th>Status Masa Sewa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Cek dulu apakah $result_riwayat ada isinya
                        if ($result_riwayat && mysqli_num_rows($result_riwayat) > 0) {
                            while ($riwayat = mysqli_fetch_assoc($result_riwayat)) {
                                $sisa = (int)$riwayat['sisa_hari'];
                                $status_pesanan = isset($riwayat['status_pesanan']) ? strtolower($riwayat['status_pesanan']) : '';

                                // LOGIKA BARU: Kalau lunas/selesai, tidak ada jatuh tempo
                                if ($status_pesanan == 'selesai') {
                                    $badge_class = "badge-success";
                                    $status_teks = "Lunas / Selesai";
                                } elseif ($sisa < 0) {
                                    $badge_class = "badge-danger";
                                    $status_teks = "Lewat " . abs($sisa) . " Hari";
                                } elseif ($sisa <= 3) {
                                    $badge_class = "badge-danger";
                                    $status_teks = "Kritis (" . $sisa . " Hari Lagi)";
                                } elseif ($sisa <= 7) {
                                    $badge_class = "badge-warning";
                                    $status_teks = $sisa . " Hari Lagi";
                                } else {
                                    $badge_class = "badge-success";
                                    $status_teks = $sisa . " Hari Lagi";
                                }
                        ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($riwayat['nomor_kamar']); ?></strong></td>
                                    <td><?= htmlspecialchars($riwayat['nama_tipe']); ?></td>
                                    <td><?= !empty($riwayat['nama']) ? htmlspecialchars($riwayat['nama']) : '-'; ?></td>
                                    <td><?= date('d M Y', strtotime($riwayat['tgl_mulai'])); ?></td>
                                    <td><?= date('d M Y', strtotime($riwayat['tgl_habis'])); ?></td>
                                    <td>
                                        <span class="badge-status <?= $badge_class; ?>">
                                            <?= $status_teks; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php
                            } // tutup while
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #888; padding: 20px;">
                                    Tidak ada riwayat aktivitas hunian kamar pada periode ini.
                                </td>
                            </tr>
                        <?php } // tutup if 
                        ?>
                    </tbody>
                </table>
            </div>

            <script>
                const btnMenu = document.getElementById('btn-menu');
                const sidebar = document.getElementById('sidebar');

                btnMenu.onclick = function() {
                    sidebar.classList.toggle('expand');
                }

                // MENAMBAHKAN FUNGSI SUBMIT OTOMATIS SAAT TANGGAL DIUBAH
                function submitFilter() {
                    document.getElementById('form-filter').submit();
                }
            </script>
</body>

</html>