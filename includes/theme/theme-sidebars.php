<?php 
 /**
 * Register three Zoner Theme widget areas.
 * @since Zoner Theme 1.0
 * @return void
 */
if ( ! function_exists( 'zoner_widgets_init' ) ) {
	function zoner_widgets_init() {
		global $zoner_config;
	
		register_sidebar( array(
			'name'          => __( 'Blog sidebar', 'zoner-lite' ),
			'id'            => 'primary',
			'description'   => __( 'Blog sidebar area.', 'zoner-lite' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	
		register_sidebar( array(
			'name'          => __( 'Page sidebar', 'zoner-lite' ),
			'id'            => 'secondary',
			'description'   => __( 'Page sidebar area.', 'zoner-lite' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	
	
		$total_sidebars_count = '';
		if (isset($zoner_config['footer-widget-areas']))
		$total_sidebars_count = $zoner_config['footer-widget-areas'];
	
	
		if ( empty( $total_sidebars_count )) $total_sidebars_count = 4;
	
		for ( $i = 1; $i <= intval( $total_sidebars_count ); $i++ ) {
			register_sidebar( 
				array( 'name' 			=> sprintf( __( 'Footer area %d', 'zoner-lite' ), $i ), 
					   'id'   			=> sprintf( 'footer-%d', $i ), 
					   'description' 	=> sprintf( __( 'Widgetized footer area %d.', 'zoner-lite' ), $i ), 
					   'before_widget'  => '<aside id="%1$s" class="widget %2$s">',
					   'after_widget'   => '</aside>',
					   'before_title' 	=> '<h3 class="widget-title">',
					   'after_title'  	=> '</h3>'
					 ) 
			);
		}
	}
	add_action( 'widgets_init', 'zoner_widgets_init' );
} 


if ( ! function_exists( 'zoner_sidebar' ) ) {
	function zoner_sidebar( $id = 'primary' ) {
		return dynamic_sidebar( $id );
	} 
}

if ( ! function_exists( 'zoner_active_sidebar' ) ) {
	function zoner_active_sidebar( $id ) {
		$is_active = false;
		if( is_active_sidebar( $id ) )  $is_active = true;
		return $is_active;
	} 
}