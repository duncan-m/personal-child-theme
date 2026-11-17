<?php
/** Duncan Malcolm presentation layer. Parent Blocksy templates remain untouched. */
defined('ABSPATH') || exit;
add_action('after_setup_theme', function () {
    add_theme_support('editor-styles');
    add_editor_style('assets/editor.css');
    load_child_theme_textdomain('duncan-malcolm', get_stylesheet_directory() . '/languages');
});
add_action('wp_enqueue_scripts', function () {
    $file = get_stylesheet_directory() . '/assets/site.css';
    wp_enqueue_style('dm-site', get_stylesheet_directory_uri() . '/assets/site.css', array(), filemtime($file));
    if (is_singular()) {
        wp_enqueue_style('dm-article', get_stylesheet_directory_uri() . '/assets/article.css', array('dm-site'), filemtime(get_stylesheet_directory() . '/assets/article.css'));
    }
    $js = get_stylesheet_directory() . '/assets/site.js';
    wp_enqueue_script('dm-site', get_stylesheet_directory_uri() . '/assets/site.js', array(), filemtime($js), true);
}, 99);
add_filter('body_class', function ($classes) {
    $classes[] = 'dm-site';
    if ((is_singular('post') || is_page()) && !is_front_page() && !is_page_template('templates/editorial-home.php')) {
        $classes[] = 'dm-document';
    }
    if (get_theme_mod('dm_footer_enabled', true)) { $classes[] = 'dm-has-contact-bar'; }
    return $classes;
});
add_filter('blocksy:builder:footer:enabled', function ($enabled) {
    return get_theme_mod('dm_footer_enabled', true) ? false : $enabled;
});
add_action('blocksy:footer:after', function () {
    if (!get_theme_mod('dm_footer_enabled', true)) { return; }
    $contact = get_theme_mod('dm_contact_url', '');
    $linkedin = get_theme_mod('dm_linkedin_url', '');
    ?>
    <footer class="dm-contact-bar" aria-label="<?php esc_attr_e('Contact', 'duncan-malcolm'); ?>">
      <div class="dm-wrap dm-contact-inner">
        <p class="dm-contact-prompt"><?php echo esc_html(get_theme_mod('dm_contact_prompt', 'Working on a complex product?')); ?></p>
        <?php if ($contact) : ?><a class="dm-contact-link" href="<?php echo esc_url($contact); ?>"><?php esc_html_e('Let’s talk', 'duncan-malcolm'); ?> <span aria-hidden="true">↗</span></a><?php endif; ?>
        <span class="dm-contact-name"><?php echo esc_html(get_bloginfo('name')); ?></span>
        <?php if ($linkedin) : ?><a class="dm-linkedin" href="<?php echo esc_url($linkedin); ?>">LinkedIn <span aria-hidden="true">↗</span></a><?php endif; ?>
      </div>
    </footer>
    <?php
});
add_action('customize_register', function ($customizer) {
    $customizer->add_section('dm_editorial', array('title' => __('Duncan Malcolm', 'duncan-malcolm'), 'priority' => 35));
    foreach (array('dm_contact_url' => 'Contact URL (https://… or mailto:…)', 'dm_linkedin_url' => 'LinkedIn URL') as $id => $label) {
        $customizer->add_setting($id, array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
        $customizer->add_control($id, array('label' => $label, 'section' => 'dm_editorial', 'type' => 'text'));
    }
    $customizer->add_setting('dm_contact_prompt', array('default' => 'Working on a complex product?', 'sanitize_callback' => 'sanitize_text_field'));
    $customizer->add_control('dm_contact_prompt', array('label' => __('Contact bar text', 'duncan-malcolm'), 'section' => 'dm_editorial'));
    $customizer->add_setting('dm_footer_enabled', array('default' => true, 'sanitize_callback' => function ($v) { return (bool) $v; }));
    $customizer->add_control('dm_footer_enabled', array('label' => __('Use sticky contact bar instead of Blocksy footer', 'duncan-malcolm'), 'type' => 'checkbox', 'section' => 'dm_editorial'));
});
add_action('init', function () {
    register_block_pattern_category('dm-editorial', array('label' => __('Duncan Malcolm', 'duncan-malcolm')));
    register_block_pattern('dm-editorial/home', array(
        'title' => __('Duncan Malcolm — Homepage', 'duncan-malcolm'),
        'categories' => array('dm-editorial'),
        'description' => __('Hero, company strip, live writing and interview lists.', 'duncan-malcolm'),
        'content' => str_replace('{{DM_PORTRAIT_PLACEHOLDER}}', esc_url(get_stylesheet_directory_uri() . '/assets/portrait-placeholder.svg'), file_get_contents(get_stylesheet_directory() . '/patterns/home.html')),
    ));
});
require_once get_stylesheet_directory() . '/inc-editorial.php';
require_once get_stylesheet_directory() . '/inc-featured.php';
require_once get_stylesheet_directory() . '/inc-setup.php';

// Suppress only Blocksy's excerpt beneath an individual post title.
function dm_hide_post_header_excerpt($excerpt) {
    return '';
}
add_action('blocksy:hero:custom_description:before', function () {
    if (!is_admin() && is_singular('post')) {
        add_filter('get_the_excerpt', 'dm_hide_post_header_excerpt', PHP_INT_MAX);
    }
});
add_action('blocksy:hero:custom_description:after', function () {
    remove_filter('get_the_excerpt', 'dm_hide_post_header_excerpt', PHP_INT_MAX);
});
