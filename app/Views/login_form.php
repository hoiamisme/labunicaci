<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- iCheck Bootstrap -->
  <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">

  <!-- Tambahan Gaya -->
  <style>
    body {
      background: linear-gradient(to right,rgb(253, 253, 253),rgb(239, 223, 255));
    }

    .login-box {
      margin-top: 8%;
    }

    .login-logo a {
      color:rgb(67, 73, 165);
      font-size: 28px;
      font-weight: bold;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .login-card-body {
      border-radius: 12px;
      background-color: #ffffff;
    }

    .btn-primary {
      background: linear-gradient(to right, #00c6ff, #0072ff);
      border: none;
      border-radius: 8px;
    }

    .form-control {
      border-radius: 8px;
    }

    .input-group-text {
      border-radius: 0 8px 8px 0;
    }

    .alert-danger {
      border-radius: 8px;
      font-size: 14px;
    }

    a {
      font-weight: bold;
      color: #555;
    }

    a:hover {
      color: #007bff;
      text-decoration: underline;
    }
  </style>
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Login</b> Sistem</a>
  </div>

  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Silakan login untuk melanjutkan</p>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('/login/auth') ?>" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="row">
          <div class="col-8 d-flex align-items-center">
            <a href="<?= base_url('/registrasi') ?>">📝 Daftar akun baru</a>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">🔓 Login</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JS Dependencies -->
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/dist/js/adminlte.min.js') ?>"></script>

</body>
</html>
