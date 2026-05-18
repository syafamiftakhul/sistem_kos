<?php
include '../koneksi.php';
$search = $_GET['search'] ?? '';
/** @var mysqli $koneksi */

$query = "SELECT c.nama, k.nomor_kamar, p.id_kamar, c.no_hp, IFNULL(t.periode, '1') as periode, IFNULL(t.tgl_transaksi, p.tgl_pesan) AS tgl_masuk
          FROM pesanan p
          JOIN customer c ON p.no_ktp = c.no_ktp
          JOIN kamar k ON p.id_kamar = k.id_kamar
          LEFT JOIN transaksi t ON p.id_pesanan = t.id_pesanan
          WHERE p.status_pesanan = 'lunas'
          AND (  c.nama LIKE '%$search%' OR k.nomor_kamar LIKE '%$search%' OR c.no_hp LIKE '%$search%')";

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
        <aside class="sidebar-admin expand " id="sidebar">
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

        <main class="main-content">
            <header>
                <div class="header-title">
                    <h1>Manajemen Penghuni</h1>
                    <p>Kelola Data Penghuni Kos-kosan</p>
                </div>
            </header>

            <section class="data-section">
                <form method="GET" class="search-box" id="searchForm">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" id="searchInput" placeholder="Masukkan nama, kamar, atau telepon..." value="<?= $_GET['search'] ?? ''; ?>">
                </form>

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
                                        <td><span class="badge-kamar">Kamar <?= htmlspecialchars($row['nomor_kamar']); ?></span></td>
                                        <td>
                                            <div class="contact-info">
                                                <div><i class="fas fa-phone-alt" style="margin-right: 8px; color: #81A6C6;"></i> <?= $row['no_hp']; ?></div>
                                            </div>
                                        </td>
                                        <td><?= (!empty($row['tgl_masuk']) && $row['tgl_masuk'] != '0000-00-00') ? date('d M Y', strtotime($row['tgl_masuk'])) : '-'; ?></td>
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

    <script>
        let timeout = null;

        document.getElementById('searchInput').addEventListener('keyup', function() {

            clearTimeout(timeout);

            timeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);

        });
    </script>
</body>

</html>