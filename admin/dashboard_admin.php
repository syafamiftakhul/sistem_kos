<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

if (!isset($_SESSION['akses']) || $_SESSION['akses'] != 1) {
    header("Location: ../login.php");
    exit();
}

$bulan_ini = date('m');
$tahun_ini = date('Y');

$query_kamar = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kamar");
$total_kamar = mysqli_fetch_assoc($query_kamar)['total'] ?? 0;

$query_stat_kamar = mysqli_query($koneksi, "
    SELECT 
        SUM(CASE WHEN status_kamar = 'terisi' THEN 1 ELSE 0 END) as terisi,
        SUM(CASE WHEN status_kamar = 'tersedia' THEN 1 ELSE 0 END) as kosong 
    FROM kamar");
$stat_kamar   = mysqli_fetch_assoc($query_stat_kamar);
$total_terisi = $stat_kamar['terisi'] ?? 0;
$total_kosong = $stat_kamar['kosong'] ?? 0;

$query_user = mysqli_query($koneksi, "SELECT COUNT(DISTINCT no_ktp) as total FROM kamar WHERE no_ktp IS NOT NULL AND no_ktp != ''");
$total_user = mysqli_fetch_assoc($query_user)['total'] ?? 0;

$query_user_baru = mysqli_query($koneksi, "
    SELECT COUNT(DISTINCT no_ktp) as total FROM pesanan 
    WHERE MONTH(tgl_pesan) = '$bulan_ini' 
    AND YEAR(tgl_pesan) = '$tahun_ini'");
$user_baru = mysqli_fetch_assoc($query_user_baru)['total'] ?? 0;

$query_pendapatan = mysqli_query($koneksi, "
    SELECT SUM(jml_bayar) as total FROM transaksi 
    WHERE status_transaksi = 2 
    AND MONTH(tgl_transaksi) = '$bulan_ini' 
    AND YEAR(tgl_transaksi) = '$tahun_ini'");
$total_pendapatan = mysqli_fetch_assoc($query_pendapatan)['total'] ?? 0;

$query_tunggakan = mysqli_query($koneksi, "
    SELECT SUM(jml_bayar) as total FROM transaksi 
    WHERE status_transaksi = 1 
    AND MONTH(tgl_transaksi) = '$bulan_ini' 
    AND YEAR(tgl_transaksi) = '$tahun_ini'");
$total_tunggakan = mysqli_fetch_assoc($query_tunggakan)['total'] ?? 0;

$query_stat_bayar = mysqli_query($koneksi, "
    SELECT 
        SUM(CASE WHEN status_transaksi = 2 THEN 1 ELSE 0 END) as lunas,
        COUNT(*) as total_tagihan
    FROM transaksi 
    WHERE MONTH(tgl_transaksi) = '$bulan_ini'");
$stat_bayar    = mysqli_fetch_assoc($query_stat_bayar);
$jml_lunas     = $stat_bayar['lunas'] ?? 0;
$total_tagihan = $stat_bayar['total_tagihan'] ?? 0;

$query_jml_tunggak = mysqli_query($koneksi, "
    SELECT COUNT(DISTINCT no_ktp) as total FROM transaksi 
    WHERE status_transaksi = 1 
    AND MONTH(tgl_transaksi) = '$bulan_ini'");
$jml_tunggak = mysqli_fetch_assoc($query_jml_tunggak)['total'] ?? 0;

$query_pesanan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status_pesanan = 2");
$total_pesanan = mysqli_fetch_assoc($query_pesanan)['total'] ?? 0;

$query_pembayaran = "SELECT pesanan.*, customer.nama, tipe_kamar.nama_tipe 
                     FROM pesanan 
                     JOIN customer ON pesanan.no_ktp = customer.no_ktp 
                     JOIN kamar ON pesanan.id_kamar = kamar.id_kamar 
                     JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
                     ORDER BY pesanan.tgl_pesan DESC LIMIT 5";
$result_pembayaran = mysqli_query($koneksi, $query_pembayaran);

$query_kontrak = "SELECT customer.nama, tipe_kamar.nama_tipe, pesanan.tgl_pesan,
                  DATEDIFF(DATE_ADD(pesanan.tgl_pesan, INTERVAL 30 DAY), CURDATE()) as sisa_hari
                  FROM pesanan
                  JOIN customer ON pesanan.no_ktp = customer.no_ktp
                  JOIN kamar ON pesanan.id_kamar = kamar.id_kamar
                  JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe
                  WHERE DATEDIFF(DATE_ADD(pesanan.tgl_pesan, INTERVAL 30 DAY), CURDATE()) BETWEEN 0 AND 30
                  ORDER BY sisa_hari ASC LIMIT 5";

$result_kontrak = mysqli_query($koneksi, $query_kontrak);

$query_pengaduan = "SELECT pengaduan.*, customer.nama, tipe_kamar.nama_tipe 
                    FROM pengaduan 
                    JOIN kamar ON pengaduan.id_kamar = kamar.id_kamar 
                    JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe
                    JOIN customer ON kamar.no_ktp = customer.no_ktp
                    ORDER BY pengaduan.tgl_lapor DESC LIMIT 5";

$result_pengaduan = mysqli_query($koneksi, $query_pengaduan);

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

        <main class="main-content">
            <header class="content-header">
                <h1>DASHBOARD</h1>
                <p>Ringkasan Manajemen Kos Anda</p>
            </header>

            

            <section class="stats-row">
                <!-- Total Kamar -->
                <div class="card-stat">
                    <div class="stat-text">
                        <span>Total Kamar</span>
                        <h3><?php echo $total_kamar; ?></h3>
                        <small><?php echo $total_terisi; ?> Terisi, <?php echo $total_kosong; ?> Kosong</small>
                    </div>
                    <div class="stat-icon-box blue">
                        <img src="../assets/img/key-small.png" alt="" style="width: 25px;">
                    </div>
                </div>

                <!-- Total Penghuni -->
                <div class="card-stat">
                    <div class="stat-text">
                        <span>Total Penghuni</span>
                        <h3><?php echo $total_user; ?></h3>
                        <!-- Menampilkan jumlah user baru berdasarkan tgl_pesan bulan ini -->
                        <small><?php echo $user_baru; ?> baru bulan ini</small>
                    </div>
                    <div class="stat-icon-box light-blue">
                        <img src="../assets/img/user-small.png" alt="" style="width: 25px;">
                    </div>
                </div>

                <!-- Pendapatan -->
                <div class="card-stat">
                    <div class="stat-text">
                        <span>Pembayaran Bulan Ini</span>
                        <h3>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></h3>
                        <!-- Menampilkan ratio pembayaran lunas dari total tagihan bulan ini -->
                        <small><?php echo $jml_lunas; ?> dari <?php echo $total_tagihan; ?> lunas</small>
                    </div>
                    <div class="stat-icon-box beige">$</div>
                </div>

                <!-- Tunggakan -->
                <div class="card-stat">
                    <div class="stat-text">
                        <span>Total Tunggakan</span>
                        <h3 style="color: #333;">Rp <?php echo number_format($total_tunggakan, 0, ',', '.'); ?></h3>
                        <!-- Menampilkan jumlah penghuni unik yang masih punya tunggakan -->
                        <small><?php echo $jml_tunggak; ?> Penghuni Belum Bayar</small>
                    </div>
                    <div class="stat-icon-box orange">!</div>
                </div>
            </section>

            <section class="data-grid">
                <div class="info-panel">
                    <h4>Pembayaran Terbaru</h4>

                    <?php
                    if (mysqli_num_rows($result_pembayaran) > 0) {
                        while ($row = mysqli_fetch_assoc($result_pembayaran)) {
                            // Logika warna badge berdasarkan status
                            // Status 2 = Lunas (Success), Status 1 = Pending (Warning)
                            $status_class = ($row['status'] == 2) ? 'success' : 'warning';
                            $status_label = ($row['status'] == 2) ? 'Lunas' : 'Pending';

                            // Format Tanggal
                            $tanggal = date('d M Y', strtotime($row['tanggal_bayar']));
                    ?>
                            <div class="data-item">
                                <div class="item-info">
                                    <strong><?php echo htmlspecialchars($row['nama']); ?></strong>
                                    <!-- Menampilkan nama_tipe dari tabel tipe_kamar -->
                                    <span><?php echo $row['nama_tipe']; ?> • <?php echo date('d M Y', strtotime($row['tgl_pesan'])); ?></span>
                                </div>
                                <div class="item-status">
                                    <!-- jml_bayar diambil dari tabel transaksi jika perlu, 
             tapi karena ini list pesanan, kita pakai nominal statis atau ambil dari tabel transaksi -->
                                    <strong>Rp. <?php echo number_format($row['jml_bayar'], 0, ',', '.'); ?></strong>
                                    <span class="badge <?php echo ($row['status_pesanan'] == 2) ? 'success' : 'warning'; ?>">
                                        <?php echo ($row['status_pesanan'] == 2) ? 'Lunas' : 'Pending'; ?>
                                    </span>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p style='padding: 15px; color: #888;'>Belum ada data pembayaran.</p>";
                    }
                    ?>

                </div>

                <div class="info-panel">
                    <h4>Kontrak Akan Berakhir</h4>

                    <?php
                    if (mysqli_num_rows($result_kontrak) > 0) {
                        while ($kontrak = mysqli_fetch_assoc($result_kontrak)) {
                            $tgl_berakhir = date('d F Y', strtotime($kontrak['tgl_pesan'] . ' + 30 days'));
                            $sisa = $kontrak['sisa_hari'];

                            // Logika Warna: Kalau <= 7 hari pakai class 'danger' (merah), kalau > 7 pakai 'info' (biru)
                            $badge_color = ($sisa <= 7) ? 'danger' : 'info';
                    ?>
                            <div class="data-item">
                                <div class="item-info">
                                    <strong><?php echo htmlspecialchars($kontrak['nama']); ?></strong>
                                    <span><?php echo $kontrak['nama_tipe']; ?></span>
                                </div>
                                <div class="item-status">
                                    <span><?php echo $tgl_berakhir; ?></span>
                                    <!-- Warna badge berubah sesuai sisa hari -->
                                    <span class="badge <?php echo $badge_color; ?>">
                                        <?php echo ($sisa <= 0) ? 'Habis Hari Ini' : $sisa . ' Hari Lagi'; ?>
                                    </span>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p style='padding: 10px; color: #888; font-size: 13px;'>Tidak ada kontrak yang segera berakhir.</p>";
                    }
                    ?>
                </div>

                <div class="info-panel">
                    <h4>Pengaduan Terbaru</h4>
                    <?php
                    while ($adu = mysqli_fetch_assoc($result_pengaduan)) {
                        // Logika penentuan warna badge
                        $status = $adu['status_pengaduan'];
                        if ($status == 'Selesai') {
                            $badge_class = 'success'; // Hijau
                        } elseif ($status == 'Diproses') {
                            $badge_class = 'info';    // Biru
                        } else {
                            $badge_class = 'warning'; // Kuning (Terbaru/Pending)
                        }
                    ?>
                        <div class="data-item">
                            <div class="item-info">
                                <strong><?php echo htmlspecialchars($adu['deskripsi']); ?></strong>
                                <span><?php echo $adu['nama_tipe'] . " - " . htmlspecialchars($adu['nama']); ?></span>
                            </div>
                            <div class="item-status">
                                <span><?php echo date('d M Y', strtotime($adu['tgl_lapor'])); ?></span>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo $status; ?>
                                </span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
        </main>
    </div>
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