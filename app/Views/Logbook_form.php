<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logbook Laboratorium</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/logbook.css') ?>">
</head>
<body class="hold-transition layout-navbar-fixed layout-top-nav">
<div class="wrapper">

    <!-- Navbar -->
    <?= view('partial/header') ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <h1 class="m-0">📚 Logbook Laboratorium</h1>
                <p>Riwayat Peminjaman Alat dan Pemakaian Bahan Laboratorium</p>
            </div>
        </div>

        <div class="content">
            <div class="container">

                <!-- Statistik -->
                <div class="mb-4">
                    <h3>📊 Statistik</h3>
                    <ul>
                        <li>Total Peminjaman Alat: <strong><?= $statistik['total_alat'] ?? 0 ?></strong></li>
                        <li>Total Pemakaian Bahan: <strong><?= $statistik['total_bahan'] ?? 0 ?></strong></li>
                        <li>Total Semua Aktivitas: <strong><?= $statistik['total_semua'] ?? 0 ?></strong></li>
                        <li>Total Disetujui: <strong><?= $statistik['total_approve'] ?? 0 ?></strong></li>
                        <li>Total Pending: <strong><?= $statistik['total_pending'] ?? 0 ?></strong></li>
                        <li>Aktivitas Hari Ini: <strong><?= $statistik['aktivitas_hari_ini'] ?? 0 ?></strong></li>
                    </ul>
                </div>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger">
                        ⚠️ <?= esc($error_message) ?>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <a href="<?= site_url('logbook/export') ?>" class="btn btn-outline-primary">📄 Export CSV</a>
                </div>

                <!-- Logbook Alat -->
                <div class="card mb-4">
                    <div class="card-header"><h3>🔧 Logbook Peminjaman Alat</h3></div>
                    <div class="card-body table-responsive">
                        <?php if (!empty($dataAlat)): ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pengguna</th>
                                        <th>Nama Alat</th>
                                        <th>Penambahan</th>
                                        <th>Pengurangan</th>
                                        <th>Tanggal Dipinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Tujuan Pemakaian</th>
                                        <th>Status</th>
                                        <th>Pesan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($dataAlat as $alat): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($alat['nama_lengkap'] ?? '-') ?></td>
                                            <td><?= esc($alat['nama_alat'] ?? '-') ?></td>
                                            <td><?= esc($alat['penambahan'] ?? '0') ?></td>
                                            <td><?= esc($alat['pengurangan'] ?? '0') ?></td>
                                            <td><?= !empty($alat['tanggal_dipinjam']) ? date('d/m/Y H:i', strtotime($alat['tanggal_dipinjam'])) : '-' ?></td>
                                            <td><?= !empty($alat['tanggal_kembali']) ? date('d/m/Y H:i', strtotime($alat['tanggal_kembali'])) : '⏳ Belum Kembali' ?></td>
                                            <td><?= esc($alat['tujuan_pemakaian'] ?? '-') ?></td>
                                            <td><?= esc($alat['status'] ?? '-') ?></td>
                                            <td><?= esc($alat['pesan'] ?? '-') ?></td>
                                            <td><button class="btn btn-sm btn-info" onclick="showDetail('alat', <?= $alat['id_logalat'] ?>)">👁️ Detail</button></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                            <p><strong>Total peminjaman alat: <?= count($dataAlat) ?></strong></p>
                        <?php else: ?>
                            <p>📭 Tidak ada data peminjaman alat.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Logbook Bahan -->
                <div class="card mb-4">
                    <div class="card-header"><h3>🧪 Logbook Pemakaian Bahan</h3></div>
                    <div class="card-body table-responsive">
                        <?php if (!empty($dataBahan)): ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pengguna</th>
                                        <th>Nama Bahan</th>
                                        <th>Penambahan</th>
                                        <th>Pengurangan</th>
                                        <th>Tanggal</th>
                                        <th>Tujuan Pemakaian</th>
                                        <th>Status</th>
                                        <th>Pesan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($dataBahan as $bahan): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($bahan['nama_lengkap'] ?? '-') ?></td>
                                            <td><?= esc($bahan['nama_bahan'] ?? '-') ?></td>
                                            <td><?= esc($bahan['penambahan'] ?? '0') ?></td>
                                            <td><?= esc($bahan['pengurangan'] ?? '0') ?></td>
                                            <td><?= !empty($bahan['tanggal']) ? date('d/m/Y H:i', strtotime($bahan['tanggal'])) : '-' ?></td>
                                            <td><?= esc($bahan['tujuan_pemakaian'] ?? '-') ?></td>
                                            <td><?= esc($bahan['status'] ?? '-') ?></td>
                                            <td><?= esc($bahan['pesan'] ?? '-') ?></td>
                                            <td><button class="btn btn-sm btn-info" onclick="showDetail('bahan', <?= $bahan['id_logbahan'] ?>)">👁️ Detail</button></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                            <p><strong>Total pemakaian bahan: <?= count($dataBahan) ?></strong></p>
                        <?php else: ?>
                            <p>📭 Tidak ada data pemakaian bahan.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="alert alert-light">
                    💡 Ingin menambah data baru? <a href="/pemakaian" class="btn btn-sm btn-primary ml-2">📦 Buka Halaman Pemakaian</a>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📄 Detail Logbook</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="detailContent">
                <p>⏳ Loading data...</p>
            </div>
        </div>
    </div>
