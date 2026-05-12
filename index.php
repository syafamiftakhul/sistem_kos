<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="initial-scale=1, width=device-width" />

  <link rel="stylesheet" href="assets/css/global.css" />
  <link rel="stylesheet" href="assets/css/index.css?v=2" />

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
          <h2 class="beranda" onclick="window.location.href='dashboard.php'" style="margin: 0; font-size: 1rem; cursor: pointer;">Dashboard Saya</h2>
          <a href="logout.php" class="logout-link" style="text-decoration: none; color: red;">Keluar</a>
        <?php else: ?>
          <a href="login.php" class="button-masuk" style="text-decoration: none; border: 1px solid #81A6C6; padding: 8px 20px; border-radius: 5px;">Masuk</a>
          <a href="daftar.php" class="button-daftar" style="text-decoration: none; background: #81A6C6; color: white; padding: 8px 20px; border-radius: 5px;">Daftar</a>
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
              <img class="wifi-icon" alt="" src="assets/img/wifi.png" />
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
    <section class="select-fields">
      <div class="select-fields-child"></div>
      <div class="select-field">
        <div class="label">Tipe Kamar</div>
        <div class="description">Description</div>
        <select class="select-dropdown">
          <option value="">Semua Tipe Kamar</option>
          <option value="deluxe_a1">Deluxe Room A1</option>
          <option value="deluxe_a2">Deluxe Room A2</option>
          <option value="standard">Standard Room</option>
        </select>
      </div>
      <div class="select-field">
        <div class="label">Range Harga</div>
        <div class="description">Description</div>
        <select class="select-dropdown">
          <option value="">Semua Harga</option>
          <option value="under_500">&lt; Rp 500.000</option>
          <option value="500_800">Rp 500.000 - Rp 800.000</option>
          <option value="over_800">&gt; Rp 800.000</option>
        </select>
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