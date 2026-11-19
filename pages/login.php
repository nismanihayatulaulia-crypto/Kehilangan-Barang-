<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (sudah_login()) { header('Location: ' . BASE_URL . '/index.php'); exit; }

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = $db->prepare('SELECT id_user, nama, password, role FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $u = $stmt->get_result()->fetch_assoc();

        if ($u && password_verify($password, $u['password'])) {
            session_regenerate_id(true);
            $_SESSION['id_user'] = (int)$u['id_user'];
            $_SESSION['nama']    = $u['nama'];
            $_SESSION['role']    = $u['role'];

            // Admin ke panel admin (jika halamannya sudah ada), pengguna ke Daftar Barang
            if ($u['role'] === 'admin' && file_exists(__DIR__ . '/admin/dashboard.php')) {
                header('Location: ' . BASE_URL . '/pages/admin/dashboard.php');
            } else {
                header('Location: ' . BASE_URL . '/index.php');
            }
            exit;
        }
        $error = 'Email atau password salah.';
    }
}

$judul = 'Login';
include __DIR__ . '/../includes/header.php';
?>
<div class="auth-wrap">
  <div class="card auth-card">
    <h1>Login</h1>
    <p class="subtitle">Masuk sebagai Pengguna atau Admin</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= e($email) ?>" placeholder="nama@email.com">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••">
      </div>
      <button class="btn btn-primary btn-block" type="submit">Masuk</button>
    </form>

    <p class="auth-foot">Belum punya akun? <a href="<?= BASE_URL ?>/pages/registrasi.php">Registrasi</a></p>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>