<?php
require_once __DIR__ . '/auth.php';
$namaUser = $_SESSION['nama'] ?? 'Pengguna';
$admin = adalah_admin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($judul ?? 'SI Kehilangan Barang') ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<nav class="navbar">
  <a class="brand" href="<?= BASE_URL ?>/index.php">SI Kehilangan Barang<?= $admin ? ' — Panel Admin' : '' ?></a>
  <div class="menu">
    <?php if ($admin): ?>
      <a href="<?= BASE_URL ?>/index.php">Beranda</a>
      <a href="<?= BASE_URL ?>/pages/admin/dashboard.php">Panel Admin</a>
      <span class="user">Admin ▾</span>
      <a class="btn-outline-light" href="<?= BASE_URL ?>/pages/logout.php">Logout</a>
    <?php elseif (sudah_login()): ?>
      <a href="<?= BASE_URL ?>/index.php">Beranda</a>
      <a href="<?= BASE_URL ?>/pages/lapor-hilang.php">Lapor Hilang</a>
      <a href="<?= BASE_URL ?>/pages/lapor-temuan.php">Lapor Temuan</a>
      <a href="<?= BASE_URL ?>/pages/laporan-saya.php">Laporan Saya</a>
      <span class="user">Halo, <?= e($namaUser) ?> ▾</span>
      <a class="btn-outline-light" href="<?= BASE_URL ?>/pages/logout.php">Logout</a>
    <?php else: ?>
      <a href="<?= BASE_URL ?>/index.php">Beranda</a>
      <a href="<?= BASE_URL ?>/index.php?jenis=hilang">Barang Hilang</a>
      <a href="<?= BASE_URL ?>/index.php?jenis=temuan">Barang Temuan</a>
      <a class="btn-outline-light" href="<?= BASE_URL ?>/pages/login.php">Login</a>
      <a class="btn-outline-light" href="<?= BASE_URL ?>/pages/registrasi.php">Registrasi</a>
    <?php endif; ?>
  </div>
</nav>
<main class="container">