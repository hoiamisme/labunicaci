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


