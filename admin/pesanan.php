<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

$filter = $_GET['status'] ?? 'Semua';
$search = $_GET['search'] ?? '';

// FIX KUNCI 1: Gunakan LEFT JOIN agar data pesanan TETEP MUNCUL di admin maupun user 
// meskipun kamar terkait di-set kosong atau no_ktp-nya di tabel kamar dilepas!
$query = "SELECT p.*, c.nama AS nama_penghuni, 
                 k.id_kamar, k.nomor_kamar
          FROM pesanan p
          LEFT JOIN customer c ON p.no_ktp = c.no_ktp
          LEFT JOIN kamar k ON p.id_kamar = k.id_kamar
          WHERE 1=1";

// Menyesuaikan filter kategori menu atas ke isi data ENUM database lu (huruf kecil)
if ($filter != 'Semua') {
    if ($filter == 'Disetujui') {
        $query .= " AND LOWER(p.status_pesanan) = 'lunas'";
    } elseif ($filter == 'Dibatalkan') {
        $query .= " AND LOWER(p.status_pesanan) = 'batal'";
    } else {
        $query .= " AND LOWER(p.status_pesanan) = '" . strtolower($filter) . "'";
    }
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

// FIX KUNCI 2: Hitung data ringkasan box bawah pakai LOWER() biar sinkron dengan isi ENUM database asli lu
$total_pesanan   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pesanan"))['jml'];
$total_disetujui = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pesanan WHERE LOWER(status_pesanan) = 'lunas'"))['jml'];
$total_pending   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pesanan WHERE LOWER(status_pesanan) = 'pending'"))['jml'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_admin.css">
    <link rel="stylesheet" href="../assets/css/pesanan.css">
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
            <header>
                <div class="header-title">
                    <h1>Manajemen Pesanan</h1>
                    <p>Kelola Data Pesanan Kos-kosan</p>
                </div>
            </header>

            <div class="action-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari nomor kamar atau penghuni.." value="<?= htmlspecialchars($search); ?>" onchange="cariData(this.value)" onkeydown="if(event.key === 'Enter') cariData(this.value)">
                </div>
            </div>

            <div class="category-filter">
                <a href="?status=Semua" class="cat-item <?= $filter == 'Semua' ? 'active' : '' ?>">Semua</a>
                <a href="?status=Pending" class="cat-item <?= $filter == 'Pending' ? 'active' : '' ?>">Pending</a>
                <a href="?status=Disetujui" class="cat-item <?= $filter == 'Disetujui' ? 'active' : '' ?>">Disetujui</a>
                <a href="?status=Dibatalkan" class="cat-item <?= $filter == 'Dibatalkan' ? 'active' : '' ?>">Dibatalkan</a>
            </div>

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
                                    <td><strong><?php echo htmlspecialchars((string)$row['id_pesanan']); ?></strong></td>
                                    <td><?php echo htmlspecialchars((string)$row['no_ktp']); ?></td>
                                    <td><?php echo htmlspecialchars((string)$row['nama_penghuni']); ?></td>
                                    <td><span class="badge-kamar" style="background:#e5e7eb; padding:3px 8px; border-radius:4px; font-weight:600;">Kamar <?php echo htmlspecialchars((string)$row['nomor_kamar']); ?></span></td>
                                    <td><?= date('d M Y', strtotime($row['tgl_pesan'])); ?></td>
                                    <td>
                                        <span class="badge-status <?php echo strtolower((string)$row['status_pesanan']); ?>">
                                            <?php echo ucfirst((string)$row['status_pesanan']); ?>
                                        </span>
                                    </td>
                                    
                                    <td class="action-icons">
                                        <?php if (strtolower((string)$row['status_pesanan']) == 'pending') : ?>
                                            <a href="proses_pesanan.php?id=<?php echo $row['id_pesanan']; ?>&aksi=setujui" class="edit" title="Setujui" style="color: #10B981; font-size: 18px; margin-right: 10px;"><i class="fas fa-check-circle"></i></a>
                                            <a href="proses_pesanan.php?id=<?php echo $row['id_pesanan']; ?>&aksi=tolak" class="delete" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')" title="Batalkan" style="color: #EF4444; font-size: 18px;"><i class="fas fa-times-circle"></i></a>
                                        <?php elseif (strtolower((string)$row['status_pesanan']) == 'lunas') : ?>
                                            <span style="color: #10B981; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fas fa-check"></i> Selesai
                                            </span>
                                        <?php else : ?>
                                            <span style="color: #EF4444; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fas fa-ban"></i> Ditolak
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 50px; color: #999;">
                                    <i class="fas fa-shopping-cart" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                                    Belum ada data pesanan masuk.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="order-summary" style="margin-top: 30px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="summary-card total">
                    <div class="card-content">
                        <span class="label">Total Pesanan</span>
                        <h3><?= $total_pesanan ?></h3>
                    </div>
                    <div class="card-icon blue"><i class="fas fa-shopping-cart"></i></div>
                </div>

                <div class="summary-card disetujui">
                    <div class="card-content">
                        <span class="label">Disetujui</span>
                        <h3><?= $total_disetujui ?></h3>
                    </div>
                    <div class="card-icon green"><i class="fas fa-check-double"></i></div>
                </div>

                <div class="summary-card waiting">
                    <div class="card-content">
                        <span class="label">Menunggu Persetujuan</span>
                        <h3><?= $total_pending ?></h3>
                    </div>
                    <div class="card-icon orange"><i class="fas fa-clock"></i></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const btnMenu = document.getElementById('btn-menu');
        const sidebar = document.getElementById('sidebar');

        btnMenu.onclick = function() {
            sidebar.classList.toggle('expand');
        }

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