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

<hr>

<form method="post" action="<?= site_url('alatbahan/proses_pengurangan') ?>">
    <label>Nama Alat</label>
    <select name="alat_id">
        <option value="">-- Pilih Alat --</option>
        <?php foreach ($alat as $a): ?>
            <option value="<?= $a['id_alat'] ?>"><?= esc($a['nama_alat']) ?></option>
        <?php endforeach ?>
    </select>

    <label>Nama Bahan</label>
    <select name="bahan_id">
        <option value="">-- Pilih Bahan --</option>
        <?php foreach ($bahan as $b): ?>
            <option value="<?= $b['id_bahan'] ?>"><?= esc($b['nama_bahan']) ?></option>
        <?php endforeach ?>
    </select>

    <label>Volume</label>
    <input type="number" name="volume" required min="1">

    <label>Lokasi</label>
    <input type="text" name="lokasi" required pattern="[a-zA-Z0-9\s]+" title="Tidak boleh karakter spesial">

    <button type="submit">Simpan</button>
</form>
