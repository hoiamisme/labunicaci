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
    <title>Manajemen User</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<body>

<h1>👥 Manajemen User</h1>
<p>Kelola pengguna sistem laboratorium</p>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0;">
        ✅ <?= session()->getFlashdata('success') ?>
    </div>
<?php elseif (session()->getFlashdata('error')): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0;">
        ❌ <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Statistics -->
<h3>📊 Statistik User</h3>
<div style="margin-bottom: 20px;">
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

<!-- Actions -->
<div style="margin-bottom: 20px;">
    <a href="<?= site_url('manajemen-user/export') ?>" class="btn btn-success">📄 Export CSV</a>
    <a href="<?= site_url('manajemen-user/statistik') ?>" class="btn btn-info">📊 Statistik Detail</a>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" action="<?= site_url('manajemen-user') ?>" id="filterForm">
        <div style="display: flex; gap: 10px; align-items: end; flex-wrap: wrap;">
            <div>
                <label>🔍 Cari User:</label><br>
                <input type="text" name="search" id="searchInput" value="<?= esc($search) ?>" placeholder="Nama, email, cohort, prodi..." style="padding: 5px; width: 200px;">
            </div>
            <div>
                <label>👤 Filter Role:</label><br>
                <select name="role" id="roleFilter" style="padding: 5px;">
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

<!-- User Table -->
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

    <!-- Pagination -->
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
    <div style="text-align: center; padding: 40px; border: 1px solid #ccc; border-radius: 5px;">
        <h3>👥 Tidak ada user ditemukan</h3>
        <p>Tidak ada user yang sesuai dengan filter yang dipilih</p>
    </div>
<?php endif; ?>

<!-- Modal Detail -->
<div id="detailModal" style="display: none; position: fixed; top: 50px; left: 50px; right: 50px; bottom: 50px; background: white; border: 3px solid #333; padding: 20px; overflow-y: auto; z-index: 1000;">
    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #ccc;">
        <h3>👤 Detail User</h3>
        <button onclick="closeModal()" class="btn btn-danger">❌ Tutup</button>
    </div>
    <div id="detailContent">
        <p>Loading...</p>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="resetPasswordModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 3px solid #333; padding: 20px; z-index: 1000; min-width: 300px;">
    <h3>🔑 Reset Password</h3>
    <form id="resetPasswordForm">
        <input type="hidden" id="resetUserId" name="user_id">
        <div style="margin-bottom: 15px;">
            <label>Password Baru:</label><br>
            <input type="password" id="newPassword" required minlength="6" style="width: 100%; padding: 5px;">
            <small>Minimal 6 karakter</small>
        </div>
        <div style="text-align: right;">
            <button type="button" onclick="closeResetModal()" class="btn btn-warning">Batal</button>
            <button type="submit" class="btn btn-success">Reset Password</button>
        </div>
    </form>
</div>

<!-- Overlay -->
<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 999;" onclick="closeModal()"></div>

<script>
// Show Detail Modal
function showDetail(userId) {
    document.getElementById('detailContent').innerHTML = '<p>⏳ Loading data...</p>';
    document.getElementById('detailModal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
    
    $.get('<?= site_url("manajemen-user/detail/") ?>' + userId)
        .done(function(response) {
            if (response.success) {
                const user = response.data;
                let content = '<div style="display: flex; gap: 20px;">';
                
                // Foto
                content += '<div>';
                if (user.foto_profil) {
                    content += '<img src="<?= base_url("uploads/profiles/") ?>' + user.foto_profil + '" width="150" height="150" style="border-radius: 10px; object-fit: cover;">';
                } else {
                    content += '<div style="width: 150px; height: 150px; background: #ccc; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 48px;">👤</div>';
                }
                content += '</div>';
                
                // Info
                content += '<div style="flex: 1;">';
                content += '<h4>📋 Informasi User</h4>';
                content += '<table style="width: 100%;">';
                content += '<tr><td><strong>ID:</strong></td><td>' + user.id_regis + '</td></tr>';
                content += '<tr><td><strong>Nama:</strong></td><td>' + user.nama_lengkap + '</td></tr>';
                content += '<tr><td><strong>Email:</strong></td><td>' + user.email + '</td></tr>';
                content += '<tr><td><strong>Cohort:</strong></td><td>' + user.cohort + '</td></tr>';
                content += '<tr><td><strong>Program Studi:</strong></td><td>' + user.prodi + '</td></tr>';
                content += '<tr><td><strong>Role:</strong></td><td><span class="role-badge role-' + user.role + '">' + (user.role === 'admin' ? '👑 Admin' : '👤 User') + '</span></td></tr>';
                content += '</table>';
                content += '</div>';
                content += '</div>';
                
                document.getElementById('detailContent').innerHTML = content;
            } else {
                document.getElementById('detailContent').innerHTML = '<p style="color: red;">❌ ' + response.message + '</p>';
            }
        })
        .fail(function() {
            document.getElementById('detailContent').innerHTML = '<p style="color: red;">❌ Gagal memuat data</p>';
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
    document.getElementById('resetPasswordModal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
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
    document.getElementById('detailModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

function closeResetModal() {
    document.getElementById('resetPasswordModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
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