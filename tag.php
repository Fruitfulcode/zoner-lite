<?php
/**
 * The template for displaying Tag pages
 *
 * Used to display archive-type pages for posts in a tag.
 *
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */

get_header(); 
?>
	<?php do_action('zoner_before_content') ?>
		<?php do_action('the_main_content'); ?>
	<?php do_action('zoner_after_content') ?>	

<?php
	get_footer();