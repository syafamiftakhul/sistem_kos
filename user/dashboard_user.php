<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

$query_kamar = mysqli_query($koneksi, "
    SELECT k.*, tk.nama_tipe, tk.harga, tk.fasilitas
    FROM kamar k
    JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
");

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$query = mysqli_query($koneksi, "SELECT * FROM customer WHERE id_user='$id_user'");

if ($query && mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    $nama = $data['nama'];
} else {
    $query_user = mysqli_query($koneksi, "SELECT email FROM user WHERE id_user='$id_user'");
    if ($query_user && mysqli_num_rows($query_user) > 0) {
        $data_user = mysqli_fetch_assoc($query_user);
        $nama = $data_user['email'];
    } else {
        $nama = "User";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="../assets/css/global.css" />
    <link rel="stylesheet" href="../assets/css/index.css?v=3" />

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
</head>

<body>
    <section class="dashboard-user1-inner" style="position: absolute; width: 100%; top: 0; left: 0; z-index: 100; background: #ffffff; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);">
        <header class="rectangle-parent" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; width: 100%; box-sizing: border-box;">

            <div class="frame-parent" style="display: flex; align-items: center; gap: 15px;">
                <div class="key" style="background-color: #81A6C6; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <a href="../index.php" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                        <img class="icon" src="../assets/img/key.png" alt="Logo Kos" style="width: 24px; height: 24px; object-fit: contain; filter: brightness(0) invert(1);">
                    </a>
                </div>
                <h3 class="kos-aqsya-residence" style="margin: 0; font-size: 1.1rem; font-weight: 500; color: #000;">Kos Aqsya Residence</h3>
            </div>

            <nav class="frame-group" style="display: flex; align-items: center; gap: 30px;">

                <div class="beranda-wrapper">
                    <h2 class="beranda"
                        onclick="window.location.href='../index.php'"
                        style="margin: 0; font-size: 0.95rem; cursor: pointer; color: #000; font-weight: 500;">
                        Beranda
                    </h2>
                </div>

                <?php if (isset($_SESSION['id_user'])): ?>

                    <h2 class="beranda"
                        onclick="window.location.href='dashboard_private_user.php'"
                        style="margin: 0; font-size: 0.95rem; cursor: pointer; color: #000; font-weight: 500;">
                        Dashboard Saya
                    </h2>

                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="display: flex; align-items: center; gap: 8px; border: 1px solid #81A6C6; padding: 8px 16px; border-radius: 8px; color: #81A6C6; font-weight: 500; background: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                            </svg>
                            <span style="font-size: 0.95rem;"><?php echo htmlspecialchars($nama); ?></span>
                        </div>
                        <a href="../logout.php" style="text-decoration:none; color:red; font-size: 0.95rem; font-weight: 500;">
                            Keluar
                        </a>
                    </div>

                <?php else: ?>

                    <a href="../login.php"
                        class="button-masuk"
                        style="text-decoration:none; border:1px solid #81A6C6; color:#81A6C6; padding:8px 20px; border-radius:8px; font-weight: 500;">
                        Masuk
                    </a>

                    <a href="../daftar.php"
                        class="button-daftar"
                        style="text-decoration:none; background:#81A6C6; color:white; padding:8px 20px; border-radius:8px; font-weight: 500;">
                        Daftar
                    </a>

                <?php endif; ?>

            </nav>
        </header>
    </section>

    <div class="dashboard-user1">
        <div class="hero-section" style="position: relative; width: 100%; height: 500px; overflow: hidden; margin: 0;">
            <img src="../assets/img/bedroom1.jpg" style="width: 100%; height: 100%; object-fit: cover;">

            <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5);"></div>

            <div class="hero-content" style="position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white; width: 80%; z-index: 2;">
                <h1 style="font-size: 48px; margin: 0; font-weight: 700;">Kos Aqsya Residence</h1>

                <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 15px;">
                    <img src="../assets/img/map.png" style="width: 20px; filter: brightness(0) invert(1);">
                    <span style="font-size: 18px;">Jl. Mangu Indah No.88, Kaliwungu, Kab. Kendal</span>
                </div>

                <div style="margin-top: 15px; font-size: 20px; font-weight: bold;">
                    <span style="color: #FFD700;">★</span> 4.8 <span style="font-weight: normal; font-size: 16px;">(145 Ulasan)</span>
                </div>

                <p style="max-width: 700px; margin: 20px auto; font-size: 16px; line-height: 1.6; opacity: 0.9;">
                    Kos modern dan nyaman dengan fasilitas lengkap di lokasi strategis Kaliwungu.
                    Dekat dengan pusat bisnis, kampus, dan transportasi umum.
                </p>
            </div>
        </div>
    </div>
    <section class="frame-section">
        <div class="frame-parent3">
            <div class="fasilitas-kos-wrapper">
                <h2 class="fasilitas-kos">Fasilitas Kos</h2>
            </div>
            <div class="facility-icons">
                <div class="facility-rows">
                    <div class="fasilitas1">
                        <div class="fasilitas1-child"></div>
                        <div class="icon-rows">
                            <img class="wifi-icon" alt="" src="../assets/img/wifii.png" />
                        </div>
                        <div class="wifi-100mbps">WiFi<br />100Mbps</div>
                    </div>
                </div>
                <div class="facility-rows">
                    <div class="fasilitas-2">
                        <div class="fasilitas1-child"></div>
                        <div class="directions-car-wrapper">
                            <img
                                class="wifi-icon"
                                alt=""
                                src="../assets/img/parkir.png" />
                        </div>
                        <div class="parkir-motor">Parkir Motor & Mobil</div>
                    </div>
                </div>
                <div class="fasilitas-3">
                    <div class="fasilitas1-child"></div>
                    <div class="coffee-wrapper">
                        <img class="dapur-icon" alt="" src="../assets/img/dapur.png" />
                    </div>
                    <div class="dapur-icon">Dapur Bersama</div>
                </div>
                <div class="fasilitas-4">
                    <div class="fasilitas1-child"></div>
                    <div class="shield-wrapper">
                        <div class="shield">
                            <img class="icon2" alt="" src="../assets/img/cctv.png" />
                        </div>
                    </div>
                    <h3 class="cctv">CCTV</h3>
                </div>
                <div class="fasilitas-5">
                    <div class="fasilitas1-child"></div>
                    <div class="message-circle-wrapper">
                        <div class="shield">
                            <img class="icon3" alt="" src="../assets/img/tamu.png" />
                        </div>
                    </div>
                    <h3 class="cctv">Ruang Tamu</h3>
                </div>
                <div class="fasilitas-6">
                    <div class="fasilitas1-child"></div>
                    <div class="shield">
                        <img class="icon4" alt="" src="../assets/img/ac.png" />
                    </div>
                    <div class="ac-wrapper">
                        <h3 class="ac">AC</h3>
                    </div>
                </div>
                <div class="fasilitas-7-wrapper">
                    <div class="fasilitas-7">
                        <div class="fasilitas1-child"></div>
                        <div class="users-wrapper">
                            <div class="shield">
                                <img class="icon5" alt="" src="../assets/img/kamarmandi.png" />
                            </div>
                        </div>
                        <div class="parkir-motor">Kamar Mandi<br />Bersama</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <main class="select-fields-parent">
        <h2 class="kamar-tersedia">Kamar Tersedia</h2>

        <style>
            .deluxe-content {
                display: flex;
                flex-direction: column;
                background: var(--white);
                border-radius: 12px;
                overflow: hidden;
                margin-bottom: 25px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                transition: transform 0.3s ease, box-shadow 0.3s ease !important;
                cursor: pointer;
            }

            .deluxe-content:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
            }
        </style>

        <div class="frame-parent4" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">

      <?php $semua_kamar = mysqli_fetch_all($query_kamar, MYSQLI_ASSOC); ?>

      <?php if (!empty($semua_kamar)): ?>
        <?php foreach ($semua_kamar as $kamar):
          $status = strtolower($kamar['status_kamar']);
          $badge_text = ($status == 'terisi') ? 'Terisi' : 'Tersedia';
          $badge_bg   = ($status == 'terisi') ? '#e74c3c' : '#8bc34a';

          // KUNCI UTAMA: Semua tombol sekarang nembak ke SATU file detail_kamar.php yang sama!
          $link_detail = "detail_kamar.php";
        ?>
          <section class="deluxe-content">
            <div class="tempimagedb346c-1-parent" style="position: relative; height: 200px;">
              <img class="tempimagedb346c-1-icon" src="../assets/img/bedroom1.jpg" alt="Room" style="width: 100%; height: 100%; object-fit: cover;" />
              <div class="status-containers-inner" style="position: absolute; top: 10px; right: 10px; background: <?= $badge_bg; ?>; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                <h3 style="margin:0; font-size: 12px;"><?= $badge_text; ?></h3>
              </div>
            </div>
            <div class="deluxe-info" style="padding: 20px;">
              <h2 class="deluxe-room-a1" style="margin: 0 0 5px 0; font-size: 1.5rem;"><?= htmlspecialchars($kamar['nama_tipe']); ?> <?= htmlspecialchars($kamar['nomor_kamar']); ?></h2>
              <div class="single-1">Single - 1 Orang - 20m²</div>
              
              <div class="amenity-items-parent" style="margin-top: 10px; display: flex; gap: 5px; flex-wrap: wrap;">
                <?php 
                if (!empty($kamar['fasilitas'])) {
                  // Memecah string fasilitas berdasarkan koma (,) menjadi array
                  $list_fasilitas = explode(',', $kamar['fasilitas']); 
                  foreach ($list_fasilitas as $fasilitas_item) {
                    echo '<span class="amenity-items" style="background: #f0f0f0; padding: 2px 8px; border-radius: 4px; font-size: 12px; margin-right: 5px;">' . htmlspecialchars(trim($fasilitas_item)) . '</span>';
                  }
                } else {
                  echo '<span class="amenity-items">-</span>';
                }
                ?>
              </div>

              <div class="price-container" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
                <div class="mulai-dari">Mulai Dari</div>
                <div class="price-range" style="display: flex; justify-content: space-between; align-items: center;">
                  <h2 class="rp-1000000" style="margin:0;">Rp <?= number_format((float)$kamar['harga'], 0, ',', '.'); ?> <small>/bln</small></h2>
                  <a href="../detail_kamar.php?id_tipe=<?= $kamar['id_tipe']; ?>" class="button-daftar2" style="text-decoration:none; background-color: #81A6C6; color: white; padding: 6px 18px; border-radius: 4px; font-size: 14px;">Detail</a>
                </div>
              </div>
            </div>
          </section>
        <?php endforeach; ?>
        <?php endif; ?> </div>


        </div>

        </div>
        </div>
    </main>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-col footer-col-info">
                <h2 class="footer-title">Kos Aqsya Residence</h2>
                <p class="footer-desc">
                    Kos modern dan nyaman dengan fasilitas lengkap di lokasi
                    strategis Kaliwungu. Dekat dengan pusat bisnis, kampus, dan
                    transportasi umum. Lingkungan aman dan nyaman.
                </p>
            </div>
            <div class="footer-col footer-col-contact">
                <h2 class="footer-title">Kontak</h2>
                <p class="footer-text">
                    Ibu Rum<br />081234456567<br />kosbudherum@gmail.com
                </p>
            </div>
            <div class="footer-col footer-col-address">
                <h2 class="footer-title">Alamat</h2>
                <p class="footer-text">
                    Jl. Mangu Indah No.88, Kaliwungu, Kab. Kendal<br />Jawa Tengah, 51372
                </p>
            </div>
        </div>
        <div class="footer-copyright">
            <p>© 2026 Kos Aqsya Residence. All rights reserved.</p>
        </div>
    </footer>
    </div>
</body>

</html>