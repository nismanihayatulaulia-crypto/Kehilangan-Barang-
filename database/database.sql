CREATE DATABASE IF NOT EXISTS kehilangan_barang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kehilangan_barang;

CREATE TABLE users (
  id_user     INT AUTO_INCREMENT PRIMARY KEY,
  nama        VARCHAR(100) NOT NULL,
  email       VARCHAR(100) NOT NULL UNIQUE,
  password    VARCHAR(255) NOT NULL,
  role        ENUM('pengguna','admin') NOT NULL DEFAULT 'pengguna',
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE kategori (
  id_kategori    INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori  VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE laporan_barang (
  id_laporan        INT AUTO_INCREMENT PRIMARY KEY,
  id_user           INT NOT NULL,
  id_kategori       INT NOT NULL,
  jenis             ENUM('hilang','temuan') NOT NULL,
  nama_barang       VARCHAR(100) NOT NULL,
  ciri_ciri         TEXT NOT NULL,
  lokasi            VARCHAR(150) NOT NULL,
  lokasi_simpan     VARCHAR(150) NULL,
  tanggal_kejadian  DATE NOT NULL,
  foto              VARCHAR(255) NULL,
  kontak_pelapor    VARCHAR(50) NOT NULL,
  status            ENUM('Hilang','Ditemukan','Dikembalikan') NOT NULL,
  terverifikasi     TINYINT(1) NOT NULL DEFAULT 0,
  created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_laporan_user FOREIGN KEY (id_user)
    REFERENCES users (id_user) ON DELETE RESTRICT,
  CONSTRAINT fk_laporan_kategori FOREIGN KEY (id_kategori)
    REFERENCES kategori (id_kategori) ON DELETE RESTRICT,
  INDEX idx_laporan_status   (status),
  INDEX idx_laporan_jenis    (jenis),
  INDEX idx_laporan_tanggal  (tanggal_kejadian),
  INDEX idx_laporan_nama     (nama_barang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE klaim (
  id_klaim       INT AUTO_INCREMENT PRIMARY KEY,
  id_laporan     INT NOT NULL,
  id_user        INT NOT NULL,
  keterangan     TEXT NOT NULL,
  status_klaim   ENUM('Menunggu','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu',
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_klaim_laporan FOREIGN KEY (id_laporan)
    REFERENCES laporan_barang (id_laporan) ON DELETE CASCADE,
  CONSTRAINT fk_klaim_user FOREIGN KEY (id_user)
    REFERENCES users (id_user) ON DELETE RESTRICT,
  UNIQUE KEY uq_klaim_laporan_user (id_laporan, id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO kategori (nama_kategori) VALUES
  ('Dokumen dan Kartu'), ('Elektronik'), ('Tas dan Dompet'),
  ('Kunci'), ('Pakaian dan Aksesoris'),
  ('Alat Tulis dan Buku'), ('Lainnya');
