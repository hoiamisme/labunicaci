<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Instrumen</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">

    <!-- Tambahan CSS -->
    <style>
        body {
            background: linear-gradient(to bottom right, #f5f7fa, #c3cfe2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h1.m-0.text-dark {
            color: #2d3e50;
            font-weight: bold;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            background: linear-gradient(to right, #3a7bd5, #00d2ff);
            border: none;
            border-radius: 8px;
        }

        .btn-secondary {
            background-color: #f1f1f1;
            color: #333;
            border-radius: 8px;
            border: none;
        }

        .alert-info {
            border-radius: 8px;
            background-color: #e7f3fe;
            border-color: #b3d7ff;
            color: #31708f;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: none;
        }

        .table thead {
            background-color: #3a7bd5;
            color: #fff;
        }

        .table-bordered {
            border-radius: 10px;
            overflow: hidden;
        }

        .table td, .table th {
            text-align: center;
            vertical-align: middle;
        }

        .pagination .page-link {
            border-radius: 8px;
            color: #3a7bd5;
        }

        .pagination .disabled .page-link {
            color: #999;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(to right, #3a7bd5, #00d2ff);
            border: none;
            color: white;
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
                <h1 class="m-0 text-dark">📏 Daftar Instrumen</h1>
            </div>
        </div>

        <div class="content">
            <div class="container">

                <!-- FORM PENCARIAN -->
                <form method="GET" action="/inventory/daftar-instrumen" class="form-inline mb-3">
                    <input type="text" name="search" class="form-control mr-2" placeholder="🔍 Cari nama alat..." value="<?= esc($search ?? '') ?>">
                    <select name="location" class="form-control mr-2">
                        <option value="">📍 Semua Lokasi</option>
                        <?php if (!empty($locations)): ?>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= esc($loc) ?>" <?= ($location ?? '') == $loc ? 'selected' : '' ?>>
                                    <?= esc($loc) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <button type="submit" class="btn btn-primary mr-2">🔍 Cari</button>
                    <a href="/inventory/daftar-alat" class="btn btn-secondary">🗑️ Reset</a>
                </form>

                <!-- INFO HASIL PENCARIAN -->
                <?php if (!empty($search) || !empty($location)): ?>
                    <div class="alert alert-info">
                        <strong>📊 Hasil Pencarian:</strong>
                        <?php if (!empty($search)): ?>
                            Nama: "<em><?= esc($search) ?></em>"
                        <?php endif; ?>
                        <?php if (!empty($location)): ?>
                            Lokasi: "<em><?= esc($location) ?></em>"
                        <?php endif; ?>
                        - Ditemukan <strong><?= $totalItems ?? 0 ?></strong> alat
                    </div>
                <?php endif; ?>

                <!-- TABEL DATA -->
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Instrumen</th>
                                    <th>Jumlah</th>
                                    <th>Lokasi</th>
                                    <?php if (session()->get('role') === 'admin'): ?>
                                        <th>Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($items)): ?>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td><?= $item['id_instrumen'] ?></td>
                                            <td><?= esc($item['nama_instrumen']) ?></td>
                                            <td>
                                                <?= $item['jumlah_instrumen'] ?>
                                                <?php if ($item['jumlah_instrumen'] <= 3): ?>
                                                    <span class="text-danger">⚠️</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($item['lokasi']) ?></td>
                                            <?php if (session()->get('role') === 'admin'): ?>
                                                <td>
                                                    <button onclick="hapusAlat(<?= $item['id_instrumen'] ?>, '<?= esc($item['nama_instrumen']) ?>')" class="btn btn-danger btn-sm">
                                                        🗑️ Hapus
                                                    </button>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="<?= session()->get('role') === 'admin' ? '5' : '4' ?>">
                                            <div class="text-center text-muted">
                                                <?php if (!empty($search) || !empty($location)): ?>
                                                    🔍 Tidak ada alat yang sesuai dengan pencarian
                                                <?php else: ?>
                                                    📦 Tidak ada data alat
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PAGINATION -->
                <?php if (($totalPages ?? 1) > 1): ?>
                    <div class="mt-3">
                        <?php 
                        $currentPage = $currentPage ?? 1;
                        $searchQuery = !empty($search) ? "&search=" . urlencode($search) : "";
                        $locationQuery = !empty($location) ? "&location=" . urlencode($location) : "";
                        $queryString = $searchQuery . $locationQuery;
                        ?>

                        <nav>
                            <ul class="pagination">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="/inventory/daftar-alat?page=<?= $currentPage - 1 ?><?= $queryString ?>">← Sebelumnya</a>
                                    </li>
                                <?php endif; ?>

                                <li class="page-item active">
                                    <span class="page-link">Halaman <?= $currentPage ?> dari <?= $totalPages ?></span>
                                </li>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="/inventory/daftar-alat?page=<?= $currentPage + 1 ?><?= $queryString ?>">Selanjutnya →</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- AdminLTE Scripts -->
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/dist/js/adminlte.min.js') ?>"></script>

<!-- JS Hapus Alat -->
<?php if (session()->get('role') === 'admin'): ?>
<script>
    function hapusAlat(id, nama) {
        if (confirm(`⚠️ Yakin ingin menghapus alat "${nama}"?\n\nData yang dihapus tidak dapat dikembalikan!`)) {
            fetch(`/inventory/hapus-alat/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ '_method': 'DELETE' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Alat berhasil dihapus!');
                    location.reload();
                } else {
                    alert('❌ Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('❌ Terjadi kesalahan saat menghapus data');
                console.error('Error:', error);
            });
        }
    }
</script>
<?php endif; ?>

</body>
</html>
