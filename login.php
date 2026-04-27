
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Kos Aqsya Residence</title>
    
    <link rel="stylesheet" href="assets/css/login.css">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
</head>
<body>

    <div class="login-card">
        <h2>Masuk</h2>
        <p>Masukan kredensial untuk Anda untuk melanjutkan</p>

        <!-- NOTIFIKASI -->
        <?php if (isset($_GET['pesan'])): ?>
            <div style="color: #e74c3c; background: #fdeaea; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; text-align: center;">
                <?php 
                    if ($_GET['pesan'] == "gagal") echo "Login gagal! Email atau password salah.";
                    if ($_GET['pesan'] == "wajib_login") echo "Anda harus login dulu untuk memesan.";
                    if ($_GET['pesan'] == "logout") echo "Anda telah berhasil keluar.";
                ?>
            </div>
        <?php endif; ?>

        <!-- FORM (CUMA 1 SEKARANG) -->
        <form action="proses_login.php" method="POST">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan Email. . ." required><br><br>

            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan Password. . ." required><br><br>

            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="divider"></div>

        <div class="footer-text a">
            Belum Punya Akun? <a href="daftar.php">Daftar sini</a>
        </div>
    </div>

</body>
</html>