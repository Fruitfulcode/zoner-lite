<?php

global 	$zoner_inc_theme_url, 
		$zoner_prefix,
		$zoner_is_redux_active;
		
		$zoner_inc_theme_url   = get_template_directory_uri() . '/includes/theme/';
		$zoner_prefix = '_zoner_';
		$zoner_is_redux_active = class_exists('ReduxFramework');
		
if ( ! isset( $content_width ) ) $content_width = 950;
		
if ( ! function_exists( 'zoner_setup' ) ) :
/**
 * Zoner Theme setup.
 * Set up theme defaults and registers support for various WordPress features.
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 * @since Zoner Theme 1.0
 */
function zoner_setup() {
	/*
	 * Make Zoner Theme available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Zoner Theme, use a find and
	 * replace to change 'zoner-lite' to the name of your theme in all
	 * template files.
	 */
	 
	load_theme_textdomain( 'zoner-lite', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	set_post_thumbnail_size( 750, 750, true );

	add_image_size( 'zoner-avatar-ceo', 190, 190, true );
	
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'zoner-lite' )
	) );

	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery', 'chat'));
}
endif; // zoner_setup


/*Walkers*/
class Zoner_Submenu_Class extends Walker_Nav_Menu {
	 function start_lvl(&$output, $depth = 0, $args = array()) {
		$classes 	 = array('sub-menu', 'list-unstyled', 'child-navigation');
		$class_names = implode( ' ', $classes );
		$output .= "\n" . '<ul class="' . $class_names . '">' . "\n";
	}
	
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) )
        $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
	
	function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
		global $wp_query, $zoner_config; 
	   
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names_arr = array();
		$class_names = $value = '';


		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names =  join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names_arr[] = esc_attr( $class_names );
	   
		if ( $args->has_children )
		$class_names_arr[] = 'has-child';

		$class_names = ' class="'. implode(' ', $class_names_arr) . '"';
		
		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . $item->url .'"' : '';
		
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

if ( ! function_exists( 'zoner_add_page_parent_class' ) ) {
	function zoner_add_page_parent_class( $css_class, $page, $depth, $args ) {
		if ( ! empty( $args['has_children'] ) )
		$css_class[] = 'parent';
		
		return $css_class;
	}
}

class Zoner_Page_Walker extends Walker_page {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
			$output .= "\n$indent<ul class='sub-menu list-unstyled child-navigation'>\n";
	}
}
/*End custom walkers*/

/*Customize*/
if ( ! function_exists( 'zoner_customize_register' ) ) :
	function zoner_customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
		
	}
endif; // zoner_customize_register

/**
 * Adjust content_width value for image attachment template.
 * @since Zoner Theme 1.0
 * @return void
 */
if ( ! function_exists( 'zoner_content_width' ) ) :
function zoner_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 950;
	}
}
endif; //zoner_content_width

/**
 * Enqueue scripts and styles for the front end.
 * @since Zoner Theme 1.0
 * @return void
 */
