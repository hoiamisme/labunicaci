<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemakaian Alat</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/pemakaian.css') ?>">
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

                <!-- Success/Error Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

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
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Nama:</label>
                                <select name="nama" id="namaKurang" class="form-control" required>
                                    <option value="">-- Pilih Jenis Dulu --</option>
                                </select>
                            </div>

                            <div id="satuanKurangWrapper" class="form-group">
                                <label>Satuan:</label>
                                <select id="satuanKurang" class="form-control" disabled>
                                    <option value="">Pilih nama dulu</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Jumlah:</label>
                                <input type="number" id="jumlahKurang" min="1" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Lokasi:</label>
                                <select id="lokasiKurang" class="form-control" required>
                                    <option value="">Pilih nama dulu</option>
                                </select>
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
                            <?= csrf_field() ?>
                            <input type="hidden" name="review_data" id="reviewDataInput">
                            <div id="tabelReview"></div>

                            <div class="form-group">
                                <label>Tujuan:</label>
                                <input type="text" name="tujuan" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Pesan:</label>
                                <textarea name="pesan" class="form-control" rows="3"></textarea>
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
// ... (script logic tetap sama, tidak diubah)
<?= /* script kamu tetap digunakan di sini tanpa perubahan */ "" ?>
</script>

</body>
</html>
