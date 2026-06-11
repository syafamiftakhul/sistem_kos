<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */

// 1. TANGKAP PARAMETER RENTANG TANGGAL
$tgl_awal_pilihan  = $_GET['tgl_awal'] ?? '2026-02-01';
$tgl_akhir_pilihan = $_GET['tgl_akhir'] ?? '2026-03-11';

$target_bulanan = 5000000;

$kondisi_where_transaksi = "WHERE t.tgl_transaksi BETWEEN '$tgl_awal_pilihan' AND '$tgl_akhir_pilihan'";
$kondisi_where_pesanan   = "AND p.tgl_pesan BETWEEN '$tgl_awal_pilihan' AND '$tgl_akhir_pilihan'";
$kondisi_where_riwayat   = "WHERE p.tgl_pesan BETWEEN '$tgl_awal_pilihan' AND '$tgl_akhir_pilihan'";

// 2. QUERY UTAMA LAPORAN PENDAPATAN
$sql = "SELECT 
            bulan_num,
            bulan_nama,
            SUM(jml_bayar) as total_pendapatan,
            COUNT(DISTINCT id_kamar) as kamar_terisi
        FROM (
            SELECT 
                MONTH(t.tgl_transaksi) as bulan_num,
                DATE_FORMAT(t.tgl_transaksi, '%M') as bulan_nama, 
                t.jml_bayar,
                p.id_kamar
            FROM transaksi t 
            LEFT JOIN pesanan p ON t.id_pesanan = p.id_pesanan
            $kondisi_where_transaksi

            UNION ALL

            SELECT 
                MONTH(p.tgl_pesan) as bulan_num,
                DATE_FORMAT(p.tgl_pesan, '%M') as bulan_nama,
                1000000 as jml_bayar,
                p.id_kamar
            FROM pesanan p
            LEFT JOIN transaksi t ON p.id_pesanan = t.id_pesanan
            WHERE p.status_pesanan = 'lunas' 
              AND t.id_transaksi IS NULL 
              $kondisi_where_pesanan
        ) as gabungan
        GROUP BY bulan_num, bulan_nama
        ORDER BY bulan_num ASC";

$result = mysqli_query($koneksi, $sql);

// 3. QUERY RIWAYAT HUNIAN
$sql_riwayat = "SELECT 
                    k.nomor_kamar,
                    tk.nama_tipe,
                    c.nama, 
                    p.tgl_pesan as tgl_mulai,
                    DATE_ADD(p.tgl_pesan, INTERVAL 1 MONTH) as tgl_habis,
                    DATEDIFF(DATE_ADD(p.tgl_pesan, INTERVAL 1 MONTH), CURRENT_DATE()) as sisa_hari
                FROM pesanan p
                JOIN kamar k ON p.id_kamar = k.id_kamar
                JOIN tipe_kamar tk ON k.id_tipe = tk.id_tipe
                JOIN customer c ON p.no_ktp = c.no_ktp 
                $kondisi_where_riwayat AND p.status_pesanan = 'lunas'
                ORDER BY p.tgl_pesan DESC";

$result_riwayat = mysqli_query($koneksi, $sql_riwayat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - Aqsya Kos</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; padding: 20px; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; color: #222; }
        .header p { margin: 5px 0 0 0; color: #555; font-size: 14px; }
        .info-periode { margin-bottom: 20px; font-size: 14px; background: #f8fafc; padding: 10px; border-left: 4px solid #81A6C6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 13px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; }
        th { background-color: #f1f5f9; font-weight: bold; color: #1e293b; }
        .text-right { text-align: right; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; color: #1e293b; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        
        /* Otomatis memicu dialog printer PDF browser */
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN KEUANGAN & RIWAYAT HUNIAN</h1>
        <h1>AQSYA KOS</h1>
        <p>Sistem Informasi Manajemen Rumah Kos</p>
    </div>

    <div class="info-periode">
        <strong>Periode Laporan:</strong> <?= date('d M Y', strtotime($tgl_awal_pilihan)); ?> s.d <?= date('d M Y', strtotime($tgl_akhir_pilihan)); ?>
    </div>

    <div class="section-title">I. Ringkasan Pendapatan Bulanan</div>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Pendapatan</th>
                <th>Target</th>
                <th>Kamar Terisi</th>
                <th>Pencapaian</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $pendapatan = $row['total_pendapatan'];
                    $persen_pencapaian = ($pendapatan / $target_bulanan) * 100;
                ?>
                    <tr>
                        <td><strong><?= $row['bulan_nama']; ?></strong></td>
                        <td>Rp <?= number_format($pendapatan, 0, ',', '.'); ?></td>
                        <td>Rp <?= number_format($target_bulanan, 0, ',', '.'); ?></td>
                        <td><?= $row['kamar_terisi']; ?> Kamar</td>
                        <td><strong><?= round($persen_pencapaian, 1); ?>%</strong></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #777;">Belum ada data transaksi pada rentang tanggal ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">II. Laporan Riwayat Hunian & Batas Kontrak Kamar</div>
    <table>
        <thead>
            <tr>
                <th>No. Kamar</th>
                <th>Tipe Kamar</th>
                <th>Nama Penghuni</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Jatuh Tempo</th>
                <th>Sisa Masa Sewa</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result_riwayat) > 0): ?>
                <?php while ($riwayat = mysqli_fetch_assoc($result_riwayat)): 
                    $sisa = $riwayat['sisa_hari'];
                    $status_teks = ($sisa < 0) ? "Lewat " . abs($sisa) . " Hari" : $sisa . " Hari Lagi";
                ?>
                    <tr>
                        <td><strong><?= $riwayat['nomor_kamar']; ?></strong></td>
                        <td><?= $riwayat['nama_tipe']; ?></td>
                        <td><?= !empty($riwayat['nama']) ? $riwayat['nama'] : '-'; ?></td>
                        <td><?= date('d M Y', strtotime($riwayat['tgl_mulai'])); ?></td>
                        <td><?= date('d M Y', strtotime($riwayat['tgl_habis'])); ?></td>
                        <td><?= $status_teks; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #777;">Tidak ada aktivitas hunian pada rentang tanggal ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        // Saat halaman terbuka, otomatis langsung memicu perintah cetak/save PDF browser
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>