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

    <h2>🧪 Daftar Bahan</h2>
    
    <!-- FORM PENCARIAN -->
    <div style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
        <form method="GET" action="/inventory/daftar-bahan" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" 
                   name="search" 
                   placeholder="🔍 Cari nama bahan..." 
                   value="<?= esc($search ?? '') ?>"
                   style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; width: 250px;">
            
            <select name="location" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">📍 Semua Lokasi</option>
                <?php if (!empty($locations)): ?>
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?= esc($loc) ?>" <?= ($location ?? '') == $loc ? 'selected' : '' ?>>
                            <?= esc($loc) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            
            <button type="submit" style="padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                🔍 Cari
            </button>
            
            <a href="/inventory/daftar-bahan" style="padding: 8px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                🗑️ Reset
            </a>
        </form>
    </div>

    <!-- INFO HASIL PENCARIAN -->
    <?php if (!empty($search) || !empty($location)): ?>
        <div style="margin-bottom: 15px; padding: 10px; background-color: #d1ecf1; border-radius: 4px;">
            <strong>📊 Hasil Pencarian:</strong>
            <?php if (!empty($search)): ?>
                Nama: "<em><?= esc($search) ?></em>"
            <?php endif; ?>
            <?php if (!empty($location)): ?>
                Lokasi: "<em><?= esc($location) ?></em>"
            <?php endif; ?>
            - Ditemukan <strong><?= $totalItems ?? 0 ?></strong> bahan
        </div>
    <?php endif; ?>
    
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Nama Bahan</th>
                <th style="padding: 10px;">Jumlah</th>
                <th style="padding: 10px;">Satuan</th>
                <th style="padding: 10px;">Lokasi</th>
                <?php if (session()->get('role') === 'admin'): ?>
                    <th style="padding: 10px;">🔧 Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td style="padding: 8px; text-align: center;"><?= $item['id_bahan'] ?></td>
                        <td style="padding: 8px;"><?= esc($item['nama_bahan']) ?></td>
                        <td style="padding: 8px; text-align: center;">
                            <?= $item['jumlah_bahan'] ?>
                            <?php if ($item['jumlah_bahan'] <= 10): ?>
                                <span style="color: red; font-weight: bold;">⚠️</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 8px; text-align: center;"><?= esc($item['satuan_bahan']) ?></td>
                        <td style="padding: 8px;"><?= esc($item['lokasi']) ?></td>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <td style="padding: 8px; text-align: center;">
                                <button onclick="hapusBahan(<?= $item['id_bahan'] ?>, '<?= esc($item['nama_bahan']) ?>')" 
                                        style="padding: 5px 10px; background-color: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;">
                                    🗑️ Hapus
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= session()->get('role') === 'admin' ? '6' : '5' ?>" style="padding: 20px; text-align: center;">
                        <?php if (!empty($search) || !empty($location)): ?>
                            🔍 Tidak ada bahan yang sesuai dengan pencarian
                        <?php else: ?>
                            🧪 Tidak ada data bahan
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <?php if (($totalPages ?? 1) > 1): ?>
        <div style="margin-top: 20px; text-align: center;">
            <?php 
            $currentPage = $currentPage ?? 1;
            $searchQuery = !empty($search) ? "&search=" . urlencode($search) : "";
            $locationQuery = !empty($location) ? "&location=" . urlencode($location) : "";
            $queryString = $searchQuery . $locationQuery;
            ?>
            
            <?php if ($currentPage > 1): ?>
                <a href="/inventory/daftar-bahan?page=<?= $currentPage - 1 ?><?= $queryString ?>" 
                   style="padding: 8px 12px; margin: 0 5px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                    ← Sebelumnya
                </a>
            <?php endif; ?>
            
            <span style="margin: 0 15px;">
                Halaman <?= $currentPage ?> dari <?= $totalPages ?>
            </span>
            
            <?php if ($currentPage < $totalPages): ?>
                <a href="/inventory/daftar-bahan?page=<?= $currentPage + 1 ?><?= $queryString ?>" 
                   style="padding: 8px 12px; margin: 0 5px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                    Selanjutnya →
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- SCRIPT JAVASCRIPT UNTUK HAPUS -->
    <?php if (session()->get('role') === 'admin'): ?>
    <script>
        function hapusBahan(id, nama) {
            if (confirm(`⚠️ Yakin ingin menghapus bahan "${nama}"?\n\nData yang dihapus tidak dapat dikembalikan!`)) {
                // Kirim request DELETE
                fetch(`/inventory/hapus-bahan/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        '_method': 'DELETE'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ Bahan berhasil dihapus!');
                        location.reload(); // Refresh halaman
                    } else {
                        alert('❌ Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('❌ Terjadi kesalahan saat menghapus data');
                    console.error('Error:', error);
                });
            }
        }
    </script>
    <?php endif; ?>
</body>
</html>