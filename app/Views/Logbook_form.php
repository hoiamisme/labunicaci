<!-- filepath: app/Views/logbook.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Riwayat Peminjaman Alat dan Bahan Laboratorium</title>
    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
</head>
<body>
    <h2>Daftar Riwayat Peminjaman Alat dan Bahan Laboratorium</h2>

    <form method="get" action="<?= site_url('logbook') ?>">
        <label>Input:</label>
        <select name="tipe" onchange="this.form.submit()">
            <option value="">-- Pilih Jenis --</option>
            <option value="peminjaman" <?= $tipe == 'peminjaman' ? 'selected' : '' ?>>Peminjaman</option>
            <option value="pemakaian" <?= $tipe == 'pemakaian' ? 'selected' : '' ?>>Pemakaian</option>
        </select>
    </form>
    <br>

    <table id="logbookTable" class="display">
        <thead>
            <tr>
                <th>Nama Pengguna</th>
                <th>Alat</th>
                <th>Bahan</th>
                <th>Waktu</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logbook as $row): ?>
                <tr>
                    <td><?= esc($row['nama_pengguna']) ?></td>
                    <td><?= esc($row['alat']) ?></td>
                    <td><?= esc($row['bahan']) ?></td>
                    <td><?= esc($row['waktu']) ?></td>
                    <td><?= esc($row['keterangan']) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <script>
    $(document).ready(function() {
        $('#logbookTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
    </script>
</body>
</html>
