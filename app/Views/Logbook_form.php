<?php if (session()->get('logged_in')): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #f1f1f1;">
        <div>
            <p>Halo, <?= esc(session('nama_lengkap')) ?></p>
        </div>
        <div>
            <a href="/dashboard" style="margin-right: 10px;">🏠 Dashboard</a>
            <a href="/manajemen" style="margin-right: 10px;">🛠️ Manajemen</a>
            <a href="/pemakaian" style="margin-right: 10px;">📦 Pemakaian</a>
            <strong><a href="/logbook" style="margin-right: 10px; color: #007bff;">📚 Logbook</a></strong>
            <a href="/manajemen-user" style="margin-right: 10px;">👥 Manajemen User</a>
            <a href="/inventory/daftar-alat" style="margin-right: 10px;">🔧 Daftar Alat</a>
            <a href="/inventory/daftar-bahan" style="margin-right: 10px;">🧪 Daftar Bahan</a>
            <a href="/inventory/daftar-instrumen" style="margin-right: 10px;">📏 Daftar Instrumen</a>
            <a href="/profiles" style="margin-right: 10px;">👤 Profiles</a>
            <a href="/logout">🔒 Logout</a>
        </div>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Logbook Laboratorium</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>

<h1>📚 Logbook Laboratorium</h1>
<p>Riwayat Peminjaman Alat dan Pemakaian Bahan Laboratorium</p>

<hr>

<!-- Statistik -->
<h3>📊 Statistik</h3>
<ul>
    <li>Total Peminjaman Alat: <strong><?= $statistik['total_alat'] ?? 0 ?></strong></li>
    <li>Total Pemakaian Bahan: <strong><?= $statistik['total_bahan'] ?? 0 ?></strong></li>
    <li>Total Semua Aktivitas: <strong><?= $statistik['total_semua'] ?? 0 ?></strong></li>
    <li>Total Disetujui: <strong><?= $statistik['total_approve'] ?? 0 ?></strong></li>
    <li>Total Pending: <strong><?= $statistik['total_pending'] ?? 0 ?></strong></li>
    <li>Aktivitas Hari Ini: <strong><?= $statistik['aktivitas_hari_ini'] ?? 0 ?></strong></li>
</ul>

<hr>

<!-- Error Message (jika ada) -->
<?php if (isset($error_message)): ?>
<div style="background: #ffe6e6; border: 1px solid #ff0000; padding: 10px; margin: 10px 0; border-radius: 5px;">
    <strong>⚠️ Error:</strong> <?= esc($error_message) ?>
</div>
<?php endif; ?>

<!-- Actions -->
<p>
    <a href="<?= site_url('logbook/export') ?>">📄 Export CSV</a> | 
    <a href="<?= site_url('logbook/statistik') ?>">📊 Statistik Detail</a>
</p>

<hr>

<!-- Tabel Data -->
<h3>📋 Data Logbook</h3>

