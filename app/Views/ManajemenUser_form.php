<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen User</title>

    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <style>
        .stats-card {
            display: inline-block;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 5px;
            text-align: center;
            min-width: 120px;
        }
        .stats-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .user-table th, .user-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .user-table th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-info { background: #17a2b8; color: white; }
        .filter-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 4px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
        }
        .role-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .role-admin { background: #dc3545; color: white; }
        .role-user { background: #28a745; color: white; }
    </style>
</head>
<body class="hold-transition layout-navbar-fixed layout-top-nav">

<div class="wrapper">

    <?= view('partial/header') ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <h1 class="m-0 text-dark">👥 Manajemen User</h1>
            </div>
        </div>

        <div class="content">
            <div class="container">
                <p>Kelola pengguna sistem laboratorium</p>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        ✅ <?= session()->getFlashdata('success') ?>
                    </div>
                <?php elseif (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        ❌ <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <h3>📊 Statistik User</h3>
                <div class="mb-3">
                    <div class="stats-card">
                        <div class="stats-number"><?= $stats['total_user'] ?></div>
                        <div>Total User</div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-number"><?= $stats['total_admin'] ?></div>
                        <div>Admin</div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-number"><?= $stats['total_regular_user'] ?></div>
                        <div>Regular User</div>
                    </div>
                </div>

                <div class="mb-3">
                    <a href="<?= site_url('manajemen-user/export') ?>" class="btn btn-success">📄 Export CSV</a>
                    <a href="<?= site_url('manajemen-user/statistik') ?>" class="btn btn-info">📊 Statistik Detail</a>
                </div>

                <div class="filter-section">
                    <form method="GET" action="<?= site_url('manajemen-user') ?>" id="filterForm">
                        <div class="d-flex align-items-end flex-wrap" style="gap: 10px;">
                            <div>
                                <label>🔍 Cari User:</label><br>
                                <input type="text" name="search" id="searchInput" value="<?= esc($search) ?>" placeholder="Nama, email, cohort, prodi..." class="form-control" style="width: 200px;">
                            </div>
                            <div>
                                <label>👤 Filter Role:</label><br>
                                <select name="role" id="roleFilter" class="form-control">
                                    <option value="">Semua Role</option>
                                    <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">🔍 Filter</button>
                                <a href="<?= site_url('manajemen-user') ?>" class="btn btn-warning">🔄 Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                <h3>📋 Daftar User</h3>
                <p>Menampilkan <?= count($users) ?> dari <?= $totalUsers ?> user</p>

                <?php if (!empty($users)): ?>
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Cohort</th>
                                <th>Program Studi</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = ($currentPage - 1) * $perPage + 1; ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?php if (!empty($user['foto_profil'])): ?>
                                            <img src="<?= base_url('uploads/profiles/' . $user['foto_profil']) ?>" 
                                                    width="40" height="40" style="border-radius: 50%; object-fit: cover;" 
                                                    alt="Foto <?= esc($user['nama_lengkap']) ?>">
                                        <?php else: ?>
                                            <div style="width: 40px; height: 40px; background: #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                👤
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($user['nama_lengkap']) ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td><?= esc($user['cohort']) ?></td>
                                    <td><?= esc($user['prodi']) ?></td>
                                    <td>
                                        <span class="role-badge role-<?= $user['role'] ?>">
                                            <?= $user['role'] === 'admin' ? '👑 Admin' : '👤 User' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button onclick="showDetail(<?= $user['id_regis'] ?>)" class="btn btn-info">👁️ Detail</button>
                                        
                                        <?php if ($user['role'] === 'user'): ?>
                                            <button onclick="updateRole(<?= $user['id_regis'] ?>, 'admin')" class="btn btn-warning">👑 Jadikan Admin</button>
                                        <?php else: ?>
                                            <button onclick="updateRole(<?= $user['id_regis'] ?>, 'user')" class="btn btn-warning">👤 Jadikan User</button>
                                        <?php endif; ?>
                                        
                                        <button onclick="resetPassword(<?= $user['id_regis'] ?>)" class="btn btn-success">🔑 Reset Password</button>
                                        
                                        <?php if ($user['id_regis'] != session()->get('id_regis')): ?>
                                            <button onclick="deleteUser(<?= $user['id_regis'] ?>, '<?= esc($user['nama_lengkap']) ?>')" class="btn btn-danger">🗑️ Hapus</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <a href="<?= site_url('manajemen-user?page=' . ($currentPage - 1) . '&search=' . urlencode($search) . '&role=' . urlencode($role)) ?>">« Previous</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="<?= site_url('manajemen-user?page=' . $i . '&search=' . urlencode($search) . '&role=' . urlencode($role)) ?>" 
                                   class="<?= $i == $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <a href="<?= site_url('manajemen-user?page=' . ($currentPage + 1) . '&search=' . urlencode($search) . '&role=' . urlencode($role)) ?>">Next »</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="card text-center py-4">
                        <div class="card-body">
                            <h3>👥 Tidak ada user ditemukan</h3>
                            <p>Tidak ada user yang sesuai dengan filter yang dipilih</p>
                        </div>
                    </div>
                <?php endif; ?>

                <div id="detailModal" class="modal" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">👤 Detail User</h5>
                                <button type="button" class="close" onclick="closeModal()">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="detailContent">
                                <p>Loading...</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" onclick="closeModal()">❌ Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="resetPasswordModal" class="modal" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">🔑 Reset Password</h5>
                                <button type="button" class="close" onclick="closeResetModal()">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <form id="resetPasswordForm">
                                <div class="modal-body">
                                    <input type="hidden" id="resetUserId" name="user_id">
                                    <div class="form-group">
                                        <label for="newPassword">Password Baru:</label>
                                        <input type="password" id="newPassword" class="form-control" required minlength="6">
                                        <small class="form-text text-muted">Minimal 6 karakter</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning" onclick="closeResetModal()">Batal</button>
                                    <button type="submit" class="btn btn-success">Reset Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="overlay" class="modal-backdrop fade show" style="display: none;" onclick="closeModal()"></div>

            </div>
        </div>
    </div>

</div>

<script src="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/AdminLTE-3.2.0/dist/js/adminlte.min.js') ?>"></script>

<script>
// Show Detail Modal
function showDetail(userId) {
    document.getElementById('detailContent').innerHTML = '<p>⏳ Loading data...</p>';
    $('#detailModal').modal('show');
    
    $.get('<?= site_url("manajemen-user/detail/") ?>' + userId)
        .done(function(response) {
            if (response.success) {
                const user = response.data;
                let content = '<div class="row">';
                
                // Foto
                content += '<div class="col-md-4 text-center">';
                if (user.foto_profil) {
                    content += '<img src="<?= base_url("uploads/profiles/") ?>' + user.foto_profil + '" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">';
                } else {
                    content += '<div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px; font-size: 48px;">👤</div>';
                }
                content += '</div>';
                
                // Info
                content += '<div class="col-md-8">';
                content += '<h4>📋 Informasi User</h4>';
                content += '<table class="table table-bordered table-striped">';
                content += '<tr><th>ID</th><td>' + user.id_regis + '</td></tr>';
                content += '<tr><th>Nama</th><td>' + user.nama_lengkap + '</td></tr>';
                content += '<tr><th>Email</th><td>' + user.email + '</td></tr>';
                content += '<tr><th>Cohort</th><td>' + user.cohort + '</td></tr>';
                content += '<tr><th>Program Studi</th><td>' + user.prodi + '</td></tr>';
                content += '<tr><th>Role</th><td><span class="role-badge role-' + user.role + '">' + (user.role === 'admin' ? '👑 Admin' : '👤 User') + '</span></td></tr>';
                content += '</table>';
                content += '</div>';
                content += '</div>';
                
                document.getElementById('detailContent').innerHTML = content;
            } else {
                document.getElementById('detailContent').innerHTML = '<p class="text-danger">❌ ' + response.message + '</p>';
            }
        })
        .fail(function() {
            document.getElementById('detailContent').innerHTML = '<p class="text-danger">❌ Gagal memuat data</p>';
        });
}

// Update Role
function updateRole(userId, newRole) {
    const roleName = newRole === 'admin' ? 'Admin' : 'User';
    if (!confirm(`Yakin ingin mengubah role menjadi ${roleName}?`)) return;
    
    $.post('<?= site_url("manajemen-user/update-role") ?>', {
        user_id: userId,
        role: newRole
    })
    .done(function(response) {
        if (response.success) {
            alert('✅ ' + response.message);
            location.reload();
        } else {
            alert('❌ ' + response.message);
        }
    })
    .fail(function() {
        alert('❌ Gagal mengubah role');
    });
}

// Delete User
function deleteUser(userId, userName) {
    if (!confirm(`Yakin ingin menghapus user "${userName}"?\n\nTindakan ini tidak dapat dibatalkan!`)) return;
    
    $.post('<?= site_url("manajemen-user/delete-user") ?>', {
        user_id: userId
    })
    .done(function(response) {
        if (response.success) {
            alert('✅ ' + response.message);
            location.reload();
        } else {
            alert('❌ ' + response.message);
        }
    })
    .fail(function() {
        alert('❌ Gagal menghapus user');
    });
}

// Reset Password
function resetPassword(userId) {
    document.getElementById('resetUserId').value = userId;
    document.getElementById('newPassword').value = '';
    $('#resetPasswordModal').modal('show');
}

// Submit Reset Password
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('resetUserId').value;
    const newPassword = document.getElementById('newPassword').value;
    
    if (newPassword.length < 6) {
        alert('Password minimal 6 karakter');
        return;
    }
    
    $.post('<?= site_url("manajemen-user/reset-password") ?>', {
        user_id: userId,
        new_password: newPassword
    })
    .done(function(response) {
        if (response.success) {
            alert('✅ ' + response.message);
            closeResetModal();
        } else {
            alert('❌ ' + response.message);
        }
    })
    .fail(function() {
        alert('❌ Gagal reset password');
    });
});

// Close Modals
function closeModal() {
    $('#detailModal').modal('hide');
}

function closeResetModal() {
    $('#resetPasswordModal').modal('hide');
}

// Auto submit on search input (dengan debounce)
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        document.getElementById('filterForm').submit();
    }, 500); // Submit setelah 500ms user berhenti mengetik
});

// Auto submit on role change
document.getElementById('roleFilter').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});

// Prevent form submit on Enter if search is empty
document.getElementById('filterForm').addEventListener('submit', function(e) {
    const search = document.getElementById('searchInput').value.trim();
    const role = document.getElementById('roleFilter').value;
    
    // Jika tidak ada filter sama sekali, tidak perlu submit
    if (!search && !role) {
        e.preventDefault();
        window.location.href = '<?= site_url('manajemen-user') ?>';
        return false;
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeResetModal();
    }
});
</script>

</body>
</html>