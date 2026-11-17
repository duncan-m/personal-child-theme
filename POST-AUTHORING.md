# Creating posts for the Duncan Malcolm theme

Use this guide with theme version 1.1.9 or later. The theme supplies all presentation styles. New posts do not need a `<style>` block, CSS files pasted into the body, external fonts or scripts.

## WordPress workflow (Gutenberg is not required)

1. Create or edit a normal WordPress post. Put its title in the WordPress title field.
2. Put a short, plain-language summary in the Excerpt field. It is used by the homepage featured card, not printed beneath the individual post title.
3. Paste the generated article body into the Classic Editor's **Text** tab, or a WPBakery **Raw HTML** element that preserves the markup. Do not paste HTML into Visual mode. Use the editor preview to check the result; Classic Editor's editing surface is not a complete preview of the front-end layout.
4. Wrap the body in `<div class="dm-post">…</div>`. Use normal paragraphs, lists and H2/H3 headings. Do not add another H1, page header, site footer or full HTML document.
5. Preview at desktop and phone widths. Check links, table scrolling, labels, calculations and sources, then publish normally.
6. To feature the post, open **Appearance → Duncan Malcolm Setup → Featured homepage essay**, search its title, choose it and save.

## Ready-to-copy examples

- [Article components](examples/article-components.html): a gallery of supported snippets. Copy only the components useful to your article; the gallery is not a template requiring every component.
- [Existing card-payments article without embedded CSS](examples/card-payments-post.html): the supplied article's body and classes, with only its `<style>` block removed.

Both files are HTML fragments intended for the WordPress post body. Preview them through the theme; opening the raw HTML file alone will not load its styles. All invented numbers in the component gallery are explicitly illustrative. Replace them with verified data or retain that label.

## Supported components

| Purpose | Markup/classes |
| --- | --- |
| Article wrapper | `div.dm-post` |
| Opening lead; small eyebrow | `p.dm-lead`; `span.dm-kicker` |
| Dark key takeaway | `aside.dm-takeaway` with kicker and paragraph |
| Assumption, caveat or source note | `aside.dm-note` containing paragraphs |
| Framed chart, diagram or table | `figure.dm-figure`, optional `p.dm-figure-title` and `p.dm-figure-intro`, then `figcaption` |
| Standard table | Ordinary `table`, `caption`, `thead`, `tbody`, scoped `th` and `td`; optional `tfoot` for totals |
| Numeric/currency cells | `dm-number` on each numeric `th`/`td`; `dm-financial-table` on the table for identification |
| Wide table | `div.dm-table-scroll` around it; optional `table.dm-table-wide` when a minimum width is needed |
| Risk matrix | `table.dm-matrix` and cell classes `dm-risk-0` through `dm-risk-6` (lower to higher risk) |
| Matrix legend | `div.dm-matrix-key`, a `span.dm-spectrum` and visible lower/higher labels |
| Conceptual portfolio | `div.dm-portfolio-grid`, `span.dm-tile`, optional `dm-tile-medium` or `dm-tile-high`; see the gallery's legend |
| Two-way comparison | `dl.dm-comparison` with `div` groups containing `dt` and `dd` |
| Definitions/modifiers | `dl.dm-definitions` with the same definition-list structure |
| Numbered actions | `ol.dm-steps`; each `li` may contain an H3, paragraphs and `div.dm-action` |
| Review questions | `aside.dm-prompt` with `ul.dm-checklist` |
| Metric summaries | `dl.dm-metrics`, grouped `dt`/`dd` values and a `small` context line |
| Worked calculation | `div.dm-formula` with normal paragraphs; use plain text maths |
| Simple sequence | `ol.dm-flow` with `li > span`; explain what the sequence/arrows mean in a figure caption |
| Code/API payload | `pre > code`, escaped HTML; optionally make a scrollable `pre` keyboard focusable with `tabindex="0"` and a descriptive `aria-label` |
| Closing statement | `p.dm-close` |
| Sources/footnotes | `div.dm-sources` with a heading/label and ordered list |

Existing `dm-payment-post` and `pp-*` classes remain supported for the supplied article. Use the canonical `dm-*` names for new work. Basic headings and tables also receive shared defaults outside the wrapper.

### Tables

Use actual table markup, not a grid of paragraphs. Give the table a descriptive `caption`; use `<th scope="col">` for column headings and `<th scope="row">` for row labels. Wrap wide tables as follows:

```html
<div class="dm-table-scroll" role="region" tabindex="0" aria-label="Processing fee comparison">
  <table class="dm-financial-table">
    <caption>Processing fees — GBP, reporting period and assumptions here</caption>
    <thead><tr><th scope="col">Item</th><th scope="col" class="dm-number">Amount</th></tr></thead>
    <tbody><tr><th scope="row">Example fee</th><td class="dm-number">£1.70</td></tr></tbody>
  </table>
</div>
```

