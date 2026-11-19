<?php
// Fungsi sesi dan hak akses (dipakai Marwa untuk modul autentikasi).
if (session_status() === PHP_SESSION_NONE) session_start();

// BASE_URL: kosongkan jika proyek ada di root server, isi '/kehilangan-barang' jika di subfolder.
if (!defined('BASE_URL')) define('BASE_URL', '/kehilangan-barang');

function sudah_login() { return isset($_SESSION['id_user']); }
function adalah_admin() { return sudah_login() && $_SESSION['role'] === 'admin'; }

function wajib_login() {
    if (!sudah_login()) { header('Location: ' . BASE_URL . '/pages/login.php'); exit; }
}
function wajib_admin() {
    if (!adalah_admin()) { header('Location: ' . BASE_URL . '/index.php'); exit; }
}
function e($teks) { return htmlspecialchars($teks ?? '', ENT_QUOTES, 'UTF-8'); }
