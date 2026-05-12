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
            <header>
                <div class="header-title">
                    <h1>Manajemen Pengaduan</h1>
                    <p>Kelola Data Pengaduan Kos-kosan</p>
                </div>
            </header>
            <!-- Search & Action Bar -->
            <div class="action-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari nomor kamar atau penghuni..">
                </div>
            </div>

            <div class="category-filter">
                <a href="?status=Semua" class="cat-item <?= $filter == 'Semua' ? 'active' : '' ?>">Semua</a>
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
                        <?php if (mysqli_num_rows($result) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($row['tgl_pengaduan'])); ?></td>
                                    <td><?= $row['nama_penghuni']; ?></td>
                                    <td><?= $row['id_kamar']; ?></td>
                                    <td>Layanan</td>
                                    <td style="max-width: 250px;">
                                        <strong><?= $row['subjek_laporan']; ?></strong><br>
                                        <small style="color: #888;"><?= $row['isi_pengaduan']; ?></small>
                                    </td>
                                    <td>
                                        <div class="status-wrapper">
                                            <?php
                                            $status = $row['status_pengaduan'];
                                            $icon = ($status == 'Selesai' || $status == 'Proses') ? 'fa-clock' : 'fa-exclamation-triangle';
                                            ?>
                                            <i class="fas <?= $icon; ?>"></i>
                                            <span class="badge-status <?= strtolower($status); ?>">
                                                <?= ucfirst($status); ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <form action="update_status_pengaduan.php" method="POST">
                                            <input type="hidden" name="id_pengaduan" value="<?= $row['id_pengaduan']; ?>">
                                            <select name="status_baru" class="combo-status" onchange="this.form.submit()">
                                                <option value="Baru" <?= $status == 'Baru' ? 'selected' : ''; ?>>Baru</option>
                                                <option value="Proses" <?= $status == 'Proses' ? 'selected' : ''; ?>>Proses</option>
                                                <option value="Selesai" <?= $status == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
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
            <script>
                const btnMenu = document.getElementById('btn-menu');
                const sidebar = document.getElementById('sidebar');

                btnMenu.onclick = function() {
                    sidebar.classList.toggle('expand');
                }
            </script>
</body>

</html>