if ( ! function_exists( 'zoner_scripts' ) ) {
	function zoner_scripts() {
		global 	$zoner_inc_theme_url, $zoner_config;
		$is_rtl = 0;
		
		$is_mobile = 0;
		if ( wp_is_mobile()) $is_mobile = 1;
		
		if (is_rtl())
		$is_rtl = 1;
		
		if ( is_singular()) wp_enqueue_script( 'comment-reply' );
		
		
		wp_register_script( 'zoner-mainJs',	 $zoner_inc_theme_url . 'assets/js/custom.js',	 array( 'jquery' ), '20142807', true );
		wp_localize_script( 'zoner-mainJs', 'ZonerGlobal', 	array( 	'is_rtl'		=> $is_rtl,
																	'is_mobile' 	=> $is_mobile
																	) );  
		
		/*Custom Css*/
		wp_enqueue_style( 'font-awesome', 				$zoner_inc_theme_url . 'assets/fonts/font-awesome.min.css');
		wp_enqueue_style( 'font-elegant-icons', 		$zoner_inc_theme_url . 'assets/fonts/ElegantIcons.css');
		wp_enqueue_style( 'bootstrap', 	 				$zoner_inc_theme_url . 'assets/bootstrap/css/bootstrap.min.css');
		
		if (is_rtl())
		wp_enqueue_style( 'bootstrap-rtl', 				$zoner_inc_theme_url . 'assets/bootstrap/css/bootstrap-rtl.min.css');
		wp_enqueue_style( 'bootstrap-social', 			$zoner_inc_theme_url . 'assets/bootstrap/css/bootstrap-social-buttons.css');
		wp_enqueue_style( 'bootstrap-select', 			$zoner_inc_theme_url . 'assets/css/bootstrap-select.min.css');
		wp_enqueue_style( 'owl-carousel', 				$zoner_inc_theme_url . 'assets/css/owl.carousel.css');
		wp_enqueue_style( 'owl-carousel-trans', 		$zoner_inc_theme_url . 'assets/css/owl.transitions.css');
		
		
		wp_enqueue_style( 'zoner-style', get_stylesheet_uri() );
		
		/*Custom Js*/
		wp_enqueue_script( 'zoner-skip-link-focus-fix',	$zoner_inc_theme_url . 'assets/js/skip-link-focus-fix.js', array(), '20142807', true );
		
		wp_enqueue_script( 'bootstrap',					$zoner_inc_theme_url . 'assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '20142807', true );
		wp_enqueue_script( 'bootstrap-select', 			$zoner_inc_theme_url . 'assets/js/bootstrap-select.min.js',	  array( 'jquery' ), '20142807', true );
		
		wp_enqueue_script( 'icheck', 					$zoner_inc_theme_url . 'assets/js/icheck.min.js',	 array( 'jquery' ), '20142807', true );
		
		if (!empty($zoner_config['smoothscroll']))
	    wp_enqueue_script( 'smoothscroll', 				$zoner_inc_theme_url . 'assets/js/smoothscroll.js', array( 'jquery' ), '20142807', true );
		
		wp_enqueue_script( 'owl-carousel', 				$zoner_inc_theme_url . 'assets/js/owl.carousel.min.js',	 array( 'jquery' ), '20142807', true );
		wp_enqueue_script( 'jquery-validate', 			$zoner_inc_theme_url . 'assets/js/jquery.validate.min.js',	 array( 'jquery' ), '20142807', true );
		wp_enqueue_script( 'jquery-placeholder',		$zoner_inc_theme_url . 'assets/js/jquery.placeholder.js',	 array( 'jquery' ), '20142807', true );
		
		
		/*Custom scripts*/
		do_action('zoner_before_enqueue_script');
		
		wp_enqueue_script('zoner-mainJs');		
		
		do_action('zoner_after_enqueue_script');		
	}
}


/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Zoner Theme 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
if ( ! function_exists( 'zoner_body_classes' ) ) :
function zoner_body_classes( $classes ) {
	global $zoner_prefix, $zoner_config;
	$posts_page = get_option( 'page_for_posts' );	
	
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_header_image() ) {
		$classes[] = 'header-image';
	} else {
		$classes[] = 'masthead-fixed';
	}

	if ( is_archive() || is_search() || is_home() || is_404() || is_tag() || is_category()) {
		$classes[] = 'list-view';
		$classes[] = 'page-sub-page';
		$classes[] = 'page-legal';
	}
	
	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$classes[] = 'footer-widgets';
	}

	if ( is_singular() && !is_front_page() && !is_page()) {
		$classes[] = 'singular';
		$classes[] = 'page-sub-page';
		$classes[] = 'page-legal';
	}

	
	if ( is_front_page() || (is_home() && (empty($posts_page) && ($posts_page > 0)))) {
		$classes[] = 'page-homepage';
	} 
	
	return $classes;
}
endif; //zoner_body_classes


/**
 * Extend the default WordPress post classes.
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 
 * @since Zoner Theme 1.0
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
if ( ! function_exists( 'zoner_post_classes' ) ) :
function zoner_post_classes( $classes ) {
	if ( ! post_password_required() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}
	return $classes;
}
endif; //zoner_post_classes

if ( ! function_exists( 'zoner_list_authors' ) ) :
/**
 * Print a list of all site contributors who published at least one post.
 * @since Zoner Theme 1.0
 * @return void
 */
function zoner_list_authors() {
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'orderby' => 'post_count',
		'order'   => 'DESC',
		'who'     => 'authors',
	) );

	foreach ( $contributor_ids as $contributor_id ) :
		$post_count = count_user_posts( $contributor_id );

		// Move on if user has not published a post (yet).
		if ( ! $post_count ) {
			continue;
		}
	?>

	<div class="contributor">
		<div class="contributor-info">
			<div class="contributor-avatar"><?php echo get_avatar( $contributor_id, 132 ); ?></div>
			<div class="contributor-summary">
				<h2 class="contributor-name"><?php echo get_the_author_meta( 'display_name', $contributor_id ); ?></h2>
				<p class="contributor-bio">
					<?php echo get_the_author_meta( 'description', $contributor_id ); ?>
				</p>
				<a class="contributor-posts-link" href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
					<?php printf( _n( '%d Article', '%d Articles', $post_count, 'zoner-lite' ), $post_count ); ?>
				</a>
			</div><!-- .contributor-summary -->
		</div><!-- .contributor-info -->
	</div><!-- .contributor -->

	<?php
	endforeach;
}
endif; //zoner_list_authors


/**
 * Getter function for Featured Content Plugin.
 * @since Zoner Theme 1.0
 * @return array An array of WP_Post objects.
 */
