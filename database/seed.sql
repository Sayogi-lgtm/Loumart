USE loumart;

-- INSERT IGNORE menjaga data yang sudah diubah anggota saat seed dijalankan ulang.
INSERT IGNORE INTO produk (id, nama, kategori, harga, stok, gambar) VALUES
    (1, 'Kaos Polos', 'Pakaian', 75000, 20, 'kaos-polos.jpg'),
    (2, 'Kemeja Flanel', 'Pakaian', 185000, 12, 'kemeja-flanel.jpg'),
    (3, 'Celana Jeans', 'Pakaian', 275000, 8, 'celana-jeans.jpg'),
    (4, 'Tas Ransel', 'Aksesori', 320000, 7, 'tas-ransel.jpg'),
    (5, 'Topi Kasual', 'Aksesori', 65000, 15, 'topi-kasual.jpg'),
    (6, 'Sepatu Sneakers', 'Alas Kaki', 450000, 5, 'sepatu-sneakers.jpg'),
    (7, 'Botol Minum', 'Perlengkapan', 45000, 0, 'botol-minum.jpg'),
    (8, 'Jaket Hoodie', 'Pakaian', 350000, 6, 'jaket-hoodie.jpg');
