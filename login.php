<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? 'Ricky Naila';
    $name = explode('@', $email)[0];
    if (empty($name) || $name === 'Ricky Naila') {
        $name = 'Ricky Naila';
    } else {
        $name = ucfirst($name);
    }
    $_SESSION['user_name'] = $name;
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
    <link rel="stylesheet" href="auth.css">
</head>
<body class="auth-bg">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Masuk</h2>
            <p>Masukan kredensial untuk Anda untuk melanjutkan</p>
        </div>

        <form action="" method="POST">
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
            <p>Belum Punya Akun? <a href="daftar.php">Daftar sini</a></p>
        </div>
    </div>
</body>
</html>