if ( ! function_exists( 'zoner_get_featured_posts' ) ) :
function zoner_get_featured_posts() {
	/**
	 * Filter the featured posts to return in Zoner Theme.
	 * @since Zoner Theme 1.0
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'zoner_get_featured_posts', array() );
}
endif; //zoner_get_featured_posts

/**
 * A helper conditional function that returns a boolean value.
 * @since Zoner Theme 1.0
 * @return bool Whether there are featured posts.
 */
if ( ! function_exists( 'zoner_has_featured_posts' ) ) :
function zoner_has_featured_posts() {
	return ! is_paged() && (bool) zoner_get_featured_posts();
}
endif; //zoner_has_featured_posts

/*Custom functions*/
if ( ! function_exists( 'zoner_get_logo' ) ) {
	function zoner_get_logo() {
		global $zoner_config;

		$original_logo = $retina_logo = $width = $height = null;
		if (!empty($zoner_config['logo-dimensions']['width']))
			$width 	= $zoner_config['logo-dimensions']['width'];
		if (!empty($zoner_config['logo-dimensions']['height']))
			$height = $zoner_config['logo-dimensions']['height'];

		if (!empty($zoner_config['logo']['url'])) { $original_logo = esc_url($zoner_config['logo']['url']); } else { $original_logo = ''; }
		if (!empty($zoner_config['logo-retina']['url'])) { $retina_logo 	 = esc_url($zoner_config['logo-retina']['url']);  } else {  $retina_logo   = ''; }

		/*Full Backend Options*/
		$description  = $name = '';
		$description  = esc_attr(get_bloginfo('description'));
		$name  		  = esc_attr(get_bloginfo('name'));

		if (!empty($original_logo) || !empty($retina_logo)) {
			if ($original_logo) echo '<a class="navbar-brand nav logo" href="' 			. esc_url( home_url( '/' ) ) . '" title="' . $description .'" rel="home"><img style="width:'.$width.'; height:'.$height.';" width="'.$width.'" height="'.$height.'" src="'. $original_logo  .'" alt="' . $description . '"/></a>';
			if ($retina_logo) 	echo '<a class="navbar-brand nav logo retina" href="' 	. esc_url( home_url( '/' ) ) . '" title="' . $description .'" rel="home"><img style="width:'.$width.'; height:'.$height.';" width="'.$width.'" height="'.$height.'" src="'. $retina_logo    .'" alt="' . $description . '"/></a>';

		} else {
			echo '<a class="navbar-brand nav" href="' . esc_url( home_url( '/' ) ) . '" title="'. $description .'" rel="home"><h1 class="site-title">'. $name .'</h1><h2 class="site-description">'. $description .'</h2></a>';
		}
	}
}

if ( ! function_exists( 'zoner_get_main_nav' ) ) {
	function zoner_get_main_nav() {
		?>
		<nav class="primary-navigation collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation" aria-label="<?php _e( 'Primary Navigation', 'zoner-lite' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				 wp_nav_menu( array( 
								'theme_location' 	=> 'primary', 
								'menu_class' 	 	=> 'nav navbar-nav', 
								'container'		 	=> false, 
								'walker' 			=> new zoner_submenu_class())); 
			} else {
				?>
					<ul class="nav navbar-nav">
						<?php wp_list_pages(array('title_li' => '', 'sort_column' => 'ID', 'walker' => new Zoner_Page_Walker())); ?>	
					</ul>
				<?php	
			}		
			?>
		</nav>	
		<?php
	}
}		


/*Search form*/
if ( ! function_exists( 'zoner_search_form' ) ) {
	function zoner_search_form( $form ) {
		$form = '';
							
		$form .= '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >';
			$form .= '<div class="input-group">';
				$form .= '<label for="s" class="screen-reader-text">'.__('Search', 'zoner-lite').'</label>';
				$form .= '<input type="search" class="form-control" value="' . get_search_query() . '" name="s" id="s" placeholder="'.__('Enter Keyword', 'zoner-lite').'"/>';
				$form .= '<span class="input-group-btn"><button class="btn btn-default search" type="button" aria-label="'.__('Search', 'zoner-lite').'"><i class="fa fa-search" aria-hidden="true"></i></button></span>';
				$form .= '<input type="submit" class="screen-reader-text" value="'.__('Search', 'zoner-lite').'" tabindex="-1"/>';
			$form .= '</div><!-- /input-group -->';
		$form .= '</form>';
		return $form;
	}
} //zoner_search_form

if ( ! function_exists( 'zoner_kses_data' ) ) {
	function zoner_kses_data($text = null) {
		$allowed_tags = wp_kses_allowed_html( 'post' );
		return wp_kses($text, $allowed_tags);
	}
}

if ( ! function_exists( 'zoner_change_excerpt_more' ) ) {
	function zoner_change_excerpt_more( $more ) {
		return '&#8230;<span class="screen-reader-text">  '.get_the_title().'</span>';
	}
}

if ( ! function_exists( 'zoner_modify_read_more_link' ) ) {
	function zoner_modify_read_more_link() {
		return '';
	}
}	

