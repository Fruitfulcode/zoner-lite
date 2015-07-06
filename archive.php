<?php
/**
 * The template for displaying Archive pages
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
