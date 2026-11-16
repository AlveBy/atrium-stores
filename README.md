# Atrium Stores

Stores for [Atrium](https://github.com/AlveBy/atrium): name, picture, address, phones, e-mail, opening date, a point on the map and a description. With the public theme each store has a page at `/stores/{code}`.

## Install

```bash
composer require alveby/atrium-stores
php artisan atrium:module:install alveby/atrium-stores
```

Add `--demo` to fill it with two stores. With [`alveby/atrium-catalog`](https://github.com/AlveBy/atrium/tree/main/packages/catalog) installed, a product says in which stores it can be bought. The catalog keeps that list itself, so either module works without the other.

To move the pages, list `Atrium\Stores\Models\Store` in `config/atrium.php` under `public_urls`.

## Contributing

This repository is a read-only copy of `packages/stores` in [AlveBy/atrium](https://github.com/AlveBy/atrium). Issues and pull requests go there.

## License

MIT
