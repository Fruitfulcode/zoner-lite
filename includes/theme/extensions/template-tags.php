<?php
/**
 * Custom template tags for Zoner Theme
 *
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */

/**
 * Find out if blog has more than one category.
 *
 * @since Zoner Theme 1.0
 *
 * @return boolean true if blog has more than 1 category
 */
function zoner_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'zoner_category_count' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'zoner_category_count', $all_the_cool_cats );
	}

	if ( 1 !== (int) $all_the_cool_cats ) {
		// This blog has more than 1 category so zoner_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so zoner_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in zoner_categorized_blog.
 *
 * @since Zoner Theme 1.0
 *
 * @return void
 */
function zoner_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'zoner_category_count' );
}
add_action( 'edit_category', 'zoner_category_transient_flusher' );
add_action( 'save_post',     'zoner_category_transient_flusher' );