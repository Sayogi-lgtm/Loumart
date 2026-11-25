<main class="lm-katalog">
    <svg class="lm-icon-sprite" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <symbol id="lm-icon-search" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.35-4.35"></path>
        </symbol>
        <symbol id="lm-icon-chevron-up" viewBox="0 0 24 24">
            <path d="m18 15-6-6-6 6"></path>
        </symbol>
        <symbol id="lm-icon-tag" viewBox="0 0 24 24">
            <path d="M20.59 13.41 11 3.83V3H4v7h.83l9.58 9.59a2 2 0 0 0 2.83 0l3.35-3.35a2 2 0 0 0 0-2.83Z"></path>
            <circle cx="7.5" cy="7.5" r=".5"></circle>
        </symbol>
        <symbol id="lm-icon-shirt" viewBox="0 0 24 24">
            <path d="M20.4 3.5 16 2a4 4 0 0 1-8 0L3.6 3.5a2 2 0 0 0-1.3 2.2l.6 3.5a1 1 0 0 0 1 .8H6v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V10h2.1a1 1 0 0 0 1-.8l.6-3.5a2 2 0 0 0-1.3-2.2Z"></path>
        </symbol>
        <symbol id="lm-icon-bag" viewBox="0 0 24 24">
            <path d="M6 2h12l3 4v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6l3-4Z"></path>
            <path d="M3 6h18M16 10a4 4 0 0 1-8 0"></path>
        </symbol>
        <symbol id="lm-icon-coffee" viewBox="0 0 24 24">
            <path d="M10 2v2m4-2v2M6 8h12v9a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8Zm12 2h2a3 3 0 0 1 0 6h-2"></path>
        </symbol>
        <symbol id="lm-icon-glass" viewBox="0 0 24 24">
            <path d="M5 3h14l-1.8 17a2 2 0 0 1-2 2H8.8a2 2 0 0 1-2-2L5 3Z"></path>
            <path d="M6 12c3-2 5 2 8 0s4-1 5-1"></path>
        </symbol>
        <symbol id="lm-icon-shoes" viewBox="0 0 24 24">
            <path d="M4 16v-2.4C4 11.5 3 10.5 3 8c0-2.7 1.5-6 4.5-6C9.4 2 10 3.8 10 5.5c0 3.1-2 5.7-2 8.7V16a2 2 0 0 1-4 0Zm10 4v-2.4c0-2.1 1-3.1 1-5.6 0-2.7 1.5-6 4.5-6 1.9 0 2.5 1.8 2.5 3.5 0 3.1-2 5.7-2 8.7V20a2 2 0 0 1-4 0Z"></path>
        </symbol>
        <symbol id="lm-icon-honey" viewBox="0 0 24 24">
            <path d="M7 16.3c2.2 0 4-1.8 4-4.1 0-1.2-.6-2.3-1.7-3.2S7.3 6.8 7 5.3c-.3 1.5-1.1 2.8-2.3 3.8S3 11.1 3 12.2c0 2.3 1.8 4.1 4 4.1Z"></path>
            <path d="M12.6 6.6A11 11 0 0 0 14 3c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a7 7 0 0 1-11.9 5"></path>
        </symbol>
        <symbol id="lm-icon-notebook" viewBox="0 0 24 24">
            <path d="M6 3h12a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm8 0v19M2 7h4m-4 5h4m-4 5h4"></path>
        </symbol>
        <symbol id="lm-icon-lamp" viewBox="0 0 24 24">
            <path d="M8 3h8l4 8H4l4-8Zm4 8v7m-4 3h8m-7-3h6"></path>
        </symbol>
        <symbol id="lm-icon-plus" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14"></path>
        </symbol>
    </svg>

    <section class="lm-katalog__hero" aria-labelledby="lm-katalog-title">
        <div class="lm-katalog__hero-inner">
            <div class="lm-katalog__hero-copy">
                <h1 id="lm-katalog-title">Mau belanja apa hari ini?</h1>
                <p>Pilih produk, total dihitung otomatis, dan pembayaran dikonfirmasi admin.</p>
                <label class="lm-katalog__search" for="lm-cari-produk">
                    <svg class="lm-katalog__search-icon" aria-hidden="true"><use href="#lm-icon-search"></use></svg>
                    <span class="visually-hidden">Cari nama produk</span>
                    <input id="lm-cari-produk" type="search" placeholder="Cari nama produk..." autocomplete="off">
                </label>
            </div>
            <aside class="lm-katalog__promo" aria-label="Promo belanja">
                <span class="lm-katalog__promo-label">Promo belanja</span>
                <strong>Diskon 10%</strong>
                <p>Otomatis untuk subtotal Rp500.000 atau lebih.</p>
                <svg aria-hidden="true"><use href="#lm-icon-tag"></use></svg>
            </aside>
        </div>
    </section>

    <section class="lm-katalog__content" aria-label="Katalog produk">
        <nav class="lm-katalog__category-nav" aria-label="Filter kategori">
            <button class="lm-katalog__category is-active" type="button" aria-pressed="true">Semua</button>
            <button class="lm-katalog__category" type="button" aria-pressed="false">Pakaian</button>
            <button class="lm-katalog__category" type="button" aria-pressed="false">Aksesori</button>
            <button class="lm-katalog__category" type="button" aria-pressed="false">Makanan &amp; Minuman</button>
            <button class="lm-katalog__category" type="button" aria-pressed="false">Rumah Tangga</button>
            <button class="lm-katalog__category" type="button" aria-pressed="false">Sepatu</button>
            <button class="lm-katalog__category" type="button" aria-pressed="false">ATK</button>
        </nav>

        <div class="lm-katalog__results">
            <div class="lm-katalog__results-heading">
                <h2>Semua produk</h2>
                <div class="lm-katalog__controls">
                    <span>8 produk</span>
                    <label class="lm-katalog__sort">
                        <span>Urutkan</span>
                        <select aria-label="Urutkan produk">
                            <option>Paling relevan</option>
                        </select>
                    </label>
                    <label class="lm-katalog__availability">
                        <input type="checkbox" checked>
                        <span>Aktif</span>
                        Tersedia saja
                    </label>
                </div>
            </div>
            <div class="lm-katalog__loading" role="status" hidden>
                <span class="lm-katalog__spinner" aria-hidden="true"></span>
                <span>Memuat katalog produk...</span>
            </div>
            <div class="lm-katalog__grid">
                <article class="lm-produk lm-produk--card">
                    <div class="lm-produk__image"><img class="lm-produk__photo" src="https://cdn.wonder.so/images/01a1f6a4-a77f-74ad-9799-11da2ba8ac61/4686d9dccfd9f1e0dec32f028982d9057c73ed7d685509bd83a61d27f19ec0bd.jpg" alt="Kaos polos katun"></div>
                    <div class="lm-produk__details"><h3>Kaos Polos Katun</h3><p>Pakaian</p></div>
                    <div class="lm-produk__footer"><div><p class="lm-produk__price">Rp75.000</p><p class="lm-produk__stock">Stok 20</p></div><button class="lm-produk__add lm-produk__add--label" type="button" aria-label="Tambah Kaos Polos Katun ke keranjang"><svg aria-hidden="true"><use href="#lm-icon-plus"></use></svg><span>Tambah</span></button></div>
                </article>
                <article class="lm-produk lm-produk--card">
                    <div class="lm-produk__image"><img class="lm-produk__photo" src="https://cdn.wonder.so/images/01a1f6a4-a77f-74ad-9799-11da2ba8ac61/cd72ed767ee273432088ba775d44d45c3a74dfaf452d90a07ac2cc52beb76736.jpg" alt="Tas tote kanvas"></div>
                    <div class="lm-produk__details"><h3>Tote Bag Kanvas</h3><p>Aksesori</p></div>
                    <div class="lm-produk__footer"><div><p class="lm-produk__price">Rp89.000</p><p class="lm-produk__stock">Stok 14</p></div><button class="lm-produk__add lm-produk__add--label" type="button" aria-label="Tambah Tote Bag Kanvas ke keranjang"><svg aria-hidden="true"><use href="#lm-icon-plus"></use></svg><span>Tambah</span></button></div>
                </article>
                <article class="lm-produk lm-produk--card">
                    <div class="lm-produk__image"><img class="lm-produk__photo" src="https://cdn.wonder.so/images/01a1f6a4-a77f-74ad-9799-11da2ba8ac61/2ec3a0535ba4ca59e251a78470d05865a41f3f0e0c7fe171605f75e97dcab2a5.jpg" alt="Biji kopi arabika"></div>
                    <div class="lm-produk__details"><h3>Kopi Arabika 250 g</h3><p>Makanan &amp; Minuman</p></div>
                    <div class="lm-produk__footer"><div><p class="lm-produk__price">Rp68.000</p><p class="lm-produk__stock">Stok 32</p></div><button class="lm-produk__add lm-produk__add--label" type="button" aria-label="Tambah Kopi Arabika 250 g ke keranjang"><svg aria-hidden="true"><use href="#lm-icon-plus"></use></svg><span>Tambah</span></button></div>
                </article>
                <article class="lm-produk lm-produk--card">
                    <div class="lm-produk__image"><img class="lm-produk__photo" src="https://cdn.wonder.so/images/01a1f6a4-a77f-74ad-9799-11da2ba8ac61/f9df746852e4f2bfe672ec2c80de881ef4b828f384205bdf1910add53b57594f.jpg" alt="Botol minum stainless"></div>
                    <div class="lm-produk__details"><h3>Botol Minum Stainless</h3><p>Rumah Tangga</p></div>
                    <div class="lm-produk__footer"><div><p class="lm-produk__price">Rp129.000</p><p class="lm-produk__stock lm-produk__stock--low">Stok 9</p></div><button class="lm-produk__add lm-produk__add--label" type="button" aria-label="Tambah Botol Minum Stainless ke keranjang"><svg aria-hidden="true"><use href="#lm-icon-plus"></use></svg><span>Tambah</span></button></div>
                </article>
                <article class="lm-produk lm-produk--card">
                    <div class="lm-produk__image"><img class="lm-produk__photo" src="https://cdn.wonder.so/images/01a1f6a4-a77f-74ad-9799-11da2ba8ac61/a08ef261e2c6ed620ce4a8e4836294beb10588386b8894f2793fdedd459e8128.jpg" alt="Sneakers kanvas putih"></div>
                    <div class="lm-produk__details"><h3>Sneakers Canvas Putih</h3><p>Sepatu</p></div>
                    <div class="lm-produk__footer"><div><p class="lm-produk__price">Rp349.000</p><p class="lm-produk__stock lm-produk__stock--low">Sisa 6</p></div><button class="lm-produk__add lm-produk__add--label" type="button" aria-label="Tambah Sneakers Canvas Putih ke keranjang"><svg aria-hidden="true"><use href="#lm-icon-plus"></use></svg><span>Tambah</span></button></div>
                </article>
                <article class="lm-produk lm-produk--card">
                    <div class="lm-produk__image"><img class="lm-produk__photo" src="https://cdn.wonder.so/images/01a1f6a4-a77f-74ad-9799-11da2ba8ac61/4bb50b0201a415bd9cbfdade0f649563152a704fd6d21b3608dcdb6b3e721bb0.jpg" alt="Madu hutan"></div>
                    <div class="lm-produk__details"><h3>Madu Hutan 500 ml</h3><p>Makanan &amp; Minuman</p></div>
                    <div class="lm-produk__footer"><div><p class="lm-produk__price">Rp95.000</p><p class="lm-produk__stock">Stok 18</p></div><button class="lm-produk__add lm-produk__add--label" type="button" aria-label="Tambah Madu Hutan 500 ml ke keranjang"><svg aria-hidden="true"><use href="#lm-icon-plus"></use></svg><span>Tambah</span></button></div>
                </article>
                <article class="lm-produk lm-produk--card">
                    <div class="lm-produk__image"><img class="lm-produk__photo" src="https://cdn.wonder.so/images/01a1f6a4-a77f-74ad-9799-11da2ba8ac61/8cce8b26c11376b54e97759b1aa07e172fb538c5c2e06417fdcf95a5a3bc4baf.jpg" alt="Buku catatan kain"><span class="lm-produk__badge lm-produk__badge--out">Stok habis</span></div>
                    <div class="lm-produk__details"><h3>Buku Catatan Kain</h3><p>ATK</p></div>
                    <div class="lm-produk__footer"><div><p class="lm-produk__price lm-produk__price--out">Rp42.000</p><p class="lm-produk__stock lm-produk__stock--out">Tidak tersedia</p></div><button class="lm-produk__add lm-produk__add--disabled" type="button" aria-label="Buku Catatan Kain habis" disabled><span>Habis</span></button></div>
                </article>
                <article class="lm-produk lm-produk--card">
                    <div class="lm-produk__image"><img class="lm-produk__photo" src="https://cdn.wonder.so/images/01a1f6a4-a77f-74ad-9799-11da2ba8ac61/bdecc134366bf7607a07a4736cf8b66dba60ea104663fc1c8583e128efacaa52.jpg" alt="Lampu meja kayu"></div>
                    <div class="lm-produk__details"><h3>Lampu Meja Kayu</h3><p>Rumah Tangga</p></div>
                    <div class="lm-produk__footer"><div><p class="lm-produk__price">Rp215.000</p><p class="lm-produk__stock lm-produk__stock--low">Sisa 5</p></div><button class="lm-produk__add lm-produk__add--label" type="button" aria-label="Tambah Lampu Meja Kayu ke keranjang"><svg aria-hidden="true"><use href="#lm-icon-plus"></use></svg><span>Tambah</span></button></div>
                </article>
            </div>
        </div>
    </section>
</main>
