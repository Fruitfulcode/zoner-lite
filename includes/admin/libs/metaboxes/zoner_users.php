<?php 

/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Zoner
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'zoner_users_mtb');
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function zoner_users_mtb( array $meta_boxes ) {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_zoner_';
	
	
	$meta_boxes[] = array(
		'id'            => 'user_edit',
		'title'         => __( 'User Profile', 'zoner' ),
		'pages'         => array( 'user' ), 
		'show_names'    => true,
		'zoner_styles' 	=> true, 
		'class'			=> 'user-profiles',
		'fields'        => array(
			array(
				'name'     => __( 'Extra User Info', 'zoner' ),
				'id'       => $prefix . 'extra_info',
				'type'     => 'title',
				'on_front' => false,
			),
			
			array(
				'name'    => __( 'Avatar', 'zoner' ),
				'id'      => $prefix . 'avatar',
				'type'    => 'file',
				'save_id' => true,
				'allow' => array( 'url', 'attachment' )
			),
			
			array(
				'name' => __( 'Facebook URL', 'zoner' ),
				'id'   => $prefix . 'facebookurl',
				'type' => 'text_url',
			),
			
			array(
				'name' => __( 'Twitter URL', 'zoner' ),
				'id'   => $prefix . 'twitterurl',
				'type' => 'text_url',
			),
			
			array(
				'name' => __( 'Google+ URL', 'zoner' ),
				'id'   => $prefix . 'googleplusurl',
				'type' => 'text_url',
			),
			
			array(
				'name' => __( 'Linkedin URL', 'zoner' ),
				'id'   => $prefix . 'linkedinurl',
				'type' => 'text_url',
			),
			
			array(
				'name' => __( 'Pinterest URL', 'zoner' ),
				'id'   => $prefix . 'pinteresturl',
				'type' => 'text_url',
			),
			
			array(
				'name' => __( 'Phone', 'zoner' ),
				'id'   => $prefix . 'tel',
				'type' => 'text_medium',
			),
			
			array(
				'name' => __( 'Mobile', 'zoner' ),
				'id'   => $prefix . 'mob',
				'type' => 'text_medium',
			),
			
			array(
				'name' => __( 'Skype', 'zoner' ),
				'id'   => $prefix . 'skype',
				'type' => 'text_medium',
			),
			
		)
	);
	
	return $meta_boxes;
	
}	