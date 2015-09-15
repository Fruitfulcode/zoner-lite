<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Zoner
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'zoner_pages_mtb');
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function zoner_pages_mtb( array $meta_boxes ) {
	// Start with an underscore to hide fields from custom fields list
	$zoner_prefix = '_zoner_';
	
	$meta_boxes[] = array(
		'id'         => 'pages_layout',
		'title'      => __( 'Layout', 'zoner-lite' ),
		'pages'      => array( 'page'), 
		
		'context'    => 'side',
		'priority'   => 'low',
		'show_names' => true, 
		'fields'     => array(
			array(
				'name'    	 => __( 'Page layout', 'zoner-lite' ),
				'id' 		 => $zoner_prefix . 'pages_layout',
				'type' 		 => 'custom_layout_sidebars',
				'default'	 => 1
			),
			
		)
	);
	
	
	return $meta_boxes;
}	