<?php
// Memulai session untuk menyimpan data login
session_start();

// Menghubungkan ke database
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Query untuk mencari user berdasarkan email
    $query = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password (Jika kamu simpan password tanpa enkripsi)
        // Disarankan kedepannya pakai password_hash() & password_verify()
        if ($password === $row['password']) {
            
            // SIMPAN DATA KE SESSION
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['email']   = $row['email'];
            $_SESSION['akses']   = $row['akses']; // 1 untuk Admin, 0 untuk User (contoh)

            // Cek akses: Jika admin (1) ke dashboard admin, jika user (0) ke index
            if ($row['akses'] == 1) {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email tidak terdaftar!'); window.location.href='login.php';</script>";
    }
}
?>