<?php
session_start();
include "koneksi.php";

if (isset($_POST['konfirmasi'])) {
    // 1. Ambil data dari form (tambahin sesuai field yang lu punya)
    $no_ktp = $_POST['no_ktp'] ?? ''; 
    
    // 2. Urusan File Foto
    $nama_file = $_FILES['bukti_transfer']['name'];
    $tmp_file  = $_FILES['bukti_transfer']['tmp_name'];
    $ukuran    = $_FILES['bukti_transfer']['size'];
    $error     = $_FILES['bukti_transfer']['error'];

    // Cek apakah ada file yang diupload
    if ($error === 0) {
        // Validasi Ukuran (2MB = 2.097.152 bytes)
        if ($ukuran > 2097152) {
            echo "<script>alert('Gagal! File kegedean (Maks 2MB)'); window.history.back();</script>";
            exit;
        }

        // Bikin nama file unik biar gak ketuker
        $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_baru = "STRUK_" . time() . "_" . uniqid() . "." . $ekstensi;
        $tujuan    = "uploads/" . $nama_baru;

        // 3. Proses Pindah File & Simpan ke DB
        if (move_uploaded_file($tmp_file, $tujuan)) {
            
            // Query Update (Gue asumsiin datanya udah ada tinggal update bukti & status)
            // Sesuaikan nama kolom 'bukti_transaksi' dengan yang di TablePlus tadi
            $query = "UPDATE transaksi SET 
                      bukti_transaksi = '$nama_baru', 
                      status_transaksi = 'pending' 
                      WHERE no_ktp = '$no_ktp' AND status_transaksi != 'lunas'
                      ORDER BY id_transaksi DESC LIMIT 1";

            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Mantap! Bukti transfer udah kekirim. Tunggu admin ACC ya!'); window.location='index.php';</script>";
            } else {
                echo "Error DB: " . mysqli_error($koneksi);
            }
        } else {
            echo "Gagal upload file ke folder. Cek permission folder uploads lu bre!";
        }
    } else {
        echo "Waduh, filenya nggak kebaca atau rusak bre.";
    }
}
?>