That amount is a markup example, not a market rate. Use `dm-number` for values that should align numerically. Keep the currency and unit explicit, use consistent precision, distinguish 0 from unknown/not applicable, and explain deductions or negative values in text. Use `tfoot` for totals and verify the arithmetic.

Only add `dm-table-wide` when a table genuinely needs horizontal space. A risk matrix has its own width and cell formatting; do not apply `dm-matrix` just to get a coloured ordinary table. Use a nearby `p.dm-scroll-hint` to explain scrolling when useful.

### Graphics and evidence

- Every figure needs a clear title or accessible label, a caption explaining what readers should take from it, and a source when it presents factual data.
- State currency, units, reporting window, population and denominators. For cohorts, state maturity and any incomplete observation period.
- Label conceptual/illustrative figures. The portfolio tiles and heatmap colours do not compute risk, probabilities or financial outcomes. They must not imply observed proportions unless the data actually supports them.
- Use labels as well as colour. Keep risk severity separate from profitability or positive/negative change.
- The simple flow component represents sequence. A real payment diagram may need separate message, funds and liability flows. Do not suggest a simplified list is a complete payment architecture.
- Trend charts, waterfalls, complex timelines and network diagrams still need their own data and accessible SVG/image or appropriate chart output. Place them in `dm-figure`; do not invent CSS classes and assume the theme will generate the chart. Include useful alt text and a textual summary or data table where needed.
- Avoid tiny chart text. Shared small labels have a readable floor rather than blindly applying the 0.9 typography scale to everything.

### Sources and references

Cite factual claims with real, verified links. Do not invent publication dates, research, quotes, figures, payment-scheme rules or regulatory requirements. For rules and fees, state the relevant date, jurisdiction, scheme/provider and exceptions. Clearly separate facts, estimates, assumptions and opinion.

For footnotes, use unique IDs based on the article slug, e.g. `risk-source-1` and `risk-ref-1`. Put a numbered link in `sup`, and a return link in the source entry. The theme styles these elements but does not generate citations or verify sources.

## Copy-and-paste prompt for generating a new article

Paste the brief below into your writing tool together with this guide and, when useful, the component example file:

```text
Create a WordPress article for my Duncan Malcolm theme (v1.1.9+).

Topic: [topic]
Audience: [audience and assumed knowledge]
Purpose / argument: [what the reader should understand or do]
Length: [approximate words]
Verified source material: [links, notes and data]
Voice: clear, direct British English; explain technical terms; avoid hype.

Return:
1. A suggested WordPress title as plain text.
2. A short suggested Excerpt as plain text for the homepage card.
3. The article body as one copyable HTML fragment inside <div class="dm-post">.

Follow the supplied POST-AUTHORING.md and examples. Use normal semantic HTML and
only the documented dm-* component classes. No <style>, style attributes,
JavaScript, external fonts, full-page scaffolding or duplicate H1. Do not paste
the title or Excerpt into the body automatically.

Choose components only where they help explain the argument. Use the shared
financial table, figure, note, comparison or step styles where appropriate.
Include caption/headers/units in tables and a keyboard-focusable scroll wrapper
for wide tables. Keep numbers right-aligned with dm-number and verify totals.

Do not fabricate sources or data. Mark illustrative calculations explicitly.
For payments/finance claims, state relevant dates, scope and assumptions.
Use text labels alongside colour. Do not imply a heatmap is a calibrated model.
If a required chart cannot be represented with the supported components, explain
what verified data/asset is needed instead of inventing unsupported classes.

After the HTML, list any facts, sources or assumptions that still need checking.
Do not put that editorial checklist into the publishable article body.
```

## Migrating the supplied existing post

Install the updated theme first. Then either remove only the existing opening `<style>…</style>` block from the post, or replace the body with [the CSS-free copy](examples/card-payments-post.html). Keep its `dm-payment-post` wrapper and `pp-*` classes; the theme now supplies their styles.

The copy preserves the supplied wording and data. It is not a fresh content/fact review. Save a revision and compare the preview before publishing, particularly the matrix, captions, sources and mobile scrolling. Do not reapply starter site setup for this migration.

## Where styles live

- `assets/site.css`: global site, homepage and standard document typography.
- `assets/article.css`: tables and reusable article components, including legacy aliases.
- `assets/editor.css`: imports both stylesheets for supported editor previews.

Adjust shared styles in the child theme, not in individual posts. The theme does not automatically rewrite or remove existing embedded CSS from the WordPress database.
