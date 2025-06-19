<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">
</head>
<body class="hold-transition layout-navbar-fixed layout-top-nav">

<div class="wrapper">

    <!-- Navbar -->
    <?= view('partial/header') ?> <!-- Pastikan file ini juga pakai path yang sama -->

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <h1 class="m-0 text-dark">👤 Profil Saya</h1>
            </div>
        </div>

        <div class="content">
            <div class="container">
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0"><i class="fas fa-user-cog"></i> Informasi Akun</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> <?= esc($user['nama_lengkap']) ?></p>
                        <p><strong>Cohort:</strong> <?= esc($user['cohort']) ?></p>
                        <p><strong>Prodi:</strong> <?= esc($user['prodi']) ?></p>

                        <?php if($user['foto_profil']): ?>
                            <img src="<?= base_url('uploads/' . $user['foto_profil']) ?>" width="150" class="img-thumbnail mb-3">
                        <?php endif; ?>

                        <form action="<?= base_url('profiles/update') ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Password Baru:</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Ulangi Password:</label>
                                <input type="password" name="repassword" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Upload Foto Profil:</label>
                                <input type="file" name="foto_profil" class="form-control-file">
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
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

<!-- Optional: Untuk label input file -->
<script>
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function(e){
            let fileName = e.target.files[0]?.name;
            if (fileName) {
                e.target.nextElementSibling.innerText = fileName;
            }
        });
    });
</script>

</body>
</html>
