<?php
/**
 * The template for displaying posts in the Video post format
 *
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post video-content'); ?>>
	<?php if ( is_search() || !is_single()) : ?>
		<?php zoner_get_post_thumbnail(); ?>	
		<?php zoner_get_post_title(); ?>
		<?php zoner_get_post_meta(); ?>
		<?php the_excerpt(); ?>
		<?php zoner_get_readmore_link(); ?>
	<?php else : ?>
		<?php zoner_get_post_thumbnail(); ?>	
		<?php zoner_get_post_title(); ?>
		<?php zoner_get_post_meta(); ?>
		<?php the_content(); ?>
		<?php wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'zoner-lite' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		?>	
	
	<?php endif; ?>
</article><!-- #post-## -->

<?php if (is_single()) zoner_get_post_about_author(); ?>
<?php if (is_single()) zoner_visibilty_comments(); ?>