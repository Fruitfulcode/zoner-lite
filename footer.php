<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */
?>
	</div><!-- #main -->
	
	<!-- Footer -->
	<footer id="page-footer">
		<div class="inner">
			<?php do_action('zoner_footer_elements'); ?>	
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>