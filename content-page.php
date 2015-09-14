<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */
?>
<?php 
	global $prefix;
		$class_page = array();
		$page_layout = get_post_meta(get_the_ID(), $prefix.'pages_layout', true); 
		if ($page_layout == -1)
		$class_page[] = 'container';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<section id="content" class="content">
		<header class="<?php echo implode(' ', $class_page); ?>">
			<h1><?php the_title(); ?></h1>
		</header>
		
		<section id="legal" class="legal">
		<?php 
			global $post;
			$post_content = '';
			$post_content = $post->post_content;
			if (strpos($post_content,'vc_row') !== false) {
				the_content(); 
			} else {
			?>
				<section class="block">
					<div class="container">
						<div class="vc_span12 wpb_column column_container">
							<div class="wpb_wrapper">
								<?php the_content(); ?>
								<?php wp_link_pages( array(
																'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'zoner-lite' ) . '</span>',
																'after'       => '</div>',
																'link_before' => '<span>',
																'link_after'  => '</span>',
														) ); ?>
							</div>
						</div> 
					</div>
				</section>	

			<?php	
			}
		?>
		
		<?php 
			if (is_page() && !is_front_page() && !is_home())
			zoner_visibilty_comments(); 
		?>
		
		<?php 
			edit_post_link( '<i title="' . __("Edit", 'zoner-lite') . '" class="fa fa-pencil-square-o"></i><span class="edit-link-text">'.__("Edit", 'zoner-lite') .'</span>', '<div class="'.implode(' ', $class_page).'">', '</div>' ); 
		?>
		
		</section><!-- .legal -->
	</section>
</article><!-- #post-## -->