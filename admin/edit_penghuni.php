<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

// 1. Tangkap id_kamar dari URL
$id_kamar = $_GET['id'] ?? '';

if (empty($id_kamar)) {
    header("Location: penghuni.php");
    exit;
}

// 2. Ambil data penghuni aktif yang mau diedit berdasarkan id_kamar
$query = "SELECT c.no_ktp, c.nama, c.no_hp, k.nomor_kamar, t.tgl_masuk, t.periode, t.id_transaksi
          FROM pesanan p
          JOIN customer c ON p.no_ktp = c.no_ktp
          JOIN kamar k ON p.id_kamar = k.id_kamar
          LEFT JOIN transaksi t ON p.id_pesanan = t.id_pesanan
          WHERE p.id_kamar = '$id_kamar' AND p.status_pesanan = 'lunas' LIMIT 1";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

// Jika penghuni tidak ditemukan, balikin ke halaman list
if (!$data) {
    header("Location: penghuni.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Penghuni - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/tambah_kamar.css">
</head>
<body>
    <div class="main-wrapper"> 
        <div class="form-container">
            <div class="form-header">
                <h2>Edit Data Penghuni</h2>
                <p>Ubah informasi sewa, tanggal masuk, atau kontak dari penghuni kos.</p>
            </div>

            <form action="proses_edit_penghuni.php" method="POST">
                <input type="hidden" name="no_ktp" value="<?= $data['no_ktp']; ?>">
                <input type="hidden" name="id_transaksi" value="<?= $data['id_transaksi']; ?>">
                <input type="hidden" name="id_kamar_lama" value="<?= $id_kamar; ?>">

                <div class="input-group">
                    <label>Nama Penghuni</label>
                    <input type="text" value="<?= htmlspecialchars($data['nama']); ?>" readonly style="background: #f5f5f5; color: #666; cursor: not-allowed;">
                </div>

                <div class="input-group">
                    <label>Kamar Saat Ini</label>
                    <input type="text" value="Kamar <?= htmlspecialchars($data['nomor_kamar'] ?? $id_kamar); ?>" readonly style="background: #f5f5f5; color: #666; cursor: not-allowed;">
                </div>

                <div class="input-group">
                    <label>Kontak HP</label>
                    <input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp']); ?>" placeholder="Contoh: 0812345678" required>
                </div>

                <div class="input-group">
                    <label>Tanggal Masuk Kos</label>
                    <input type="date" name="tgl_masuk" value="<?= $data['tgl_masuk'] ?? date('Y-m-d'); ?>" required>
                </div>

                <div class="input-group">
                    <label>Periode Sewa (Bulan)</label>
                    <input type="number" name="periode" value="<?= $data['periode'] ?? 1; ?>" min="1" placeholder="Contoh: 3" required>
                </div>

                <div class="form-actions" style="display: flex; gap: 15px; margin-top: 20px;">
                    <a href="penghuni.php" class="btn-batal" style="text-decoration: none; padding: 10px 20px; background: #eee; border-radius: 8px; color: #333; text-align: center; line-height: 20px;">Batal</a>
                    <button type="submit" name="update_penghuni" class="btn-simpan" style="padding: 10px 20px; background: #81A6C6; color: white; border: none; border-radius: 8px; cursor: pointer;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>