if ( ! function_exists( 'zoner_get_footer_area_sidebars' ) ) {
	function zoner_get_footer_area_sidebars() {
		global $zoner_config;
		
		$footer_dynamic_sidebar = '';
		$zoner_sidebars_class = array();
		$total_sidebars_count = 0;
		$total_sidebars_count = $zoner_config['footer-widget-areas'];
		
		
		if ($total_sidebars_count != 0) {
			
			if ($total_sidebars_count == 1) {
				$zoner_sidebars_class[] = 'col-md-12';
				$zoner_sidebars_class[] = 'col-sm-12';
			} else if ($total_sidebars_count == 2) {
				$zoner_sidebars_class[] = 'col-md-6';
				$zoner_sidebars_class[] = 'col-sm-6';
			} else if ($total_sidebars_count == 3) {
				$zoner_sidebars_class[] = 'col-md-4';
				$zoner_sidebars_class[] = 'col-sm-4';
			} else if ($total_sidebars_count == 4) {
				$zoner_sidebars_class[] = 'col-md-3';
				$zoner_sidebars_class[] = 'col-sm-3';
			} else {
				$zoner_sidebars_class[] = 'col-md-3';
				$zoner_sidebars_class[] = 'col-sm-3';
			}
		
		
			ob_start();
						
			for ( $i = 1; $i <= intval( $total_sidebars_count ); $i++ ) {
				
				if (zoner_active_sidebar('footer-'.$i)) {
					echo '<div class="'.implode(' ', $zoner_sidebars_class).'">';
						zoner_sidebar('footer-'.$i);
					echo '</div>';
				}
			} 
			
			$footer_dynamic_sidebar = ob_get_clean();
			if (!empty($footer_dynamic_sidebar)) {
			
			?>
			
				<section id="footer-main">
					<div class="container">
						<div class="row">		
							<?php echo $footer_dynamic_sidebar; ?>		
						</div>	
					</div>	
				</section>	
			<?php 
			
			}
		}
	}
}	


if ( ! function_exists( 'zoner_get_social' ) ) {
	function zoner_get_social() {
		global $zoner_config, $zoner_is_redux_active;
		$ftext = $fsocial = $out_ftext = ''; 
		$out_ = '';
		
		if (!empty($zoner_config['footer-text'])) {
			$ftext = zoner_kses_data(stripslashes($zoner_config['footer-text']));
		} elseif (!$zoner_is_redux_active) {
			$ftext = __('Zoner Lite theme by <a title="WordPress Development" href="https://github.com/fruitfulcode/">Fruitful Code</a>, Powered by <a href="http://wordpress.org/">WordPress</a>', 'zoner-lite');
		}
			
		if (is_home() || is_front_page()) {
			$out_ftext .= $ftext;
		} else {
			$out_ftext .= '<nofollow>';
				$out_ftext .= $ftext;
			$out_ftext .= '</nofollow>';
			
		}
		
		if (!empty($zoner_config['footer-issocial'])) {
			if ($zoner_config['footer-issocial']) {
				$fsocial .= '<div class="social pull-right">';
					$fsocial .= '<div class="icons">';
					if (!empty($zoner_config['facebook-url'])) 	{ $fsocial .= '<a title="'.__('Facebook', 'zoner-lite').'" 	href="'.esc_url($zoner_config['facebook-url']).'"><i class="icon social_facebook"></i></a>'; }	
					if (!empty($zoner_config['twitter-url'])) 	{ $fsocial .= '<a title="'.__('Twitter', 'zoner-lite').'" 	href="'.esc_url($zoner_config['twitter-url']).'"><i class="icon social_twitter"></i></a>'; }	
					if (!empty($zoner_config['linkedin-url'])) 	{ $fsocial .= '<a title="'.__('Linked In', 'zoner-lite').'" href="'.esc_url($zoner_config['linkedin-url']).'"><i class="icon social_linkedin"></i></a>'; }	
					if (!empty($zoner_config['myspace-url'])) 	{ $fsocial .= '<a title="'.__('My space', 'zoner-lite').'" 	href="'.esc_url($zoner_config['myspace-url']).'"><i class="icon social_myspace"></i></a>'; }	
					if (!empty($zoner_config['gplus-url'])) 	{ $fsocial .= '<a title="'.__('Google+', 'zoner-lite').'"	href="'.esc_url($zoner_config['gplus-url']).'"><i class="icon social_googleplus"></i></a>'; }	
					if (!empty($zoner_config['dribbble-url'])) 	{ $fsocial .= '<a title="'.__('Dribble', 'zoner-lite').'" 	href="'.esc_url($zoner_config['dribbble-url']).'"><i class="icon social_dribbble"></i></a>';	}						
					if (!empty($zoner_config['flickr-url'])) 	{ $fsocial .= '<a title="'.__('Flickr', 'zoner-lite').'" 	href="'.esc_url($zoner_config['flickr-url']).'"><i class="icon social_flickr"></i></a>'; }						
					if (!empty($zoner_config['youtube-url'])) 	{ $fsocial .= '<a title="'.__('YouTube', 'zoner-lite').'" 	href="'.esc_url($zoner_config['youtube-url']).'"><i class="icon social_youtube"></i></a>'; }						
					if (!empty($zoner_config['delicious-url'])) 	{ $fsocial .= '<a title="'.__('Delicious', 'zoner-lite').'" href="'.esc_url($zoner_config['delicious-url']).'"><i class="icon social_delicious"></i></a>'; }						
					if (!empty($zoner_config['deviantart-url']))	{ $fsocial .= '<a title="'.__('Deviantart', 'zoner-lite').'" href="'.esc_url($zoner_config['deviantart-url']).'"><i class="icon social_deviantart"></i></a>'; }						
					if (!empty($zoner_config['rss-url'])) 			{ $fsocial .= '<a title="'.__('RSS', 'zoner-lite').'" 		href="'.esc_url($zoner_config['rss-url']).'"><i class="icon social_rss"></i></a>'; }						
					if (!empty($zoner_config['instagram-url']))  { $fsocial .= '<a title="'.__('Instagram', 'zoner-lite').'" href="'.esc_url($zoner_config['instagram-url']).'"><i class="icon social_instagram"></i></a>'; }						
					if (!empty($zoner_config['pinterest-url']))  { $fsocial .= '<a title="'.__('Pinterset', 'zoner-lite').'" href="'.esc_url($zoner_config['pinterest-url']).'"><i class="icon social_pinterest"></i></a>'; }						
					if (!empty($zoner_config['vimeo-url'])) 		{ $fsocial .= '<a title="'.__('Vimeo', 'zoner-lite').'" 	href="'.esc_url($zoner_config['vimeo-url']).'"><i class="icon social_vimeo"></i></a>'; }						
					if (!empty($zoner_config['picassa-url'])) 		{ $fsocial .= '<a title="'.__('Picassa', 'zoner-lite').'" 	href="'.esc_url($zoner_config['picassa-url']).'"><i class="icon social_picassa"></i></a>'; }						
					if (!empty($zoner_config['social_tumblr']))		{ $fsocial .= '<a title="'.__('Tumblr', 'zoner-lite').'" 	href="'.esc_url($zoner_config['social_tumblr']).'"><i class="icon social_tumblr"></i></a>'; }						
					if (!empty($zoner_config['email-address']))  	{ $fsocial .= '<a title="'.__('Email', 'zoner-lite').'" 	href="mailto:'.esc_attr($zoner_config['email-address']).'"><i class="icon icon_mail_alt"></i></a>'; }						
					if (!empty($zoner_config['skype-username'])) 	{ $fsocial .= '<a title="'.__('Call to', 'zoner-lite').' '.esc_attr($zoner_config['skype-username']).'" href="href="skype:'.esc_attr($zoner_config['skype-username']).'?call"><i class="icon social_skype"></i></a>'; }						
					$fsocial .= '</div><!-- /.icons -->';
				$fsocial .= '</div><!-- /.social -->';
			}
		}
		

		$out_ = '<section id="footer-copyright">';
			$out_ .= '<div class="container">';
				if (!empty($out_ftext)) {
					$out_ .= '<div class="copyright pull-left">'.$out_ftext.'</div><!-- /.copyright -->';
				} else {
					$out_ .= '<div class="copyright pull-left"><a title="'.get_bloginfo('name').'" href="'.home_url( '/' ).'">'.$out_ftext.'</a></div><!-- /.copyright -->';
				}
				
				if ($fsocial != '') $out_ .= $fsocial;
				$out_ .='<span class="go-to-top pull-right"><a href="#page-top" class="roll">' . __('Go to top', 'zoner-lite') . '</a></span>';
				
			$out_ .= '</div><!-- /.container -->';
		$out_ .= '</section>';
		
		echo $out_;
	}
}


