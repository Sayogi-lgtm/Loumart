<main class="lm-keranjang">
    <svg class="lm-keranjang__icon-sprite" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <symbol id="lm-cart-check" viewBox="0 0 24 24">
            <path d="m5 12 4 4L19 6"></path>
        </symbol>
        <symbol id="lm-cart-shirt" viewBox="0 0 24 24">
            <path d="M20.4 3.5 16 2a4 4 0 0 1-8 0L3.6 3.5a2 2 0 0 0-1.3 2.2l.6 3.5a1 1 0 0 0 1 .8H6v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V10h2.1a1 1 0 0 0 1-.8l.6-3.5a2 2 0 0 0-1.3-2.2Z"></path>
        </symbol>
        <symbol id="lm-cart-minus" viewBox="0 0 24 24">
            <path d="M5 12h14"></path>
        </symbol>
        <symbol id="lm-cart-plus" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14"></path>
        </symbol>
        <symbol id="lm-cart-trash" viewBox="0 0 24 24">
            <path d="M3 6h18m-2 0-.9 14H5.9L5 6m4 0V4h6v2m-5 4v6m4-6v6"></path>
        </symbol>
        <symbol id="lm-cart-info" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M12 11v5m0-8h.01"></path>
        </symbol>
        <symbol id="lm-cart-bag" viewBox="0 0 24 24">
            <path d="M6 2h12l3 4v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6l3-4Zm-3 4h18M16 10a4 4 0 0 1-8 0"></path>
        </symbol>
    </svg>

    <header class="lm-keranjang__header">
        <div class="lm-keranjang__header-inner">
            <div class="lm-keranjang__title">
                <h1>Keranjang belanja</h1>
                <p>1 produk siap di-checkout</p>
            </div>
            <nav class="lm-keranjang__stepper" aria-label="Langkah pemesanan">
                <ol>
                    <li class="is-complete">
                        <span class="lm-keranjang__step-icon"><svg aria-hidden="true"><use href="#lm-cart-check"></use></svg></span>
                        <span class="lm-keranjang__step-label">Katalog</span>
                    </li>
                    <li class="is-active" aria-current="step">
                        <span class="lm-keranjang__step-icon">2</span>
                        <span class="lm-keranjang__step-label">Keranjang</span>
                    </li>
                    <li>
                        <span class="lm-keranjang__step-icon">3</span>
                        <span class="lm-keranjang__step-label">Checkout</span>
                    </li>
                    <li>
                        <span class="lm-keranjang__step-icon">4</span>
                        <span class="lm-keranjang__step-label">Status</span>
                    </li>
                </ol>
            </nav>
        </div>
    </header>

    <section class="lm-keranjang__content" aria-label="Isi keranjang">
        <div class="lm-keranjang__items" data-cart-items>
            <article class="lm-keranjang__item">
                <div class="lm-keranjang__item-image">
                    <svg aria-hidden="true"><use href="#lm-cart-shirt"></use></svg>
                </div>
                <div class="lm-keranjang__item-main">
                    <p class="lm-keranjang__item-category">Pakaian</p>
                    <h2>Kaos Polos Katun</h2>
                    <p class="lm-keranjang__item-meta">Rp75.000 per buah <span aria-hidden="true">·</span> Stok tersedia 20</p>
                    <div class="lm-keranjang__item-actions">
                        <div class="lm-keranjang__quantity" aria-label="Jumlah barang">
                            <button type="button" aria-label="Kurangi jumlah Kaos Polos Katun">
                                <svg aria-hidden="true"><use href="#lm-cart-minus"></use></svg>
                            </button>
                            <span aria-live="polite">2</span>
                            <button type="button" aria-label="Tambah jumlah Kaos Polos Katun">
                                <svg aria-hidden="true"><use href="#lm-cart-plus"></use></svg>
                            </button>
                        </div>
                        <button class="lm-keranjang__remove" type="button" aria-label="Hapus Kaos Polos Katun dari keranjang">
                            <svg aria-hidden="true"><use href="#lm-cart-trash"></use></svg>
                        </button>
                    </div>
                </div>
                <p class="lm-keranjang__item-subtotal">Rp150.000</p>
            </article>

            <a class="lm-keranjang__continue" href="/">
                <span aria-hidden="true">←</span>
                Lanjut belanja
            </a>
        </div>

        <aside class="lm-keranjang__summary" aria-labelledby="lm-keranjang-summary-title" data-cart-summary>
            <h2 id="lm-keranjang-summary-title">Ringkasan keranjang</h2>
            <div class="lm-keranjang__summary-total">
                <span>Total sementara</span>
                <strong>Rp150.000</strong>
            </div>
            <div class="lm-keranjang__note">
                <svg aria-hidden="true"><use href="#lm-cart-info"></use></svg>
                <p>Belum termasuk diskon dan pajak 11%. Rincian lengkap muncul di langkah Checkout.</p>
            </div>
            <a class="lm-keranjang__checkout" href="/checkout">
                Checkout
                <span aria-hidden="true">→</span>
            </a>
            <p class="lm-keranjang__stock-note">Stok dicek ulang saat pesanan dibuat.</p>
        </aside>
    </section>

    <section class="lm-keranjang__empty" data-cart-empty hidden aria-labelledby="lm-keranjang-empty-title">
        <div class="lm-keranjang__empty-icon">
            <svg aria-hidden="true"><use href="#lm-cart-bag"></use></svg>
        </div>
        <h2 id="lm-keranjang-empty-title">Keranjang masih kosong</h2>
        <p>Yuk, cari barang yang kamu butuhkan di katalog.</p>
        <a class="lm-keranjang__checkout" href="/">Lihat katalog</a>
    </section>
</main>
