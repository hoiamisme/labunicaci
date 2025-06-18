<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Alat dan Bahan</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #2ec4b6;
            --warning-color: #ff9f1c;
            --danger-color: #f72585;
            --accent-color: #4caf50;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }

        .content-wrapper {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .page-title {
            color: var(--primary-color);
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            padding: 25px 0;
            border-bottom: 3px solid var(--primary-color);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.15);
        }

        .card-header {
            border-radius: 20px 20px 0 0 !important;
            padding: 1.2rem 1.5rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .card-header.bg-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
        }

        .card-header.bg-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #ffbf69 100%) !important;
        }

        .card-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.8rem;
            display: block;
        }

        .form-control {
            font-size: 1rem;
            color: #2c3e50;
            background-color: #ffffff;
            border: 2px solid #e9ecef;
            padding: 0.8rem 1rem;
        }

        .form-control::placeholder {
            color: #95a5a6;
            opacity: 0.8;
        }

        .form-control:focus {
            color: #2c3e50;
            background-color: #ffffff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
        }

        select.form-control {
            font-weight: 500;
            color: #2c3e50;
            padding: 0.75rem 1rem;
            width: 100%;
            appearance: auto;
            -webkit-appearance: auto;
            -moz-appearance: auto;
            background-color: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            height: auto;
            line-height: 1.5;
            font-size: 1rem;
        }

        select.form-control option {
            color: #2c3e50;
            padding: 10px;
            font-size: 1rem;
            background-color: #ffffff;
        }

        /* Improve dropdown arrow visibility */
        select.form-control::-ms-expand {
            display: block;
        }

        /* Ensure text is not cut off */
        .form-group {
            overflow: visible;
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom styling for datalist */
        datalist {
            display: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 2rem;
                padding: 15px 0;
            }
        }

        /* Add styling for form titles */
        .form-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--accent-color);
        }

        /* Improve spacing between form elements */
        .form-group:not(:last-child) {
            margin-bottom: 2rem;
        }

        /* Add required field indicator */
        .required:after {
            content: '*';
            color: var(--danger-color);
            margin-left: 4px;
        }

        /* Improve input group styling */
        .input-group {
            border-radius: 10px;
            overflow: hidden;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            color: #2c3e50;
            font-weight: 500;
        }
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
                <!-- Update the title class -->
                <h1 class="page-title">🛠️ Manajemen Alat dan Bahan</h1>
            </div>
        </div>

        <div class="content">
            <div class="container">

                <!-- Form Tambah -->
                <div class="card mb-4 fade-in">
                    <div class="card-header bg-primary text-white">Tambah Data</div>
                    <div class="card-body">
                        <form action="<?= base_url('manajemen/tambah') ?>" method="post" id="formTambah">
                            <div class="form-group">
                                <label>Jenis:</label>
                                <select name="jenis" id="jenisTambah" class="form-control" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="alat">Alat</option>
                                    <option value="bahan">Bahan</option>
                                    <option value="instrumen">Instrumen</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Nama:</label>
                                <input type="text" name="nama" id="namaTambahInput" class="form-control" required list="daftarNama">
                                <datalist id="daftarNama"></datalist>
                            </div>

                            <div class="form-group" id="satuanTambahWrapper">
                                <label>Satuan:</label>
                                <select name="satuan" class="form-control">
                                    <option value="gram">gram</option>
                                    <option value="mililiter">mililiter</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Lokasi:</label>
                                <input type="text" name="lokasi" id="lokasiTambahInput" class="form-control" required list="daftarLokasi">
                                <datalist id="daftarLokasi">
                                    <?php foreach($lokasi as $lok): ?>
                                        <option value="<?= strtolower($lok) ?>"></option>
                                    <?php endforeach; ?>
                                </datalist>
                            </div>

                            <div class="form-group">
                                <label>Jumlah:</label>
                                <input type="number" name="jumlah" step="any" min="0" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success">Tambah</button>
                        </form>
                    </div>
                </div>

                <!-- Form Kurangi -->
                <div class="card fade-in">
                    <div class="card-header bg-warning text-dark">Kurangi Data</div>
                    <div class="card-body">
                        <form action="<?= base_url('manajemen/kurang') ?>" method="post" id="formKurang">
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
                                <select name="nama" id="namaKurang" class="form-control" required>
                                    <option value="">Pilih jenis dulu</option>
                                </select>
                            </div>

                            <div class="form-group" id="satuanKurangWrapper">
                                <label>Satuan:</label>
                                <select name="satuan" id="satuanKurang" class="form-control" required>
                                    <option value="">Pilih nama dulu</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Jumlah:</label>
                                <input type="number" name="jumlah" id="jumlahKurang" min="0" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Lokasi:</label>
                                <select name="lokasi" id="lokasiKurang" class="form-control" required>
                                    <option value="">Pilih nama dulu</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-danger">Kurangi</button>
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    // === TAMBAH ===
    const jenisTambah = document.getElementById("jenisTambah");
    const satuanTambahWrapper = document.getElementById("satuanTambahWrapper");
    const lokasiTambahInput = document.getElementById("lokasiTambahInput");
    const namaTambahInput = document.getElementById("namaTambahInput");
    const daftarNama = document.getElementById("daftarNama");

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

    lokasiTambahInput.addEventListener("input", () => {
        lokasiTambahInput.value = lokasiTambahInput.value.toLowerCase();
    });

    namaTambahInput.addEventListener("input", () => {
        namaTambahInput.value = namaTambahInput.value.toLowerCase();
    });

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

                lokasiKurang.innerHTML += `<option value="${item.lokasi.toLowerCase()}">${item.lokasi.toLowerCase()}</option>`;

                jumlahKurang.max = jenis === "bahan" ? item.jumlah_bahan :
                                   jenis === "alat" ? item.jumlah_alat :
                                   item.jumlah_instrumen;
            });
    });

    toggleSatuanKurang();
});
</script>

</body>
</html>
