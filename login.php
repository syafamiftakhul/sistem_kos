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

        <form action="proses_login.php" method="POST">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan Email. . ." required><br><br>

            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan Password. . ." required><br><br>

            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="divider"></div>

        <div class="footer-text">
            Belum Punya Akun? <a href="daftar.php">Daftar sini</a>
        </div>
    </div>

</body>
</html>