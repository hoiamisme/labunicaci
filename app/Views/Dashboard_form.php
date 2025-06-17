<?php if (session()->get('logged_in')): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #f1f1f1;">
        <div>
            <p>Halo, <?= esc(session('nama_lengkap')) ?></p>
        </div>
        <div>
            <strong><a href="/dashboard" style="margin-right: 10px; color: #007bff;">🏠 Dashboard</a></strong>
            <a href="/manajemen" style="margin-right: 10px;">🛠️ Manajemen</a>
            <a href="/pemakaian" style="margin-right: 10px;">📦 Pemakaian</a>
            <a href="/logbook" style="margin-right: 10px;">📚 Logbook</a>
            <a href="/manajemen-user" style="margin-right: 10px;">👥 Manajemen User</a>
            <strong><a href="/inventory/daftar-alat" style="margin-right: 10px; color: #007bff;">🔧 Daftar Alat</a></strong>
            <a href="/inventory/daftar-bahan" style="margin-right: 10px;">🧪 Daftar Bahan</a>
            <a href="/inventory/daftar-instrumen" style="margin-right: 10px;">📏 Daftar Instrumen</a>
            <a href="/profiles" style="margin-right: 10px;">👤 Profiles</a>
            <a href="/logout">🔒 Logout</a>
        </div>
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>

    <p>Selamat datang, <strong><?= esc(session()->get('nama_lengkap')) ?></strong>!</p>

    <a href="<?= base_url('profiles') ?>">
        <button>Kelola Profil</button>
    </a>
</body>
</html>

