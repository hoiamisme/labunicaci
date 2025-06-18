<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/style_bold.css') ?>">
</head>
<body>
    <div>
    <a href="/dashboard" style="margin-right: 10px;">🏠 Dashboard</a>
    <a href="/manajemen" style="margin-right: 10px;">🛠️ Manajemen</a>
    <a href="/pemakaian" style="margin-right: 10px;">📦 Pemakaian</a>
    <a href="/logbook" style="margin-right: 10px;">📚 Logbook</a>
    <a href="/manajemen-user" style="margin-right: 10px;">👥 Manajemen User</a>
    <a href="/inventory/daftar-alat" style="margin-right: 10px;">🔧 Daftar Alat</a>
    <a href="/inventory/daftar-bahan" style="margin-right: 10px;">🧪 Daftar Bahan</a>
    <a href="/inventory/daftar-instrumen" style="margin-right: 10px;">📏 Daftar Instrumen</a></strong>
    <a href="/pemberitahuan" style="margin-right: 10px;">🔔 Pemberitahuan</a>
    <a href="/profiles" style="margin-right: 10px;">👤 Profiles</a>
    <a href="/logout">🔒 Logout</a>
</div>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Laboratorium</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/style_reguler.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #007bff; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.danger { border-left-color: #dc3545; }
        .stat-card.success { border-left-color: #28a745; }
        .stat-value { font-size: 2em; font-weight: bold; color: #333; }
        .stat-label { color: #666; font-size: 0.9em; margin-top: 5px; }
        .alert-box { padding: 15px; margin: 10px 0; border-radius: 5px; display: flex; align-items: center; justify-content: space-between; }
        .alert-warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .alert-info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        .alert-danger { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .top-list { list-style: none; padding: 0; }
        .top-list li { padding: 8px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; }
        .chart-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .quick-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h1>🏠 Dashboard Laboratorium</h1>
    <p>Selamat datang, <strong><?= esc($user_info['nama'] ?? 'User') ?></strong>! 
       <span style="color: #666;">(<?= date('l, d F Y') ?>)</span></p>

    <?php if (isset($error_message)): ?>
        <div class="alert-box alert-danger">
            <span>❌ <?= esc($error_message) ?></span>
        </div>
    <?php endif; ?>

    <!-- ALERTS/NOTIFICATIONS -->
    <?php if (!empty($alerts)): ?>
        <div style="margin-bottom: 30px;">
            <h3>🚨 Notifikasi Penting</h3>
            <?php foreach ($alerts as $alert): ?>
                <div class="alert-box alert-<?= $alert['type'] ?>">
                    <span><?= $alert['icon'] ?> <strong><?= $alert['title'] ?>:</strong> <?= $alert['message'] ?></span>
                    <a href="<?= $alert['link'] ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.9em;">
                        <?= $alert['action'] ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- STATISTICS CARDS -->
    <h3>📊 Statistik Inventory</h3>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $stats_inventory['total_alat'] ?? 0 ?></div>
            <div class="stat-label">🔧 Total Alat</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats_inventory['total_bahan'] ?? 0 ?></div>
            <div class="stat-label">🧪 Total Bahan</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats_inventory['total_instrumen'] ?? 0 ?></div>
            <div class="stat-label">📏 Total Instrumen</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-value"><?= ($stats_inventory['alat_stok_rendah'] ?? 0) + ($stats_inventory['bahan_stok_rendah'] ?? 0) + ($stats_inventory['instrumen_stok_rendah'] ?? 0) ?></div>
            <div class="stat-label">⚠️ Stok Rendah</div>
        </div>
    </div>

    <h3>📈 Statistik Aktivitas</h3>
    <div class="stats-grid">
        <div class="stat-card success">
            <div class="stat-value"><?= $stats_aktivitas['aktivitas_hari_ini'] ?? 0 ?></div>
            <div class="stat-label">📅 Aktivitas Hari Ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats_aktivitas['aktivitas_minggu_ini'] ?? 0 ?></div>
            <div class="stat-label">📆 Aktivitas Minggu Ini</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-value"><?= ($stats_aktivitas['peminjaman_pending'] ?? 0) + ($stats_aktivitas['pemakaian_pending'] ?? 0) ?></div>
            <div class="stat-label">⏳ Pending Approval</div>
        </div>
        <div class="stat-card danger">
            <div class="stat-value"><?= $stats_aktivitas['alat_belum_kembali'] ?? 0 ?></div>
            <div class="stat-label">🔄 Alat Belum Kembali</div>
        </div>
    </div>

    <?php if (session('role') === 'admin'): ?>
    <h3>👥 Statistik User</h3>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $stats_user['total_user'] ?? 0 ?></div>
            <div class="stat-label">👤 Total User</div>
        </div>
        <div class="stat-card success">
            <div class="stat-value"><?= $stats_user['user_aktif'] ?? 0 ?></div>
            <div class="stat-label">✅ User Aktif</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-value"><?= $stats_user['user_pending'] ?? 0 ?></div>
            <div class="stat-label">⏳ User Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats_user['admin_count'] ?? 0 ?></div>
            <div class="stat-label">👑 Admin</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- CHARTS -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 30px 0;">
        <!-- Inventory Chart -->
        <div class="chart-container">
            <h4>📊 Distribusi Inventory</h4>
            <canvas id="inventoryChart" width="400" height="200"></canvas>
        </div>
        
        <!-- Weekly Activity Chart -->
        <div class="chart-container">
            <h4>📈 Aktivitas 7 Hari Terakhir</h4>
            <canvas id="weeklyChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- TOP DATA -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 30px 0;">
        <!-- Top Alat -->
        <div class="chart-container">
            <h4>🏆 Top 5 Alat Sering Dipinjam</h4>
            <?php if (!empty($top_data['alat_sering_dipinjam'])): ?>
                <ul class="top-list">
                    <?php foreach ($top_data['alat_sering_dipinjam'] as $alat): ?>
                        <li>
                            <span><?= esc($alat['nama_alat']) ?></span>
                            <strong><?= $alat['total_dipinjam'] ?>x</strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="color: #666; text-align: center; padding: 20px;">Belum ada data</p>
            <?php endif; ?>
        </div>

        <!-- Top Bahan -->
        <div class="chart-container">
            <h4>🏆 Top 5 Bahan Sering Digunakan</h4>
            <?php if (!empty($top_data['bahan_sering_dipakai'])): ?>
                <ul class="top-list">
                    <?php foreach ($top_data['bahan_sering_dipakai'] as $bahan): ?>
                        <li>
                            <span><?= esc($bahan['nama_bahan']) ?></span>
                            <strong><?= $bahan['total_digunakan'] ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="color: #666; text-align: center; padding: 20px;">Belum ada data</p>
            <?php endif; ?>
        </div>

        <!-- Top User -->
        <div class="chart-container">
            <h4>🏆 Top 5 User Paling Aktif</h4>
            <?php if (!empty($top_data['user_paling_aktif'])): ?>
                <ul class="top-list">
                    <?php foreach ($top_data['user_paling_aktif'] as $user): ?>
                        <li>
                            <span><?= esc($user['nama_lengkap']) ?></span>
                            <strong><?= $user['total_aktivitas'] ?> aktivitas</strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="color: #666; text-align: center; padding: 20px;">Belum ada data</p>
            <?php endif; ?>
        </div>

        <!-- Top Lokasi -->
        <div class="chart-container">
            <h4>🏆 Top 5 Lokasi Terpopuler</h4>
            <?php if (!empty($top_data['lokasi_terpopuler'])): ?>
                <ul class="top-list">
                    <?php foreach ($top_data['lokasi_terpopuler'] as $lokasi => $total): ?>
                        <li>
                            <span><?= esc($lokasi) ?></span>
                            <strong><?= $total ?> item</strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="color: #666; text-align: center; padding: 20px;">Belum ada data</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="chart-container">
        <h4>🚀 Quick Actions</h4>
        <div class="quick-actions">
            <a href="/manajemen" class="btn btn-primary">🛠️ Tambah Inventory</a>
            <a href="/pemakaian" class="btn btn-success">📦 Pemakaian Baru</a>
            <a href="/logbook" class="btn btn-warning">📚 Review Logbook</a>
            <?php if (session('role') === 'admin'): ?>
                <a href="/manajemen-user" class="btn btn-primary">👥 Kelola User</a>
            <?php endif; ?>
            <a href="/profiles" class="btn btn-primary">👤 Kelola Profil</a>
        </div>
    </div>
</div>

<script>
// Inventory Chart
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
            legend: {
                position: 'bottom'
            }
        }
    }
});
<?php endif; ?>

// Weekly Activity Chart
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
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
<?php endif; ?>

// Auto-refresh setiap 5 menit
setTimeout(function() {
    location.reload();
}, 300000);
</script>

</body>
</html>