<?php
// Jalankan SEKALI lewat browser: http://localhost/kehilangan-barang/buat-admin.php
// Setelah akun admin terbentuk, HAPUS file ini.
require_once __DIR__ . '/config/database.php';
$email = 'admin@kampus.test';
$password = 'admin123';   // ganti setelah login pertama
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $db->prepare("INSERT INTO users (nama, email, password, role) VALUES ('Admin', ?, ?, 'admin')");
$stmt->bind_param('ss', $email, $hash);
try { $stmt->execute(); echo "Admin dibuat. Email: $email | Password: $password. Hapus file ini sekarang."; }
catch (mysqli_sql_exception $e) { echo 'Gagal (mungkin admin sudah ada): ' . htmlspecialchars($e->getMessage()); }
