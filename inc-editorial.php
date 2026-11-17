<?php
/** Built-in editorial blocks: no companion plugin required. */
defined('ABSPATH') || exit;
add_action('init', function () {
    wp_register_script('dm-theme-editorial-blocks', get_stylesheet_directory_uri() . '/blocks/editor.js', array('wp-blocks','wp-element','wp-block-editor','wp-components','wp-data','wp-core-data','wp-server-side-render','wp-i18n'), filemtime(__DIR__ . '/blocks/editor.js'), true);
    foreach (array('writing','interviews') as $name) {
        if (WP_Block_Type_Registry::get_instance()->is_registered('dm/' . $name)) { unregister_block_type('dm/' . $name); }
        register_block_type(__DIR__ . '/blocks/' . $name, array('editor_script' => 'dm-theme-editorial-blocks', 'render_callback' => 'dm_theme_editorial_render_' . $name));
    }
}, 20);
function dm_theme_editorial_interview_id() {
    $term = get_category_by_slug('interviews');
    return $term ? (int) $term->term_id : 0;
}
function dm_theme_editorial_interview_ids() {
    $id = dm_theme_editorial_interview_id();
    return $id ? array_merge(array($id), array_map('intval', get_term_children($id, 'category'))) : array();
}
function dm_theme_editorial_empty($message) {
    return current_user_can('edit_posts') ? '<p class="dm-empty">' . esc_html($message) . '</p>' : '';
}
function dm_theme_editorial_category($id) {
    $excluded = dm_theme_editorial_interview_ids();
    foreach (get_the_category($id) as $category) {
        if (!in_array((int) $category->term_id, $excluded, true)) { return $category->name; }
    }
    return __('Writing', 'dm-editorial');
}
function dm_theme_editorial_render_writing($attributes) {
    $excluded = dm_theme_editorial_interview_ids();
    $args = array('post_type'=>'post','post_status'=>'publish','posts_per_page'=>1,'ignore_sticky_posts'=>true,'no_found_rows'=>true,'category__not_in'=>$excluded);
    $selected = !empty($attributes['featuredId']) ? absint($attributes['featuredId']) : 0;
    $featured = null;
    if ($selected) {
        $candidate = get_post($selected);
        if ($candidate && $candidate->post_type === 'post' && $candidate->post_status === 'publish' && (!$excluded || !has_category($excluded, $selected))) { $featured = $candidate; }
    }
    if (!$featured) { $found = get_posts($args); $featured = $found ? $found[0] : null; }
    $list_args = $args; $list_args['posts_per_page'] = 3;
    if ($featured) { $list_args['post__not_in'] = array($featured->ID); }
    $posts = get_posts($list_args);
    $archive_id = absint(get_option('page_for_posts'));
    $archive = !empty($attributes['archiveUrl']) ? $attributes['archiveUrl'] : ($archive_id ? get_permalink($archive_id) : '');
    ob_start(); ?>
    <section class="dm-writing dm-wrap" aria-label="<?php echo esc_attr($attributes['heading'] ?? 'Writing & ideas'); ?>">
    <header class="dm-section-header"><h2><?php echo esc_html($attributes['heading'] ?? 'Writing & ideas'); ?></h2>
    <?php if ($archive) : ?><a class="dm-all-writing" href="<?php echo esc_url($archive); ?>"><?php esc_html_e('View all writing', 'dm-editorial'); ?> <span aria-hidden="true">↗</span></a><?php endif; ?></header>
    <?php if ($featured) : $id = $featured->ID; ?>
    <div class="dm-writing-grid"><article class="dm-featured">
    <p class="dm-eyebrow dm-muted"><?php echo esc_html('FEATURED ESSAY · ' . dm_theme_editorial_category($id)); ?></p>
    <h3><a href="<?php echo esc_url(get_permalink($id)); ?>"><?php echo esc_html(get_the_title($id)); ?></a></h3>
    <?php $subtitle = get_post_meta($id, '_dm_subtitle', true); if ($subtitle) : ?><p class="dm-subtitle"><?php echo esc_html($subtitle); ?></p><?php endif; ?>
    <p class="dm-excerpt"><?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt($id)), 30)); ?></p>
    <a class="dm-read" href="<?php echo esc_url(get_permalink($id)); ?>"><?php esc_html_e('Read the essay', 'dm-editorial'); ?> <span aria-hidden="true">↗</span></a>
    <?php if (has_post_thumbnail($id)) { echo get_the_post_thumbnail($id, 'medium_large', array('class'=>'dm-featured-image','loading'=>'lazy')); } else { ?><div class="dm-card-art" aria-hidden="true"><i></i><i></i><i></i></div><?php } ?>
    </article><div class="dm-article-list">
    <?php foreach ($posts as $item) : ?><article><p class="dm-eyebrow dm-muted"><?php echo esc_html(dm_theme_editorial_category($item->ID)); ?></p>
    <a class="dm-article-link" href="<?php echo esc_url(get_permalink($item)); ?>"><h3><?php echo esc_html(get_the_title($item)); ?></h3><span class="dm-arrow" aria-hidden="true">→</span></a></article><?php endforeach; ?>
    </div></div>
    <?php else : echo dm_theme_editorial_empty('Publish a writing post to populate this section. Select a featured essay in this block’s settings.'); endif; ?>
    </section>
    <?php return ob_get_clean();
}
function dm_theme_editorial_render_interviews($attributes) {
    $category = dm_theme_editorial_interview_id();
    $posts = $category ? get_posts(array('post_type'=>'post','post_status'=>'publish','posts_per_page'=>2,'cat'=>$category,'ignore_sticky_posts'=>true,'no_found_rows'=>true)) : array();
    ob_start(); ?>
    <section class="dm-interviews dm-wrap" aria-label="<?php esc_attr_e('Interviews', 'dm-editorial'); ?>"><p class="dm-eyebrow dm-muted"><?php esc_html_e('FROM THE INTERVIEW ARCHIVE', 'dm-editorial'); ?></p>
    <h2><?php echo esc_html($attributes['heading'] ?? 'Conversations worth revisiting.'); ?></h2>
    <?php if ($posts) : ?><ul class="dm-interview-list"><?php foreach ($posts as $item) :
    $external = get_post_meta($item->ID, '_dm_interview_url', true);
    ?><li><a href="<?php echo esc_url($external ?: get_permalink($item)); ?>"><span><?php echo esc_html(get_the_title($item)); ?></span><span aria-hidden="true">→</span></a></li><?php endforeach; ?></ul>
    <?php else : echo dm_theme_editorial_empty('Publish posts in the Interviews category (slug: interviews) to populate this section.'); endif; ?></section>
    <?php return ob_get_clean();
}
// Keep the main Writing archive separate from interviews without altering category archives.
add_action('pre_get_posts', function ($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_home()) { return; }
    $query->set('category__not_in', array_unique(array_merge((array) $query->get('category__not_in'), dm_theme_editorial_interview_ids())));
});
add_action('add_meta_boxes', function () {
    add_meta_box('dm-editorial-details', __('Editorial details', 'dm-editorial'), 'dm_theme_editorial_meta_box', 'post', 'normal');
});
function dm_theme_editorial_meta_box($post) {
    wp_nonce_field('dm_theme_editorial_save', 'dm_theme_editorial_nonce'); ?>
    <p><label for="dm-subtitle">Featured essay subtitle</label><br><input class="widefat" id="dm-subtitle" name="dm_subtitle" value="<?php echo esc_attr(get_post_meta($post->ID, '_dm_subtitle', true)); ?>"></p>
    <p><label for="dm-interview-url">Optional external interview URL</label><br><input type="url" class="widefat" id="dm-interview-url" name="dm_interview_url" value="<?php echo esc_attr(get_post_meta($post->ID, '_dm_interview_url', true)); ?>"></p>
    <p>Leave the interview URL empty to link to this post. Set the post excerpt for featured essay copy.</p>
    <?php
}
add_action('save_post_post', function ($id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (wp_is_post_revision($id) || !current_user_can('edit_post', $id)) { return; }
    if (!isset($_POST['dm_theme_editorial_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['dm_theme_editorial_nonce'])), 'dm_theme_editorial_save')) { return; }
    if (isset($_POST['dm_subtitle'])) { update_post_meta($id, '_dm_subtitle', sanitize_text_field(wp_unslash($_POST['dm_subtitle']))); }
    if (isset($_POST['dm_interview_url'])) { update_post_meta($id, '_dm_interview_url', esc_url_raw(wp_unslash($_POST['dm_interview_url']), array('https','http'))); }
});
