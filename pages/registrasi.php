<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (sudah_login()) { header('Location: ' . BASE_URL . '/index.php'); exit; }

$error = '';
$berhasil = false;
$nama = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = trim($_POST['nama'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if ($nama === '') {
        $error = 'Nama lengkap wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $cek = $db->prepare('SELECT 1 FROM users WHERE email = ?');
        $cek->bind_param('s', $email);
        $cek->execute();
        if ($cek->get_result()->num_rows > 0) {
            $error = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'pengguna';
            $ins = $db->prepare('INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)');
            $ins->bind_param('ssss', $nama, $email, $hash, $role);
            $ins->execute();
            $berhasil = true;
        }
    }
}

$judul = 'Registrasi';
include __DIR__ . '/../includes/header.php';
?>
<div class="auth-wrap">
  <div class="card auth-card">
    <h1>Registrasi Akun</h1>
    <p class="subtitle">Buat akun untuk melaporkan barang</p>

    <?php if ($berhasil): ?>
      <div class="alert alert-success">Registrasi berhasil. Silakan login.</div>
      <a class="btn btn-primary btn-block" href="<?= BASE_URL ?>/pages/login.php">Ke Halaman Login</a>
    <?php else: ?>
      <?php if ($error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="form-group">
          <label for="nama">Nama Lengkap *</label>
          <input type="text" id="nama" name="nama" value="<?= e($nama) ?>" placeholder="Masukkan nama lengkap">
        </div>
        <div class="form-group">
          <label for="email">Email *</label>
          <input type="email" id="email" name="email" value="<?= e($email) ?>" placeholder="nama@email.com">
        </div>
        <div class="form-group">
          <label for="password">Password *</label>
          <input type="password" id="password" name="password" placeholder="Minimal 8 karakter">
        </div>
        <div class="form-group">
          <label for="konfirmasi">Konfirmasi Password *</label>
          <input type="password" id="konfirmasi" name="konfirmasi" placeholder="Ulangi password">
        </div>
        <button class="btn btn-primary btn-block" type="submit">Daftar</button>
      </form>

      <p class="auth-foot">Sudah punya akun? <a href="<?= BASE_URL ?>/pages/login.php">Login</a></p>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>