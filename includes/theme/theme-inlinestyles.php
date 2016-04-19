<?php
if ( ! function_exists( 'zoner_get_inline_styles' ) ) {				
	function zoner_get_inline_styles () {
		global $zoner_config;
		$style = '';
		
		if (!empty($zoner_config['content-link-color'])) {
			$regular = $hover = $active = '';
			
			$regular	= esc_attr($zoner_config['content-link-color']['regular']);
			$hover 		= esc_attr($zoner_config['content-link-color']['hover']);
			$active		= esc_attr($zoner_config['content-link-color']['active']);
			
			$style .= '
					a { color:'.$regular.'; }
					a:hover { color:'.$hover.'; }
					a:active { color:'.$active.';}
				';
		}
		
		/*Logo*/
		if(!empty($zoner_config['logo-retina']['url'])) {
			$style .= '
				@media only screen and (-webkit-min-device-pixel-ratio: 2), 
					only screen and (min-device-pixel-ratio: 2),
					only screen and (min-resolution: 2dppx) {
						.navbar .navbar-header .navbar-brand.nav.logo { display: none; }
						.navbar .navbar-header .navbar-brand.nav.logo.retina 	{ display: inline-block; width:50%;}
					}'. "\n";
		} 
		
		
		/*Body*/
		
		if (!empty($zoner_config['body-background'])) {
			$body_styles = $zoner_config['body-background'];
			$bg_color	 = $body_styles['background-color'];
			$style .= '
				.page-sub-page #page-content::after {
					background: -moz-linear-gradient(top, #f1f1f1 0%, '.$bg_color.' 80%);
					background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f1f1f1), color-stop(80%, '.$bg_color.'));
					background: -webkit-linear-gradient(top, #f1f1f1 0%, '.$bg_color.' 80%);
					background: -o-linear-gradient(top, #f1f1f1 0%, '.$bg_color.' 80%);
					background: -ms-linear-gradient(top, #f1f1f1 0%, '.$bg_color.' 80%);
					background: linear-gradient(to bottom, #f1f1f1 0%, '.$bg_color.' 80%);
					filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#f1f1f1", endColorstr="'.$bg_color.'",GradientType=0 );
				}	
			';	
				
			if (!empty($body_styles['media']['id']) && isset($body_styles['media']['id'])) {
				$style .= '
					.page-sub-page #page-content:after { 
						background:inherit; 
					}
				';	
				
			}
		}
		
		/*Header*/
		if (!empty($zoner_config['header-background-color'])) {
			 $style .= '
				 .navigation { 
					background-color:'.esc_attr($zoner_config['header-background-color']).'; 
				 }
			 ';	
		}
		
		/*Menu First level*/
		if (!empty($zoner_config['menu-link-color'])) {
			$regular = $hover = $active = '';
			
			$regular	= esc_attr($zoner_config['menu-link-color']['regular']);
			$hover 		= esc_attr($zoner_config['menu-link-color']['hover']);
			$active		= esc_attr($zoner_config['menu-link-color']['active']);
			
			$style .= '
					.navigation .navbar .navbar-nav > li a { color:'.$regular.'; }
					.navigation .navbar .navbar-nav > li > a:hover,
					.navigation .navbar .navbar-nav > li > a:focus { color:'.$hover.'; }
					
					.navigation .navbar .navbar-nav > li.current_page_item > a, 
					.navigation .navbar .navbar-nav > li.current-menu-item > a, 
					.navigation .navbar .navbar-nav > li.current-menu-parent > a, 
					.navigation .navbar .navbar-nav > li.current_page_parent > a, 
					.navigation .navbar .navbar-nav > li.current-menu-ancestor > a, 
					.navigation .navbar .navbar-nav > li.active a {
						color:'.$active.';
					}
					';
		}
		
		/*Submenu link color*/
		if (!empty($zoner_config['submenu-link-color'])) {
			$regular = $hover = $active = '';
			
			$regular	= esc_attr($zoner_config['submenu-link-color']['regular']);
			$hover 		= esc_attr($zoner_config['submenu-link-color']['hover']);
			$active		= esc_attr($zoner_config['submenu-link-color']['active']);
			
			$bg_regular	= esc_attr($zoner_config['submenu-itembg-color']['regular']);
			$bg_hover 	= esc_attr($zoner_config['submenu-itembg-color']['hover']);
			$bg_active	= esc_attr($zoner_config['submenu-itembg-color']['active']);
			
			
			$style .= '
						.navigation .navbar .navbar-nav > li .child-navigation a {
							color:'.$regular.';
						}
						
						.navigation .navbar .navbar-nav > li .child-navigation li a:hover {
							background-color:'.$bg_hover.';
							color:'.$hover.';
						}
						
						.navigation .navbar .navbar-nav > li .child-navigation > li:hover > a, 
						.navigation .navbar .navbar-nav > li .child-navigation > li.current-menu-ancestor > a, 
						.navigation .navbar .navbar-nav > li .child-navigation > li .child-navigation > li.current-menu-item > a, 
						.navigation .navbar .navbar-nav > li.current-menu-ancestor > .child-navigation li.current-menu-item > a {
							background-color:'.$bg_active.';
							color:'.$active.';
						 }
					';
		}
		
		if (!empty($zoner_config['submenu-color'])) {
			 $submenu_color = esc_attr($zoner_config['submenu-color']);
			 $style .= '
				.navigation .navbar .navbar-nav > li .child-navigation {
					background-color:'.$submenu_color.'; 
				}
				
				.navigation .navbar .navbar-nav > li > .child-navigation > li:first-child a:after {
					border-color: transparent transparent '.$submenu_color.' transparent;
				}
			 ';	
		}
		
		if (!empty($zoner_config['submenu-itemborder-color'])) {
			$rgba = array();
			$rgba = $zoner_config['submenu-itemborder-color'];
			$style .= '
				.navigation .navbar .navbar-nav > li .child-navigation li a {
					border-color:rgba(' . redux_Helpers::hex2rgba($rgba['color']) . ',' . $rgba['alpha'] . '); 
				}
			';	
		}
		
		if (!empty($zoner_config['underline-item-color'])) {
			$style .= '
				.navigation .navbar .navbar-nav > li a:after {
					background-color:'.esc_attr($zoner_config['underline-item-color']).'; 
				}
			';	
		}
		
		
		if (!empty($zoner_config['p-opacity'])) {
			$opacity = $zoner_config['p-opacity'];
			$style .= '
					.blog-posts .blog-post .blog-post-content p, .container p {
						filter: progid:DXImageTransform.Microsoft.Alpha(Opacity='. $opacity*100 .');
						opacity: '.$opacity.';
					}
				
			';
		}
		
		
		/*Footer*/
		if (!empty($zoner_config['footer-copyright-color'])) {
			$color = $zoner_config['footer-copyright-color'];
			$style .= '
					#page-footer .inner #footer-copyright {
						color:'.$color.';
					}
			';
		}
		
		if (!empty($zoner_config['footer-copyright-bg-color'])) {
			$bg_color = $zoner_config['footer-copyright-bg-color'];
			$style .= '
					#page-footer .inner #footer-copyright {
						background-color:'.$bg_color.';
					}
			';
		}
		
		if (!empty($zoner_config['custom-css'])) {
			$style .= wp_kses_stripslashes($zoner_config['custom-css']);
		}
			
		if (!empty($zoner_config['global-color-scheme'])) {
			$main_color = $zoner_config['global-color-scheme'];
			
			$primary_color 		= '#007cbe';
			$secondary_color 	= '#128dd4';
			
			
			if ($main_color == 1) {
				$primary_color 		= '#998675';
				$secondary_color	= '#937E6C';
			} else if ($main_color == 2) {
				$primary_color 		= '#00c109';
				$secondary_color 	= '#00B309';
			} else if ($main_color == 3) {
				$primary_color 		= '#707070';
				$secondary_color 	= '#686868';
			} else if ($main_color == 4) {
				$primary_color 		= '#e83183';
				$secondary_color 	= '#E7237B';
			} else if ($main_color == 5) {
				$primary_color 		= '#f7941d';
				$secondary_color 	= '#F79113';
			} else if ($main_color == 6) {
				$primary_color 		= '#e2372f';
				$secondary_color 	= '#e02B21';
			} else if ($main_color == 7) {
				$primary_color 		= '#7c00c3';
				$secondary_color 	= '#7100B5';
			}
			
			$style .= '	
					.background-color-default,	
					.btn.btn-default, select.btn-default,
					.checkbox.switch .icheckbox:before,
					.jGrowl .jGrowl-notification,
					.site .info-box-row .ffs-info-box .ffs-icon-container					{
						background-color:'.$primary_color.';
					}
				';
			
			$style .= '	
					.btn.btn-default:hover, select.btn-default:hover {
						background-color: '.$secondary_color.';
					}
				';
			
			$style .= '	
					a:hover h1, a:hover h2, a:hover h3, a:hover h4, .avatar-wrapper .remove-btn i.fa,
					.link-icon .fa, .link-arrow:after, .link-arrow.back:before,
					.universal-button figure, .universal-button .arrow, ul.list-links li a:hover, .widget ul li a:hover,
					.navigation .navbar .navbar-nav > li.has-child:after,
					.navigation .navbar .navbar-nav li .child-navigation li.has-child:after,
					.navigation .secondary-navigation a.promoted,
					.navigation .secondary-navigation a.sing-in,
					#sidebar ul li, #sidebar ul li, #sidebar .sidebar-navigation li i,
					.comment-list .comment .reply .fa,
					.bootstrap-select .selectpicker .caret:after,
					.bootstrap-select .selectpicker .filter-option:before,
					.error-page .title header, .infomessage .title header,
					.pagination li a:hover, .pagination li a:active, .pagination li a:focus,
					#page-footer .inner #footer-copyright a:hover,
					.navigation .navbar .navbar-nav li.mobile-submit i {
						color:'.$primary_color.';
					}
				';
					
			$style .= ' 
					hr.divider,
					#sidebar .sidebar-navigation li:hover,
					.checkbox.switch .icheckbox:hover,
					.checkbox.switch .icheckbox.checked,
					.pagination li a:hover, .pagination li a:active, .pagination li a:focus {
						border-color:'.$primary_color.';
					}
				';
			
			$style .= ' 
					.navigation .navbar .navbar-nav > li > .child-navigation > li:first-child:hover a:hover:after, 
					.navigation .navbar .navbar-nav > li.current-menu-ancestor > .child-navigation > li.current-menu-item a:after {
						border-color: transparent transparent '.$primary_color.' transparent;
					}
				';
			
			$style .= ' 
					#sidebar .sidebar-navigation li:hover:after {
						border-color: transparent transparent transparent '.$primary_color.';
					}
				';		
				
			$style .= ' 
					.site .info-box-row .ffs-info-box .ffs-icon-container::after {
						border-color: transparent '.$primary_color.' transparent transparent;
					}
				';		
				
		}
		
		if (!empty($style)) 	
			wp_add_inline_style( 'zoner-style', $style); 
		
	}
	add_action('wp_enqueue_scripts', 'zoner_get_inline_styles', 99);
}	