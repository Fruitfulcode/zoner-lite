<?php
	add_action( 'after_setup_theme',  'zoner_setup' );
	add_filter( 'nav_menu_css_class', 'zoner_nav_parent_class', 10, 2 );
	
	/*Main Content Part*/
	add_action('zoner_before_content', 'zoner_before_content');
	add_action('zoner_after_content',  'zoner_after_content');
	add_action('zoner_the_main_content', 'zoner_get_main_content');
	
	add_filter( 'page_css_class', 'zoner_add_page_parent_class', 10, 4 );
	add_action( 'customize_register', 'zoner_customize_register' );
	add_action( 'template_redirect', 'zoner_content_width' );
	add_action( 'wp_enqueue_scripts', 'zoner_scripts', 10 );
	
	add_filter( 'body_class', 'zoner_body_classes' );
	add_filter( 'post_class', 'zoner_post_classes' );
	add_filter( 'get_search_form', 'zoner_search_form' );
	add_filter( 'excerpt_more', 'zoner_change_excerpt_more');
	add_filter( 'excerpt_length', 'zoner_set_excerpt_length', 999 );	
	add_filter( 'the_content_more_link', 'zoner_modify_read_more_link' );
	add_filter( 'edit_post_link', 'zoner_edit_post_link');
	
	add_action( 'zoner_comments_template', 'zoner_visibilty_comments');
	add_filter( 'the_content', 'zoner_post_chat', 99);
	add_filter( 'img_caption_shortcode', 'zoner_img_caption', 10, 3 );
	
	/*Footer*/
	add_action('zoner_footer_elements', 'zoner_get_footer_area_sidebars', 1);
	add_action('zoner_footer_elements', 'zoner_get_social', 3);
	