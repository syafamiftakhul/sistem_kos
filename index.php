<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="initial-scale=1, width=device-width" />

  <link rel="stylesheet" href="assets/css/global.css" />
  <link rel="stylesheet" href="assets/css/index.css" />

  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" />
</head>

<body>
  <div class="dashboard-user1">
    <section class="tempimagettnyuq-1-parent">
      <img
        class="tempimagettnyuq-1-icon"
        alt=""
        src="assets/img/bedroom1.jpg" />

      <img class="frame-child" alt="" src="./public/Rectangle-23.svg" />
    </section>
    <section class="dashboard-user1-inner">
      <header class="rectangle-parent">
        <div class="frame-item"></div>
        <div class="frame-parent">
          <div class="rectangle-group">
            <div class="frame-inner"></div>
            <div class="key">
              <a href="index.php">
                <img class="icon" src="assets/img/key.png" alt="Logo Kos" style="width: 20px; height: 20px; object-fit: contain;">
              </a>
            </div>
          </div>
          <div class="kos-aqsya-residence-wrapper">
            <h3 class="kos-aqsya-residence">Kos Aqsya Residence</h3>
          </div>
        </div>
        <div class="frame-wrapper">
          <nav class="frame-group">

            <div class="beranda-wrapper">
              <h2 class="beranda" onclick="window.location.href='index.php'">Beranda</h2>
            </div>

            <?php if (isset($_SESSION['id_user'])): ?>

              <!-- USER SUDAH LOGIN -->
              <div class="beranda-wrapper" style="margin-left: 24px;">
                <h2 class="beranda" onclick="window.location.href='dashboard.php'">
                  Dashboard Saya
                </h2>
              </div>

                <a href="logout.php" class="logout-link">Keluar</a>
              </div>

            <?php else: ?>

              <!-- USER BELUM LOGIN -->
              <a href="login.php" class="button-masuk">
                Masuk
              </a>

              <a href="daftar.php" class="button-daftar">
                Daftar
              </a>

            <?php endif; ?>

          </nav>
        </div>
      </header>
    </section>
    <section class="dashboard-user1-child">
      <div class="frame-container">
        <div class="frame-div">
          <div class="frame-parent2">
            <div class="kos-aqsya-residence-parent">
              <h1 class="kos-aqsya-residence2">Kos Aqsya Residence</h1>
              <div class="location-on-parent">
                <img
                  class="location-on-icon"
                  alt=""
                  src="assets/img/map.png" />

                <div class="jl-mangu-indah-no88-kaliwun-wrapper">
                  <h2 class="jl-mangu-indah">
                    Jl. Mangu Indah No.88, Kaliwungu, Kab. Kendal
                  </h2>
                </div>
              </div>
            </div>
            <div class="frame-wrapper2">
              <div class="star-parent">
                <img
                  class="star-icon"
                  loading="lazy"
                  alt=""
                  src="assets/img/star.png" />

                <div class="review-items">
                  <h2 class="h2">️4.8</h2>
                </div>
                <div class="review-items2">
                  <h2 class="ulasan">(145 Ulasan)</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="kos-modern-dan">
          Kos modern dan nyaman dengan fasilitas lengkap di lokasi strategis
          Kaliwungu. Dekat dengan pusat bisnis, kampus, dan transportasi umum.
          Lingkungan aman dengan keamanan 24 jam.
        </div>
      </div>
    </section>
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
          <div class="select">
            <div class="value">WiFi, AC, ..</div>
            <img
              class="chevron-down-icon"
              alt=""
              src="./public/Chevron-down.svg" />

            <div class="options">
              <div class="hello-world">Hello World</div>
              <div class="option-2">Option 2</div>
              <div class="option-2">Option 3</div>
              <div class="option-2">Option 4</div>
              <div class="option-2">Option 5</div>
            </div>
          </div>
        </div>
        <div class="select-field">
          <div class="label">Range Harga</div>
          <div class="description">Description</div>
          <div class="select">
            <div class="value">Rp 1.000.000</div>
            <img
              class="chevron-down-icon"
              alt=""
              src="./public/Chevron-down.svg" />

            <div class="options">
              <div class="hello-world">Hello World</div>
              <div class="option-2">Option 2</div>
              <div class="option-2">Option 3</div>
              <div class="option-2">Option 4</div>
              <div class="option-2">Option 5</div>
            </div>
          </div>
        </div>
      </section>
      <main class="select-fields-parent">
        <div class="kamar-tersedia-parent">
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
      <section class="information">
        <div class="information-child"></div>
        <section class="footer-content">
          <div class="footer-info">
            <h2 class="kos-aqsya-residence3">Kos Aqsya Residence</h2>
            <div class="kontak-parent">
              <h2 class="kontak">Kontak</h2>
              <h2 class="kontak">Alamat</h2>
            </div>
          </div>
          <div class="contact-details">
            <div class="description-container">
              <div class="kos-modern-dan2">
                Kos modern dan nyaman dengan fasilitas lengkap di lokasi
                strategis Kaliwungu. Dekat dengan pusat bisnis, kampus, dan
                transportasi umum. Lingkung...
              </div>
            </div>
            <div class="contact-data">
              <div class="ibu-rum-081234456567">
                Ibu Rum<br />081234456567<br />kosbudherum@gmail.com
              </div>
              <div class="jl-mangu-indah-no88-kaliwun-container">
                <div class="jl-mangu-indah2">
                  Jl. Mangu Indah No.88, Kaliwungu, Kab. Kendal
                </div>
              </div>
            </div>
          </div>
        </section>
        <div class="information-item"></div>
        <div class="kos-aqsya-residence-all-right-wrapper">
          <h2 class="kos-aqsya-residence4">
            © 2026 Kos Aqsya Residence. All rights reserved.
          </h2>
        </div>
      </section>
  </div>
</body>

</html>