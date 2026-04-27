<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password === $row['password']) {
            
            // SIMPAN DATA KE SESSION
            $_SESSION['id_user'] = $row['no_ktp']; // Sesuaikan PK di ERD
            $_SESSION['nama']    = $row['nama'];
            $_SESSION['akses']   = $row['akses']; 

            // REDIRECT LOGIC
            if ($row['akses'] == 1) {
                // Khusus Admin
                header("Location:admin/dashboard_admin.php");
            } else {
                // Selain angka 1 (mau 0 atau 2), lempar ke Dashboard User
                header("Location:user/dashboard_user.php");
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