<?php
/**
 * The template for displaying Author archive pages
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */
?>

<?php get_header(); ?>

<?php do_action('zoner_before_content'); ?>	
	
	<?php 
		global $zoner_config, $prefix;
		$layout = 1;
		
		$query_author  = get_queried_object();
		$query_user_id = $query_author->ID;
		$curr_user_id  = get_current_user_id();
		
		if (!empty($zoner_config['pp-author-agents-layout'])) $layout = (int)$zoner_config['pp-author-agents-layout'];
		if (is_author() && is_user_logged_in() && ($curr_user_id == $query_user_id)) $layout = 1;	
		
		if ($layout == 1) {
				zoner_get_author_content();
		} elseif ($layout == 2) {
			echo '<div class="col-md-3 col-sm-3">';
				zoner_get_sidebar_part('primary'); 
			echo '</div>';
			echo '<div class="col-md-9 col-sm-9">';
				zoner_get_author_content(); 
			echo '</div>';
		} elseif ($layout == 3) {
			echo '<div class="col-md-9 col-sm-9">';
				zoner_get_author_content(); 
			echo '</div>';
			echo '<div class="col-md-3 col-sm-3">';
				zoner_get_sidebar_part('primary'); 
			echo '</div>';
		} else {
			echo '<div class="col-md-3 col-sm-3">';
				zoner_get_author_content();
			echo '</div class="col-md-12">';	
		}
	?>	

<?php do_action('zoner_after_content'); ?>	

<?php get_footer(); ?>