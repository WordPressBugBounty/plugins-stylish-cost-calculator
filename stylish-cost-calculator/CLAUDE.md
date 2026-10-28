# AGENTS.md

## Project
- Stylish Cost Calculator is a WordPress plugin for calculator/quote forms. Main bootstrap: `stylish-cost-calculator.php`; AJAX-heavy legacy surface: `stylish-cost-ajax.php`.
- Runtime is mostly PHP + WordPress hooks/shortcodes/AJAX, with plain JS/jQuery in `assets/js/`, admin MVC-style code in `admin/`, frontend rendering in `frontend/`, Elementor/Gutenberg integrations, cron notifications, and CSS/assets under `assets/`.
- WordPress.org release metadata lives in `readme.txt`; avoid version/readme/changelog churn unless the task is a release/version update.
- The setup wizard creates calculators through `sccCalculatorOp` / `load_by_params`, using the `passed` / `data` response and saving recommendations in browser `wizardQuizData` before opening the editor. It has no PDF setup-guide export or bundled PDF engine.

## Setup And Commands
- Use Node.js 24 LTS (`nvm use`) and PHP 8.2+ for development tools. These development requirements do not change the plugin's PHP 7.0 runtime minimum; Composer resolves dev packages against PHP 8.2.
- Install locked dev tools with `npm ci`. The postinstall runs `composer install --no-interaction`; prepare installs Husky. CI sets `HUSKY=0` to skip hook installation.
- The tracked `.husky/pre-commit` runs `npm run lint-staged`; its script uses `--hide-unstaged` to preserve unrelated work. Staged PHP is formatted and then checked, because PHP-CS-Fixer's `fix` command alone does not fail on invalid syntax. Format PHP directly with `php ./vendor/bin/php-cs-fixer fix --config ./.php-cs-fixer.php --verbose --allow-risky=yes`; never format the whole legacy repo as part of a tooling update.
- Run the CI compatibility check, when Composer deps are installed, with `./vendor/bin/phpcs -q --ignore=*/*.js --standard=./phpcs.backwards-compat.xml .`.
- Run the broader local PHP sniff, when needed, with `./vendor/bin/phpcs --standard=./phpcs.xml .`.
- Run `npm test` for wizard checks, owned-PHP syntax/compile diagnostics, strict runtime fixtures and bundled UI-library browser-global/plugin API checks. It needs only Node.js and PHP on PATH; no dependency install, WordPress, database or network requests are needed. Run `npm run test:php` for PHP checks alone; set `PHP_BINARY` to select a PHP executable.
- `.github/workflows/run-backwards-compatibility-phpcs.yml` runs PHP regression/WordPress integration on PHP 8.2, 8.3, 8.4 and 8.5, alongside the PHP 7.0+ compatibility scan and production build/audit. Build tools use PHP 8.2 and Node.js from `.nvmrc`.
- WordPress integration uses only CI's disposable `scc_php_compat` MySQL database with `SCC_WP_COMPAT_FIXTURE=1`; its MU fixture blocks HTTP/mail and fails on plugin diagnostics. Never run that fixture against an existing site/database.
- Bundled Sortable and Tom Select come from pinned official npm distributions; provenance is in each library's `README.md`. Historical Tom Select `base` paths contain the Popular bundle because SCC requires `dropdown_input` and `remove_button`. Keep library and caller asset versions synchronized for cache invalidation. Use `controlInput: null` for non-searchable controls; disabling `control_input` prevents dropdown focus.

## Coding Rules
- Prefer existing WordPress APIs, hooks, filters, escaping/sanitization, nonce, options, cron, upload, mail, HTTP, and `$wpdb` helpers over generic PHP alternatives.
- New PHP files must start with an `ABSPATH` guard. Use kebab-case PHP filenames; class files use `class-*.php`. Existing legacy files may not match this.
- For global PHP symbols use the existing `scc_` / `SCC_` style. In namespaced PHP, match the nearby class/method naming before adding a new convention.
- JS uses plain browser JS/jQuery; use camelCase variables, existing `scc*` function naming, and do not introduce a bundler or framework for a small change.
- CSS classes should be kebab-case and prefer the `scc-` prefix for SCC-owned UI.
- Database table names are SCC-owned and prefixed in the existing schema; use snake_case columns.

## Security And Data
- Treat every `$_GET`, `$_POST`, `$_REQUEST`, upload, webhook, and AJAX payload as untrusted. Unslash, sanitize/validate, authorize, and verify nonces before mutating state.
- Use `$wpdb->prepare()` for interpolated SQL and keep direct SQL narrowly scoped. Do not concatenate request data into queries.
- Use `wp_send_json_success()` / `wp_send_json_error()` for AJAX responses where practical, and avoid leaking secrets, license keys, tokens, raw SQL, stack traces, or customer lead/quote data to logs or responses.
- Escape output at the boundary with the appropriate WordPress escaping helper unless a local rendering path already safely handles the value.

## Change Boundaries
- Do not edit `vendor/`, `node_modules/`, or Composer-installed dependencies. Treat minified third-party libraries under `lib/` as bundled vendor code unless the task explicitly targets them.
- Keep edits focused. This repo has large legacy files; avoid opportunistic rewrites, broad reformatting, or moving code solely to satisfy style preferences.
- Preserve WordPress/PHP backwards compatibility. `readme.txt` currently declares Requires PHP 7.0; `phpcs.backwards-compat.xml` checks PHP 7.0+.
- Ask before overwriting local environment/config files. Never add fake dev/prod data paths; mocks belong only in tests.

## Agent Workflow
- Before changing code, inspect the touched feature's controller/view/model/JS/CSS path and reuse nearby helper functions, AJAX actions, option names, hooks, and response shapes.
- For external APIs or WordPress/plugin behavior that is not already clear from this repo, check current official documentation first.
- After changes, report the exact verification performed. For code changes, include a concise Developer QA Checklist with shell/API/log/DB checks relevant to the diff.
- If a request is ambiguous enough to affect architecture, data migration, payments, licensing, deletion, email/SMS delivery, or production rollout, state the assumption before implementing.
