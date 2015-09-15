<?php

/*Metaboxes activation*/
add_action( 'init', 'zoner_initialize_cmb_meta_boxes', 9999  );
function zoner_initialize_cmb_meta_boxes() {
	if ( ! class_exists( 'cmb_Meta_Box' ) ) require_once dirname(__FILE__) . '/metaboxes/init.php';
}

require dirname(__FILE__) . '/metaboxes/zoner_fields_for_metaboxes.php';
require dirname(__FILE__) . '/metaboxes/zoner_pages.php';

/*Tgm activation*/
require_once dirname(__FILE__) . '/tgm/class-tgm-init.php';
