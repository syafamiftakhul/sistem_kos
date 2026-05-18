<?php
include '../koneksi.php';
/** @var mysqli $koneksi */


$id = $_GET['id'] ?? '';

$query = mysqli_query($koneksi, "SELECT * FROM tipe_kamar WHERE id_tipe = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: tipe_kamar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tipe Kamar - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/tambah_kamar.css">
</head>
<body>
    <div class="main-wrapper"> <div class="form-container">
            <div class="form-header">
                <h2>Edit Tipe Kamar</h2>
                <p>Silakan isi data tipe kamar dan fasilitas yang sesuai.</p>
            </div>

                <form action="proses_edit_tipe.php" method="POST">
                <div class="input-group">
                    <label>Id Tipe</label>
                    <input type="text" name="id_tipe"  value="<?= $data['id_tipe']; ?>" placeholder="Contoh: T01" required>
                </div>

                <div class="input-group">
                    <label>Nama Tipe Kamar</label>
                    <input type="text" name="nama_tipe" value="<?= $data['nama_tipe']; ?>" placeholder="Contoh: Deluxe" required>
                </div>

                <div class="input-group">
                    <label>Harga Per Bulan</label>
                    <input type="number" name="harga"  value="<?= $data['harga']; ?>" placeholder="Contoh: 1500000" required>
                </div>

                <div class="input-group">
                    <label>Fasilitas</label>
                    <textarea name="fasilitas" rows="5" placeholder="Contoh: AC, WiFi, Kamar Mandi Dalam"  required><?= $data['fasilitas']; ?></textarea>
                </div>

                <div class="form-actions" style="display: flex; gap: 15px; margin-top: 20px;">
                    <a href="tipe_kamar.php" class="btn-batal" style="text-decoration: none; padding: 10px 20px; background: #eee; border-radius: 8px; color: #333;">Batal</a>
                    <button type="submit" name="simpan" class="btn-simpan" style="padding: 10px 20px; background: #81A6C6; color: white; border: none; border-radius: 8px; cursor: pointer;">Simpan Tipe</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function fetchFasilitas() {
            const idTipe = document.getElementById('select-tipe').value;
            const textarea = document.getElementById('display-fasilitas');
            if (!idTipe) { textarea.value = ""; return; }

            fetch('get_fasilitas.php?id=' + idTipe)
                .then(res => res.text())
                .then(data => { textarea.value = data; });
        }
    </script>
</body>
</html>