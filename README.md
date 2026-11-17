# Duncan Malcolm child theme

A WordPress child theme for [Blocksy](https://wordpress.org/themes/blocksy/), with an editable editorial homepage, writing and interview blocks, and a sticky contact bar.

## Installation

1. Install the free Blocksy parent theme.
2. Upload the packaged `duncan-malcolm` theme ZIP via **Appearance → Themes → Add New → Upload Theme**, then activate it. For a Git checkout, place this repository in `wp-content/themes/duncan-malcolm`.
3. Follow [START-HERE.md](START-HERE.md). The optional **Appearance → Duncan Malcolm Setup** action creates starter content and changes site settings when you apply it.

Requires WordPress 6.6+ and PHP 7.4+. Review [VALIDATION.md](VALIDATION.md) for testing scope and staging checks.

## Version 1.1.1

Merged the supplied CSS into `assets/site.css`:

- Company strip padding: `20px 5px`.
- Writing section padding: `60px 0`.
- Section heading bottom margin: `30px`.
- Interviews top padding: `55px`.

Existing mobile overrides are preserved. The editor imports the same stylesheet. Front-end assets already use modification times for cache invalidation.

## Package from a checkout

From the repository root:

```sh
git archive --format=zip --prefix=duncan-malcolm/ --output=duncan-malcolm-theme-v1.1.1.zip HEAD
```

Theme source files live at the repository root. No build step or companion plugin is required.
