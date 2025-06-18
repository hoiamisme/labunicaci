<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url('css/style_reguler.css') ?>">
</head>
<body>
    <div class="login-container">
        <h2>Form Login</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <p class="error"><?= session()->getFlashdata('error') ?></p>
        <?php endif; ?>

        <form action="/login/auth" method="post">
            <label>Email</label><br>
            <input type="email" name="email" required><br><br>

            <label>Password</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Login</button>
        </form>

        <p class="register-link">Belum punya akun? <a href="/registrasi">Daftar di sini</a></p>
    </div>
</body>
</html>
