<?php
/**
 * The template for displaying Front Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */

	get_header(); 
?>
	
	<?php 
		global $post; 
		$page_on_front  = get_option('page_on_front');
		$page_for_posts = get_option('page_for_posts');
		
		$page_layout = get_post_meta($page_on_front, $prefix.'pages_layout', true);
		if (!$page_layout) {
			 $page_layout = -1;
		}
		
		if (is_front_page() && (!empty($page_on_front) && !empty($page_for_posts)) && ($page_on_front == $page_for_posts)) {
	?>	
			<section id="front-page" class="wpb_row block vc_row-fluid">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-danger"><strong><?php _e("Front page displays Error.", 'zoner'); ?></strong> <?php _e('Select different pages!', 'zoner'); ?></div>
						</div>	
					</div>		
				</div>	
			</section>	
	<?php	
		} else {
			
			$front_page_content = $post->post_content;
			if ((strpos($front_page_content, 'vc_row') == false) || ((int)$page_layout > 1))
				do_action('zoner_before_content'); 
				do_action('the_main_content');
			if ((strpos($front_page_content, 'vc_row') == false) || ((int)$page_layout > 1))
				do_action('zoner_after_content'); 
		}	
	?>
		
<?php 
	get_footer();
