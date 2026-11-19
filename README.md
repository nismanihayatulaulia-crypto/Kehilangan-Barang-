# Sistem Informasi Kehilangan Barang

## Cara menjalankan (lokal)
1. Pasang XAMPP atau Laragon, aktifkan Apache dan MySQL.
2. Salin folder proyek ini ke `htdocs` (XAMPP) atau `www` (Laragon), nama folder: `kehilangan-barang`.
3. Buka phpMyAdmin, tab **Impor**, pilih `database/database.sql`.
4. Sesuaikan `config/database.php` bila user/password MySQL berbeda.
5. Buka `http://localhost/kehilangan-barang/`.
6. Buka `buat-admin.php` sekali untuk membuat akun admin, lalu hapus file itu.
   (Jika folder diganti namanya, ubah BASE_URL di `includes/auth.php`.)

## Aturan cabang (branch)
- `main`: hasil gabungan yang sudah ditinjau
- `dev-nisma`: frontend
- `dev-marwa`: backend
Gabung ke `main` hanya setelah ditinjau pasangan.

## Struktur
config/ koneksi DB | includes/ header, footer, auth | pages/ halaman | assets/ CSS/JS | uploads/ foto | database/ skema SQL