if ( ! function_exists( 'zoner_visibilty_comments' ) ) {
	function zoner_visibilty_comments() {
		global $zoner_config, $post, $zoner_is_redux_active;
		
		if (!empty($zoner_config['pp-comments']) || !$zoner_is_redux_active) {
			$is_comment = $zoner_config['pp-comments'];
			$post_type = get_post_type();
			if ( ( $is_comment == $post_type || $is_comment == 'both' || !$zoner_is_redux_active ) && is_page() ) { 
				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) { comments_template(); }
			}	
			
			if ( ( $is_comment == $post_type || $is_comment == 'both' || !$zoner_is_redux_active ) && is_single() ) { 
				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) { comments_template(); }
			}	
		}	
	}
}

if ( ! function_exists( 'zoner_seconadry_navigation' ) ) {
	function zoner_seconadry_navigation() {
		global $zoner_config;
		
		$current_user = wp_get_current_user();
		
		$site_url = home_url( '/' );
		if (function_exists('icl_get_home_url'))
			$site_url = icl_get_home_url();
		?>
		<div class="secondary-navigation">
			<div class="container">
				<div class="contact">
					<?php if (!empty($zoner_config['header-phone'])) { ?>	
						<figure><strong><?php _e('Phone', 'zoner-lite'); ?>:</strong><?php echo esc_attr($zoner_config['header-phone']); ?></figure>
					<?php } ?>
					<?php if (!empty($zoner_config['header-email'])) { ?>	
						<figure><strong><?php _e('Email', 'zoner-lite'); ?>:</strong><a href="mailto:<?php echo esc_attr($zoner_config['header-email']); ?>"><?php echo esc_attr($zoner_config['header-email']); ?></a></figure>
					<?php } ?>
				</div>
				<div class="user-area">
					<div class="actions">
						<?php if ( is_user_logged_in() ) { ?>
							<a class="promoted" href="<?php echo get_author_posts_url($current_user->ID); ?>"><i class="fa fa-user" aria-hidden="true"></i> <strong><?php echo $current_user->display_name; ?></strong></a>
							<a class="promoted logout" href="<?php echo wp_logout_url(esc_url($site_url)); ?>" title="<?php _e('Sign Out', 'zoner-lite'); ?>"><?php _e('Sign Out', 'zoner-lite'); ?></a>
						<?php } ?>
					</div>
					<?php 
						if (isset($zoner_config['wmpl-flags-box'])) {
							if ( function_exists( 'icl_get_languages' ) ) {
								$languages = icl_get_languages('skip_missing=0&orderby=code');
									if(!empty($languages)) {
					?>
										<div class="language-bar">
											<?php 
									
												foreach($languages as $l) { 
													if($l['country_flag_url']){
														if(!$l['active']) { echo '<a href="'.$l['url'].'">'; } else { echo '<a class="active" href="'.$l['url'].'">'; };
															echo '<img src="'.$l['country_flag_url'].'" height="11" alt="'.$l['language_code'].'" width="16" />';
														if(!$l['active']) echo '</a>';
													}
									
												}
											?>
										</div>
					<?php 
								}
							}
						}
					?>	
				</div>
				
				
			</div>
		</div>	
		
		<?php
	}
}
			

