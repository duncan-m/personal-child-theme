# Validation — 1.1.2

Current checks: all PHP source files parsed with php-parser in PHP 7.4 mode; both JavaScript files passed node --check; CSS parsed with tinycss2; all JSON parsed; installable ZIP integrity verified.

Headless Chromium layout fixtures using the downloaded Blocksy parent stylesheet passed at 1536, 1280, 1024, 900, 768, 681, 680, 540, 480, 375 and 320px. Checks covered right-hand artwork above 480px, stacked artwork on narrow phones, no page overflow, header height and title bounds. An uploaded featured image was also checked in the right-hand column. Desktop/mobile fixture screenshots were inspected. These fixtures use representative markup, not a running WordPress site.

A live WordPress/Blocksy staging check remains required for header builder configuration, menu interaction, the editor, custom logos, actual content and contact destinations. No WordPress runtime checks were rerun for 1.1.2. validation-results.json remains the supplied historical 1.1.0 results.

## Previous checks — 1.1.1

This update applies the supplied CSS to assets/site.css and bumps the theme version. The stylesheet is byte-for-byte identical to the supplied CSS. Compared with 1.1.0, only four spacing declarations change; mobile overrides are preserved. PHP and JavaScript source files are unchanged.

Current checks: JavaScript syntax, JSON parsing, CSS parsing, exact CSS comparison, unchanged PHP source comparison and installable ZIP integrity. PHP is unavailable in this workspace, so PHP lint and WordPress runtime tests were not rerun. Browser, responsive and block-editor checks require a WordPress staging site with Blocksy installed.

The results below and validation-results.json were supplied with version 1.1.0 and are historical; they were not independently rerun for 1.1.1.

## Supplied validation — 1.1.0

Tested with WordPress 7.1.4, Blocksy 2.1.62, PHP 8.3.36 and SQLite in the official WordPress Playground runtime, with WP_DEBUG enabled. No companion plugin was installed in this run.

All 30 runtime checks passed. These covered starter pages and example content, Reading and menu assignments, repeated setup without duplicate pages/posts or lost About content, restoring previous Reading/site-title settings, editorial queries and exclusions, safe handling of draft/invalid featured selections, registered blocks, escaped subtitle output, PHP parsing, exactly one homepage H1, contact footer and header contact button rendering. Rendered HTML contained no PHP warnings/fatal errors. Both JavaScript files passed syntax checks. ZIP structure was verified.

Remaining staging checks: real browser visual comparison, responsive overflow, keyboard/zoom behaviour, block-editor interaction, media replacement, actual link destinations, performance and parent-theme update. The earlier browser socket restriction prevents claiming these were tested. Review with your actual assets before launch. Declared minimum WordPress/PHP versions are not a tested version matrix.

The setup screen submits nonce-protected actions restricted to administrators. Programmatic setup/restore logic was tested; the interactive admin-form workflow still needs a staging check. Example bodies and fixture contact destinations are never silently inserted: optional examples are created only by the setup button, and no fixture contact URL is included in the production theme.
