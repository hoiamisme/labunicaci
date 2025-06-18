<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Laboratorium</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">
    
    <!-- Additional Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }
        
        .content-wrapper {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            min-height: 100vh;
        }

        .dashboard-title {
            color: var(--primary-color);
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            padding: 25px 0;
            border-bottom: 3px solid var(--accent-color);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .welcome-text {
            color: #34495e;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .info-box {
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background: white;
            border: none;
            overflow: hidden;
        }

        .info-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.15);
        }

        .info-box-content {
            padding: 25px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 500;
        }

        .chart-container {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .chart-container h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 20px;
        }

        .top-list {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .top-list h5 {
            color: var(--primary-color);
            font-weight: 600;
            padding-bottom: 15px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--accent-color);
        }

        .top-list ul {
            list-style: none;
            padding-left: 0;
        }

        .top-list li {
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 10px;
            background: #f8f9fa;
            transition: all 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-list li:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .badge {
            padding: 8px 12px;
            font-size: 0.9rem;
            border-radius: 8px;
        }

        .section-title {
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 1.8rem;
            margin: 2.5rem 0 1.5rem;
            padding-left: 15px;
            border-left: 5px solid var(--accent-color);
        }

        .alert {
            border-radius: 15px;
            border: none;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        /* Custom gradients for info boxes */
        .bg-info {
            background: linear-gradient(135deg, #4361ee 0%, #4895ef 100%) !important;
            color: white !important;
        }

        .bg-success {
            background: linear-gradient(135deg, #2ec4b6 0%, #4cc9f0 100%) !important;
            color: white !important;
        }

        .bg-warning {
            background: linear-gradient(135deg, #ff9f1c 0%, #ffbf69 100%) !important;
            color: white !important;
        }

        .bg-danger {
            background: linear-gradient(135deg, #f72585 0%, #ff4d6d 100%) !important;
            color: white !important;
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
                <h1 class="dashboard-title">🏠 Dashboard Laboratorium</h1>
                <p class="welcome-text">Selamat datang, <strong><?= esc($user_info['nama'] ?? 'User') ?></strong>!
                <span class="text-muted">(<?= date('l, d F Y') ?>)</span></p>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?= esc($error_message) ?></div>
                <?php endif; ?>

                <?php if (!empty($alerts)): ?>
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle mr-2"></i>Notifikasi Penting</h5>
                        <?php foreach ($alerts as $alert): ?>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span><?= $alert['icon'] ?> <strong><?= $alert['title'] ?>:</strong> <?= $alert['message'] ?></span>
                                <a href="<?= $alert['link'] ?>" class="btn btn-sm btn-warning"><?= $alert['action'] ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="content">
            <div class="container">
                <!-- Statistik Section -->
                <h4 class="section-title">📊 Statistik Overview</h4>
                
                <!-- Statistik Inventory -->
                <div class="row">
                    <div class="col-md-3"><div class="info-box bg-info"><div class="info-box-content">🔧 Total Alat: <?= $stats_inventory['total_alat'] ?? 0 ?></div></div></div>
                    <div class="col-md-3"><div class="info-box bg-success"><div class="info-box-content">🧪 Total Bahan: <?= $stats_inventory['total_bahan'] ?? 0 ?></div></div></div>
                    <div class="col-md-3"><div class="info-box bg-warning"><div class="info-box-content">📏 Total Instrumen: <?= $stats_inventory['total_instrumen'] ?? 0 ?></div></div></div>
                    <div class="col-md-3"><div class="info-box bg-danger"><div class="info-box-content">⚠️ Stok Rendah: <?= ($stats_inventory['alat_stok_rendah'] ?? 0) + ($stats_inventory['bahan_stok_rendah'] ?? 0) + ($stats_inventory['instrumen_stok_rendah'] ?? 0) ?></div></div></div>
                </div>

                <!-- Statistik Aktivitas -->
                <div class="row">
                    <div class="col-md-3"><div class="info-box bg-info"><div class="info-box-content">📅 Hari Ini: <?= $stats_aktivitas['aktivitas_hari_ini'] ?? 0 ?></div></div></div>
                    <div class="col-md-3"><div class="info-box bg-success"><div class="info-box-content">📆 Minggu Ini: <?= $stats_aktivitas['aktivitas_minggu_ini'] ?? 0 ?></div></div></div>
                    <div class="col-md-3"><div class="info-box bg-warning"><div class="info-box-content">⏳ Pending: <?= ($stats_aktivitas['peminjaman_pending'] ?? 0) + ($stats_aktivitas['pemakaian_pending'] ?? 0) ?></div></div></div>
                    <div class="col-md-3"><div class="info-box bg-danger"><div class="info-box-content">🔄 Belum Kembali: <?= $stats_aktivitas['alat_belum_kembali'] ?? 0 ?></div></div></div>
                </div>

                <?php if (session('role') === 'admin'): ?>
                    <!-- Statistik User -->
                    <div class="row">
                        <div class="col-md-3"><div class="info-box bg-primary"><div class="info-box-content">👤 Total User: <?= $stats_user['total_user'] ?? 0 ?></div></div></div>
                        <div class="col-md-3"><div class="info-box bg-success"><div class="info-box-content">✅ Aktif: <?= $stats_user['user_aktif'] ?? 0 ?></div></div></div>
                        <div class="col-md-3"><div class="info-box bg-warning"><div class="info-box-content">⏳ Pending: <?= $stats_user['user_pending'] ?? 0 ?></div></div></div>
                        <div class="col-md-3"><div class="info-box bg-danger"><div class="info-box-content">👑 Admin: <?= $stats_user['admin_count'] ?? 0 ?></div></div></div>
                    </div>
                <?php endif; ?>

                <!-- Charts Section -->
                <h4 class="section-title">📈 Analisis Data</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5>📊 Distribusi Inventory</h5>
                            <canvas id="inventoryChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5>📈 Aktivitas 7 Hari Terakhir</h5>
                            <canvas id="weeklyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Lists Section -->
                <h4 class="section-title">🏆 Statistik Top Performer</h4>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="top-list">
                            <h5>🔧 Top 5 Alat Sering Dipinjam</h5>
                            <ul>
                                <?php foreach ($top_data['alat_sering_dipinjam'] ?? [] as $alat): ?>
                                    <li>
                                        <span><?= esc($alat['nama_alat']) ?></span>
                                        <strong class="badge badge-info"><?= $alat['total_dipinjam'] ?>x</strong>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="top-list">
                            <h5>🏆 Top 5 Bahan Sering Digunakan</h5>
                            <ul>
                                <?php foreach ($top_data['bahan_sering_dipakai'] ?? [] as $bahan): ?>
                                    <li>
                                        <span><?= esc($bahan['nama_bahan']) ?></span>
                                        <strong class="badge badge-success"><?= $bahan['total_digunakan'] ?></strong>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="top-list">
                            <h5>👤 Top 5 User Paling Aktif</h5>
                            <ul>
                                <?php foreach ($top_data['user_paling_aktif'] ?? [] as $user): ?>
                                    <li>
                                        <span><?= esc($user['nama_lengkap']) ?></span>
                                        <strong class="badge badge-warning"><?= $user['total_aktivitas'] ?> aktivitas</strong>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="top-list">
                            <h5>📍 Top 5 Lokasi Terpopuler</h5>
                            <ul>
                                <?php foreach ($top_data['lokasi_terpopuler'] ?? [] as $lokasi => $jumlah): ?>
                                    <li>
                                        <span><?= esc($lokasi) ?></span>
                                        <strong class="badge badge-danger"><?= $jumlah ?> item</strong>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AdminLTE Scripts -->
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/dist/js/adminlte.min.js') ?>"></script>

<!-- Chart.js -->
<script>
<?php if (!empty($chart_data['inventory_chart'])): ?>
const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
new Chart(inventoryCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($chart_data['inventory_chart']['labels']) ?>,
        datasets: [{
            data: <?= json_encode($chart_data['inventory_chart']['data']) ?>,
            backgroundColor: ['#007bff', '#28a745', '#ffc107'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
<?php endif; ?>

<?php if (!empty($chart_data['aktivitas_mingguan'])): ?>
const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
new Chart(weeklyCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chart_data['aktivitas_mingguan']['labels']) ?>,
        datasets: [{
            label: 'Aktivitas',
            data: <?= json_encode($chart_data['aktivitas_mingguan']['data']) ?>,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
<?php endif; ?>

setTimeout(() => location.reload(), 300000); // refresh tiap 5 menit
</script>

</body>
</html>


