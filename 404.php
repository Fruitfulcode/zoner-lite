<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */

get_header(); ?>
<?php 
	global $zoner_config;
	$bg_404 	= esc_url($zoner_config['404-image']['url']);
	$text_404 	= __('404', 'zoner-lite');
	if (isset($zoner_config['404-text'])) 
		$text_404 = esc_attr($zoner_config['404-text']);
?>

	<div class="container">
		<section id="404" role="main">
			<div class="error-page">
				<div class="title">
					<img alt="<?php echo $text_404; ?>" src="<?php echo $bg_404; ?>" class="top">
                    <header><?php echo $text_404; ?></header>
                    <img alt="" src="<?php echo $bg_404; ?>" class="bottom">
                 </div>
                 <h1 class="no-border"><?php _e('Page not found', 'zoner-lite'); ?></h1>
                 <a href="<?php echo esc_url(home_url('')); ?>" class="link-arrow back" onclick="history.back(-1)"><?php _e('Go Back', 'zoner-lite'); ?></a>
			</div>
		</section>
	</div><!-- /.container -->

<?php
	get_footer();
