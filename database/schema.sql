CREATE DATABASE IF NOT EXISTS loumart
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE loumart;

CREATE TABLE IF NOT EXISTS produk (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nama VARCHAR(150) NOT NULL,
    kategori VARCHAR(80) NOT NULL,
    harga BIGINT UNSIGNED NOT NULL,
    stok INT UNSIGNED NOT NULL DEFAULT 0,
    gambar VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pesanan (
    id VARCHAR(20) NOT NULL,
    pemesan VARCHAR(150) NOT NULL,
    kontak VARCHAR(30) NOT NULL,
    alamat TEXT NOT NULL,
    subtotal BIGINT UNSIGNED NOT NULL,
    diskon BIGINT UNSIGNED NOT NULL,
    pajak BIGINT UNSIGNED NOT NULL,
    total BIGINT UNSIGNED NOT NULL,
    status VARCHAR(30) NOT NULL,
    tanggal DATE NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_pesanan_status (status),
    INDEX idx_pesanan_tanggal (tanggal)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS detail_pesanan (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    pesanan_id VARCHAR(20) NOT NULL,
    produk_id INT UNSIGNED NULL,
    nama_produk VARCHAR(150) NOT NULL,
    qty INT UNSIGNED NOT NULL,
    harga_satuan BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_detail_pesanan_pesanan (pesanan_id),
    INDEX idx_detail_pesanan_produk (produk_id),
    CONSTRAINT fk_detail_pesanan_pesanan
        FOREIGN KEY (pesanan_id) REFERENCES pesanan (id)
        ON DELETE CASCADE,
    CONSTRAINT fk_detail_pesanan_produk
        FOREIGN KEY (produk_id) REFERENCES produk (id)
        ON DELETE SET NULL
) ENGINE=InnoDB;
