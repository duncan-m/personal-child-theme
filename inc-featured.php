<?php
/** Homepage essay selection, available with the block editor disabled. */
defined('ABSPATH') || exit;
function dm_featured_is_eligible($id) {
    $post = get_post($id);
    $excluded = dm_theme_editorial_interview_ids();
    return $post && $post->post_type === 'post' && $post->post_status === 'publish'
        && (!$excluded || !has_category($excluded, $post->ID));
}
function dm_featured_choices($search = '') {
    $args = array(
        'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 20,
        'orderby' => array('date' => 'DESC', 'ID' => 'DESC'), 'ignore_sticky_posts' => true,
        'no_found_rows' => true, 'category__not_in' => dm_theme_editorial_interview_ids(),
    );
    if ($search !== '') { $args['s'] = $search; $args['search_columns'] = array('post_title'); }
    $query = new WP_Query($args);
    $choices = array();
    foreach ($query->posts as $post) {
        $choices[] = array('id' => $post->ID, 'label' => dm_featured_label($post->ID));
    }
    return $choices;
}
function dm_featured_label($id) {
    $title = html_entity_decode(wp_strip_all_tags(get_the_title($id)), ENT_QUOTES, get_bloginfo('charset'));
    if ($title === '') { $title = __('(Untitled)', 'duncan-malcolm'); }
    return $title . ' — ' . get_the_date(get_option('date_format'), $id);
}
function dm_featured_screen() {
    $saved = get_theme_mod('dm_featured_post_id', null);
    $value = $saved === null ? 'block' : (string) absint($saved);
    $search = isset($_GET['dm_post_search']) && is_string($_GET['dm_post_search']) ? sanitize_text_field(wp_unslash($_GET['dm_post_search'])) : '';
    $choices = dm_featured_choices($search);
    if ($saved && !in_array(absint($saved), array_column($choices, 'id'), true)) {
        array_unshift($choices, array('id' => absint($saved), 'label' => dm_featured_label(absint($saved))));
    }
    ?>
    <section aria-labelledby="dm-featured-heading">
    <h2 id="dm-featured-heading">Featured homepage essay</h2>
    <p>Choose the essay shown in the homepage’s featured card. Recent published writing posts appear below; search by title to find older posts. Interviews are excluded.</p>
    <?php if (isset($_GET['dm_featured_saved'])) : ?><div class="notice notice-success inline"><p>Featured homepage essay saved.</p></div><?php endif; ?>
    <?php if ($saved && !dm_featured_is_eligible(absint($saved))) : ?><div class="notice notice-warning inline"><p>The saved essay is no longer eligible. The homepage shows the latest published writing post until you choose another.</p></div><?php endif; ?>
    <form method="get" action="<?php echo esc_url(admin_url('themes.php')); ?>" id="dm-featured-search-form">
    <input type="hidden" name="page" value="dm-setup">
    <p><label for="dm-featured-search"><strong>Search post titles</strong></label><br>
    <input type="search" id="dm-featured-search" name="dm_post_search" class="regular-text" value="<?php echo esc_attr($search); ?>" autocomplete="off" aria-controls="dm-featured-post" aria-describedby="dm-featured-status">
    <button type="submit" class="button">Search</button></p>
    </form>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="dm-featured-save-form">
    <input type="hidden" name="action" value="dm_save_featured"><?php wp_nonce_field('dm_save_featured'); ?>
    <div id="dm-featured-picker" class="dm-featured-picker" data-search-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-search-nonce="<?php echo esc_attr(wp_create_nonce('dm_search_featured')); ?>">
    <label for="dm-featured-post"><strong>Featured essay</strong></label><br>
    <select id="dm-featured-post" name="featured_post" style="width:100%;max-width:640px" aria-describedby="dm-featured-help dm-featured-status">
    <option value="block" <?php selected($value, 'block'); ?>>Keep existing homepage block setting</option>
    <option value="0" <?php selected($value, '0'); ?>>Automatic — latest published writing post</option>
    <?php foreach ($choices as $choice) : ?><option value="<?php echo esc_attr($choice['id']); ?>" <?php selected($value, (string) $choice['id']); ?>><?php echo esc_html($choice['label']); ?></option><?php endforeach; ?>
    </select>
    </div>
    <p id="dm-featured-status" role="status" aria-live="polite"><?php echo $search === '' ? 'Showing recent posts (up to 20).' : 'Showing matching titles (up to 20). Refine your search if needed.'; ?></p>
    <p id="dm-featured-help" class="description">Saving only changes the featured essay. It does not reapply site setup or edit homepage content. Your selected essay is excluded from the adjacent writing list.</p>
    <?php submit_button('Save featured essay', 'primary', 'submit', false); ?>
    </form>
    </section><hr>
    <?php
}
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'appearance_page_dm-setup' && (!isset($_GET['page']) || $_GET['page'] !== 'dm-setup')) { return; }
    wp_enqueue_style('dm-featured-admin', get_stylesheet_directory_uri() . '/assets/featured-admin.css', array(), filemtime(__DIR__ . '/assets/featured-admin.css'));
    wp_enqueue_script('dm-featured-admin', get_stylesheet_directory_uri() . '/assets/featured-admin.js', array(), filemtime(__DIR__ . '/assets/featured-admin.js'), true);
});
add_action('wp_ajax_dm_search_featured', function () {
    if (!current_user_can('manage_options')) { wp_send_json_error(array('message' => 'Permission denied.'), 403); }
    check_ajax_referer('dm_search_featured', 'nonce');
    $search = isset($_GET['search']) && is_string($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
    wp_send_json_success(dm_featured_choices($search));
});
add_action('admin_post_dm_save_featured', function () {
    if (!current_user_can('manage_options')) { wp_die('Permission denied.', '', array('response' => 403)); }
    check_admin_referer('dm_save_featured');
    $value = isset($_POST['featured_post']) && is_string($_POST['featured_post']) ? wp_unslash($_POST['featured_post']) : '';
    if ($value === 'block') {
        remove_theme_mod('dm_featured_post_id');
    } elseif ($value === '0' || (ctype_digit($value) && (int) $value > 0 && dm_featured_is_eligible((int) $value))) {
        set_theme_mod('dm_featured_post_id', (int) $value);
    } else {
        wp_die('Choose a published writing post. Drafts, pages and interviews cannot be featured.', '', array('response' => 400));
    }
    wp_safe_redirect(admin_url('themes.php?page=dm-setup&dm_featured_saved=1#dm-featured-heading'));
    exit;
});
