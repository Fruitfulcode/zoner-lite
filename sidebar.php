<?php
/**
 * The Sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */
?>
<div id="secondary">
	<?php if ( is_active_sidebar( 'primary' ) || is_active_sidebar( 'secondary' ) ) : ?>
	<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'primary' ); ?>
	</div><!-- #primary-sidebar -->
	<?php endif; ?>
</div><!-- #secondary -->