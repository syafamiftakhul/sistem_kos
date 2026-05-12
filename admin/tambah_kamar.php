<?php
include '../koneksi.php';
/** @var mysqli $koneksi */
$query_tipe = mysqli_query($koneksi, "SELECT * FROM tipe_kamar");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kamar - Aqsya Kos</title>
    <link rel="stylesheet" href="../assets/css/tambah_kamar.css">
</head>
<body>
    <div class="main-wrapper"> <div class="form-container">
            <div class="form-header">
                <h2>Tambah Unit Kamar</h2>
                <p>Silakan isi data nomor kamar dan pilih tipe yang sesuai.</p>
            </div>

            <form action="proses_tambah_kamar.php" method="POST">
                <div class="input-group">
                    <label>Nomor Kamar</label>
                    <input type="text" name="no_kamar" placeholder="Contoh: B12" required>
                </div>

                <div class="input-group">
                    <label>Tipe Kamar</label>
                    <select name="id_tipe" id="select-tipe" required onchange="fetchFasilitas()">
                        <option value="">-- Pilih Tipe Kamar --</option>
                        <?php while($t = mysqli_fetch_assoc($query_tipe)) : ?>
                            <option value="<?= $t['id_tipe']; ?>"><?= $t['nama_tipe']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <label>Fasilitas (Otomatis Muncul)</label>
                    <textarea id="display-fasilitas" readonly placeholder="Pilih tipe kamar dulu..."></textarea>
                </div>

                <div class="form-actions">
                    <a href="kamar.php" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan Data</button>
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