<?php
mysqli_report(MYSQLI_REPORT_OFF);

// Coba 127.0.0.1 dulu (Herd/Mac), lalu localhost (XAMPP/Windows)
$koneksi = @mysqli_connect('127.0.0.1', 'root', '', 'db_sistem_kos');
if (!$koneksi) {
    $koneksi = @mysqli_connect('localhost', 'root', '', 'db_sistem_kos');
}
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, 'utf8mb4');

// Setelah konek: error SQL jadi exception (tidak diam-diam lagi)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!function_exists('q')) {
    // Jalankan query aman: q($koneksi, "SELECT ... WHERE id = ?", 'i', $id)
    function q(mysqli $k, string $sql, string $types = '', ...$params): mysqli_stmt {
        $stmt = mysqli_prepare($k, $sql);
        if ($types !== '') {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        return $stmt;
    }

    // Ambil satu baris hasil dari q()
    function q_row(mysqli_stmt $stmt): ?array {
        $r = mysqli_stmt_get_result($stmt);
        return $r ? (mysqli_fetch_assoc($r) ?: null) : null;
    }
}