<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Instrumen</title>
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
    <title>Profil Saya</title>
</head>
<body>
    <h1>Profil Saya</h1>

    <?php if(session()->getFlashdata('error')): ?>
        <p style="color: red"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>
    <?php if(session()->getFlashdata('success')): ?>
        <p style="color: green"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <p><strong>Nama:</strong> <?= esc($user['nama_lengkap']) ?></p>
    <p><strong>Cohort:</strong> <?= esc($user['cohort']) ?></p>
    <p><strong>Prodi:</strong> <?= esc($user['prodi']) ?></p>

    <?php if($user['foto_profil']): ?>
        <img src="<?= base_url('uploads/' . $user['foto_profil']) ?>" width="150">
    <?php endif; ?>

    <form action="<?= base_url('profiles/update') ?>" method="post" enctype="multipart/form-data">
        <label>Password Baru:</label><br>
        <input type="password" name="password"><br>

        <label>Ulangi Password:</label><br>
        <input type="password" name="repassword"><br>

        <label>Upload Foto Profil:</label><br>
        <input type="file" name="foto_profil"><br><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>
