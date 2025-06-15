<h2>Tambah Data</h2>
<form action="<?= base_url('manajemen/tambah') ?>" method="post">
    <label>Jenis:</label>
    <select name="jenis" id="jenisTambah">
        <option value="alat">Alat</option>
        <option value="bahan">Bahan</option>
    </select><br>

    <label>Nama:</label>
    <input type="text" name="nama" required><br>

    <label>Jumlah:</label>
    <input type="number" name="jumlah" step="any" required><br>

    <label>Satuan:</label>
    <select name="satuan">
        <option value="gram">gram</option>
        <option value="mililiter">mililiter</option>
    </select><br>

    <label>Lokasi:</label>
    <input type="text" name="lokasi" required><br>

    <label>Tanggal:</label>
    <input type="date" name="tanggal" required><br>

    <button type="submit">Tambah</button>
</form>

<hr>

<h2>Kurangi Data</h2>
<form action="<?= base_url('manajemen/kurang') ?>" method="post">
    <label>Jenis:</label>
    <select name="jenis" id="jenisKurang">
        <option value="alat">Alat</option>
        <option value="bahan">Bahan</option>
    </select><br>

    <label>Nama:</label>
    <select name="nama">
        <?php foreach(array_merge($alat, $bahan) as $item): ?>
            <option value="<?= $item['nama_alat'] ?? $item['nama_bahan'] ?>">
                <?= $item['nama_alat'] ?? $item['nama_bahan'] ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Jumlah:</label>
    <input type="number" name="jumlah" step="any" required><br>

    <label>Satuan:</label>
    <select name="satuan">
        <option value="gram">gram</option>
        <option value="mililiter">mililiter</option>
    </select><br>

    <label>Lokasi:</label>
    <select name="lokasi">
        <?php foreach($lokasi as $lok): ?>
            <option value="<?= $lok ?>"><?= $lok ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Tanggal:</label>
    <input type="date" name="tanggal" required><br>

    <button type="submit">Kurangi</button>
</form>
