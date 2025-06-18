<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen User</title>

    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('adminlte/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #2ec4b6;
            --warning-color: #ff9f1c;
            --danger-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #2c3e50;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-color);
        }

        .content-wrapper {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            min-height: 100vh;
            padding: 25px 0;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border: none;
            border-radius: 15px;
            padding: 20px;
            margin: 10px;
            text-align: center;
            min-width: 200px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-number {
            font-size: 32px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        /* Table Styling */
        .user-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .user-table th {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .user-table td {
            padding: 12px 15px;
            text-align: left;
            vertical-align: middle;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            border-bottom: 1px solid #eee;
        }

        .user-table tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        /* Specific column widths */
        .user-table th:nth-child(1), /* NO */
        .user-table td:nth-child(1) {
            width: 5%;
        }

        .user-table th:nth-child(2), /* FOTO */
        .user-table td:nth-child(2) {
            width: 8%;
        }

        .user-table th:nth-child(3), /* NAMA LENGKAP */
        .user-table td:nth-child(3) {
            width: 15%;
        }

        .user-table th:nth-child(4), /* EMAIL */
        .user-table td:nth-child(4) {
            width: 20%;
        }

        .user-table th:nth-child(5), /* COHORT */
        .user-table td:nth-child(5) {
            width: 10%;
        }

        .user-table th:nth-child(6), /* PROGRAM STUDI */
        .user-table td:nth-child(6) {
            width: 12%;
        }

        .user-table th:nth-child(7), /* ROLE */
        .user-table td:nth-child(7) {
            width: 10%;
        }

        .user-table th:nth-child(8), /* AKSI */
        .user-table td:nth-child(8) {
            width: 20%;
        }

        /* Button group in action column */
        .btn-group {
            display: flex;
            gap: 8px;
            flex-wrap: nowrap;
            align-items: center;
        }

        .btn {
            padding: 6px 12px;
            margin: 3px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            min-width: 100px; /* Ensure minimum width */
            white-space: nowrap; /* Prevent text wrapping */
            text-overflow: clip; /* Show full text */
            overflow: visible; /* Ensure text is visible */
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #4cc9f0 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #ffbf69 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #ff4d6d 100%);
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, #4895ef 0%, #4cc9f0 100%);
            color: white;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
        }

        /* Role Badges */
        .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: inline-block;
            min-width: 80px; /* Ensure minimum width */
            text-align: center;
            white-space: nowrap; /* Prevent text wrapping */
        }

        .role-admin {
            background: linear-gradient(135deg, var(--danger-color) 0%, #ff4d6d 100%);
            color: white;
        }

        .role-user {
            background: linear-gradient(135deg, var(--success-color) 0%, #4cc9f0 100%);
            color: white;
        }

        /* Pagination */
        .pagination {
            margin-top: 25px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 10px 15px;
            margin: 0 5px;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            background: white;
            color: var(--dark-color);
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .pagination a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .pagination a.active {
            background: var(--primary-color);
            color: white;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }

        .modal-body {
            padding: 25px;
        }

        .close {
            color: white;
            opacity: 1;
        }

        /* Alert Messages */
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        /* Page Title */
        .content-header h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid var(--primary-color);
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .user-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
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