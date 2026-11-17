<?php
/**
 * Template Name: Editorial Homepage
 * Template Post Type: page
 * A thin full-width wrapper; all homepage content remains in the block editor.
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('dm-home'); ?>>
    <?php the_content(); ?>
</article>
<?php endwhile;
get_footer();
