<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($judul ?? 'Sistem Informasi Kehilangan Barang') ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<nav class="navbar">
  <a class="brand" href="<?= BASE_URL ?>/index.php">Kehilangan Barang</a>
  <div class="menu">
    <a href="<?= BASE_URL ?>/index.php">Daftar Barang</a>
    <?php if (adalah_admin()): ?>
      <a href="<?= BASE_URL ?>/pages/admin/dashboard.php">Panel Admin</a>
      <a href="<?= BASE_URL ?>/pages/logout.php">Logout</a>
    <?php elseif (sudah_login()): ?>
      <a href="<?= BASE_URL ?>/pages/lapor-hilang.php">Lapor Hilang</a>
      <a href="<?= BASE_URL ?>/pages/lapor-temuan.php">Lapor Temuan</a>
      <a href="<?= BASE_URL ?>/pages/laporan-saya.php">Laporan Saya</a>
      <a href="<?= BASE_URL ?>/pages/logout.php">Logout</a>
    <?php else: ?>
      <a href="<?= BASE_URL ?>/pages/login.php">Login</a>
      <a href="<?= BASE_URL ?>/pages/registrasi.php">Registrasi</a>
    <?php endif; ?>
  </div>
</nav>
<main class="container">
