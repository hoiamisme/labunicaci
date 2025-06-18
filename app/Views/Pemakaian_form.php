<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemakaian Alat</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">

    <style>
        .hidden { display: none; }
        ul { padding-left: 20px; }
        li { margin-bottom: 5px; }
    </style>
</head>
<body class="hold-transition layout-navbar-fixed layout-top-nav">

<div class="wrapper">

    <!-- Navbar -->
    <?= view('partial/header') ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <h1 class="m-0 text-dark">🤝 Peminjaman Alat</h1>
            </div>
        </div>

        <div class="content">
            <div class="container">
                <!-- FORM KURANGI -->
                <div class="card mb-4">
                    <div class="card-header">Form Kurangi</div>
                    <div class="card-body">
                        <form id="formKurang">
                            <div class="form-group">
                                <label>Jenis:</label>
                                <select name="jenis" id="jenisKurang" class="form-control" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="alat">Alat</option>
                                    <option value="bahan">Bahan</option>
                                    <option value="instrumen">Instrumen</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Nama:</label>
                                <select name="nama" id="namaKurang" class="form-control" required></select>
                            </div>

                            <div id="satuanKurangWrapper" class="form-group">
                                <label>Satuan:</label>
                                <select id="satuanKurang" class="form-control" disabled></select>
                            </div>

                            <div class="form-group">
                                <label>Jumlah:</label>
                                <input type="number" id="jumlahKurang" min="0" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Lokasi:</label>
                                <select id="lokasiKurang" class="form-control" required></select>
                            </div>

                            <button type="button" onclick="tambahKeReview()" class="btn btn-primary">Tambah ke Review</button>
                        </form>
                    </div>
                </div>

                <!-- FORM REVIEW -->
                <div class="card">
                    <div class="card-header">Review Pemakaian</div>
                    <div class="card-body">
                        <form action="<?= base_url('pemakaian/submitReview') ?>" method="post" id="formReview">
                            <input type="hidden" name="review_data" id="reviewDataInput">
                            <div id="tabelReview"></div>

                            <div class="form-group">
                                <label>Tujuan:</label>
                                <input type="text" name="tujuan" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Pesan:</label>
                                <textarea name="pesan" class="form-control"></textarea>
                            </div>

                            <button type="submit" id="submitButton" class="btn btn-success" disabled>Submit Semua</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- AdminLTE JS -->
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/dist/js/adminlte.min.js') ?>"></script>

<!-- Script Form Logic -->
<script>
document.addEventListener("DOMContentLoaded", function () {
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

    toggleSatuanKurang();
});

let reviewList = [];

function renderReview() {
    const container = document.getElementById('tabelReview');
    const inputHidden = document.getElementById('reviewDataInput');
    const submitButton = document.getElementById('submitButton');

    if (reviewList.length === 0) {
        container.innerHTML = '<p>Belum ada data ditambahkan.</p>';
        inputHidden.value = '';
        submitButton.disabled = true;
        return;
    }

    let html = '<ul>';
    reviewList.forEach((item, i) => {
        html += `<li><strong>${item.jenis}</strong>: [${item.nama}, ${item.jumlah}, ${item.lokasi}]
                 <button type="button" class="btn btn-danger btn-sm ml-2" onclick="hapusReview(${i})">❌</button></li>`;
    });
    html += '</ul>';

    container.innerHTML = html;
    inputHidden.value = JSON.stringify(reviewList);
    submitButton.disabled = false;
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
