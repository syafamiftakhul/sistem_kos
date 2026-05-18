<?php
session_start();
include "../koneksi.php";
$search = $_GET['search'] ?? '';
/** @var mysqli $koneksi */

$query_tipe = mysqli_query($koneksi, "
SELECT * FROM tipe_kamar WHERE id_tipe LIKE '%$search%'OR nama_tipe LIKE '%$search%'OR fasilitas LIKE '%$search%'");

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tipe Kamar - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_admin.css">
    <link rel="stylesheet" href="../assets/css/tipe_kamar_admin.css">
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
                <a href="dashboard_admin.php" class="nav-link">
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
            <header class="header-title">
                <h1>Kelola Tipe Kamar</h1>
                <p>Pengelolaan Tipe Kamar dan Tambah Tipe Kamar</p>
            </header>

            <div class="action-bar">
                <form method="GET" class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text"
                        name="search"
                        placeholder="Cari tipe kamar..."
                        value="<?= $_GET['search'] ?? ''; ?>"
                        onkeyup="this.form.submit()">
                </form>
                <a href="tambah_tipe_kamar.php" class="btn-tambah" id="btn-tambah-tipe">
                    <i class="fas fa-plus"></i> Tambah Tipe Kamar
                </a>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="tipe-table">
                    <thead>
                        <tr>
                            <th>Id Tipe</th>
                            <th>Nama Tipe</th>
                            <th>Fasilitas</th>
                            <th>Harga</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_tipe) > 0) {
                            while ($row = mysqli_fetch_assoc($query_tipe)) {
                        ?>
                                <tr>
                                    <td style="vertical-align: middle;"><strong>#<?= $row['id_tipe']; ?></strong></td>
                                    <td style="vertical-align: middle;"><?= $row['nama_tipe']; ?></td>
                                    <td style="vertical-align: middle;">
                                        <span style="font-size: 13px; color: #666; line-height: 1.5;">
                                            <?= nl2br($row['fasilitas'] ?? '-'); ?>
                                        </span>
                                    </td>
                                    <td style="vertical-align: middle; font-weight: bold;">
                                        Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        <div style="display: flex; gap: 15px; justify-content: center; align-items: center;">
                                            <a href="edit_tipe.php?id=<?= $row['id_tipe']; ?>" style="color: #81A6C6;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="hapus_tipe.php?id=<?= $row['id_tipe']; ?>" style="color: #F44336;" onclick="return confirm('Yakin mau hapus tipe ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="5" style="text-align: center; color: #888; padding: 20px;">Belum ada data</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            </tbody>
            </table>
        </div>

        <!-- Modal Overlay -->
        <div class="modal-overlay" id="modal-tipe">
            <div class="modal-box">
                <i class="fas fa-times close-btn" id="close-modal"></i>
                <h2>Tambah Tipe</h2>
                <form action="proses_tambah_tipe.php" method="POST">
                    <div class="form-group">
                        <label>Id Tipe</label>
                        <input type="text" name="id_tipe" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Tipe</label>
                        <input type="text" name="nama_tipe" required>
                    </div>
                    <div class="form-group">
                        <label>Fasilitas</label>
                        <input type="text" name="fasilitas" required>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" required>
                    </div>
                    <button type="submit" class="btn-submit">Simpan</button>
                </form>
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
    </script>
</body>

</html>