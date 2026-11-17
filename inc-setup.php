<?php
/** Explicit, repeatable starter setup. No changes run on activation. */
defined('ABSPATH') || exit;
add_action('admin_menu', function () {
    add_theme_page('Duncan Malcolm Setup', 'Duncan Malcolm Setup', 'manage_options', 'dm-setup', 'dm_setup_screen');
});
add_action('admin_notices', function () {
    if (!current_user_can('manage_options') || get_option('dm_setup_applied')) { return; }
    echo '<div class="notice notice-info"><p>Duncan Malcolm is ready to set up. <a href="' . esc_url(admin_url('themes.php?page=dm-setup')) . '">Open the theme setup screen</a>.</p></div>';
});
function dm_setup_page($key, $title, $content = '') {
    $ids = (array) get_option('dm_setup_pages', array());
    if (!empty($ids[$key]) && get_post($ids[$key]) && get_post_status($ids[$key]) !== 'trash') { return (int) $ids[$key]; }
    $id = wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>$title,'post_content'=>$content), true);
    if (is_wp_error($id)) { wp_die(esc_html($id->get_error_message())); }
    $ids[$key] = $id; update_option('dm_setup_pages', $ids);
    return $id;
}
function dm_setup_screen() {
    if (!current_user_can('manage_options')) { return; }
    $ids = (array) get_option('dm_setup_pages', array()); ?>
    <div class="wrap"><h1>Duncan Malcolm — Site Setup</h1>
    <p>Everything is built into this child theme. The Blocksy parent must remain installed; no companion plugin, Pro licence or page builder is needed.</p>
    <?php if (get_option('dm_setup_applied')) : ?><div class="notice notice-success inline"><p>Starter setup applied. Your homepage, menus, styling and contact bar are configured.</p></div><?php endif; ?>
    <p><strong>Apply starter setup</strong> creates new published Home, Writing and About pages, sets Home as the front page, configures the header/navigation and applies the reference styling. Existing content is not overwritten. Reading settings, site title and header/menu configuration are backed up for the Restore button.</p>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <input type="hidden" name="action" value="dm_apply_setup"><?php wp_nonce_field('dm_apply_setup'); ?>
    <table class="form-table"><tr><th><label for="dm-site-name">Site name</label></th><td><input id="dm-site-name" class="regular-text" name="site_name" value="<?php echo esc_attr(get_option('dm_setup_applied') ? get_bloginfo('name') : 'Duncan Malcolm'); ?>"></td></tr>
    <tr><th><label for="dm-contact">Contact URL</label></th><td><input id="dm-contact" class="regular-text" name="contact_url" value="<?php echo esc_attr(get_theme_mod('dm_contact_url','')); ?>" placeholder="mailto:you@example.com"><p class="description">Optional. Add an email link or contact-page URL. Contact buttons appear once a destination is supplied.</p></td></tr>
    <tr><th><label for="dm-linkedin">LinkedIn URL</label></th><td><input id="dm-linkedin" class="regular-text" name="linkedin_url" value="<?php echo esc_attr(get_theme_mod('dm_linkedin_url','')); ?>" placeholder="https://www.linkedin.com/in/…"></td></tr></table>
    <p><label><input type="checkbox" name="include_demo" value="1" <?php checked(!get_option('dm_setup_applied')); ?>> Include the six example essays/interviews from the mockup (published starter content; replace before launch).</label></p>
    <?php submit_button(get_option('dm_setup_applied') ? 'Reapply layout and links' : 'Apply starter setup'); ?></form>
    <?php if (!empty($ids['home'])) : ?><p><a class="button button-primary" href="<?php echo esc_url(get_edit_post_link($ids['home'])); ?>">Edit homepage and replace images</a> <a class="button" href="<?php echo esc_url(get_permalink($ids['home'])); ?>">View homepage</a></p><?php endif; ?>
    <h2>Finish your content</h2><ol><li>Edit the homepage to replace the portrait and the four company-name placeholders with your original images.</li><li>Replace example post bodies and About copy. The Writing block’s sidebar lets you select a featured essay.</li><li>Contact links can also be changed in Appearance → Customise → Duncan Malcolm.</li><li>Review the site on mobile and desktop before launching.</li></ol>
    <p>Reapplying setup reuses its recorded pages and example posts; it does not reset their edited content. It reapplies the header and navigation. Switching themes disables these built-in dynamic blocks; your posts remain in WordPress.</p>
    <?php if (get_option('dm_setup_backup')) : ?><h2>Restore previous configuration</h2><p>Restores the Reading, site-title, header/menu and theme settings saved before the first setup. It does not delete created pages or example posts.</p><form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="dm_restore_setup"><?php wp_nonce_field('dm_restore_setup'); submit_button('Restore previous settings', 'secondary'); ?></form><?php endif; ?>
    </div><?php
}
function dm_apply_starter($site_name, $contact, $linkedin, $include_demo) {
    if (!get_option('dm_setup_backup')) {
        update_option('dm_setup_backup', array(
            'show_on_front'=>get_option('show_on_front'), 'page_on_front'=>get_option('page_on_front'), 'page_for_posts'=>get_option('page_for_posts'),
            'blogname'=>get_option('blogname'), 'mods'=>get_theme_mods(),
        ));
    }
    // Migrate the previous separate plugin without losing its post metadata.
    if (function_exists('deactivate_plugins')) { deactivate_plugins('dm-editorial/dm-editorial.php'); }
    $term = get_category_by_slug('interviews');
    if (!$term) {
        $result = wp_insert_term('Interviews','category',array('slug'=>'interviews'));
        if (is_wp_error($result)) { wp_die(esc_html($result->get_error_message())); }
        $term = get_term($result['term_id'],'category');
    }
    $content = str_replace('{{DM_PORTRAIT_PLACEHOLDER}}', esc_url(get_stylesheet_directory_uri() . '/assets/portrait-placeholder.svg'), file_get_contents(__DIR__ . '/patterns/home.html'));
    $home = dm_setup_page('home','Home',$content);
    update_post_meta($home,'_wp_page_template','templates/editorial-home.php');
    $writing = dm_setup_page('writing','Writing');
    $about = dm_setup_page('about','About','<!-- wp:paragraph --><p>I’m Duncan Malcolm, a product leader working across payments, fintech and regulated, high-growth businesses.</p><!-- /wp:paragraph -->');
    if ($include_demo && !get_option('dm_example_posts')) {
        $payments = term_exists('Payments','category');
        if (!$payments) { $payments = wp_insert_term('Payments','category'); }
        $product = term_exists('Product & UX','category');
        if (!$product) { $product = wp_insert_term('Product & UX','category'); }
        $payments_id = is_wp_error($payments) ? 1 : (int) $payments['term_id'];
        $product_id = is_wp_error($product) ? 1 : (int) $product['term_id'];
        $examples = array(
            array('Revenue up. Risk hidden.','A closer look at the complexity behind growing payment revenue.',$payments_id),
            array('A beginner’s guide to payment fraud','',$payments_id),
            array('The product management toolkit','',$product_id),
            array('Why SaaS companies are becoming payment facilitators','',$payments_id),
            array('Scaling organisations with Verne Harnish','',$term->term_id),
            array('Building SoundCloud with Alexander Ljung','',$term->term_id),
        );
        $ids = array();
        foreach ($examples as $i=>$example) {
            $id = wp_insert_post(array('post_type'=>'post','post_status'=>'publish','post_title'=>$example[0],'post_excerpt'=>$example[1], 'post_date'=>gmdate('Y-m-d H:i:s',time()-$i*3600), 'post_date_gmt'=>gmdate('Y-m-d H:i:s',time()-$i*3600), 'post_category'=>array($example[2]),'post_content'=>'<!-- wp:paragraph --><p>Starter example — replace this text with your finished article or interview before launch.</p><!-- /wp:paragraph -->'),true);
            if (!is_wp_error($id)) { $ids[]=$id; update_post_meta($id,'_dm_starter_example',1); }
        }
        update_option('dm_example_posts',$ids);
        if ($ids) {
            update_post_meta($ids[0],'_dm_subtitle','The card payments problem');
            // Only choose a demo feature if this is still the unedited starter pattern.
            $page = get_post($home);
            if ($page->post_content === $content) { wp_update_post(array('ID'=>$home,'post_content'=>str_replace('"featuredId":0','"featuredId":'.$ids[0],$content))); }
        }
    }
    update_option('show_on_front','page'); update_option('page_on_front',$home); update_option('page_for_posts',$writing);
    if ($site_name) { update_option('blogname',$site_name); }
    set_theme_mod('dm_contact_url',$contact); set_theme_mod('dm_linkedin_url',$linkedin); set_theme_mod('dm_footer_enabled',true);
    $menu_id = (int) get_option('dm_setup_menu');
    if (!$menu_id || !wp_get_nav_menu_object($menu_id)) {
        $menu_id = wp_create_nav_menu('Duncan Malcolm ' . wp_generate_password(6,false));
        if (is_wp_error($menu_id)) { wp_die(esc_html($menu_id->get_error_message())); }
        update_option('dm_setup_menu',$menu_id);
    }
    foreach ((array) wp_get_nav_menu_items($menu_id) as $item) { wp_delete_post($item->ID,true); }
    foreach (array('Writing'=>get_permalink($writing),'Interviews'=>get_category_link($term->term_id),'About'=>get_permalink($about)) as $label=>$url) {
        wp_update_nav_menu_item($menu_id,0,array('menu-item-title'=>$label,'menu-item-url'=>$url,'menu-item-status'=>'publish'));
    }
    $locations = (array) get_theme_mod('nav_menu_locations',array());
    $locations['menu_1']=$menu_id; $locations['menu_mobile']=$menu_id; set_theme_mod('nav_menu_locations',$locations);
    $builder = blocksy_manager()->header_builder;
    $end = $contact ? array('menu','button') : array('menu');
    $section = $builder->get_structure_for(array('id'=>'type-1','mode'=>'placements','items'=>array(
        'desktop'=>array('middle-row'=>array('start'=>array('logo'),'end'=>$end)),
        'mobile'=>array('middle-row'=>array('start'=>array('logo'),'end'=>array('trigger')),'offcanvas'=>array('start'=>array('mobile-menu')))
    )));
    $section['items'][] = array('id'=>'logo','values'=>array('has_site_title'=>'yes','has_tagline'=>'no'));
    $section['items'][] = array('id'=>'button','values'=>array('header_button_text'=>'Get in touch ↗','header_button_link'=>$contact));
    set_theme_mod('header_placements',array('current_section'=>'type-1','sections'=>array($section)));
    update_option('dm_setup_applied',1);
}
add_action('admin_post_dm_apply_setup',function () {
    if (!current_user_can('manage_options')) { wp_die('Permission denied.'); }
    check_admin_referer('dm_apply_setup');
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    dm_apply_starter(sanitize_text_field(wp_unslash($_POST['site_name'] ?? 'Duncan Malcolm')),esc_url_raw(wp_unslash($_POST['contact_url'] ?? '')),esc_url_raw(wp_unslash($_POST['linkedin_url'] ?? ''),array('http','https')),!empty($_POST['include_demo']));
    wp_safe_redirect(admin_url('themes.php?page=dm-setup')); exit;
});
function dm_restore_starter() {
    $backup = get_option('dm_setup_backup'); if (!$backup) { return; }
    foreach (array('show_on_front','page_on_front','page_for_posts','blogname') as $key) { update_option($key,$backup[$key]); }
    remove_theme_mods();
    foreach ((array) $backup['mods'] as $key=>$value) { set_theme_mod($key,$value); }
    delete_option('dm_setup_applied'); delete_option('dm_setup_backup');
}
add_action('admin_post_dm_restore_setup',function () {
    if (!current_user_can('manage_options')) { wp_die('Permission denied.'); }
    check_admin_referer('dm_restore_setup'); dm_restore_starter();
    wp_safe_redirect(admin_url('themes.php?page=dm-setup')); exit;
});
