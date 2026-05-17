<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = "SELECT * FROM user WHERE email='$email'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        // cek password
        if ($password == $row['password']) {

            // SESSION
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['akses'] = $row['akses'];
            $_SESSION['no_ktp']  = $row['no_ktp'];
            $_SESSION['nama']    = $row['nama'];

            // AKSES
            if ($row['akses'] == 'admin') {

                // ADMIN
                header("Location: admin/dashboard_admin.php");
            } else {
            
                // USER
                header("Location: user/dashboard_user.php");
            }

            exit;
        } else {

            echo "<script>
                    alert('Password salah!');
                    window.location.href='login.php';
                  </script>";
        }
    } else {

        echo "<script>
                alert('Email tidak terdaftar!');
                window.location.href='login.php';
              </script>";
    }
}
