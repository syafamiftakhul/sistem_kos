<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

if (!isset($_GET['id'])) {
    die("ID Transaksi tidak ditemukan.");
}

$id_transaksi = mysqli_real_escape_string($koneksi, $_GET['id']);

// Fetch transaction details
$query = mysqli_query($koneksi, "SELECT t.*, c.nama, c.no_hp, p.id_kamar, k.nomor_kamar, tk.nama_tipe, tk.harga
                                 FROM transaksi t
                                 JOIN customer c ON t.no_ktp = c.no_ktp
                                 LEFT JOIN pesanan p ON t.id_pesanan = p.id_pesanan
                                 LEFT JOIN kamar k ON p.id_kamar = k.id_kamar
                                 LEFT JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
                                 WHERE t.id_transaksi = '$id_transaksi'");

if (mysqli_num_rows($query) == 0) {
    die("Data transaksi tidak ditemukan.");
}

$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran - Kos Aqsya Residence</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 40px;
            background: #fff;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #F3E3D0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-header h1 {
            color: #81A6C6;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 14px;
            line-height: 1.6;
        }
        .invoice-details div {
            flex: 1;
        }
        .table-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table-details th {
            background-color: #81A6C6;
            color: #fff;
            text-align: left;
            padding: 12px;
            font-size: 14px;
        }
        .table-details td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .total-amount {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
        }
        .struk-container {
            margin-top: 30px;
            border-top: 1px dashed #ccc;
            padding-top: 20px;
        }
        .struk-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #555;
        }
        .struk-img {
            max-width: 100%;
            max-height: 450px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            display: block;
        }
        .footer-note {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 50px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .print-btn {
            background-color: #81A6C6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 20px;
            display: inline-block;
            text-decoration: none;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
            .invoice-box {
                border: none;
                box-shadow: none;
                padding: 0;
            }
            .struk-container {
                page-break-inside: avoid; /* Mencegah gambar terpotong kertas print */
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div style="max-width: 800px; margin: auto; text-align: right;" class="no-print">
        <button onclick="window.print()" class="print-btn">Cetak Halaman Ini</button>
    </div>
    
    <div class="invoice-box">
        <div class="invoice-header">
            <div>
                <h1>Aqsya Residence</h1>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: #888;">Jl. Mangu Indah No.88, Kaliwungu, Kab. Kendal</p>
            </div>
            <div style="text-align: right;">
                <h2 style="margin: 0; font-size: 18px; color: #555;">KUITANSI RESMI</h2>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: #888;">No. Transaksi: #TRX-<?= $data['id_transaksi'] ?></p>
            </div>
        </div>

        <div class="invoice-details">
            <div>
                <strong>Diberikan Kepada:</strong><br>
                Nama: <?= htmlspecialchars($data['nama']) ?><br>
                Telepon: <?= htmlspecialchars($data['no_hp'] ?? '-') ?><br>
            </div>
            <div style="text-align: right;">
                <strong>Detail Transaksi:</strong><br>
                Tanggal Bayar: <?= date('d M Y', strtotime($data['tgl_transaksi'])) ?><br>
                Status: <span style="color: #2E7D32; font-weight: bold;"><?= strtoupper($data['status_transaksi'] ?? 'LUNAS') ?></span>
            </div>
        </div>

        <table class="table-details">
            <thead>
                <tr>
                    <th>Deskripsi Pembayaran</th>
                    <th>Kamar</th>
                    <th>Periode Sewa</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sewa Kamar Kos Aqsya Residence (Tipe <?= htmlspecialchars($data['nama_tipe'] ?? 'Standar') ?>)</td>
                    <td>Kamar <?= htmlspecialchars($data['nomor_kamar'] ?? $data['id_kamar']) ?></td>
                    <td><?= !empty($data['periode']) ? htmlspecialchars($data['periode']) : '1' ?> Bulan</td>
                    <td style="text-align: right;">Rp <?= number_format($data['jml_bayar'], 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

        <div class="total-amount">
            Total Pembayaran: Rp <?= number_format($data['jml_bayar'], 0, ',', '.') ?>
        </div>

        <div class="struk-container">
            <div class="struk-title">Lampiran Bukti Transfer Pembeli:</div>
            <?php if (!empty($data['bukti_transaksi']) && file_exists("../assets/uploads/" . $data['bukti_transaksi'])): ?>
                <img src="../assets/uploads/<?= htmlspecialchars($data['bukti_transaksi']) ?>" class="struk-img" alt="Struk Pembayaran">
            <?php else: ?>
                <p style="color: #c0392b; font-style: italic; font-size: 13px;">File bukti transfer tidak ditemukan di folder atau pembeli belum mengunggah struk.</p>
            <?php endif; ?>
        </div>
        <div class="footer-note">
            <p>Terima kasih atas pembayaran Anda. Ini adalah kuitansi pembayaran resmi yang sah dan diterbitkan secara elektronik oleh sistem Kos Aqsya Residence.</p>
        </div>
    </div>
</body>
</html>