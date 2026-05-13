<?php
include '../koneksi.php';
/** @var mysqli $koneksi */

// 1. Ambil ID Kamar dari URL
$id = $_GET['id'] ?? '';

// 2. Tarik data kamar yang mau diedit (JOIN dengan tipe buat ambil fasilitasnya)
$query = mysqli_query($koneksi, "SELECT kamar.*, tipe_kamar.fasilitas 
                                 FROM kamar 
                                 LEFT JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
                                 WHERE kamar.id_kamar = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: kamar.php");
    exit;
}

// 3. Ambil semua daftar tipe buat pilihan di dropdown
$query_tipe = mysqli_query($koneksi, "SELECT * FROM tipe_kamar");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kamar - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/tambah_kamar.css">
</head>
<body>
    <div class="main-wrapper"> 
        <div class="form-container">
            <div class="form-header">
                <h2>Edit Kamar Unit: <?php echo $data['nomor_kamar']; ?></h2>
                <p>Ubah tipe atau nomor unit kamar di bawah ini.</p>
            </div>

            <form action="proses_edit_kamar.php" method="POST">
                <input type="hidden" name="id_kamar" value="<?php echo $data['id_kamar']; ?>">

                <div class="input-group">
                    <label>Nomor Unit Kamar</label>
                    <input type="text" name="nomor_kamar" value="<?php echo $data['nomor_kamar']; ?>" required>
                </div>

                <div class="input-group">
                    <label>Pilih Tipe Kamar</label>
                    <select name="id_tipe" id="select-tipe" required onchange="fetchFasilitas()">
                        <?php while($t = mysqli_fetch_assoc($query_tipe)) : ?>
                            <option value="<?= $t['id_tipe']; ?>" <?= ($t['id_tipe'] == $data['id_tipe']) ? 'selected' : ''; ?>>
                                <?= $t['nama_tipe']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <label>Fasilitas Saat Ini (Berdasarkan Tipe)</label>
                    <textarea id="display-fasilitas" readonly rows="4"><?php echo $data['fasilitas']; ?></textarea>
                    <small>*Fasilitas diedit melalui menu Tipe Kamar</small>
                </div>

                <div class="input-group">
                    <label>Status Kamar</label>
                    <select name="status_kamar">
                        <option value="tersedia" <?= ($data['status_kamar'] == 'tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                        <option value="terisi" <?= ($data['status_kamar'] == 'terisi') ? 'selected' : ''; ?>>Terisi</option>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="kamar.php" class="btn-batal">Batal</a>
                    <button type="submit" name="update" class="btn-simpan">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Script biar fasilitas langsung berubah pas dropdown Tipe diganti
        function fetchFasilitas() {
            const idTipe = document.getElementById('select-tipe').value;
            const textarea = document.getElementById('display-fasilitas');
            
            fetch('get_fasilitas.php?id=' + idTipe)
                .then(res => res.text())
                .then(data => { textarea.value = data; });
        }
    </script>
</body>
</html>