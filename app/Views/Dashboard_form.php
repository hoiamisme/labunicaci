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
