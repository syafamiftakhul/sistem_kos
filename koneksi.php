<?php
// Coba koneksi ke IP (Herd/Mac) dulu
$koneksi = @mysqli_connect('127.0.0.1', 'root', '', 'db_sistem_kos');

// Kalau gagal, coba pakai localhost (XAMPP/Windows)
if (!$koneksi) {
    $koneksi = mysqli_connect('localhost', 'root', '', 'db_sistem_kos');
}

// Kalau keduanya gagal, baru kasih error
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>