if ( ! function_exists( 'zoner_before_content' ) ) {
	function zoner_before_content() {
		$elem_class = array();
		$elem_class[] = 'wpb_row';
		$elem_class[] = 'vc_row-fluid';
		$elem_class[] = 'block';
	?>
		<section class="<?php echo implode(' ', $elem_class); ?>">
			<div class="container">
				<div class="row">	
	<?php	        
		
	}
}


if ( ! function_exists( 'zoner_after_content' ) ) {
	function zoner_after_content () {
	
	?>
				</div>
			</div>
		</section>		
	<?php	        
		
	}
}

if ( ! function_exists( 'zoner_zoner_get_sidebar_part' ) ) {
	function zoner_get_sidebar_part($sidebar) {
		global $zoner_config, $zoner_prefix;
	?>
		<div id="sidebar" class="sidebar" role="complementary">
			<?php if (zoner_active_sidebar($sidebar)) zoner_sidebar($sidebar); ?>	
		</div>
	<?php
	}
}		
	
if ( ! function_exists( 'zoner_get_content_part' ) ) {
	function zoner_get_content_part($type_in) {
		$title = ''; 
		echo '<section id="content" role="main">';
			if ( have_posts() ) {
				
				$page_for_posts = get_option( 'page_for_posts' ); 
				$page_on_front  = get_option('page_on_front');
				
				if (is_home() && !empty($page_for_posts)) { 
					echo '<header><h1>'.get_the_title($page_for_posts).'</h1></header>'; 
				}  elseif (is_front_page() && empty($page_for_posts) && empty($page_on_front)) {
					echo '<header><h1>'.__('Latest posts', 'zoner-lite').'</h1></header>'; 
				}
				
				if (is_archive()) {
					if ( is_day() ) :
						$title = sprintf( __( 'Daily Archives: %s', 'zoner-lite' ),   get_the_date() );
					elseif ( is_month() ) :
						$title = sprintf( __( 'Monthly Archives: %s', 'zoner-lite' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'zoner-lite' ) ) );
					elseif ( is_year() ) :
						$title = sprintf( __( 'Yearly Archives: %s', 'zoner-lite' ),  get_the_date( _x( 'Y', 'yearly archives date format', 'zoner-lite' ) ) );
					else :
						$title = __( 'Archives', 'zoner-lite' );
					endif;
				}
				
				
				
				if (is_category()) $title = sprintf( __( 'Category: %s', 'zoner-lite' ), single_cat_title( '', false ) );
				if (is_search()) $title = sprintf( __( 'Search Results for: %s', 'zoner-lite' ), get_search_query() );
				if (is_tag()) $title = sprintf( __( 'Tag Archives: %s', 'zoner-lite' ), single_tag_title( '', false ) );
				
				if ($title != '') echo '<header><h1>'.$title.'</h1></header>';
				
					while ( have_posts() ) : the_post();
						if ($type_in == 'page') {
							get_template_part( 'content', 'page' );
						} elseif ($type_in == 'front-page') {
							the_content();
						} else {
							get_template_part( 'content', get_post_format() );
						}						
					endwhile;
					
				if ($type_in == 'post') {
					the_posts_pagination();
				}
			} else {
				echo '<header><h1>'. __('Nothing Found', 'zoner-lite').'</h1></header>';
				get_template_part( 'content', 'none' );
			}
		echo '</section>';
	}
}	
	
if ( ! function_exists( 'zoner_get_main_content' ) ) {
	function zoner_get_main_content () {
		global $zoner_config, $zoner_prefix, $post;
		$layout = 3;
		$sidebar = 'secondary';
		$add_wrapper = true;
		
		$type   = get_post_type( $post );
		
		if ($type == 'page') {
				$page_layout = get_post_meta($post->ID, $zoner_prefix.'pages_layout', true);
			if ($page_layout) $layout = $page_layout;
			$sidebar = 'secondary';
		} else {
			if (!empty($zoner_config['pp-post'])) $layout = (int)$zoner_config['pp-post'];
			$sidebar = 'primary';
		}						
		
		$page_on_front = get_option('page_on_front');
		if (is_front_page() && !empty($page_on_front)) {
			$page_layout = get_post_meta($post->ID, $zoner_prefix.'pages_layout', true);
			
			if ($page_layout) $layout  = $page_layout;
			
			$type 	 = 'front-page';
			$sidebar = 'primary';
			
			$front_page_content = $post->post_content;
			if (strpos($front_page_content, 'vc_row') !== false) $add_wrapper = false;
				
		}
		
		
		if ($layout == -1) {
			zoner_get_content_part($type);
		} elseif ($layout == 1) {
	?>
		<?php if ($add_wrapper) { ?>
			<div class="col-md-12 col-sm-12">
		<?php } ?>
			<?php zoner_get_content_part($type);?> 
		<?php if ($add_wrapper) { ?>
			</div>
		<?php } ?>	
	<?php 
		} else if ($layout == 2) { 
	?>	
		<div class="col-md-3 col-sm-3">
			<?php zoner_get_sidebar_part($sidebar); ?>
		</div>
		<div class="col-md-9 col-sm-9">
			<?php zoner_get_content_part($type); ?>
		</div>
			
	<?php 
		} else if ($layout == 3) {
	?>
		<div class="col-md-9 col-sm-9">
			<?php zoner_get_content_part($type); ?>
		</div>
		<div class="col-md-3 col-sm-3">
			<?php zoner_get_sidebar_part($sidebar); ?>
		</div>
		
			
	<?php	
		}
	}	
}

