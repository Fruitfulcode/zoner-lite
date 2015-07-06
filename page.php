<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */

get_header(); ?>
	<?php 	global $prefix;
			$page_layout = get_post_meta(get_the_ID(), $prefix.'pages_layout', true); 
	?>
	<?php if ($page_layout != -1) do_action('zoner_before_content') ?>
		<?php do_action('the_main_content'); ?>
	<?php if ($page_layout != -1) do_action('zoner_after_content') ?>
<?php
   get_footer();
