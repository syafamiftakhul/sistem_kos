<?php
session_start(); // Wajib panggil session_start dulu biar bisa dihapus

// 1. Hapus semua variabel session
session_unset();

// 2. Hancurkan session-nya
session_destroy();

// 3. Arahkan balik ke halaman login (sesuaikan path folder lu)
echo "<script>
    alert('Anda telah berhasil logout.');
    window.location='../index.php';
</script>";
exit();
?>