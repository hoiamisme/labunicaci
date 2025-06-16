<?php if (session()->get('logged_in')): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #f1f1f1;">
        <div>
            <p>Halo, <?= esc(session('nama_lengkap')) ?></p>
        </div>
        <div>
            <a href="/dashboard" style="margin-right: 10px;">🏠 Dashboard</a>
            <a href="/manajemen" style="margin-right: 10px;">🛠️ Manajemen</a>
            <a href="/pemakaian" style="margin-right: 10px;">📦 Pemakaian</a>
            <a href="/logbook" style="margin-right: 10px;">📚 Logbook</a>
            <a href="/profiles" style="margin-right: 10px;">👤 Profiles</a>
            <a href="/logout">🔒 Logout</a>
        </div>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Alat dan Bahan</title>
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>

<h2>Tambah Data</h2>
<form action="<?= base_url('manajemen/tambah') ?>" method="post" id="formTambah">
    <label>Jenis:</label>
    <select name="jenis" id="jenisTambah" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="alat">Alat</option>
        <option value="bahan">Bahan</option>
        <option value="instrumen">Instrumen</option>
    </select><br>

    <label>Nama:</label>
    <input type="text" name="nama" required><br>

    <label>Jumlah:</label>
    <input type="number" name="jumlah" step="any" min="0" required><br>

    <div id="satuanTambahWrapper">
        <label>Satuan:</label>
        <select name="satuan">
            <option value="gram">gram</option>
            <option value="mililiter">mililiter</option>
        </select><br>
    </div>

    <label>Lokasi:</label>
    <input type="text" name="lokasi" id="lokasiTambahInput" required list="daftarLokasi"><br>
    <datalist id="daftarLokasi">
        <?php foreach($lokasi as $lok): ?>
            <option value="<?= strtolower($lok) ?>"></option>
        <?php endforeach; ?>
    </datalist>

    <label>Tanggal:</label>
    <input type="date" name="tanggal" required><br>

    <button type="submit">Tambah</button>
</form>

<hr>

<h2>Kurangi Data</h2>
<form action="<?= base_url('manajemen/kurang') ?>" method="post" id="formKurang">
    <label>Jenis:</label>
    <select name="jenis" id="jenisKurang" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="alat">Alat</option>
        <option value="bahan">Bahan</option>
        <option value="instrumen">Instrumen</option>
    </select><br>

    <label>Nama:</label>
    <select name="nama" id="namaKurang" required>
        <option value="">Pilih jenis dulu</option>
    </select><br>

    <label>Jumlah:</label>
    <input type="number" name="jumlah" id="jumlahKurang" min="0" required><br>

    <label>Satuan:</label>
    <select name="satuan" id="satuanKurang" required>
        <option value="">Pilih nama dulu</option>
    </select><br>

    <label>Lokasi:</label>
    <select name="lokasi" id="lokasiKurang" required>
        <option value="">Pilih nama dulu</option>
    </select><br>

    <label>Tanggal:</label>
    <input type="date" name="tanggal" required><br>

    <button type="submit">Kurangi</button>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const jenisTambah = document.getElementById("jenisTambah");
    const satuanTambahWrapper = document.getElementById("satuanTambahWrapper");
    const lokasiTambahInput = document.getElementById("lokasiTambahInput");

    jenisTambah.addEventListener("change", function () {
        satuanTambahWrapper.style.display = (jenisTambah.value === "bahan") ? "block" : "none";
    });

    lokasiTambahInput.addEventListener("input", function () {
        lokasiTambahInput.value = lokasiTambahInput.value.toLowerCase();
    });

    // === KURANGI ===
    const jenisKurang = document.getElementById("jenisKurang");
    const namaKurang = document.getElementById("namaKurang");
    const satuanKurang = document.getElementById("satuanKurang");
    const lokasiKurang = document.getElementById("lokasiKurang");
    const jumlahKurang = document.getElementById("jumlahKurang");

    jenisKurang.addEventListener("change", function () {
        const jenis = jenisKurang.value;
        namaKurang.innerHTML = '<option>Memuat...</option>';

        fetch(`<?= base_url('api/nama-by-jenis') ?>?jenis=${jenis}`)
            .then(res => res.json())
            .then(data => {
                namaKurang.innerHTML = '<option value="">-- Pilih Nama --</option>';
                data.forEach(nama => {
                    namaKurang.innerHTML += `<option value="${nama}">${nama}</option>`;
                });

                satuanKurang.innerHTML = '<option value="">Pilih nama dulu</option>';
                lokasiKurang.innerHTML = '<option value="">Pilih nama dulu</option>';
            });
    });

    namaKurang.addEventListener("change", function () {
        const jenis = jenisKurang.value;
        const nama = namaKurang.value;

        if (!nama) return;

        fetch(`<?= base_url('api/detail-item') ?>?jenis=${jenis}&nama=${encodeURIComponent(nama)}`)
            .then(res => res.json())
            .then(item => {
                satuanKurang.innerHTML = '';
                lokasiKurang.innerHTML = '';

                if (jenis === "bahan") {
    satuanKurang.innerHTML += `<option value="${item.satuan_bahan}">${item.satuan_bahan}</option>`;
} else {
    satuanKurang.innerHTML += `<option value="-">-</option>`;
}

                if (item.lokasi) {
                    lokasiKurang.innerHTML += `<option value="${item.lokasi.toLowerCase()}">${item.lokasi.toLowerCase()}</option>`;
                }

                // batasi jumlah maksimum
                jumlahKurang.max = jenis === "bahan" ? item.jumlah_bahan : item.jumlah_alat;

                jumlahKurang.max = jenis === "bahan" ? item.jumlah_bahan :
                   jenis === "alat" ? item.jumlah_alat :
                   item.jumlah_instrumen;

            });
    });
});
</script>

</body>
</html>
