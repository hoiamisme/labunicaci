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

        // Validasi password sebelum submit
        function validateForm() {
            const password = document.getElementById('password').value;
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/;

            if (!regex.test(password)) {
                alert("Password harus memiliki huruf besar, huruf kecil, angka, dan karakter spesial.");
                return false;
            }

            return true;
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

<form action="<?= base_url('registrasi/simpan') ?>" method="post" onsubmit="return validateForm()">
    <label>Nama Lengkap:</label><br>
    <input type="text" name="nama_lengkap" required onkeypress="validateNameInput(event)"><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <?php if(isset($emailError)): ?>
        <p style="color:red"><?= $emailError ?></p>
    <?php endif; ?>

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
    <small>Password minimal 6 karakter dan harus mengandung huruf besar, huruf kecil, angka, dan simbol.</small><br>

    <label>Ulangi Password:</label><br>
    <input type="password" name="password_confirm" id="password_confirm" required>
    <input type="checkbox" onclick="togglePassword('password_confirm')"> Lihat Password<br><br>

    <button type="submit">Daftar</button>
</form>

<p>Sudah punya akun? <a href="/login">Login di sini</a></p>

</body>
</html>
