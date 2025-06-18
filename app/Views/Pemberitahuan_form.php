<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemberitahuan</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        button {
            padding: 4px 8px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
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
                <h1 class="m-0 text-dark">🔔 Pemberitahuan</h1>
            </div>
        </div>

        <div class="content">
            <div class="container">

                <?php if (session()->get('role') === 'admin'): ?>

                    <!-- Daftar Peminjaman Alat (Belum Disetujui) -->
                    <div class="card mb-5">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">Daftar Peminjaman Alat (Belum Disetujui)</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <th>Nama Alat</th>
                                        <th>Jumlah Peminjaman</th>
                                        <th>Tujuan</th>
                                        <th>Pesan</th>
                                        <th>Tanggal Dipinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($alat)): ?>
                                        <?php foreach ($alat as $item): ?>
                                            <tr>
                                                <td><?= esc($item->nama_lengkap) ?></td>
                                                <td><?= esc($item->nama_alat) ?></td>
                                                <td><?= esc($item->pengurangan) ?></td>
                                                <td><?= esc($item->tujuan_pemakaian) ?></td>
                                                <td><?= esc($item->pesan) ?></td>
                                                <td><?= esc($item->tanggal_dipinjam) ?></td>
                                                <td><?= esc($item->tanggal_kembali) ?></td>
                                                <td><?= esc($item->status) ?></td>
                                                <td>
                                                    <form action="/pemberitahuan/approveAlat" method="post" style="display:inline;">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="id_logalat" value="<?= $item->id_logalat ?>">
                                                        <button type="submit" class="btn btn-success btn-sm">✔</button>
                                                    </form>
                                                    <form action="/pemberitahuan/declineAlat" method="post" style="display:inline;">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="id_logalat" value="<?= $item->id_logalat ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm">✖</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <tr><td colspan="9">Tidak ada data.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Daftar Pengambilan Bahan -->
                    <div class="card mb-5">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">Daftar Pengambilan Bahan (Belum Disetujui)</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <th>Nama Bahan</th>
                                        <th>Jumlah Pengambilan</th>
                                        <th>Tujuan</th>
                                        <th>Pesan</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($bahan)): ?>
                                        <?php foreach ($bahan as $item): ?>
                                            <tr>
                                                <td><?= esc($item->nama_lengkap) ?></td>
                                                <td><?= esc($item->nama_bahan) ?></td>
                                                <td><?= esc($item->pengurangan) ?></td>
                                                <td><?= esc($item->tujuan_pemakaian) ?></td>
                                                <td><?= esc($item->pesan) ?></td>
                                                <td><?= esc($item->tanggal) ?></td>
                                                <td><?= esc($item->status) ?></td>
                                                <td>
                                                    <form action="/pemberitahuan/approveBahan" method="post" style="display:inline;">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="id_logbahan" value="<?= $item->id_logbahan ?>">
                                                        <button type="submit" class="btn btn-success btn-sm">✔</button>
                                                    </form>
                                                    <form action="/pemberitahuan/declineBahan" method="post" style="display:inline;">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="id_logbahan" value="<?= $item->id_logbahan ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm">✖</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <tr><td colspan="8">Tidak ada data.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php endif; ?>

                <?php if (session()->get('role') === 'user'): ?>
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">Daftar Peminjaman Alat (Sedang Dipinjam)</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <th>Nama Alat</th>
                                        <th>Jumlah</th>
                                        <th>Tujuan</th>
                                        <th>Pesan</th>
                                        <th>Tanggal Dipinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($alatDipinjam)): ?>
                                        <?php foreach ($alatDipinjam as $item): ?>
                                            <tr>
                                                <td><?= esc($item->nama_lengkap) ?></td>
                                                <td><?= esc($item->nama_alat) ?></td>
                                                <td><?= esc($item->pengurangan) ?></td>
                                                <td><?= esc($item->tujuan_pemakaian) ?></td>
                                                <td><?= esc($item->pesan) ?></td>
                                                <td><?= esc($item->tanggal_dipinjam) ?></td>
                                                <td><?= esc($item->tanggal_kembali) ?></td>
                                                <td><?= esc($item->status) ?></td>
                                                <td>
                                                    <form action="/pemberitahuan/returnAlat" method="post" style="display:inline;">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="id_logalat" value="<?= $item->id_logalat ?>">
                                                        <button type="submit" class="btn btn-primary btn-sm">✔ Kembalikan</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <tr><td colspan="9">Tidak ada data.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

</div>

<!-- AdminLTE JS -->
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/dist/js/adminlte.min.js') ?>"></script>
</body>
</html>
