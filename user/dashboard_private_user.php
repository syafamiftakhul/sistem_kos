<?php
session_start();
include '../koneksi.php';
/** @var mysqli $koneksi */


if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $akses = $_SESSION['akses'];

    // Langsung tembak cari nama panjang berdasarkan hak akses, jangan lewat email dulu
    if ($akses == 2 || $akses == 'customer') {
        $query = mysqli_query($koneksi, "SELECT nama FROM customer WHERE id_user='$id_user'");
        if ($query && mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            $nama = $data['nama'];
        }
    } else if ($akses == 1 || $akses == 'admin') {
        $query = mysqli_query($koneksi, "SELECT nama FROM user WHERE id_user='$id_user'");
        if ($query && mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            $nama = $data['nama'];
        }
    }
}


$query_cek_customer = mysqli_query($koneksi, "SELECT nama, no_ktp, no_hp FROM customer WHERE id_user = '$id_user'");
$data_customer = mysqli_fetch_assoc($query_cek_customer);

$no_ktp = $data_customer['no_ktp'] ?? null;
$no_telp = $data_customer['no_hp'] ?? '-'; // Perbaikan variabel biar gak typo no_telp/no_hp

$booking = null;
$keluhan = null;

// Ganti query_booking jadi begini:
$query_booking = mysqli_query($koneksi, "SELECT pesanan.*, kamar.nomor_kamar, tipe_kamar.nama_tipe, tipe_kamar.harga, transaksi.tgl_masuk, transaksi.periode 
    FROM pesanan 
    JOIN kamar ON pesanan.id_kamar = kamar.id_kamar 
    JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
    LEFT JOIN transaksi ON pesanan.id_pesanan = transaksi.id_pesanan
    WHERE pesanan.no_ktp = '$no_ktp' 
      AND pesanan.status_pesanan IN ('lunas', 'pending', 'proses', 'selesai') 
    ORDER BY pesanan.id_pesanan DESC LIMIT 1");
$booking = mysqli_fetch_assoc($query_booking);

if (!empty($booking)) {
    $periode_bulan = (int)($booking['periode'] ?? 1);
    $tgl_masuk = $booking['tgl_masuk'];

    $tgl_masuk_dt = new DateTime($tgl_masuk);
    $tgl_selesai = clone $tgl_masuk_dt;
    $tgl_selesai->modify('+' . $periode_bulan . ' month');

    $today = new DateTime();
    $diff = $today->diff($tgl_selesai);
    $sisa_hari = (int)$diff->format('%r%a');
}



// Mengambil data semua keluhan milik customer yang login
$query_keluhan = mysqli_query($koneksi, "SELECT pengaduan.*, kamar.nomor_kamar, tipe_kamar.nama_tipe 
        FROM pengaduan 
        JOIN kamar ON pengaduan.id_kamar = kamar.id_kamar 
        JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
        WHERE pengaduan.no_ktp = '$no_ktp' 
        ORDER BY tgl_lapor DESC");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Saya - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/dashboard_private_user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <header style="display: flex; align-items: center; padding: 16px 40px; border-bottom: 1px solid #E5E7EB; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
        <div style="background-color: #81A6C6; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
            <a href="../index.php" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                <img src="../assets/img/key.png" alt="Logo" style="width: 18px; filter: brightness(0) invert(1);">
            </a>
        </div>
        <h1 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #81A6C6; letter-spacing: -0.02em;">Kos Aqsya Residence</h1>
    </header>

    <div class="main-content">
        <a href="dashboard_user.php" style="color: #6B7280; text-decoration: none; display: inline-flex; align-items: center; margin-bottom: 24px; font-size: 14px; font-weight: 500;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali
        </a>
        <h2 class="dashboard-title">Dashboard Saya</h2>
        <p class="dashboard-subtitle">Pantau pesanan kamar dan sampaikan keluhan Anda di sini.</p>

        <h3>Pesanan Saya</h3>
        <?php if (!empty($no_ktp) && !empty($booking)) : ?>
            <div class="card-box">
                <div class="card-top">
                    <div>
                        <div class="booking-id">Booking #<?= $booking['id_pesanan']; ?></div>
                        <div class="booking-date">
                            <i class="far fa-calendar"></i>
                            <?php
                            if (!empty($booking)) {
                                $periode_bulan = (int)($booking['periode'] ?? 1);
                                $tgl_masuk = $booking['tgl_masuk'];

                                // Cek apakah data tgl_masuk sudah ada (berarti sudah disetujui admin/sudah lunas)
                                if (!empty($tgl_masuk)) {
                                    $tgl_masuk_dt = new DateTime($tgl_masuk);
                                    $tgl_selesai = clone $tgl_masuk_dt;
                                    $tgl_selesai->modify('+' . $periode_bulan . ' month');

                                    $today = new DateTime();
                                    $diff = $today->diff($tgl_selesai);
                                    $sisa_hari = (int)$diff->format('%r%a');
                                } else {
                                    // Kalau belum disetujui, set null agar tidak muncul di tampilan
                                    $sisa_hari = null;
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    $status = $booking['status_pesanan'];

                    // Logika badge dinamis
                    if ($status == 'lunas') {
                        echo '<span class="badge-confirm" style="background:#10B981;">Terkonfirmasi</span>';
                    } elseif ($status == 'pending') {
                        echo '<span class="badge-confirm" style="background:#F59E0B;">Menunggu Verifikasi</span>';
                    } elseif ($status == 'selesai') {
                        echo '<span class="badge-confirm" style="background:#6B7280;">Kadaluwarsa</span>';
                    } else {
                        echo '<span class="badge-confirm" style="background:#6B7280;">Proses</span>';
                    }
                    ?>
                </div>

                <div class="grid-info" style="position: relative;">
                    <div>
                        <div class="info-label">Nama Penghuni</div>
                        <div class="info-value"><?= htmlspecialchars($data_customer['nama'] ?? 'User'); ?></div>
                    </div>
                    <div>
                        <div class="info-label">Nomor Telepon</div>
                        <div class="info-value"><?= htmlspecialchars($data_customer['no_hp'] ?? '-'); ?></div>
                    </div>
                    <div>
                        <div class="info-label">Kamar</div>
                        <div class="info-value"><?= htmlspecialchars($booking['nama_tipe'] . ' - ' . $booking['nomor_kamar']); ?></div>
                    </div>
                    <div>
                        <div class="info-label">Total Price</div>
                        <div class="info-value price-value">Rp <?= number_format((float)($booking['harga'] ?? 0), 0, ',', '.'); ?></div>
                    </div>

                   <div style="margin-top: 15px; margin-bottom: 10px; font-size: 12px; font-weight: 600; color: #6B7280; background: #F3F4F6; padding: 4px 8px; border-radius: 6px; width: fit-content;">
    <?php if ($sisa_hari !== null && $booking['status_pesanan'] == 'lunas') : ?>
        <div style="font-size: 12px; font-weight: 600; color: #6B7280; background: #F3F4F6; padding: 6px 12px; border-radius: 6px;">
            <i class="far fa-clock"></i>
            <?php
            if ($sisa_hari < 0) echo "Telah Berakhir";
            elseif ($sisa_hari == 0) echo "Habis Hari Ini";
            else echo $sisa_hari . " Hari Lagi";
            ?>
        </div>
    <?php endif; ?>
</div>
                </div>
                <?php if ($booking['status_pesanan'] == 'lunas') : ?>
                    <a href="pengaduan.php?id_kamar=<?= $booking['id_kamar']; ?>" class="btn-complaint" style="text-decoration: none; display: inline-block; text-align: center;">
                        <i class="fas fa-exclamation-circle"></i> Tambahkan Pengaduan
                    </a>
                <?php else : ?>
                    <div style="background: #FFFBEB; padding: 12px; border-radius: 8px; font-size: 13px; color: #92400E; border: 1px solid #FDE68A; margin-top: 10px;">
                        <i class="fas fa-info-circle"></i> Tombol pengaduan akan aktif setelah pembayaran diverifikasi oleh admin.
                    </div>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <p class="empty-text" style="margin-top: -10px; margin-bottom: 30px;">Anda belum melakukan riwayat pesanan</p>
        <?php endif; ?>

        <h3>Keluhan Saya</h3>
        <?php if (!empty($booking)) : ?>
            <?php if ($query_keluhan && mysqli_num_rows($query_keluhan) > 0) : ?>
                <?php while ($row_keluhan = mysqli_fetch_assoc($query_keluhan)) : ?>
                    <div class="card-box" style="margin-bottom: 16px;">
                        <div class="card-top" style="margin-bottom: 12px;">
                            <div class="keluhan-title"><?= htmlspecialchars($row_keluhan['subjek'] ?? 'Keluhan Tanpa Subjek'); ?></div>
                            <?php
                            $status = strtolower($row_keluhan['status_pengaduan'] ?? 'menunggu');
                            if ($status == 'menunggu') {
                                echo '<div class="badge-menunggu">Menunggu</div>';
                            } elseif ($status == 'proses') {
                                echo '<div class="badge-proses">Sedang Diproses</div>';
                            } elseif ($status == 'selesai') {
                                echo '<div class="badge-selesai">Selesai</div>';
                            }
                            ?>
                        </div>

                        <div class="keluhan-room">
                            <i class="fas fa-door-open"></i> <?= htmlspecialchars($row_keluhan['nama_tipe'] . ' - Room ' . $row_keluhan['nomor_kamar']); ?>
                        </div>

                        <div class="keluhan-desc">
                            <?= htmlspecialchars($row_keluhan['deskripsi'] ?? ''); ?>
                        </div>

                        <div class="keluhan-date">
                            Dikirim pada <?= htmlspecialchars(date('d/m/Y', strtotime($row_keluhan['tgl_lapor']))); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="card-box empty-state" style="text-align: center; padding: 40px 20px; border: 1px dashed #D1D5DB; border-radius: 8px;">
                    <i class="fas fa-comment-slash" style="font-size: 36px; color: #D1D5DB; margin-bottom: 16px; display: block;"></i>
                    <p class="empty-text" style="margin: 0; color: #6B7280; font-size: 14px; font-weight: 500;">Belum ada keluhan yang diajukan.</p>
                    <p style="margin: 4px 0 0 0; color: #9CA3AF; font-size: 12px;">Jika Anda mengalami kendala di kamar Anda, silakan tambahkan pengaduan menggunakan tombol di atas.</p>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="card-box empty-state" style="text-align: center; padding: 40px 20px; border: 1px dashed #D1D5DB; border-radius: 8px;">
                <i class="fas fa-lock" style="font-size: 36px; color: #D1D5DB; margin-bottom: 16px; display: block;"></i>
                <p class="empty-text" style="margin: 0; color: #6B7280; font-size: 14px; font-weight: 500;">Fitur Keluhan Dikunci</p>
                <p style="margin: 4px 0 0 0; color: #9CA3AF; font-size: 12px;">Lakukan pemesanan kamar terlebih dahulu untuk bisa memberikan pengaduan.</p>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>