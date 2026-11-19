<?php
$judul = 'Login';

require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare(
        "SELECT id_user, nama, email, password, role FROM users WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        header('Location: ' . BASE_URL . '/index.php');
        exit;
    } else {
        $error = 'Email atau password salah.';
    }
}
?>

<h1>Login</h1>

<div class="card">
    <?php if ($error): ?>
        <p><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun?
        <a href="<?= BASE_URL ?>/pages/registrasi.php">Registrasi</a>
    </p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
