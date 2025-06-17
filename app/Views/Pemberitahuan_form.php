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
    <title>Pemberitahuan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        button {
            padding: 4px 8px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Daftar Peminjaman Alat (Belum Disetujui)</h2>
<table>
    <thead>
        <tr>
            <th>Nama Lengkap</th>
            <th>Nama Alat</th>
            <th>Jumlah Peminjaman</th>
            <th>Tujuan</th>
            <th>Pesan</th>
            <th>Tanggal Dipinjam</th>
            <th>Tanggal Kembali</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($alat)): ?>
            <?php foreach ($alat as $item): ?>
                <tr>
                    <td><?= esc($item->nama_lengkap) ?></td>
                    <td><?= esc($item->nama_alat) ?></td>
                    <td><?= esc($item->pengurangan) ?></td>
                    <td><?= esc($item->tujuan_pemakaian) ?></td>
                    <td><?= esc($item->pesan) ?></td>
                    <td><?= esc($item->tanggal_dipinjam) ?></td>
                    <td><?= esc($item->tanggal_kembali) ?></td>
                    <td>
                        <form action="<?= base_url('pemberitahuan/approveAlat') ?>" method="post">
                            <input type="hidden" name="id_logalat" value="<?= $item->id_logalat ?>">
                            <button type="submit">✔</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr><td colspan="8">Tidak ada data.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h2>Daftar Pengambilan Bahan (Belum Disetujui)</h2>
<table>
    <thead>
        <tr>
            <th>Nama Lengkap</th>
            <th>Nama Bahan</th>
            <th>Jumlah Pengambilan</th>
            <th>Tujuan</th>
            <th>Pesan</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($bahan)): ?>
            <?php foreach ($bahan as $item): ?>
                <tr>
                    <td><?= esc($item->nama_lengkap) ?></td>
                    <td><?= esc($item->nama_bahan) ?></td>
                    <td><?= esc($item->pengurangan) ?></td>
                    <td><?= esc($item->tujuan_pemakaian) ?></td>
                    <td><?= esc($item->pesan) ?></td>
                    <td><?= esc($item->tanggal) ?></td>
                    <td>
                        <form action="<?= base_url('pemberitahuan/approveBahan') ?>" method="post">
                            <input type="hidden" name="id_logbahan" value="<?= $item->id_logbahan ?>">
                            <button type="submit">✔</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr><td colspan="7">Tidak ada data.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
