<?php
include '../koneksi.php';
$search = $_GET['search'] ?? '';
/** @var mysqli $koneksi */

// FIX ANTI ONLY_FULL_GROUP_BY: Kita gunakan fungsi MAX() atau MIN() pada kolom penunjang 
// agar MySQL mengizinkan data ditarik meskipun menggunakan GROUP BY c.no_ktp
$query = "SELECT c.no_ktp, c.nama, c.no_hp,
                 CASE 
                    WHEN MAX(k.status_kamar) = 'kosong' OR MAX(k.status_kamar) IS NULL OR MAX(k.no_ktp) = '' THEN '-'
                    ELSE MAX(k.nomor_kamar)
                 END AS nomor_kamar,
                 MAX(k.id_kamar) AS id_kamar,
                 IFNULL(MAX(t.tgl_transaksi), MAX(p.tgl_pesan)) AS tgl_masuk
          FROM customer c
          LEFT JOIN kamar k ON c.no_ktp = k.no_ktp
          LEFT JOIN pesanan p ON c.no_ktp = p.no_ktp AND p.status_pesanan = 'lunas'
          LEFT JOIN transaksi t ON p.id_pesanan = t.id_pesanan
          WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (c.nama LIKE '%$search%' OR k.nomor_kamar LIKE '%$search%' OR c.no_hp LIKE '%$search%')";
}

$query .= " GROUP BY c.no_ktp ORDER BY tgl_masuk DESC";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Penghuni - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/penghuni.css">
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
                <a href="dashboard_admin.php" class="nav-link active">
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
                <div class="action-bar">
                    <form method="GET" class="search-box" id="searchForm">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" id="searchInput" placeholder="Masukkan nama, kamar, atau telepon..." value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </form>
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
                                        <td><?php echo (!empty($row['nomor_kamar']) && $row['nomor_kamar'] != '') ? $row['nomor_kamar'] : '-'; ?></td>
                                        <td>
                                            <div class="contact-info">
                                                <div><i class="fas fa-phone-alt" style="margin-right: 8px; color: #81A6C6;"></i> <?= htmlspecialchars($row['no_hp']); ?></div>
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