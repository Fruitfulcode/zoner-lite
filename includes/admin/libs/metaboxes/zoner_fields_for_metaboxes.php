<?php 

/*Adding cutom metabox filed*/
/*Made by zoner*/
	
add_action( 'cmb_render_custom_layout_sidebars', 'zoner_custom_layout_sidebars', 10, 2 );
function zoner_custom_layout_sidebars( $field, $meta ) {
	$layout = 0;
	$layout = $meta ? $meta : $field['default'];
    ?>
		<ul class="list-layouts">
			<li>
				<input type="radio" id="remove-all-wrappers" value="-1" name="<?php echo $field['id'];?>" <?php checked( $layout, '-1' ); ?>/>
				<img title="<?php _e('Without container (Only with Visual Composer)','zoner-lite'); ?>" src="<?php echo CMB_META_BOX_URL . 'images/without-container.png'; ?>" alt="" />
			</li>
			<li>
				<input type="radio" id="full-width" value="1" name="<?php echo $field['id'];?>" <?php checked( $layout, '1' ); ?>/>
				<img title="<?php _e('Full width','zoner-lite'); ?>" src="<?php echo CMB_META_BOX_URL . 'images/full.png'; ?>" alt="" />
			</li>
			<li>
				<input type="radio" id="right-sidebar" value="2" name="<?php echo $field['id'];?>" <?php checked( $layout, '2' ); ?>/>
				<img title="<?php _e('Content Right','zoner-lite'); ?>" src="<?php echo CMB_META_BOX_URL . 'images/right.png'; ?>" alt="" />
			</li>
			<li>
				<input type="radio" id="left-sidebar" value="3" name="<?php echo $field['id'];?>" <?php checked( $layout, '3' ); ?>/>
				<img title="<?php _e('Content Left','zoner-lite'); ?>" src="<?php echo CMB_META_BOX_URL . 'images/left.png'; ?>" alt="" />
			</li>
		</ul>
		<p class="cmb_metabox_description"><?php echo esc_attr($field['desc']); ?></p>
	<?php
}


add_action( 'admin_enqueue_scripts', 'zoner_custom_layout_sidebars_script' );
function zoner_custom_layout_sidebars_script($hook) {
	wp_register_script( 'cmb-layouts', CMB_META_BOX_URL . 'js/layout/layout.js'  );
	wp_register_style ( 'cmb-layouts', CMB_META_BOX_URL . 'js/layout/layout.css' );
	
	if ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'page.php' ) {
		wp_enqueue_script( 'cmb-layouts' );
		wp_enqueue_style ( 'cmb-layouts' );
	}
}
