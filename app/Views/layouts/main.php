<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($judulHalaman, ENT_QUOTES, 'UTF-8') ?> | LouMart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <header class="lm-navbar">
        <nav class="lm-navbar__inner" aria-label="Navigasi utama">
            <a class="lm-navbar__brand" href="/" aria-label="LouMart, ke katalog">
                <img class="lm-navbar__logo" src="/assets/img/logo.svg" alt="LouMart">
            </a>

            <div class="lm-navbar__links">
                <a class="lm-navbar__link<?= $menuAktif === 'katalog' ? ' is-active' : '' ?>" href="/"<?= $menuAktif === 'katalog' ? ' aria-current="page"' : '' ?>>
                    Katalog
                </a>
                <a class="lm-navbar__link<?= $menuAktif === 'keranjang' ? ' is-active' : '' ?>" href="/keranjang"<?= $menuAktif === 'keranjang' ? ' aria-current="page"' : '' ?>>
                    <svg aria-hidden="true" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h3l2.7 13.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6L22 7H5"></path>
                    </svg>
                    <span>Keranjang</span>
                    <span class="lm-navbar__badge" aria-label="0 barang di keranjang">0</span>
                </a>
            </div>

            <a class="lm-navbar__admin<?= $menuAktif === 'admin' ? ' is-active' : '' ?>" href="/admin/produk"<?= $menuAktif === 'admin' ? ' aria-current="page"' : '' ?>>
                <svg aria-hidden="true" viewBox="0 0 24 24">
                    <path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z"></path>
                    <path d="m19.4 15 .1.1a2 2 0 0 1-2.8 2.8l-.1-.1a2 2 0 0 0-3.4 1.4v.3a2 2 0 0 1-4 0v-.2a2 2 0 0 0-3.4-1.4l-.1.1a2 2 0 0 1-2.8-2.8l.1-.1a2 2 0 0 0-1.4-3.4h-.3a2 2 0 0 1 0-4h.2a2 2 0 0 0 1.4-3.4l-.1-.1a2 2 0 0 1 2.8-2.8l.1.1a2 2 0 0 0 3.4-1.4v-.3a2 2 0 0 1 4 0v.2a2 2 0 0 0 3.4 1.4l.1-.1a2 2 0 0 1 2.8 2.8l-.1.1a2 2 0 0 0 1.4 3.4h.3a2 2 0 0 1 0 4h-.2a2 2 0 0 0-1.4 3.4Z"></path>
                </svg>
                <span>Admin</span>
            </a>
        </nav>
    </header>

    <?php if ($konten !== ''): ?>
        <?= $konten ?>
    <?php else: ?>
        <main class="lm-page-placeholder">
            <div class="lm-page-placeholder__inner">
                <h1><?= htmlspecialchars($judulHalaman, ENT_QUOTES, 'UTF-8') ?></h1>
                <p>Halaman ini sedang disiapkan.</p>
            </div>
        </main>
    <?php endif; ?>

    <footer class="lm-footer">
        <div class="lm-footer__inner">
            <span class="lm-footer__brand">LouMart</span>
            <span>Belanja mudah, harga jelas.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
