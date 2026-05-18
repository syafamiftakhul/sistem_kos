<?php
// 1. WAJIB NYALAKAN SESSION DI BARIS PALING ATAS!
session_start(); 

include "koneksi.php";
/** @var mysqli $koneksi */

// 2. TANGKAP ID TIPE SECARA DINAMIS DARI PARAMETER URL (?id_tipe=...)
$id_tipe = $_GET['id_tipe'] ?? null;

if ($id_tipe) {
    $id_tipe = mysqli_real_escape_string($koneksi, $id_tipe);
    // Ambil data spesifik dari database sesuai tipe kamar yang diklik user
    $query = mysqli_query($koneksi, "SELECT * FROM tipe_kamar WHERE id_tipe = '$id_tipe'");
    $data = mysqli_fetch_array($query);
} else {
    $data = null;
}

// 3. JIKA DATA ADA, SET VARIABELNYA. JIKA KOSONG, TENDANG BALIK KE INDEX
if ($data) {
    $nama_tipe  = $data['nama_tipe'];
    $harga_tipe = $data['harga'];
    $id_tipe    = $data['id_tipe'];
} else {
    header("Location: index.php");
    exit;
}

// 4. ATUR DESKRIPSI & FASILITAS SECARA DINAMIS BERDASARKAN ID TIPE AGAR GAK KETUKER
// Tipe 1 = Deluxe (1 Juta), Tipe 2 = Standard (700 Ribu)
if ($id_tipe == 1) {
    $txt_deskripsi = "Modern and comfortable deluxe room perfect for students and young professionals. Fully furnished with high quality amenities, private bathroom, and cooler AC.";
    $list_fasilitas = [
        'WiFi' => 'M5 12.55a11 11 0 0 1 14.08 0 M1.42 9a16 16 0 0 1 21.16 0 M8.53 16.11a6 6 0 0 1 6.95 0', 
        'Dapur Bersama' => 'M18 8h1a4 4 0 0 1 0 8h-1 M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z',
        'Parkir Motor & Mobil' => 'M3 11h18M7 11V7a5 5 0 0 1 10 0v4',
        'Kamar Mandi Dalam' => 'M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z'
    ];
} else {
    // Settingan otomatis untuk Kamar Standard Rp 700.000 (ID Tipe 2) atau tipe baru lainnya
    $txt_deskripsi = "Kamar tipe Standard dengan harga ekonomis namun tetap menawarkan kenyamanan maksimal. Sangat cocok untuk menghemat budget bulanan Anda dengan fasilitas lengkap.";
    $list_fasilitas = [
        'WiFi' => 'M5 12.55a11 11 0 0 1 14.08 0 M1.42 9a16 16 0 0 1 21.16 0 M8.53 16.11a6 6 0 0 1 6.95 0', 
        'Dapur Bersama' => 'M18 8h1a4 4 0 0 1 0 8h-1 M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z',
        'Parkir Motor' => 'M3 11h18M7 11V7a5 5 0 0 1 10 0v4',
        'Kamar Mandi Luar' => 'M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z'
    ];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kamar - Kos Aqsya Residence</title>

    <link rel="stylesheet" href="assets/css/global.css" />
    <link rel="stylesheet" href="assets/css/detail.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
</head>

<body>
    <div class="dashboard-user1 detail-page">
        <header class="detail-header">
            <div class="logo-area">
                <div class="key">
                    <img src="assets/img/key.png" alt="Logo" class="icon" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
                </div>
                <h1 class="logo-text">Kos Aqsya Residence</h1>
            </div>
        </header>

        <main class="detail-container">

            <a href="index.php" class="back-link">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>

            <div class="detail-grid">
                <div class="left-col">
                    <section class="gallery-section">
                        <div class="main-image">
                            <img src="assets/img/bedroom1.jpg" alt="Main Room" />
                        </div>

                        <div class="thumbnail-list">
                            <div class="thumb active">
                                <img src="assets/img/bedroom1.jpg" alt="Thumb 1" />
                            </div>
                            <div class="thumb">
                                <img src="assets/img/bedroom2.png" alt="Thumb 2" />
                            </div>
                        </div>
                    </section>

                    <section class="info-card">
                        <div class="room-title-area">
                            <div class="title-left">
                                <h2 class="room-name"><?php echo htmlspecialchars($nama_tipe); ?></h2>
                                <div class="location-detail">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>Kaliwungu</span>
                                </div>
                            </div>
                            <div class="rating-box">
                                <div class="rating-score">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="#ffc107" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    <span>4.8</span>
                                </div>
                                <div class="rating-count">24 Reviews</div>
                            </div>
                        </div>
                    </section>

                    <section class="info-card">
                        <h3 class="section-title">Informasi Kamar</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </div>
                                <div class="info-text">
                                    <div class="info-label">Kapasitas</div>
                                    <div class="info-val">1 person</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <polyline points="9 21 3 21 3 15"></polyline>
                                        <line x1="21" y1="3" x2="14" y2="10"></line>
                                        <line x1="3" y1="21" x2="10" y2="14"></line>
                                    </svg>
                                </div>
                                <div class="info-text">
                                    <div class="info-label">Ukuran Ruangan</div>
                                    <div class="info-val">15 m²</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                        <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                    </svg>
                                </div>
                                <div class="info-text">
                                    <div class="info-label">Kamar Tipe</div>
                                    <div class="info-val">Single</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="info-card">
                        <h3 class="section-title">Deskripsi</h3>
                        <p class="description-text">
                            <?php echo htmlspecialchars($txt_deskripsi); ?>
                        </p>
                    </section>

                    <section class="info-card">
                        <h3 class="section-title">Fasilitas</h3>
                        <div class="facility-grid">
                            <?php foreach ($list_fasilitas as $nama_fac => $icon_path) : ?>
                                <div class="fac-item">
                                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                        <path d="<?= $icon_path; ?>"></path>
                                    </svg>
                                    <span><?= htmlspecialchars($nama_fac); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>

                <div class="right-col">
                    <div class="booking-card">
                        <div class="price-header">
                            <h2>Rp <?php echo number_format($harga_tipe, 0, ',', '.'); ?></h2>
                            <span>per bulan</span>
                        </div>

                        <div class="booking-details">
                            <div class="detail-box">
                                <div class="box-label">Minimal Tinggal</div>
                                <div class="box-val">1 Month</div>
                            </div>
                            <div class="detail-box">
                                <div class="box-label">Deposit</div>
                                <div class="box-val">Rp 500.000</div>
                            </div>
                        </div>

                        <?php if (isset($_SESSION['id_user'])) : ?>
                            <button class="btn-pesan"
                                onclick="window.location.href='booking.php?id_tipe=<?php echo $id_tipe; ?>'"
                                style="cursor: pointer;">
                                Pesan Sekarang
                            </button>
                        <?php else : ?>
                            <button class="btn-pesan"
                                onclick="alert('Silakan login terlebih dahulu untuk melakukan pemesanan!'); window.location.href='login.php';"
                                style="cursor: pointer; background: #6c757d;">
                                Login untuk Memesan
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        const allThumbnails = document.querySelectorAll('.thumb');
        const displayImage = document.querySelector('.main-image img');

        allThumbnails.forEach(item => {
            item.addEventListener('click', function() {
                allThumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const clickedImgSrc = this.querySelector('img').getAttribute('src');
                displayImage.src = clickedImgSrc;
            });
        });
    </script>
</body>

</html>