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
