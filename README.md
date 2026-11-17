# Duncan Malcolm child theme

A WordPress child theme for [Blocksy](https://wordpress.org/themes/blocksy/), with an editable editorial homepage, writing and interview blocks, and a sticky contact bar.

## Installation

[Download duncan-malcolm-theme-v1.1.9.zip](https://github.com/duncan-m/personal-child-theme/raw/refs/heads/main/downloads/duncan-malcolm-theme-v1.1.9.zip)

Use this packaged ZIP for WordPress uploads. GitHub's **Code → Download ZIP** adds a repository-based folder name, which WordPress treats as a different theme.

The package preserves the original identity: folder `duncan-malcolm`, theme name `Duncan Malcolm`, parent `blocksy`, and text domain `duncan-malcolm`. Version 1.1.9 replaces the original 1.1.0 theme. During upload, choose **Replace current with uploaded** when WordPress offers it.

1. Install the free Blocksy parent theme.
2. Upload the packaged `duncan-malcolm` theme ZIP via **Appearance → Themes → Add New → Upload Theme**, then activate it. For a Git checkout, place this repository in `wp-content/themes/duncan-malcolm`.
3. Follow [START-HERE.md](START-HERE.md). The optional **Appearance → Duncan Malcolm Setup** action creates starter content and changes site settings when you apply it.

Requires WordPress 6.6+ and PHP 7.4+. Review [VALIDATION.md](VALIDATION.md) for testing scope and staging checks.

## Version 1.1.9

Reusable article styles now live in `assets/article.css`: standard and financial tables, scroll wrappers, callouts, figures, comparisons, definitions, numbered actions, checklists, sources, the supplied portfolio graphic and risk matrix, metric summaries, worked calculations, simple sequences and code. Existing `dm-payment-post` / `pp-*` markup remains supported; new articles use `dm-post` / `dm-*`. Shared typography follows the 0.9 scale, with a readable floor for small labels. Singular pages load the stylesheet after the site styles; editor styles import it too.

- [Post authoring guide and generation prompt](POST-AUTHORING.md)
- [Copyable component gallery](examples/article-components.html)
- [Supplied card-payments article without embedded CSS](examples/card-payments-post.html)

Install the update before removing the existing post’s `<style>` block. The supplied CSS-free copy preserves its wording/data; the theme does not rewrite the WordPress database. No setup reapplication is required.

Homepage headline caps are now 42px for the featured card and 28px for adjacent article links, both using the existing type scale and mobile overrides.

## Version 1.1.8

Individual posts no longer show the automatic excerpt under their title. The saved excerpt and homepage featured-card description remain available. This change is scoped to Blocksy’s single-post title description and does not edit post content or settings. No setup reapplication is required.

## Version 1.1.7

The featured essay now takes 60% of the available two-column writing layout, with 40% for the adjacent posts (after the existing gap). The mobile layout remains a single full-width column. The featured card always uses the decorative card graphic, regardless of any featured image assigned to its post. Featured-image settings on the posts themselves are preserved.

No setup reapplication is required.

## Version 1.1.6

The featured essay picker is now a single searchable dropdown. In **Appearance → Duncan Malcolm Setup → Featured homepage essay**, click the **Featured essay** field to open recent post names. Type in that field to see matching titles, click one and select **Save featured essay**. Arrow keys and Enter also select a result; Escape closes the dropdown and restores the selection.

Recent choices are embedded in the page and open immediately. Typing filters available titles while searching the full published-post catalogue. Clearing the field restores recent posts. Empty results and failed searches display messages; your saved selection is preserved. Search configuration travels with the field rather than depending on a separate global script. The native dropdown and server-side Search form remain as a fallback if the enhancement cannot initialise.

No setup reapplication is required. This release does not change public styling or your existing featured selection.

## Version 1.1.5

Set the featured homepage essay without Gutenberg:

1. Open **Appearance → Duncan Malcolm Setup**.
2. Under **Featured homepage essay**, browse the 20 most recent published writing posts or type into **Search post titles** to find older posts.
3. Choose the title and click **Save featured essay**.

Results include publication dates. Search is limited to titles and returns up to 20 eligible posts; refine the title if needed. Interviews (including descendant categories), drafts and pages are excluded. The selected post remains available when outside the latest results. Searching alone does not save anything; failed searches retain your choice. A Search button also works without JavaScript.

The setting overrides the writing block’s featured selection on the homepage only. **Automatic** uses the latest published writing post. **Keep existing homepage block setting** restores the original block setting. If the selected essay becomes ineligible, the homepage falls back to the latest writing post. Saving leaves homepage content, menus and other site settings intact. No setup reapplication is needed.

## Version 1.1.4

Standard posts and pages now share the editorial design: an ivory background, a 760px reading column, serif titles and section headings, navy body copy, orange links, and matching metadata, quotes, lists and media. These sizes retain the 0.9 typography scale. Site-level palette tokens override Blocksy’s later root defaults. The front page and Editorial Homepage template keep their existing layout. No setup reapplication is required.

## Version 1.1.3

Theme typography is 10% smaller across desktop and mobile, including the header, hero, editorial blocks, body copy and contact bar. Fixed sizes and responsive clamps share `--dm-type-scale: .9` in `assets/site.css`; layout dimensions and spacing are unchanged. The editor imports the same scaled stylesheet. No setup reapplication is required for this update.

## Version 1.1.2

Featured essay artwork now sits to the right of the copy, including uploaded featured images. On phones up to 480px wide it stacks beneath the copy. The Blocksy header matches the reference with an uppercase serif title, right-aligned navigation, navy contact button and inset divider.

For an existing site, the CSS applies on update. If your header has a different builder layout, use **Appearance → Duncan Malcolm Setup → Reapply layout and links** with your contact URL to restore the reference arrangement. Reapplying also resets starter menu/header customisations. The contact button needs a contact URL. Mobile keeps Blocksy’s menu trigger.

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
git archive --format=zip --prefix=duncan-malcolm/ --output=duncan-malcolm-theme-v1.1.9.zip HEAD -- assets blocks patterns templates examples functions.php inc-editorial.php inc-featured.php inc-setup.php style.css theme.json LICENSE.txt README.md POST-AUTHORING.md START-HERE.md VALIDATION.md validation-results.json
```

Theme source files live at the repository root. No build step or companion plugin is required.
