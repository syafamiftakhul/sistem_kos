<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    
    $akses = 0;

    $cek_email = mysqli_query($koneksi, "SELECT email FROM user WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah digunakan, silakan gunakan email lain!'); window.history.back();</script>";
    } else {
        // Buat username otomatis dari email (ambil kata sebelum @)
        $username = explode('@', $email)[0]; 

        // PASTIIN nama kolom sesuai (nama, username, email, password, akses)
        // Dan bungkus tabel `user` pake backtick biar gak bentrok di MySQL
        $query_user = "INSERT INTO `user` (nama, username, email, password, akses) 
                       VALUES ('$nama', '$username', '$email', '$password', 'penghuni')";
        
        if (mysqli_query($koneksi, $query_user)) {
            echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href='login.php';</script>";
        } else {
            // Biar lu tau error pastinya kalau gagal lagi
            die("Gagal daftar: " . mysqli_error($koneksi));
        }
    }
}
?>