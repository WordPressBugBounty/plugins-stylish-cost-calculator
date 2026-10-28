# Bundled SortableJS

- Version: **1.15.7** (MIT).
- Source: <https://github.com/SortableJS/Sortable/releases/tag/1.15.7>.
- Distribution: `sortablejs@1.15.7` from npm; `Sortable.min.js` is copied unchanged to `sortable.min.js`.
- npm tarball integrity: `sha512-Kk8wLQPlS+yi1ZEf48a4+fzHa4yxjC30M/Sr2AnQu+f/MPwvvX9XjZ6OWejiz8crBsLwSq8GHqaxaET7u6ux0A==`.
- Keep the WordPress script version in `class-page-edit-calculator.php` synchronized to invalidate browser caches.

The calculator editor uses fallback dragging, a dedicated handle, filtered headers,
and shared groups to move elements between subsections. Check that element order
and subsection membership survive a page reload after upgrading.
