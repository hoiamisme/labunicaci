<h2>Masukkan Alat dan Bahan</h2>
<form method="post" action="<?= site_url('pemakaian/add') ?>">
    <label>Jenis:</label>
    <select name="jenis" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="alat">Alat</option>
        <option value="bahan">Bahan</option>
    </select><br>

    <label>Nama:</label>
    <input type="text" name="nama" pattern="[A-Za-z\s]+" required><br>

    <label>Jumlah:</label>
    <input type="number" name="jumlah" min="1" required><br>

    <label>Satuan:</label>
    <select name="satuan" required>
        <option value="gram">gram</option>
        <option value="mililiter">mililiter</option>
    </select><br>

    <label>Lokasi:</label>
    <select name="lokasi" required>
        <?php foreach($lokasi as $lok): ?>
            <option value="<?= esc($lok) ?>"><?= esc($lok) ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Tanggal:</label>
    <input type="date" name="tanggal" required><br>

    <button type="submit">Add</button>
</form>

<hr>

<h2>Pesan Permintaan Pemakaian</h2>
<form method="post" action="<?= site_url('pemakaian/submit') ?>">
    <label>Nama:</label>
    <input type="text" name="nama" pattern="[A-Za-z\s]+" required><br>

    <label>Alat:</label>
    <input type="text" name="alat" pattern="[A-Za-z\s]+" required><br>

    <label>Bahan:</label>
    <input type="text" name="bahan" pattern="[A-Za-z\s]+" required><br>

    <label>Tujuan Pemakaian:</label>
    <input type="text" name="tujuan" pattern="[A-Za-z\s]+" required><br>

    <label>Keterangan:</label>
    <select name="keterangan" required>
        <option value="">-- Pilih Keterangan --</option>
        <option value="Praktikum">Praktikum</option>
        <option value="Skripsi">Skripsi</option>
        <option value="Lomba">Lomba</option>
    </select><br>

    <label>Pesan:</label>
    <input type="text" name="pesan" pattern="[A-Za-z\s]+" required><br>

    <button type="submit">Submit</button>
</form>
