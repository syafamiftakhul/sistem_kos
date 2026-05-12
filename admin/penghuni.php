<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

$query = "SELECT c.nama, t.id_kamar, c.no_hp, t.tgl_masuk, t.periode, t.status_transaksi 
          FROM transaksi t
          JOIN customer c ON t.no_ktp = c.no_ktp
          WHERE t.status_transaksi = 'Lunas'";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Penghuni - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_admin.css">
    <link rel="stylesheet" href="../assets/css/penghuni.css">
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

        <main class="main-content">
            <header>
                <div class="header-title">
                    <h1>Manajemen Penghuni</h1>
                    <p>Kelola Data Penghuni Kos-kosan</p>
                </div>
            </header>

            <section class="data-section">
                <div class="action-bar">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Masukkan nama, kamar, atau telepon..">
                    </div>
                </div>

                <div class="table-container">
                    <table class="kamar-table">
                        <thead>
                            <tr>
                                <th>Nama Penghuni</th>
                                <th>Kamar</th>
                                <th>Kontak HP</th>
                                <th>Tanggal Masuk</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) :
                                while ($row = mysqli_fetch_assoc($result)) :
                            ?>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <span class="user-name"><?= htmlspecialchars($row['nama']); ?></span>
                                            </div>
                                        </td>
                                        <td><span class="badge-kamar">Kamar <?= $row['id_kamar']; ?></span></td>
                                        <td>
                                            <div class="contact-info">
                                                <div><i class="fas fa-phone-alt" style="margin-right: 8px; color: #81A6C6;"></i> <?= $row['no_hp']; ?></div>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($row['tgl_masuk'])); ?></td>
                                        <td><span class="status-active">Aktif</span></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="edit_penghuni.php?id=<?= $row['id_kamar']; ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                                <a href="hapus_penghuni.php?id=<?= $row['id_kamar']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data penghuni?')"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else :
                                ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px; color: #888;">
                                        Belum ada penghuni aktif (Transaksi Lunas).
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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