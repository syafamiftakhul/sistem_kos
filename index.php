<?php 
session_start(); 
include 'koneksi.php';

$nama = "User";
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $akses = $_SESSION['akses'];
    
    $query_user = mysqli_query($koneksi, "SELECT email FROM user WHERE id_user='$id_user'");
    if ($query_user && mysqli_num_rows($query_user) > 0) {
        $data_user = mysqli_fetch_assoc($query_user);
        $nama = $data_user['email'];
    }
    
    if ($akses == 2) {
        $query = mysqli_query($koneksi, "SELECT * FROM customer WHERE id_user='$id_user'");
        if ($query && mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            $nama = $data['nama'];
        }
    } else if ($akses == 1) {
        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id_user'");
        if ($query && mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            $nama = $data['nama'];
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="initial-scale=1, width=device-width" />

  <link rel="stylesheet" href="assets/css/global.css" />
  <link rel="stylesheet" href="assets/css/index.css?v=3" />

  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" />
</head>

<body>
  <section class="dashboard-user1-inner" style="position: absolute; width: 100%; top: 0; left: 0; z-index: 100; background: rgba(255,255,255,0.9);">
    <header class="rectangle-parent" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; width: 100%; box-sizing: border-box;">

      <div class="frame-parent" style="display: flex; align-items: center; gap: 15px;">
        <div class="key">
          <a href="index.php">
            <img class="icon" src="assets/img/key.png" alt="Logo Kos" style="width: 25px; height: 25px; object-fit: contain;">
          </a>
        </div>
        <h3 class="kos-aqsya-residence" style="margin: 0; font-size: 1.2rem; font-weight: 700;">Kos Aqsya Residence</h3>
      </div>

      <nav class="frame-group" style="display: flex; align-items: center; gap: 25px;">
        <div class="beranda-wrapper">
          <h2 class="beranda" onclick="window.location.href='index.php'" style="margin: 0; font-size: 1rem; cursor: pointer;">Beranda</h2>
        </div>

        <?php if (isset($_SESSION['id_user'])): ?>
          <?php if ($_SESSION['akses'] == 1): ?>
             <h2 class="beranda" onclick="window.location.href='admin/dashboard_admin.php'" style="margin: 0; font-size: 1rem; cursor: pointer;">Dashboard Admin</h2>
          <?php else: ?>
             <h2 class="beranda" onclick="window.location.href='user/dashboard_user.php'" style="margin: 0; font-size: 1rem; cursor: pointer;">Dashboard Saya</h2>
          <?php endif; ?>
          
          <div style="display: flex; align-items: center; gap: 8px; border: 1px solid #81A6C6; padding: 6px 15px; border-radius: 20px; color: #81A6C6; font-weight: 500;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
              </svg>
              <span><?php echo htmlspecialchars($nama); ?></span>
          </div>
          <a href="logout.php" class="logout-link" style="text-decoration: none; color: red; font-size: 0.9rem;">Keluar</a>
        <?php else: ?>
          <a href="login.php" class="button-masuk" style="text-decoration: none; border: 1px solid #81A6C6; color: #81A6C6; padding: 8px 20px; border-radius: 5px; font-weight: 500;">Masuk</a>
          <a href="daftar.php" class="button-daftar" style="text-decoration: none; background: #81A6C6; color: white; padding: 8px 20px; border-radius: 5px; font-weight: 500;">Daftar</a>
        <?php endif; ?>
      </nav>
    </header>
  </section>

  <div class="dashboard-user1">
    <div class="hero-section" style="position: relative; width: 100%; height: 500px; overflow: hidden; margin: 0;">
      <img src="assets/img/bedroom1.jpg" style="width: 100%; height: 100%; object-fit: cover;">

      <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5);"></div>

      <div class="hero-content" style="position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white; width: 80%; z-index: 2;">
        <h1 style="font-size: 48px; margin: 0; font-weight: 700;">Kos Aqsya Residence</h1>

        <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 15px;">
          <img src="assets/img/map.png" style="width: 20px; filter: brightness(0) invert(1);">
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
              <img class="wifi-icon" alt="wifi" src="img/wifii.png"/>
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
                src="assets/img/car.png" />
            </div>
            <div class="parkir-motor">Parkir Motor<br />& Mobil</div>
          </div>
        </div>
        <div class="fasilitas-3">
          <div class="fasilitas1-child"></div>
          <div class="coffee-wrapper">
            <img class="coffee-icon" alt="" src="assets/img/coffee.img" />
          </div>
          <div class="parkir-motor">Dapur<br />Bersama</div>
        </div>
        <div class="fasilitas-4">
          <div class="fasilitas1-child"></div>
          <div class="shield-wrapper">
            <div class="shield">
              <img class="icon2" alt="" src="assets/img/cctv.png" />
            </div>
          </div>
          <h3 class="cctv">CCTV</h3>
        </div>
        <div class="fasilitas-5">
          <div class="fasilitas1-child"></div>
          <div class="message-circle-wrapper">
            <div class="shield">
              <img class="icon3" alt="" src="assets/img/couch.png" />
            </div>
          </div>
          <h3 class="cctv">Ruang Tamu</h3>
        </div>
        <div class="fasilitas-6">
          <div class="fasilitas1-child"></div>
          <div class="shield">
            <img class="icon4" alt="" src="assets/img/ac.png" />
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
                <img class="icon5" alt="" src="assets/img/shower.png" />
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

      <div class="frame-parent4" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">

        <section class="deluxe-content" style="display: flex; flex-direction: column; background: var(--white); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
          <div class="tempimagedb346c-1-parent" style="position: relative; height: 200px;">
            <img class="tempimagedb346c-1-icon" src="assets/img/bedroom1.jpg" alt="Room 1" style="width: 100%; height: 100%; object-fit: cover;" />
            <div class="status-containers-inner" style="position: absolute; top: 10px; right: 10px; background: #8bc34a; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px;">
              <h3 class="tersedia" style="margin:0;">Tersedia</h3>
            </div>
          </div>
          <div class="deluxe-info" style="padding: 20px;">
            <h2 class="deluxe-room-a1">Deluxe Room A1</h2>
            <div class="single-1">Single - 1 Orang - 20m²</div>
            <div class="amenity-items-parent" style="margin-top: 10px;">
              <span class="amenity-items">WiFi</span>
              <span class="feature-a-c">AC</span>
            </div>
            <div class="price-container" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
              <div class="mulai-dari">Mulai Dari</div>
              <div class="price-range" style="display: flex; justify-content: space-between; align-items: center;">
                <h2 class="rp-1000000" style="margin:0;">Rp 1.000.000 <small>/bln</small></h2>
                <a href="detail_kamar1.php" class="button-daftar2" style="text-decoration:none;">Detail</a>
              </div>
            </div>
          </div>
        </section>

        <section class="deluxe-content" style="display: flex; flex-direction: column; background: var(--white); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
          <div class="tempimagedb346c-1-parent" style="position: relative; height: 200px;">
            <img class="tempimagedb346c-1-icon" src="assets/img/bedroom1.jpg" alt="Room 2" style="width: 100%; height: 100%; object-fit: cover;" />
            <div class="status-containers-inner" style="position: absolute; top: 10px; right: 10px; background: #8bc34a; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px;">
              <h3 class="tersedia" style="margin:0;">Tersedia</h3>
            </div>
          </div>
          <div class="deluxe-info" style="padding: 20px;">
            <h2 class="deluxe-room-a1">Deluxe Room A2</h2>
            <div class="single-1">Single - 1 Orang - 20m²</div>
            <div class="amenity-items-parent" style="margin-top: 10px;">
              <span class="amenity-items">WiFi</span>
              <span class="feature-a-c">AC</span>
            </div>
            <div class="price-container" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
              <div class="mulai-dari">Mulai Dari</div>
              <div class="price-range" style="display: flex; justify-content: space-between; align-items: center;">
                <h2 class="rp-1000000" style="margin:0;">Rp 700.000 <small>/bln</small></h2>
                <a href="detail_kamar2.php" class="button-daftar2" style="text-decoration:none;">Detail</a>
              </div>
            </div>
          </div>
        </section>

        <section class="deluxe-content" style="display: flex; flex-direction: column; background: var(--white); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
          <div class="tempimagedb346c-1-parent" style="position: relative; height: 200px;">
            <img class="tempimagedb346c-1-icon" src="assets/img/bedroom1.jpg" alt="Room 3" style="width: 100%; height: 100%; object-fit: cover;" />
            <div class="status-containers-inner" style="position: absolute; top: 10px; right: 10px; background: #8bc34a; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px;">
              <h3 class="tersedia" style="margin:0;">Tersedia</h3>
            </div>
          </div>
          <div class="deluxe-info" style="padding: 20px;">
            <h2 class="deluxe-room-a1">Standard Room</h2>
            <div class="single-1">Single - 1 Orang - 15m²</div>
            <div class="amenity-items-parent" style="margin-top: 10px;">
              <span class="amenity-items">WiFi</span>
            </div>
            <div class="price-container" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
              <div class="mulai-dari">Mulai Dari</div>
              <div class="price-range" style="display: flex; justify-content: space-between; align-items: center;">
                <h2 class="rp-1000000" style="margin:0;">Rp 400.000 <small>/bln</small></h2>
                <a href="detail_kamar3.php" class="button-daftar2" style="text-decoration:none;">Detail</a>
              </div>
            </div>
          </div>
        </section>

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