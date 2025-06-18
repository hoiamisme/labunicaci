<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemberitahuan</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }
        .content-wrapper {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            min-height: 100vh;
            padding-bottom: 40px;
        }
        .content-header h1 {
            color: #4361ee;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #4361ee;
        }
        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 6px 24px rgba(67, 97, 238, 0.08);
            margin-bottom: 2.5rem;
            overflow: hidden;
        }
        .card-header {
            border-radius: 18px 18px 0 0 !important;
            padding: 1.2rem 1.5rem;
            font-weight: 600;
            font-size: 1.15rem;
            letter-spacing: 0.5px;
        }
        .bg-info {
            background: linear-gradient(135deg,rgb(39, 177, 58) 0%,rgb(59, 140, 160) 100%) !important;
            color: #fff !important;
        }
        .bg-warning {
            background: linear-gradient(135deg, #ff9f1c 0%, #ffbf69 100%) !important;
            color: #2c3e50 !important;
        }
        .card-title {
            margin-bottom: 0;
            font-size: 1.15rem;
            font-weight: 600;
        }
        .card-body {
            background: #fff;
            border-radius: 0 0 18px 18px;
            padding: 2rem 1.5rem;
        }
        table.table {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.04);
            margin-bottom: 0;
        }
        .table thead th {
            background: #4361ee;
            color: #fff;
            border: none;
            font-weight: 600;
            font-size: 1rem;
            text-align: center;
            vertical-align: middle;
        }
        .table tbody td {
            background: #fff;
            color: #2c3e50;
            border-top: 1px solid #e9ecef;
            vertical-align: middle;
            font-size: 0.98rem;
            text-align: center;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #e9ecef !important;
        }
        .table tbody tr:hover {
            background: #f1f5ff;
        }
        .btn {
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 6px 16px;
            margin: 2px 0;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(67, 97, 238, 0.07);
        }
        .btn-success {
            background: linear-gradient(135deg, #2ec4b6 0%, #4cc9f0 100%);
            border: none;
            color: #fff;
        }
        .btn-danger {
            background: linear-gradient(135deg, #f72585 0%, #ff4d6d 100%);
            border: none;
            color: #fff;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4895ef 0%, #4361ee 100%);
            border: none;
            color: #fff;
        }
        .btn-sm {
            font-size: 0.92rem;
            padding: 5px 12px;
        }
        /* Tambahkan di style section Anda */
        .bg-alat {
            background: linear-gradient(135deg, #4361ee 0%, #4895ef 100%) !important;
            color: #fff !important;
        }
        .bg-bahan {
            background: linear-gradient(135deg, #ff9f1c 0%, #ffbf69 100%) !important;
            color: #2c3e50 !important;
        }
        .table-alat thead th,
        .table-bahan thead th {
            background: linear-gradient(135deg, #ff9f1c 0%, #ffbf69 100%) !important;
            color: #2c3e50 !important;
            border: none;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem 0.5rem;
            }
            .content-header h1 {
                font-size: 1.3rem;
            }
            .table thead {
                font-size: 0.95rem;
            }
            .table tbody td {
                font-size: 0.93rem;
            }
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
