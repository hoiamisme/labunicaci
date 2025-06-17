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

<h2>Kurangi Data</h2>
<form id="formKurang">
    <label>Jenis:</label>
    <select name="jenis" id="jenisKurang" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="alat">Alat</option>
        <option value="bahan">Bahan</option>
    </select><br>

    <label>Nama:</label>
    <select name="nama" id="namaKurang" required></select><br>

    <div id="satuanKurangWrapper">
        <label>Satuan:</label>
        <select id="satuanKurang" disabled></select><br>
    </div>

    <label>Jumlah:</label>
    <input type="number" id="jumlahKurang" min="0" required><br>

    <label>Lokasi:</label>
    <select id="lokasiKurang" required></select><br>

    <button type="button" onclick="tambahKeReview()">Tambah ke Review</button>
</form>

<hr>

<h2>Review Pemakaian</h2>
<form action="<?= base_url('pemakaian/submitReview') ?>" method="post" id="formReview">
    <input type="hidden" name="review_data" id="reviewDataInput">

    <div id="tabelReview"></div>

    <label>Tujuan:</label>
    <input type="text" name="tujuan" required><br>

    <label>Keterangan:</label>
    <textarea name="keterangan" required></textarea><br>

    <label>Pesan:</label>
    <textarea name="pesan"></textarea><br>

    <button type="submit" id="submitButton" disabled>Submit Semua</button>

</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // === TAMBAH ===
    const jenisTambah = document.getElementById("jenisTambah");
    const satuanTambahWrapper = document.getElementById("satuanTambahWrapper");
    const lokasiTambahInput = document.getElementById("lokasiTambahInput");
    const namaTambahInput = document.getElementById("namaTambahInput");
    const daftarNama = document.getElementById("daftarNama");

    if (jenisTambah) {
        jenisTambah.addEventListener("change", function () {
            satuanTambahWrapper.style.display = (jenisTambah.value === "bahan") ? "block" : "none";

            daftarNama.innerHTML = '';
            const jenis = jenisTambah.value;
            if (!jenis) return;

            fetch(`<?= base_url('api/nama-by-jenis') ?>?jenis=${jenis}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(nama => {
                        const option = document.createElement('option');
                        option.value = nama;
                        daftarNama.appendChild(option);
                    });
                });
        });
    }

    if (lokasiTambahInput) {
        lokasiTambahInput.addEventListener("input", function () {
            lokasiTambahInput.value = lokasiTambahInput.value.toLowerCase();
        });
    }

    if (namaTambahInput) {
        namaTambahInput.addEventListener("input", function () {
            namaTambahInput.value = namaTambahInput.value.toLowerCase();
        });
    }

    // === KURANGI ===
    const jenisKurang = document.getElementById("jenisKurang");
    const namaKurang = document.getElementById("namaKurang");
    const satuanKurang = document.getElementById("satuanKurang");
    const satuanKurangWrapper = document.getElementById("satuanKurangWrapper");
    const lokasiKurang = document.getElementById("lokasiKurang");
    const jumlahKurang = document.getElementById("jumlahKurang");

    function toggleSatuanKurang() {
        satuanKurangWrapper.style.display = (jenisKurang.value === "bahan") ? "block" : "none";
    }

    if (jenisKurang) {
        jenisKurang.addEventListener("change", function () {
            const jenis = jenisKurang.value;
            toggleSatuanKurang();

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
                    jumlahKurang.value = '';
                    jumlahKurang.removeAttribute('max');
                });
        });
    }

    if (namaKurang) {
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
                        jumlahKurang.max = item.jumlah_bahan;
                    } else {
                        satuanKurang.innerHTML += `<option value="-">-</option>`;
                        jumlahKurang.max = jenis === "alat" ? item.jumlah_alat : item.jumlah_instrumen;
                    }

                    lokasiKurang.innerHTML += `<option value="${item.lokasi.toLowerCase()}">${item.lokasi.toLowerCase()}</option>`;
                });
        });
    }

    toggleSatuanKurang(); // Initial setup
});

// === REVIEW LIST ===
let reviewList = [];

function renderReview() {
    const container = document.getElementById('tabelReview');
    const inputHidden = document.getElementById('reviewDataInput');
    const submitButton = document.getElementById('submitButton');

    if (reviewList.length === 0) {
        container.innerHTML = '<p>Belum ada data ditambahkan.</p>';
        if (inputHidden) inputHidden.value = '';
        if (submitButton) submitButton.disabled = true;
        return;
    }

    let html = '<ul>';
    reviewList.forEach((item, i) => {
        html += `<li><strong>${item.jenis}</strong>: [${item.nama}, ${item.jumlah}, ${item.lokasi}]
                 <button type="button" onclick="hapusReview(${i})">❌</button></li>`;
    });
    html += '</ul>';

    container.innerHTML = html;
    if (inputHidden) inputHidden.value = JSON.stringify(reviewList);
    if (submitButton) submitButton.disabled = false;
}

function tambahKeReview() {
    const jenis = document.getElementById('jenisKurang').value;
    const nama = document.getElementById('namaKurang').value;
    const jumlah = parseInt(document.getElementById('jumlahKurang').value);
    const lokasi = document.getElementById('lokasiKurang').value;

    if (!jenis || !nama || !jumlah || !lokasi || jumlah <= 0) {
        alert("Semua field harus diisi dengan benar.");
        return;
    }

    const max = parseInt(document.getElementById('jumlahKurang').max || "9999");
    if (jumlah > max) {
        alert(`Jumlah tidak boleh melebihi stok (${max}).`);
        return;
    }

    reviewList.push({ jenis, nama, jumlah, lokasi });
    renderReview();

    // Reset form
    document.getElementById('jumlahKurang').value = '';
    document.getElementById('namaKurang').selectedIndex = 0;
    document.getElementById('lokasiKurang').selectedIndex = 0;
    document.getElementById('satuanKurang').innerHTML = '<option value="">Pilih nama dulu</option>';
}

function hapusReview(index) {
    reviewList.splice(index, 1);
    renderReview();
}
</script>

</body>
</html>
