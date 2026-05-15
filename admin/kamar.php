<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

// Query buat nampilin data tabel
$query_kamar = "SELECT kamar.*, tipe_kamar.nama_tipe, tipe_kamar.harga, customer.nama as nama_penghuni 
                FROM kamar 
                LEFT JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
                LEFT JOIN customer ON kamar.no_ktp = customer.no_ktp";

$result_kamar = mysqli_query($koneksi, $query_kamar);
$total_kamar = mysqli_num_rows($result_kamar);

// Query Statistik: Pakai 'kosong' bukan 'tersedia'
$query_stats = mysqli_query($koneksi, "SELECT 
    SUM(CASE WHEN status_kamar = 'terisi' THEN 1 ELSE 0 END) as terisi,
    SUM(CASE WHEN status_kamar = 'kosong' THEN 1 ELSE 0 END) as tersedia 
    FROM kamar");
$stats = mysqli_fetch_assoc($query_stats);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kamar - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_admin.css">
    <link rel="stylesheet" href="../assets/css/kamar_admin.css">
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
                    <h1>Manajemen Kamar</h1>
                    <p>Kelola Data Kamar Kos-kosan</p>
                </div>
            </header>



            <!-- Search & Action Bar -->
            <div class="action-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari nomor kamar atau penghuni..">
                </div>
                <a href="tambah_kamar.php" class="btn-tambah">
                    <i class="fas fa-plus"></i> Tambah Kamar
                </a>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="kamar-table">
                    <thead>
                        <tr>
                            <th>ID Kamar</th>
                            <th>ID Tipe</th>
                            <th>Harga</th>
                            <th>Fasilitas</th>
                            <th>Status</th>
                            <th>Penghuni</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_kamar) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result_kamar)) : ?>
                                <tr>
                                    <td><strong><?php echo $row['nomor_kamar'] ?? $row['id_kamar']; ?></strong></td>

                                    <td><?php echo $row['nama_tipe']; ?></td>
                                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <div class="facility-tags">
                                            <span class="tag">AC</span>
                                            <span class="tag">WiFi</span>
                                            <?php if ($row['harga'] >= 1000000) : ?>
                                                <span class="tag">Kamar Mandi Dalam</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-status <?php echo ($row['status_kamar'] == 'terisi') ? 'terisi' : 'tersedia'; ?>">
                                            <?php echo ($row['status_kamar'] == 'terisi') ? 'Terisi' : 'Tersedia'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $row['nama_penghuni'] ?? '-'; ?></td>
                                    <td class="action-icons">
                                        <a href="edit_kamar.php?id=<?php echo $row['id_kamar']; ?>" class="edit"><i class="far fa-edit"></i></a>
                                        <a href="hapus_kamar.php?id=<?php echo $row['id_kamar']; ?>" class="delete" onclick="return confirm('Yakin ingin menghapus kamar nomor <?php echo $row['nomor_kamar']; ?>?')"><i class="far fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px;">Data kamar belum tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer Statistik -->
            <div class="table-footer">
                <p>Total: <?php echo $total_kamar; ?> Kamar - Terisi: <?php echo $stats['terisi'] ?? 0; ?> Kamar - Tersedia: <?php echo $stats['tersedia'] ?? 0; ?> Kamar</p>
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