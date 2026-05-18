<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

$filter = isset($_GET['status']) ? $_GET['status'] : 'Semua';
$search = $_GET['search'] ?? '';

$query .= " WHERE 1=1";

if ($filter != 'Semua') {
    $query .= " AND p.status_pesanan = '$filter'";
}

if (!empty($search)) {
    $query .= " AND (
        p.id_pesanan LIKE '%$search%' OR
        c.nama LIKE '%$search%' OR
        k.nomor_kamar LIKE '%$search%' OR
        p.no_ktp LIKE '%$search%'
    )";
}
$query .= " ORDER BY p.tgl_pesan DESC";

$result_pesanan = mysqli_query($koneksi, $query);

if (!$result_pesanan) {
    die("Query Error: " . mysqli_error($koneksi));
}

$total_pesanan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pesanan"))['jml'];
$total_disetujui = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pesanan WHERE status_pesanan = 'lunas'"))['jml'];
$total_pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pesanan WHERE status_pesanan = 'Pending'"))['jml'];
$total_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pesanan WHERE status_pesanan = 'Selesai'"))['jml'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_admin.css">
    <link rel="stylesheet" href="../assets/css/pesanan.css">
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
                    <h1>Manajemen Pesanan</h1>
                    <p>Kelola Data Pesanan Kos-kosan</p>
                </div>
            </header>

            <!-- Search & Action Bar -->
            <div class="action-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari nomor kamar atau penghuni.." value="<?= htmlspecialchars($search); ?>" onkeyup="cariData(this.value)">
                </div>
            </div>

            <div class="category-filter">
                <a href="?status=Semua" class="cat-item <?= $filter == 'Semua' ? 'active' : '' ?>">Semua</a>
                <a href="?status=Pending" class="cat-item <?= $filter == 'Pending' ? 'active' : '' ?>">Pending</a>
                <a href="?status=Disetujui" class="cat-item <?= $filter == 'Disetujui' ? 'active' : '' ?>">Disetujui</a>
                <a href="?status=Dibatalkan" class="cat-item <?= $filter == 'Dibatalkan' ? 'active' : '' ?>">Dibatalkan</a>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="kamar-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>No. KTP</th>
                            <th>Nama Penghuni</th>
                            <th>Nomor Kamar</th>
                            <th>Tanggal Pesan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_pesanan) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result_pesanan)) : ?>
                                <tr>
                                    <td><strong><?php echo $row['id_pesanan']; ?></strong></td>
                                    <td><?php echo $row['no_ktp']; ?></td>
                                    <td><?php echo $row['nama_penghuni']; ?></td>
                                    <td><?php echo $row['nomor_kamar']; ?></td>
                                    <td><?= date('d M Y', strtotime($row['tgl_pesan'])); ?></td>
                                    <td>
                                        <span class="badge-status <?php echo strtolower($row['status_pesanan']); ?>">
                                            <?php echo ucfirst($row['status_pesanan']); ?>
                                        </span>
                                    </td>
                                    <td class="action-icons">
                                        <a href="proses_pesanan.php?id=<?php echo $row['id_pesanan']; ?>&aksi=setujui" class="edit" title="Setujui"><i class="fas fa-check-circle"></i></a>
                                        <a href="hapus_pesanan.php?id=<?php echo $row['id_pesanan']; ?>" class="delete" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')" title="Batalkan"><i class="fas fa-times-circle"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px;">
                                    <img src="../assets/img/empty-icon.png" style="width: 50px; opacity: 0.3; display: block; margin: 0 auto 10px;">
                                    Belum ada data pesanan masuk.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="order-summary">
                <div class="summary-card total">
                    <div class="card-content">
                        <span class="label">Total Pesanan</span>
                        <h3><?= $total_pesanan ?></h3>
                    </div>
                    <div class="card-icon blue">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>

                <div class="summary-card disetujui">
                    <div class="card-content">
                        <span class="label">Disetujui</span>
                        <h3><?= $total_disetujui ?></h3>
                    </div>
                    <div class="card-icon green">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>

                <div class="summary-card waiting">
                    <div class="card-content">
                        <span class="label">Menunggu Persetujuan</span>
                        <h3><?= $total_pending ?></h3>
                    </div>
                    <div class="card-icon orange">
                        <i class="fas fa-clock"></i>
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

            <script>
                function cariData(keyword) {
                    const url = new URL(window.location.href);

                    if (keyword.trim() !== '') {
                        url.searchParams.set('search', keyword);
                    } else {
                        url.searchParams.delete('search');
                    }

                    window.location.href = url.toString();
                }
            </script>
</body>

</html>