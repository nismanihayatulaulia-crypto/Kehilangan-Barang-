<?php
$judul = 'Registrasi';

require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/../includes/header.php';

$error = '';
$berhasil = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nama === '') {
        $error = 'Nama wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $cek = $db->prepare("SELECT id_user FROM users WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();

        if ($cek->get_result()->num_rows > 0) {
            $error = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'pengguna';

            $stmt = $db->prepare(
                "INSERT INTO users (nama, email, password, role)
                 VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param("ssss", $nama, $email, $hash, $role);
            $stmt->execute();

            $berhasil = 'Registrasi berhasil. Silakan login.';
            $stmt->close();
        }

        $cek->close();
    }
}
?>

<h1>Registrasi</h1>

<div class="card">

    <?php if ($error): ?>
        <p><?= e($error) ?></p>
    <?php endif; ?>

    <?php if ($berhasil): ?>
        <p><?= e($berhasil) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" minlength="6" required>

        <button type="submit">Daftar</button>

    </form>

    <p>
        Sudah punya akun?
        <a href="<?= BASE_URL ?>/pages/login.php">Login</a>
    </p>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
