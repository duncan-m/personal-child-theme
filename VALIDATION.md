# Validation — 1.1.5

The new selector is separate from the existing starter-setup form. It stores a theme setting and applies it only to homepage writing-block rendering. Both search and save require administrator permissions and valid nonces. Search queries target published post titles, exclude interviews and descendant categories, order by publication date/ID, and return at most 20 choices.

PHP 8.3.33 via PHP-WASM with stubbed WordPress APIs passed 28 behaviour checks: recent results, older-title search, decoded titles, eligible/ineligible selections, save permissions and nonces, safe rejection without losing the prior choice, homepage override, non-homepage preservation, adjacent-list exclusion, saved older selection, fallback after unpublishing, automatic mode and restoring the existing block setting. These are isolated tests, not a full WordPress database/runtime test.

Headless Chromium checks exercised the PHP-rendered form with mocked AJAX responses: older-title results, retaining unsaved choices, no matches, network failure, text-only title rendering, stale-response protection, clearing search to recent posts, preserving Automatic mode, and no writes during search. The form passed overflow checks at 1280, 375 and 320px, with no browser JavaScript errors. New JavaScript syntax and PHP 7.4 parser checks passed. JSON and installable ZIP identity/integrity checks passed.

No live site changes or full WordPress admin workflow tests were performed. Install the update and use Save featured essay; do not reapply starter setup for this feature.

## Previous checks — 1.1.4

Fetched the live HTML and stylesheets for the About page and payment-fraud post supplied by the user. The child stylesheet was loading, but standard page/post title sizes and palette inherited Blocksy defaults. This release adds dm-document for standard singular posts/pages (excluding the front page and Editorial Homepage template), scoped reading-layout styles and body-level palette tokens.

Headless Chromium checks against captured HTML and styles (with the updated CSS and body class applied locally) passed at 1536, 1280, 900, 680, 375 and 320px for both URLs: reading column at most 760px, serif title, navy 16.2px body copy, orange article links and no horizontal overflow. Desktop screenshots were inspected. External images/scripts were blocked in these fixtures. PHP syntax parsing, CSS parsing and JSON parsing passed; ZIP integrity and original theme identity verified.

These checks did not deploy the update to the live site or run a WordPress runtime. Review the installed update with real media, plugins and the editor. The article includes imported nested entry markup and archived external image URLs; content and destinations were preserved.

## Previous checks — 1.1.3

All 35 font-size declarations (including Blocksy entry-content font-size) now use the shared 0.9 scale. Headless Chromium computed-style comparisons confirmed 18 representative text elements render at exactly 90% of their 1.1.2 sizes (within 0.01px) at 320, 375, 680, 900, 1280 and 1536px. Responsive layout fixtures also passed the eleven viewport widths listed below. CSS parsing and ZIP integrity checks passed. PHP and JavaScript source are unchanged from 1.1.2.

The update ZIP retains the original duncan-malcolm folder, Duncan Malcolm theme name, Blocksy parent and text domain, with Version 1.1.3. No live WordPress runtime checks were performed for this update.

## Previous checks — 1.1.2

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
