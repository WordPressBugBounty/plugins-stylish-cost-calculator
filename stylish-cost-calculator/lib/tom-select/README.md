# Bundled Tom Select

- Version: **2.6.2** (Apache-2.0).
- Source: <https://github.com/orchidjs/tom-select/releases/tag/v2.6.2>.
- Distribution: `tom-select@2.6.2` from npm.
- npm tarball integrity: `sha512-eiXIkk82XzzUTOVQDFpy+ZGejuqdTvMukyUyeZBQaKnqW42UoTiTZnrod/eX0rkqJEc4rZY9DZ3zdjV07i29wQ==`.

| Local file | Upstream distribution |
| --- | --- |
| `tom-select.base.js` | `dist/js/tom-select.popular.js` |
| `tom-select.base.min.js` | `dist/js/tom-select.popular.min.js` |
| `tom-select.complete.min.js` | `dist/js/tom-select.complete.min.js` |
| `tom-select.css`, `tom-select-quotes.css` | `dist/css/tom-select.css` |
| `../../assets/css/tom-select.css` | Same CSS, followed by the existing SCC admin hover color |

Source-map references and trailing whitespace are removed from the upstream
files; source maps are not shipped. Keep SCC overrides outside the library JavaScript.

The historical **base** filenames are retained for WordPress/cache exclusions,
but intentionally contain the official **Popular** bundle. SCC needs
`dropdown_input` for frontend lists with more than seven options and
`remove_button` for the admin country selector. Do not replace these files with
the plugin-free Base distribution. The quote viewer keeps the Complete bundle.
All three JavaScript files expose the same `TomSelect` API.

Keep the script/style versions in `stylish-cost-calculator.php`,
`class-pages-breadcrumbs.php` and `calculatorQuotes.php` synchronized when
upgrading, so browsers invalidate cached assets. Check frontend searching,
selection/price updates, keyboard navigation, wizard industries, admin selectors
and quote-column removal after an upgrade.

The SCC frontend/backend script versions also include `-tom-select-2.6.2` to
prevent mixing cached legacy initializers with the new library. Non-searchable
controls must use `controlInput: null`; disabling `control_input` prevents newer
Tom Select versions from receiving the focus required to open the dropdown.