<?php if (!empty($logbook)): ?>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Nama Item</th>
                <th>Jenis</th>
                <th>Penambahan</th>
                <th>Pengurangan</th>
                <th>Tanggal</th>
                <th>Tujuan Pemakaian</th>
                <th>Status</th>
                <th>Pesan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($logbook as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($row['nama_pengguna'] ?? '-') ?></td>
                    <td><?= esc($row['nama_item'] ?? '-') ?></td>
                    <td>
                        <?php if ($row['jenis'] === 'alat'): ?>
                            🔧 Alat
                        <?php else: ?>
                            🧪 Bahan
                        <?php endif; ?>
                    </td>
                    <td><?= esc($row['penambahan'] ?? '0') ?></td>
                    <td><?= esc($row['pengurangan'] ?? '0') ?></td>
                    <td>
                        <?php if ($row['tanggal'] && $row['tanggal'] !== '-'): ?>
                            <?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?= esc($row['tujuan_pemakaian'] ?? '-') ?></td>
                    <td>
                        <?php if (($row['status'] ?? '') === 'approve'): ?>
                            <span style="color: green;">✅ Approve</span>
                        <?php elseif (($row['status'] ?? '') === 'not approve'): ?>
                            <span style="color: orange;">⏳ Not Approve</span>
                        <?php else: ?>
                            <span style="color: gray;">❓ <?= esc($row['status'] ?? 'Unknown') ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($row['pesan'] ?? '-') ?></td>
                    <td>
                        <button onclick="showDetail('<?= $row['jenis'] ?>', <?= $row['id'] ?>)">
                            👁️ Detail
                        </button>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    
    <p><strong>Total data: <?= count($logbook) ?></strong></p>
    
<?php else: ?>
    <div style="text-align: center; padding: 40px; border: 1px solid #ccc;">
        <h3>📭 Tidak ada data logbook</h3>
        <p>Belum ada aktivitas peminjaman atau pemakaian yang tercatat</p>
        <p><a href="/pemakaian">Mulai input pemakaian →</a></p>
    </div>
<?php endif; ?>

<!-- Modal Detail -->
<div id="detailModal" style="display: none; position: fixed; top: 50px; left: 50px; right: 50px; bottom: 50px; background: white; border: 3px solid #333; padding: 20px; overflow-y: auto; z-index: 1000;">
    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #ccc;">
        <h3>📄 Detail Logbook</h3>
        <button onclick="closeModal()" style="padding: 5px 10px;">❌ Tutup</button>
    </div>
    <div id="detailContent">
        <p>Loading...</p>
    </div>
</div>

<!-- Overlay -->
<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 999;" onclick="closeModal()"></div>

<script>
function showDetail(jenis, id) {
    // Show modal
    document.getElementById('detailContent').innerHTML = '<p>⏳ Loading data...</p>';
    document.getElementById('detailModal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
    
    // AJAX request
    $.get('<?= site_url("logbook/detail/") ?>' + jenis + '/' + id)
        .done(function(response) {
            if (response.success) {
                const detail = response.data;
                const jenisText = response.jenis === 'alat' ? 'Alat' : 'Bahan';
                
                let content = '<h4>👤 Informasi Pengguna</h4>';
                content += '<ul>';
                content += '<li><strong>Nama:</strong> ' + (detail.nama_lengkap || '-') + '</li>';
                content += '<li><strong>Email:</strong> ' + (detail.email || '-') + '</li>';
                content += '</ul>';
                
                content += '<h4>📦 Informasi ' + jenisText + '</h4>';
                content += '<ul>';
                content += '<li><strong>Nama:</strong> ' + (detail.nama_alat || detail.nama_bahan || '-') + '</li>';
                content += '<li><strong>Lokasi:</strong> ' + (detail.lokasi_alat || detail.lokasi_bahan || '-') + '</li>';
                
                if (detail.satuan_bahan) {
                    content += '<li><strong>Satuan:</strong> ' + detail.satuan_bahan + '</li>';
                }
                content += '</ul>';
                
                content += '<h4>📋 Detail Transaksi</h4>';
                content += '<ul>';
                content += '<li><strong>Penambahan:</strong> ' + (detail.penambahan || '0') + '</li>';
                content += '<li><strong>Pengurangan:</strong> ' + (detail.pengurangan || '0') + '</li>';
                
                // Tanggal berbeda untuk alat dan bahan
                if (response.jenis === 'alat') {
                    content += '<li><strong>Tanggal Dipinjam:</strong> ' + (detail.tanggal_dipinjam || '-') + '</li>';
                    content += '<li><strong>Tanggal Kembali:</strong> ' + (detail.tanggal_kembali || '-') + '</li>';
                } else {
                    content += '<li><strong>Tanggal:</strong> ' + (detail.tanggal || '-') + '</li>';
                }
                
                content += '<li><strong>Status:</strong> ' + (detail.status || '-') + '</li>';
                content += '<li><strong>Tujuan Pemakaian:</strong> ' + (detail.tujuan_pemakaian || '-') + '</li>';
                content += '<li><strong>Pesan:</strong> ' + (detail.pesan || '-') + '</li>';
                content += '</ul>';
                
                document.getElementById('detailContent').innerHTML = content;
            } else {
                document.getElementById('detailContent').innerHTML = '<p style="color: red;">❌ Gagal memuat data: ' + (response.error || 'Unknown error') + '</p>';
            }
        })
        .fail(function(xhr, status, error) {
            document.getElementById('detailContent').innerHTML = '<p style="color: red;">❌ Error: ' + error + '</p>';
        });
}

function closeModal() {
    document.getElementById('detailModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

// Keyboard shortcut
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>

</body>
</html>