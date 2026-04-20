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
                <img class="icon" src="<a href="www.flaticon.com/free-icons/real-estate" title="real estate icons">
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
              <h2 class="beranda" style="cursor: pointer;" onclick="window.location.href='index.php'">Beranda</h2>
            </div>

            <?php if (isset($_SESSION['user_name'])): ?>
              <div class="beranda-wrapper" style="margin-left: 24px; margin-right: 24px;">
                <h2 class="beranda" style="color: #333; font-weight: 600; cursor: pointer;" onclick="window.location.href='dashboard.php'">Dashboard Saya</h2>
              </div>
              <div class="user-profile" style="display: flex; align-items: center; justify-content: center; padding: 6px 16px; border: 1.5px solid #83A6C4; border-radius: 20px; cursor: pointer;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                  <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="#83A6C4" />
                </svg>
                <span style="color: #83A6C4; font-weight: 600; font-size: 13px; font-family: 'Inter', sans-serif;"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php" style="margin-left: 12px; font-size: 12px; color: #ff6b6b; text-decoration: none; font-family: 'Inter', sans-serif;">(Keluar)</a>
              </div>
            <?php else: ?>
              <a href="login.php" class="button-masuk">
                <div class="masuk">Masuk</div>
              </a>
              <a href="daftar.php" class="button-daftar">
                <div class="daftar">Daftar</div>
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
                  src="./public/location-on.svg" />

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
                  src="./public/Star.svg" />

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
                <img class="wifi-icon" alt="" src="./public/Wifi.svg" />
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
                  src="./public/directions-car.svg" />
              </div>
              <div class="parkir-motor">Parkir Motor<br />& Mobil</div>
            </div>
          </div>
          <div class="fasilitas-3">
            <div class="fasilitas1-child"></div>
            <div class="coffee-wrapper">
              <img class="coffee-icon" alt="" src="./public/Coffee.svg" />
            </div>
            <div class="parkir-motor">Dapur<br />Bersama</div>
          </div>
          <div class="fasilitas-4">
            <div class="fasilitas1-child"></div>
            <div class="shield-wrapper">
              <div class="shield">
                <img class="icon2" alt="" src="./public/Icon1.svg" />
              </div>
            </div>
            <h3 class="cctv">CCTV</h3>
          </div>
          <div class="fasilitas-5">
            <div class="fasilitas1-child"></div>
            <div class="message-circle-wrapper">
              <div class="shield">
                <img class="icon3" alt="" src="./public/Icon2.svg" />
              </div>
            </div>
            <h3 class="cctv">Ruang Tamu</h3>
          </div>
          <div class="fasilitas-6">
            <div class="fasilitas1-child"></div>
            <div class="shield">
              <img class="icon4" alt="" src="./public/Icon3.svg" />
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
                  <img class="icon5" alt="" src="./public/Icon4.svg" />
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
      <div class="frame-wrapper3">
        <div class="kamar-tersedia-parent">
          <h2 class="kamar-tersedia">Kamar Tersedia</h2>
          <div class="room-status-parent">
            <div class="room-status">
              <section class="room-status-inner">
                <div class="tempimagedb346c-1-parent">
                  <img
                    class="tempimagedb346c-1-icon"
                    alt=""
                    src="./public/tempImagedB346c-1@2x.png" />

                  <div class="status-containers-inner">
                    <div class="rectangle-container">
                      <img
                        class="rectangle-icon"
                        alt=""
                        src="./public/Rectangle-23.svg" />

                      <h3 class="tersedia">Tersedia</h3>
                    </div>
                  </div>
                  <div class="star-icon-two">
                    <img
                      class="star-icon-two-child"
                      alt=""
                      src="./public/Rectangle-23.svg" />

                    <img class="star-icon2" alt="" src="./public/Star.svg" />

                    <div class="wrapper">
                      <h2 class="h22">️4.8</h2>
                    </div>
                  </div>
                </div>
              </section>
              <section class="status-containers">
                <img
                  class="tempimagedb346c-1-icon"
                  alt=""
                  src="./public/tempImagedB346c-11@2x.png" />

                <div class="status-rows">
                  <div class="rectangle-container">
                    <img
                      class="rectangle-icon"
                      alt=""
                      src="./public/Rectangle-23.svg" />

                    <h3 class="tersedia">Tersedia</h3>
                  </div>
                </div>
                <div class="star-containers">
                  <img
                    class="star-icon-two-child"
                    alt=""
                    src="./public/Rectangle-23.svg" />

                  <img class="star-icon2" alt="" src="./public/Star.svg" />

                  <div class="wrapper">
                    <h2 class="h22">️4.8</h2>
                  </div>
                </div>
              </section>
              <section class="status-containers2">
                <img
                  class="tempimagedb346c-1-icon"
                  alt=""
                  src="./public/tempImagedB346c-12@2x.png" />

                <div class="status-containers-inner">
                  <div class="rectangle-container">
                    <img
                      class="rectangle-icon"
                      alt=""
                      src="./public/Rectangle-23.svg" />

                    <h3 class="tersedia">Tersedia</h3>
                  </div>
                </div>
                <div class="rectangle-parent3">
                  <img
                    class="star-icon-two-child"
                    alt=""
                    src="./public/Rectangle-23.svg" />

                  <img class="star-icon4" alt="" src="./public/Star.svg" />

                  <div class="wrapper">
                    <h2 class="h22">️4.8</h2>
                  </div>
                </div>
              </section>
            </div>
            <div class="frame-parent4">
              <section class="deluxe-content-wrapper">
                <div class="deluxe-content">
                  <div class="deluxe-content-child"></div>
                  <div class="deluxe-info">
                    <div class="deluxe-description">
                      <div class="room-details">
                        <h2 class="deluxe-room-a1">Deluxe Room A1</h2>
                        <div class="single-1">
                          Single - 1 Orang - 20m²<br /> 
                        </div>
                      </div>
                      <div class="amenity-items-parent">
                        <div class="amenity-items">
                          <div class="amenity-items-child"></div>
                          <h2 class="wifi">WiFi</h2>
                        </div>
                        <div class="feature-a-c">
                          <div class="amenity-items-child"></div>
                          <h2 class="wifi">AC</h2>
                        </div>
                        <div class="rectangle-parent4">
                          <div class="rectangle-div"></div>
                          <h3 class="bed">Bed</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="line-parent">
                    <div class="line-div"></div>
                    <div class="price-details">
                      <div class="price-container">
                        <div class="mulai-dari">Mulai Dari</div>
                        <div class="price-range">
                          <div class="price-values">
                            <div class="rp-1000000-parent">
                              <h2 class="rp-1000000">Rp. 1.000.000</h2>
                              <div class="bulan-wrapper">
                                <div class="bulan">/bulan</div>
                              </div>
                            </div>
                          </div>
                          <a href="detail.php" class="button-daftar2">
                            <div class="daftar">Lihat Detail</div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              <section class="room-types">
                <div class="deluxe-content-child"></div>
                <div class="room-info">
                  <div class="deluxe-description">
                    <div class="amenities-features">
                      <h2 class="standard-room">Deluxe Room A2</h2>
                      <div class="single-12">
                        Single - 1 Orang - 20m²<br /> 
                      </div>
                    </div>
                    <div class="feature-info">
                      <div class="amenity-items">
                        <div class="amenity-items-child"></div>
                        <h2 class="wifi">WiFi</h2>
                      </div>
                      <div class="feature-a-c">
                        <div class="amenity-items-child"></div>
                        <h2 class="wifi">AC</h2>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="room-types-item"></div>
                <div class="price-info">
                  <div class="price-container">
                    <div class="mulai-dari">Mulai Dari</div>
                    <div class="price-range">
                      <div class="price-values">
                        <div class="rp-1000000-parent">
                          <h2 class="rp-700000">Rp. 700.000</h2>
                          <div class="bulan-wrapper">
                            <div class="bulan">/bulan</div>
                          </div>
                        </div>
                      </div>
                      <button class="button-daftar2">
                        <div class="button-daftar-item"></div>
                        <div class="daftar">Lihat Detail</div>
                      </button>
                    </div>
                  </div>
                </div>
              </section>
              <section class="room-types">
                <div class="deluxe-content-child"></div>
                <div class="room-info">
                  <div class="deluxe-description">
                    <div class="amenities-features">
                      <h2 class="standard-room">Standard Room</h2>
                      <div class="single-12">
                        Single - 1 Orang - 20m²<br /> 
                      </div>
                    </div>
                    <div class="rectangle-parent5">
                      <div class="amenity-items-child"></div>
                      <h2 class="wifi">WiFi</h2>
                    </div>
                  </div>
                </div>
                <div class="room-types-item"></div>
                <div class="price-info">
                  <div class="price-container">
                    <div class="mulai-dari">Mulai Dari</div>
                    <div class="price-range">
                      <div class="price-values">
                        <div class="rp-1000000-parent">
                          <h2 class="rp-700000">Rp. 400.000</h2>
                          <div class="bulan-wrapper">
                            <div class="bulan">/bulan</div>
                          </div>
                        </div>
                      </div>
                      <button class="button-daftar2">
                        <div class="button-daftar-item"></div>
                        <div class="daftar">Lihat Detail</div>
                      </button>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
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
            <h2 class="kontak">Kontak</h2>
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