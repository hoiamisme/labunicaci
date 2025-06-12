<!DOCTYPE html>
<html>
<head>
    <title>Form Registrasi</title>
</head>
<body>

<h2>Form Registrasi</h2>

<?php if(session()->getFlashdata('success')): ?>
    <p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<?php if(isset($validation)): ?>
    <div style="color:red;">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('registrasi/simpan') ?>" method="post">
    <label>Nama Lengkap:</label><br>
    <input type="text" name="nama_lengkap" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br>

    <label>Cohort:</label><br>
    <input type="text" name="cohort" required><br>

    <label>Prodi:</label><br>
    <input type="text" name="prodi" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Daftar</button>
</form>

</body>
</html>
