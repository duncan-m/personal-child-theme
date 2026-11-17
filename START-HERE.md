# Duncan Malcolm theme — version 1.1.6

This ZIP is the theme itself. Upload it directly through Appearance → Themes → Add New → Upload Theme. Do not extract it first. Keep the free Blocksy parent theme installed. No separate plugin is required.

## One setup screen

1. Install/replace and activate this child theme.
2. Open Appearance → Duncan Malcolm Setup.
3. Enter the site name, contact URL (email link or contact page) and optional LinkedIn URL.
4. Leave the example-content option checked if you want the mockup’s articles/interviews populated.
5. Click Apply starter setup.

Setup creates published starter Home, Writing and About pages, assigns the homepage and posts archive, creates an Interviews category, configures desktop/mobile menus and the Blocksy header, and enables the navy sticky footer. It applies the reference CSS, including the stone company band and updated Writing & ideas spacing.

These are deliberate site-setting changes made only when you press the button. The setup screen backs up the previous Reading settings, site title and child-theme settings. Restore previous settings reverses those configuration changes without deleting created content. New starter pages do not overwrite existing pages. Reapplying uses the recorded starter pages and posts and preserves edited page/post content; it resets the starter header/menu layout and links.

If the previous DM Editorial plugin is installed, setup deactivates it; its post metadata is retained. The blocks now live inside the child theme. This means switching to another theme will require moving/reactivating equivalent block functionality to keep their live output. Standard posts and their metadata remain in WordPress.

## Finish the assets and copy

Use Edit homepage on the setup screen. Hero text, portrait/caption and company strip are editable WordPress blocks. Replace the portrait placeholder with your photograph. Replace the four company-name paragraphs with Image blocks inside the existing logo grid. Use genuine monochrome PNG/WebP logos, or an approved sanitised SVG workflow. No genuine logo/portrait files are included, and nothing was cropped out of the mockup.

The example posts are published only if you select that option. Their bodies clearly say they are starter examples. Replace those bodies, review the About text and add real interview destinations before launch. The homepage uses the mockup’s titles and featured-essay excerpt; they are not completed articles.

Choose the featured essay in Appearance → Duncan Malcolm Setup → Featured homepage essay, even with Gutenberg disabled. Click the Featured essay field to open a dropdown of recent published writing posts with dates. Type in the same field to see matching post titles, click a result and then click Save featured essay. Older titles are searched as you type; clearing the field restores recent posts. Saving does not reapply setup, change menus or edit homepage content. Automatic uses the latest eligible post; Keep existing homepage block setting restores the original block-controlled choice. With Gutenberg enabled, the Writing & ideas block’s settings remain available when the setup selector is set to Keep existing homepage block setting. Use the post Excerpt panel for featured description and the Editorial details box for subtitle/optional external interview URL. Interviews use the category slug `interviews`. They are excluded from writing queries, including the Writing archive. The featured essay is excluded from the adjacent three-post list.

## Further changes

Use the setup form to update links and reapply the header layout. The footer links can also be edited in Appearance → Customise → Duncan Malcolm. After changing a contact destination there, update the header button in Blocksy’s header builder too, or reapply through the setup screen. Blocksy’s builder remains available for further header changes.

CSS tokens and layout rules are in assets/site.css. The editor shares those styles. System Georgia and system sans-serif fonts are used; no external fonts are loaded. Editorial lists use server-rendered blocks included in inc-editorial.php. No parent templates are copied; templates/editorial-home.php is a thin additional page template around editable block content.

Use the navy contact bar checkbox in the Customiser to restore Blocksy’s standard footer. The custom bar reserves space for its measured height and becomes compact on mobile. No newsletter signup or hero action buttons are included.

Read VALIDATION.md before launch. Review the result on staging with your real images and content. This theme ZIP now includes the setup code and optional starter content; the resulting WordPress database and media uploads still belong to your site and need normal backups.