if ( ! function_exists( 'zoner_nav_parent_class' ) ) {			
	function zoner_nav_parent_class( $classes, $item ) {
		$cpt_name = array('');
		if ( in_array(get_post_type(), $cpt_name) && ! is_admin() ) {

			$classes = str_replace( 'current_page_parent', '', $classes );
			$page    = get_page_by_title( $item->title, OBJECT, 'page' );
			if (!empty($page->post_name)) {
				if($page->post_name === get_post_type())  $classes[] = 'current_page_parent';
			}	
		}
		
		return $classes;
	} 
}

if ( ! function_exists( 'zoner_set_excerpt_length' ) ) {				
	function zoner_set_excerpt_length( $length ) {
		return 75;
	}
}

if ( ! function_exists( 'zoner_post_chat' ) ) {				
	function zoner_post_chat($content = null) {
		global $post;
		$format = null;
		if (isset($post)) $format = get_post_format( $post->ID );
		$cnt = 0;
		
		if ($format == 'chat') {
			if (($post->post_type == 'post') && ($format == 'chat')) {
					remove_filter ('the_content',  'wpautop');
					$chatoutput = "<dl class=\"chat\">\n";
					$split = preg_split("/(\r?\n)+|(<br\s*\/?>\s*)+/", $content);
						foreach($split as $haystack) {
							if (strpos($haystack, ":")) {
								$string 	= explode(":", trim($haystack), 2);
								$who 		= strip_tags(trim($string[0]));
								$what 		= strip_tags(trim($string[1]));
								$chatoutput = $chatoutput . "<dt><i class='fa fa-weixin' aria-hidden='true'></i><span class='chat-author'><strong>$who:</strong></span></dt><dd>$what</dd>\n";
							}
							else {
								$chatoutput = $chatoutput . $haystack . "\n";
							}
							$cnt++;
							
							if (!is_single()) {
								if ($cnt > 2) break;
							}	
						}
						$content = $chatoutput . "</dl>\n";
						return $content;
			}
		} else {
			return $content;
		}
	}
}

if ( ! function_exists( 'zoner_img_caption' ) ) {				
	function zoner_img_caption( $empty_string, $attributes, $content ){
		extract(shortcode_atts(array(
			'id' 		=> '',
			'align' 	=> 'alignnone',
			'width' 	=> '',
			'caption' 	=> ''
		), $attributes));
  
		if ( empty($caption) ) return $content;
		if ($id ) $id = 'id="' . esc_attr($id) . '" ';
		return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width:'.$width.'px;">' . do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
	}
}

if ( ! function_exists( 'zoner_get_author_information' ) ) {				
	function zoner_get_author_information() {	
		?>
		<?php if ( have_posts() ) : ?>
			<?php the_post(); ?>
			<section id="author-detail" class="author-detail">
				<header><h1><?php printf( __( 'All posts by "%s"', 'zoner-lite' ), get_the_author()); ?></h1></header>
			</section>	
				
			<?php if ( get_the_author_meta( 'description' ) ) : ?>
				<div class="author-description"><?php the_author_meta( 'description' ); ?></div>
			<?php endif; ?>

			<?php
				rewind_posts();

				while ( have_posts() ) : the_post();
					get_template_part( 'content', get_post_format() );
				endwhile;
				the_posts_pagination();
				else :
					get_template_part( 'content', 'none' );
				endif;
	}
}	


/*Blog*/

/*Get Post Thumbnail*/
if ( ! function_exists( 'zoner_get_post_thumbnail' ) ) {				
	function zoner_get_post_thumbnail() {
		global $zoner_config, $post, $zoner_is_redux_active;
		
		if ( has_post_thumbnail() && ($zoner_config['pp-thumbnail'] || ! $zoner_is_redux_active)) {
			$attachment_id = get_post_thumbnail_id( $post->ID );
			$post_thumbnail = wp_get_attachment_image_src( $attachment_id, 'full');
		?> 
			<?php if (!is_single()) { ?>
				<a href="<?php the_permalink();?>">	 
			<?php } ?>
				<img src="<?php echo $post_thumbnail[0]; ?>" alt="" />
			<?php if (!is_single()) { ?>
				</a> 
			<?php } ?>
		<?php	
		} 
	}
}	