</div>

<!-- AdminLTE Scripts -->
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/dist/js/adminlte.min.js') ?>"></script>

<script>
function showDetail(jenis, id) {
    $('#detailContent').html('<p>⏳ Loading data...</p>');
    $('#detailModal').modal('show');

    $.get('<?= site_url("logbook/detail/") ?>' + jenis + '/' + id)
        .done(function(response) {
            if (response.success) {
                const detail = response.data;
                const jenisText = response.jenis === 'alat' ? 'Alat' : 'Bahan';

                let content = `<h5>👤 Informasi Pengguna</h5><ul>
                    <li><strong>Nama:</strong> ${detail.nama_lengkap || '-'}</li>
                    <li><strong>Email:</strong> ${detail.email || '-'}</li></ul>`;

                content += `<h5>📦 Informasi ${jenisText}</h5><ul>
                    <li><strong>Nama:</strong> ${detail.nama_alat || detail.nama_bahan || '-'}</li>
                    <li><strong>Lokasi:</strong> ${detail.lokasi_alat || detail.lokasi_bahan || '-'}</li>`;
                if (detail.satuan_bahan) {
                    content += `<li><strong>Satuan:</strong> ${detail.satuan_bahan}</li>`;
                }
                content += `</ul>`;

                content += `<h5>📋 Detail Transaksi</h5><ul>
                    <li><strong>Penambahan:</strong> ${detail.penambahan || '0'}</li>
                    <li><strong>Pengurangan:</strong> ${detail.pengurangan || '0'}</li>`;
                if (response.jenis === 'alat') {
                    content += `<li><strong>Tanggal Dipinjam:</strong> ${detail.tanggal_dipinjam || '-'}</li>
                        <li><strong>Tanggal Kembali:</strong> ${detail.tanggal_kembali || '-'}</li>`;
                } else {
                    content += `<li><strong>Tanggal:</strong> ${detail.tanggal || '-'}</li>`;
                }
                content += `<li><strong>Status:</strong> ${detail.status || '-'}</li>
                    <li><strong>Tujuan Pemakaian:</strong> ${detail.tujuan_pemakaian || '-'}</li>
                    <li><strong>Pesan:</strong> ${detail.pesan || '-'}</li>
                </ul>`;

                $('#detailContent').html(content);
            } else {
                $('#detailContent').html('<p>❌ Gagal memuat data.</p>');
            }
        })
        .fail(function(xhr, status, error) {
            $('#detailContent').html('<p>❌ Error: ' + error + '</p>');
        });
}
</script>
</body>
</html>
