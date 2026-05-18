<?php
include '../koneksi.php';
/** @var mysqli $koneksi */
$filter = isset($_GET['status']) ? $_GET['status'] : 'Semua';

$query = "SELECT p.*, c.nama AS nama_penghuni 
          FROM pengaduan p
          JOIN customer c ON p.no_ktp = c.no_ktp";


$query = "SELECT * FROM pengaduan";

if ($filter != 'Semua') {
    // Pastikan nama kolom statusnya juga bener (status_pengaduan)
    $query .= " WHERE status_pengaduan = '$filter'";
}

$query .= " ORDER BY tgl_lapor DESC";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_pengaduan'];
    $status = $_POST['status_baru'];

    $query = "UPDATE pengaduan SET status_pengaduan = '$status' WHERE id_pengaduan = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: pengaduan.php?status_updated=success");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

$total_laporan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pengaduan"))['jml'];
$total_proses = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pengaduan WHERE status_pengaduan = 'Proses'"))['jml'];
$total_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pengaduan WHERE status_pengaduan = 'Selesai' AND MONTH(tgl_lapor) = MONTH(CURRENT_DATE())"))['jml'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kamar - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_admin.css">
    <link rel="stylesheet" href="../assets/css/pengaduan.css">
    <!-- Font Awesome untuk icon edit & hapus -->
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
                <a href="dashboard_admin.php" class="nav-link">
                    <i class="fas fa-chart-line"></i>
                    <span class="menu-text">Dashboard</span>
                </a>

                <a href="kamar.php" class="nav-link">
                    <i class="fas fa-key"></i>
                    <span class="menu-text">Kamar</span>
                </a>

                <a href="penghuni.php" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span class="menu-text">Penghuni</span>
                </a>

                <a href="pembayaran.php" class="nav-link">
                    <i class="fas fa-credit-card"></i>
                    <span class="menu-text">Pembayaran</span>
                </a>

                <a href="pesanan.php" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="menu-text">Pesanan</span>
                </a>

                <a href="pengaduan.php" class="nav-link">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="menu-text">Pengaduan</span>
                </a>

                <a href="laporan.php" class="nav-link">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-text">Laporan</span>
                </a>

                <a href="tipe_kamar.php" class="nav-link">
                    <i class="fas fa-tags"></i>
                    <span class="menu-text">Tipe Kamar</span>
                </a>

                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </nav>
        </aside>
        <div class="main-content">
            <header>
                <div class="header-title">
                    <h1>Manajemen Pengaduan</h1>
                    <p>Kelola Data Pengaduan Kos-kosan</p>
                </div>
            </header>
            <!-- Search & Action Bar -->
            <section class="data-section">
                <div class="action-bar">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari nomor kamar atau penghuni..">
                    </div>
                </div>

                <div class="category-filter">
                    <a href="?status=Semua" class="cat-item <?= $filter == 'semua' ? 'active' : '' ?>">Semua</a>
                    <a href="?status=Baru" class="cat-item <?= $filter == 'Baru' ? 'active' : '' ?>">Baru</a>
                    <a href="?status=Proses" class="cat-item <?= $filter == 'Proses' ? 'active' : '' ?>">Proses</a>
                    <a href="?status=Selesai" class="cat-item <?= $filter == 'Selesai' ? 'active' : '' ?>">Selesai</a>
                </div>

                <!-- Table Container -->
                <div class="table-container">
                    <table class="kamar-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Penghuni</th>
                                <th>Tanggal</th>
                                <th>Isi Laporan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0) : ?>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars((string)$row['nama']) ?></strong></td>
                                        <td><span class="badge-kamar">Kamar <?= htmlspecialchars((string)($row['id_kamar'] ?? '-')) ?></span></td>
                                        <td><?= htmlspecialchars((string)($row['periode'] ?? '1')) ?> Bulan</td>
                                        <td>Rp <?= number_format((float)($row['jml_bayar'] ?? 0), 0, ',', '.') ?></td>
                                        <td><?= (!empty($row['tgl_transaksi']) && $row['tgl_transaksi'] != '0000-00-00') ? date('d M Y', strtotime($row['tgl_transaksi'])) : '-' ?></td>
                                        <td>
                                            <span class="badge-status <?= strtolower((string)($row['status_final'] ?? 'pending')) ?>">
                                                <?= ucfirst((string)($row['status_final'] ?? 'pending')) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 50px; color: #999;">
                                        <i class="fas fa-receipt" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                                        Belum ada data pembayaran untuk kategori <strong><?= ucfirst($filter) ?></strong>.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="summary-container">
                    <div class="summary-card">
                        <div class="card-info">
                            <span class="card-label">Total Pengaduan</span>
                            <h3 class="card-value"><?= $total_laporan ?></h3>
                        </div>
                        <div class="card-icon blue">
                            <i class="far fa-comment-alt"></i>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="card-info">
                            <span class="card-label">Dalam Proses</span>
                            <h3 class="card-value"><?= $total_proses ?></h3>
                        </div>
                        <div class="card-icon light-blue">
                            <i class="far fa-clock"></i>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="card-info">
                            <span class="card-label">Selesai Bulan Ini</span>
                            <h3 class="card-value"><?= $total_selesai ?></h3>
                        </div>
                        <div class="card-icon orange-light">
                            <i class="far fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </section>
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