/*Get title*/
if ( ! function_exists( 'zoner_get_post_title' ) ) {				
	function zoner_get_post_title() {
		
		$sticky_icon = '';
		
		if (is_sticky()) $sticky_icon = '<span class="sticky-wrapper"><i class="fa fa-paperclip" aria-hidden="true"></i></span>';
		
		if ( is_single() ) :
			the_title( '<header><h1 class="entry-title">' . $sticky_icon, '</h1></header>' );
		else :
			the_title( '<header><a href="' . esc_url( get_permalink() ) . '"><h2>'. $sticky_icon, '</h2></a></header>' );
		endif;
	}
}	

/*Meta*/
if ( ! function_exists( 'zoner_get_post_meta' ) ) {				
	function zoner_get_post_meta() {
		global $zoner_config, $zoner_is_redux_active;
		
			$archive_year  = get_the_time('Y'); 
			$archive_month = get_the_time('m'); 
					
		?>
			<figure class="meta">
				<?php if ($zoner_config['pp-authors'] || !$zoner_is_redux_active) { ?>
					<a class="link-icon" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' )); ?>">
						<i class="fa fa-user" aria-hidden="true"></i>
						<?php the_author(); ?>
					</a>
				<?php } ?>
				
				<?php if ($zoner_config['pp-date'] || !$zoner_is_redux_active) { ?>
					<a class="link-icon" href="<?php echo get_month_link( $archive_year, $archive_month ); ?>">
						<i class="fa fa-calendar" aria-hidden="true"></i>
						<?php the_time('d/m/Y'); ?>
					</a>
				<?php } ?>
				<?php edit_post_link( '<i title="' . __("Edit", 'zoner-lite') . '" class="fa fa-pencil-square-o" aria-hidden="true"></i>'.__("Edit", 'zoner-lite'), '', '' ); ?>
				<?php if ($zoner_config['pp-tags'] || !$zoner_is_redux_active) the_tags('<div class="tags article-tags">', ' ', '</div>');	?>
			</figure>
		<?php
	}
}	

/*Content none*/
if ( ! function_exists( 'zoner_get_post_none_content' ) ) {				
	function zoner_get_post_none_content() {
	?>
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
				<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'zoner-lite' ), admin_url( 'post-new.php' ) ); ?></p>
			<?php elseif ( is_search() ) : ?>
				<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'zoner-lite' ); ?></p>
			<?php get_search_form(); ?>
			<?php else : ?>
				<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'zoner-lite' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	<?php	
	}
}	

/*Single About The Author*/	
if ( ! function_exists( 'zoner_get_post_about_author' ) ) {				
	function zoner_get_post_about_author() {
	
	?>
		
		<section id="about-author">
			<header><h2><?php _e('About the Author', 'zoner-lite'); ?></h2></header>
			<div class="post-author">
				<?php echo get_avatar( get_the_author_meta( 'ID'),  100 );  ?>
				<div class="wrapper">
					<header><?php the_author(); ?></header>
					<?php the_author_meta( 'description'); ?>
				</div>
			</div>
		</section>	
		
	<?php 
	}
}

if ( ! function_exists( 'zoner_get_home_slider' ) ) {		
	function zoner_get_home_slider() {
		global $zoner_config, $post;
		
		if ((!is_front_page()) || (!$zoner_config['switch-slider'])) { return; }
		
		if (!empty($zoner_config['home-slides'])) {
			$slides = $zoner_config['home-slides'];
			?>
		
			<div id="slider" class="loading has-parallax">
				<div id="loading-icon"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></div>
				<div class="owl-carousel homepage-slider carousel-full-width">
	
					<?php
						foreach ($slides as $item_id) {
							$post = get_post($item_id);
							$attachment_id = get_post_thumbnail_id( $item_id );
							$slide_image = wp_get_attachment_image_src( $attachment_id, 'full');
							setup_postdata($post); ?>	
							
								<div class="slide" style="background-image:url(<?php echo esc_url($slide_image[0]); ?>);">
									<div class="overlay">
										<div class="container">
											<div class="info">
												<h3><?php the_title(); ?></h3>
												<figure><?php echo get_the_excerpt(); ?></figure>
											</div>
											<?php if (empty($zoner_config['slider-hide-read-more'])): ?>
												<hr />
												<a href="<?php the_permalink(); ?>" class="link-arrow"><?php _e('Read More', 'zoner-lite'); ?></a>
											<?php endif; ?>
										</div>
									</div>
								</div>
					
							<?php
							wp_reset_postdata();
						}
					?>						
					
				</div>
			</div>
			<?php
		}
		
 	}
}	

/*Read More*/
if ( ! function_exists( 'zoner_get_readmore_link' ) ) {				
	function zoner_get_readmore_link() {
		?> 
			<a class="link-arrow" href="<?php the_permalink();?>"><?php _e('Read More', 'zoner-lite'); ?><span class="screen-reader-text"> <?php echo get_the_title(); ?></span></a>
		<?php	
	}
}

if ( ! function_exists( 'zoner_edit_post_link' ) ) {				
	function zoner_edit_post_link($output) {
		$output = str_replace('class="post-edit-link"', 'class="link-icon"', $output);
		return $output;
	}
}	