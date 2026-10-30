<?php
require __DIR__ . '/auth.php';
include '../koneksi.php';
/** @var mysqli $koneksi */

if (!isset($_POST['update'])) {
    header("Location: kamar.php");
    exit;
}

$id_kamar    = (int)($_POST['id_kamar'] ?? 0);
$nomor_kamar = trim($_POST['nomor_kamar'] ?? '');
$id_tipe     = (int)($_POST['id_tipe'] ?? 0);
$status      = strtolower(trim($_POST['status_kamar'] ?? 'kosong'));

if (!in_array($status, ['kosong', 'booking', 'terisi'], true)) {
    $status = 'kosong';
}
if ($id_kamar <= 0 || $nomor_kamar === '' || $id_tipe <= 0) {
    echo "<script>alert('Data kamar tidak lengkap!'); window.history.back();</script>";
    exit;
}

mysqli_begin_transaction($koneksi);
try {
    $lama = q_row(q($koneksi, "SELECT status_kamar FROM kamar WHERE id_kamar = ? FOR UPDATE", 'i', $id_kamar));
    if (!$lama) {
        throw new Exception('Kamar tidak ditemukan.');
    }

    if ($status === 'kosong') {
        q($koneksi,
          "UPDATE kamar SET nomor_kamar = ?, id_tipe = ?, status_kamar = 'kosong', no_ktp = NULL WHERE id_kamar = ?",
          'sii', $nomor_kamar, $id_tipe, $id_kamar);
    } else {
        q($koneksi,
          "UPDATE kamar SET nomor_kamar = ?, id_tipe = ?, status_kamar = ? WHERE id_kamar = ?",
          'sisi', $nomor_kamar, $id_tipe, $status, $id_kamar);
    }

    // Kamar baru saja dikosongkan admin: tutup pesanan yang masih menempel
    if ($status === 'kosong' && $lama['status_kamar'] !== 'kosong') {
        q($koneksi,
          "UPDATE pesanan SET status_pesanan = 'selesai' WHERE id_kamar = ? AND status_pesanan = 'lunas'",
          'i', $id_kamar);
        q($koneksi,
          "UPDATE transaksi t JOIN pesanan p ON p.id_pesanan = t.id_pesanan
           SET t.status_transaksi = 'ditolak'
           WHERE p.id_kamar = ? AND p.status_pesanan = 'pending' AND t.status_transaksi = 'pending'",
          'i', $id_kamar);
        q($koneksi,
          "UPDATE pesanan SET status_pesanan = 'batal' WHERE id_kamar = ? AND status_pesanan = 'pending'",
          'i', $id_kamar);
    }

    mysqli_commit($koneksi);
    echo "<script>alert('Data kamar berhasil diperbarui!'); window.location='kamar.php';</script>";

} catch (mysqli_sql_exception $e) {
    mysqli_rollback($koneksi);
    $pesan = ($e->getCode() == 1062) ? 'Nomor kamar sudah dipakai kamar lain!' : 'Gagal menyimpan data kamar.';
    echo "<script>alert(" . json_encode($pesan) . "); window.history.back();</script>";
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    echo "<script>alert(" . json_encode($e->getMessage()) . "); window.location='kamar.php';</script>";
}