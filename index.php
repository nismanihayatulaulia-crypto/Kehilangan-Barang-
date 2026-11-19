<?php
$judul = 'Daftar Barang';
require_once __DIR__ . '/config/database.php';

function tgl_pendek($tgl) {
    $bln = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $t = strtotime($tgl);
    return date('d', $t) . ' ' . $bln[(int)date('n', $t) - 1] . ' ' . date('Y', $t);
}

// ---- Ambil filter dari URL ----
$q        = trim($_GET['q'] ?? '');
$kategori = (int)($_GET['kategori'] ?? 0);
$status   = $_GET['status'] ?? '';
$tanggal  = $_GET['tanggal'] ?? '';
$jenis    = $_GET['jenis'] ?? '';
$halaman  = max(1, (int)($_GET['hal'] ?? 1));
$perHal   = 8;
$offset   = ($halaman - 1) * $perHal;

$where = []; $tipe = ''; $param = [];
if ($q !== '') {
    $where[] = '(l.nama_barang LIKE ? OR l.ciri_ciri LIKE ?)';
    $like = '%' . $q . '%';
    $tipe .= 'ss'; $param[] = $like; $param[] = $like;
}
if ($kategori > 0) { $where[] = 'l.id_kategori = ?'; $tipe .= 'i'; $param[] = $kategori; }
if (in_array($status, ['Hilang', 'Ditemukan', 'Dikembalikan'], true)) {
    $where[] = 'l.status = ?'; $tipe .= 's'; $param[] = $status;
}
if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
    $where[] = 'l.tanggal_kejadian = ?'; $tipe .= 's'; $param[] = $tanggal;
}
if (in_array($jenis, ['hilang', 'temuan'], true)) {
    $where[] = 'l.jenis = ?'; $tipe .= 's'; $param[] = $jenis;
}
$sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ---- Hitung total ----
$stmt = $db->prepare("SELECT COUNT(*) FROM laporan_barang l $sqlWhere");
if ($param) $stmt->bind_param($tipe, ...$param);
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_row()[0];
$jumlahHal = max(1, (int)ceil($total / $perHal));

// ---- Ambil data halaman ini ----
$sql = "SELECT l.id_laporan, l.nama_barang, l.status, l.foto, l.lokasi, l.tanggal_kejadian, k.nama_kategori
        FROM laporan_barang l
        JOIN kategori k ON k.id_kategori = l.id_kategori
        $sqlWhere
        ORDER BY l.created_at DESC, l.id_laporan DESC
        LIMIT ? OFFSET ?";
$stmt = $db->prepare($sql);
$p2 = $param; $p2[] = $perHal; $p2[] = $offset;
$stmt->bind_param($tipe . 'ii', ...$p2);
$stmt->execute();
$hasil = $stmt->get_result();

$daftarKategori = $db->query('SELECT id_kategori, nama_kategori FROM kategori ORDER BY id_kategori');

function url_hal($n) {
    $qs = $_GET; $qs['hal'] = $n;
    return '?' . http_build_query($qs);
}

include __DIR__ . '/includes/header.php';
?>
<h1>Daftar Barang</h1>
<p class="subtitle">Cari barang hilang atau temuan di lingkungan kampus.</p>

<form method="get" class="filter-bar">
  <div>
    <label>Kata kunci</label>
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Cari nama atau ciri barang...">
  </div>
  <div>
    <label>Kategori</label>
    <select name="kategori">
      <option value="0">Semua</option>
      <?php while ($k = $daftarKategori->fetch_assoc()): ?>
        <option value="<?= $k['id_kategori'] ?>" <?= $kategori === (int)$k['id_kategori'] ? 'selected' : '' ?>><?= e($k['nama_kategori']) ?></option>
      <?php endwhile; ?>
    </select>
  </div>
  <div>
    <label>Status</label>
    <select name="status">
      <option value="">Semua</option>
      <?php foreach (['Hilang', 'Ditemukan', 'Dikembalikan'] as $s): ?>
        <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label>Tanggal</label>
    <input type="date" name="tanggal" value="<?= e($tanggal) ?>">
  </div>
  <button class="btn btn-primary" type="submit">Cari</button>
</form>

<?php if ($total === 0): ?>
  <div class="kosong">Data tidak ditemukan. Coba ubah kata kunci atau filter.</div>
<?php else: ?>
  <div class="item-grid">
    <?php while ($b = $hasil->fetch_assoc()): ?>
      <a class="item-card" href="<?= BASE_URL ?>/pages/detail.php?id=<?= (int)$b['id_laporan'] ?>">
        <div class="foto">
          <?php if (!empty($b['foto'])): ?>
            <img src="<?= BASE_URL ?>/uploads/<?= e($b['foto']) ?>" alt="<?= e($b['nama_barang']) ?>">
          <?php else: ?>[ Foto ]<?php endif; ?>
        </div>
        <div class="body">
          <span class="badge badge-<?= strtolower($b['status']) ?>"><?= e($b['status']) ?></span>
          <div class="nama"><?= e($b['nama_barang']) ?></div>
          <div class="meta"><?= e($b['nama_kategori']) ?></div>
          <div class="meta">📍 <?= e($b['lokasi']) ?></div>
          <div class="meta">📅 <?= tgl_pendek($b['tanggal_kejadian']) ?></div>
        </div>
      </a>
    <?php endwhile; ?>
  </div>

  <?php if ($jumlahHal > 1): ?>
    <div class="pagination">
      <?php if ($halaman > 1): ?><a href="<?= url_hal($halaman - 1) ?>">‹</a><?php endif; ?>
      <?php for ($i = 1; $i <= $jumlahHal; $i++): ?>
        <?php if ($i === $halaman): ?><span class="aktif"><?= $i ?></span>
        <?php else: ?><a href="<?= url_hal($i) ?>"><?= $i ?></a><?php endif; ?>
      <?php endfor; ?>
      <?php if ($halaman < $jumlahHal): ?><a href="<?= url_hal($halaman + 1) ?>">›</a><?php endif; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>