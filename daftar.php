<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-bg">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Daftar Akun Baru</h2>
            <p>Lengkapi Data Diri untuk mengisi</p>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Lengkap. . ." required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" placeholder="Masukkan Email. . ." required>
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" placeholder="Masukkan Password. . ." required>
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>

        <div class="auth-footer">
            <p>Sudah Punya Akun? <a href="login.php">Masuk sini</a></p>
        </div>
    </div>
</body>
</html>
