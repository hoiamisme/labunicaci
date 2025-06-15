
<!DOCTYPE html>
<html>
<head>
    <title>Manage User</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <h2>Manage User</h2>
    <?php if(session()->getFlashdata('success')): ?>
        <div style="color:green"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <table id="userTable" class="display">
        <thead>
            <tr>
                <th>Username</th>
                <th>Photo</th>
                <th>Status</th>
                <th>Prodi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?= esc($user['nama_lengkap']) ?></td>
                <td>
                    <?php if($user['foto_profil']): ?>
                        <img src="<?= base_url('uploads/'.$user['foto_profil']) ?>" width="50" height="50">
                    <?php else: ?>
                        <span>No Photo</span>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="post" action="<?= site_url('user/updateStatus/'.$user['id_regis']) ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="user" <?= $user['status']=='user'?'selected':'' ?>>User</option>
                            <option value="admin" <?= $user['status']=='admin'?'selected':'' ?>>Admin</option>
                        </select>
                        <?= csrf_field() ?>
                    </form>
                </td>
                <td><?= esc($user['prodi']) ?></td>
                <td>
                    <!-- Tambahkan aksi lain jika perlu -->
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <script>
    $(document).ready(function() {
        $('#userTable').DataTable();
    });
    </script>
</body>
</html>
