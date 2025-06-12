<!DOCTYPE html>
<html>
<head>
    <title>Form Registrasi</title>
    <script>
        // Validasi nama hanya huruf
        function validateNameInput(event) {
            const char = String.fromCharCode(event.which);
            if (!/^[a-zA-Z\s]+$/.test(char)) {
                event.preventDefault();
            }
        }

        // Toggle show/hide password
        function togglePassword(id) {
            const field = document.getElementById(id);
            field.type = field.type === "password" ? "text" : "password";
        }
    </script>
</head>
<body>

<h2>Form Registrasi</h2>

<?php if(session()->getFlashdata('success')): ?>
    <p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<?php if(isset($validation)): ?>
    <div style="color:red;">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('registrasi/simpan') ?>" method="post">
    <label>Nama Lengkap:</label><br>
    <input type="text" name="nama_lengkap" required onkeypress="validateNameInput(event)"><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br>

    <label>Cohort (Angka saja):</label><br>
    <input type="number" name="cohort" required><br>

    <label>Prodi:</label><br>
    <select name="prodi" required>
        <option value="">-- Pilih Prodi --</option>
        <option value="Kimia">Kimia</option>
    </select><br>

    <label>Password:</label><br>
    <input type="password" name="password" id="password" required>
    <input type="checkbox" onclick="togglePassword('password')"> Lihat Password<br>

    <label>Ulangi Password:</label><br>
    <input type="password" name="password_confirm" id="password_confirm" required>
    <input type="checkbox" onclick="togglePassword('password_confirm')"> Lihat Password<br><br>

    <button type="submit">Daftar</button>
</form>

</body>
</html>
