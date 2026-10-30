<?php
// Wajib di-include di baris paling atas setiap file admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_SESSION['akses'] ?? '') !== 'admin') {
    header('Location: ../login.php?pesan=wajib_login');
    exit;
}