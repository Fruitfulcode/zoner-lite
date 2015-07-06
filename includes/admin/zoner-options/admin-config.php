<?php
if (!class_exists('zoner_config')) {
	
    class zoner_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {
			if (!class_exists('ReduxFramework')) return;
            if (  true == redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }
		
        public function initSettings() {
            $this->theme = wp_get_theme();
            $this->setArguments();
            $this->setHelpTabs();
            $this->setSections();
            if (!isset($this->args['opt_name'])) return;
            add_action( 'zoner/loaded', array( $this, 'remove_demo' ) );
			
			$this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {}

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'zoner-framework'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'zoner-framework'),
                'icon' => 'el-icon-paper-clip',
                'fields' => array()
            );

            return $sections;
        }


        // Remove the demo link and the notice of integrated demo from the zoner-framework plugin
        function remove_demo() {
			// Used to hide the demo mode link from the plugin page. Only used when Zoner is a plugin.
			if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
					remove_filter( 'plugin_row_meta', array(
						ReduxFrameworkPlugin::instance(),
						'plugin_metalinks'
					), null, 2 );

					// Used to hide the activation notice informing users of the demo panel. Only used when Zoner is a plugin.
					remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
			}
        }
		
		
        public function setSections() {
			
			// Background Patterns Reader
            $sample_patterns_url    = get_template_directory_uri() . '/includes/admin/zoner-options/patterns/';
            $sample_patterns        = array();

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'zoner-framework'), $this->theme->display('Name'));
            
            ?>
			
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'zoner-framework'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'zoner-framework'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'zoner-framework') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'zoner-framework'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }
			
			
			/*General Section*/
			$this->sections[] = array(
                'title'     => __('General', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/general.png',
				'icon_type'	=> 'image',
                'fields'    => array (
					
						array(
							'id'        => 'adminbar-displayed',
							'type'      => 'select',
							'title'     => __('Admin bar display', 'zoner-framework'),
							'subtitle'  => __('Choose options for admin bar displaying.', 'zoner-framework'),
							'std'		=> '1',
							'options' 	=> array(
												'1' => __('Display for all', 'zoner-framework'),
												'2' => __('For admin only', 'zoner-framework'),
												'3' => __('Off', 'zoner-framework'),
									
											),
							'default'   => '1',
							'placeholder' => __('Select admin bar options', 'zoner-framework')
						),
						
						array(
							'id'        => 'tracking-code',
							'type'      => 'text',
							'title'     => __('Google Analytics ID', 'zoner-framework'),
							'subtitle'  => __("Paste your web analytics tracking id here (UA-XXXXX-X).", 'zoner-framework'),
							'validate'  => 'no_html',
							'default'   => ''
						),
						
						array(
							'id'        => 'smoothscroll',
							'type'      => 'checkbox',
							'title'     => __('Enhanced scrolling', 'zoner-framework'),
							'subtitle'  => __('Select to enable scrolling library.', 'zoner-framework'),
							'desc'      => __('Yes', 'zoner-framework'),
							'class'		=> 'icheck',
							'default'   => '1'
						),
				)
			);
			
			/*General Section*/
			$this->sections[] = array(
                'title'     => __('Logo', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/logo-options.png',
				'icon_type'	=> 'image',
                'fields'    => array (
							array(
								'id'        => 'logo',
								'type'      => 'media',
								'url'       => false,
								'title'     => __('Logo', 'zoner-framework'),
								'subtitle'  => __('Change your Logo here, upload or enter the URL to your logo image.', 'zoner-framework'),
								'default'   => array('url' => $sample_patterns_url . 'images/logo.png'),
								
							),
							
							array(
								'id'        => 'logo-retina',
								'type'      => 'media',
								'url'       => false,
								'title'     => __('Logo Retina ', 'zoner-framework'),
								'subtitle'  => __('Upload your Retina Logo. This should be your Logo in double size (If your logo is 100 x 20px, it should be 200 x 40px)', 'zoner-framework'),
								'default'   => array ('url' => $sample_patterns_url . 'images/logo@2x.png'),
							),

							 array(
								'id'                => 'logo-dimensions',
								'type'              => 'dimensions',
								'units'    => array('em','px','%'),
								'units_extended'    => 'true',  
								'title'             => __('Original Logo (Width/Height)', 'zoner-framework'),
								'subtitle'          => __("If Retina Logo uploaded, please enter the (width/height) of the Standard Logo you've uploaded (not the Retina Logo)", 'zoner-framework'),
								'default'           => array(
									'width'     => 94, 
									'height'    => 22,
								)
							),
							
							array(
								'id'        => 'favicon',
								'type'      => 'media',
								'url'       => false,
								'title'     => __('Favicon', 'zoner-framework'),
								'subtitle'  => __('A favicon is a 16x16 pixel icon that represents your site; upload your custom Favicon here.', 'zoner-framework'),
								'default'   => array('url' => $sample_patterns_url . 'favicon/favicon-16x16.png'),
							),
							
							array(
								'id'        => 'favicon-iphone',
								'type'      => 'media',
								'url'       => false,
								'title'     => __('Favicon iPhone', 'zoner-framework'),
								'subtitle'  => __('Upload a custom favicon for iPhone (57x57 pixel png).', 'zoner-framework'),
								'default'   => array('url' => $sample_patterns_url . 'favicon/favicon-57x57.png'),
							),
							
							array(
								'id'        => 'favicon-iphone-retina',
								'type'      => 'media',
								'url'       => false,
								'title'     => __('Favicon iPhone Retina', 'zoner-framework'),
								'subtitle'  => __('Upload a custom favicon for iPhone retina (114x114 pixel png).', 'zoner-framework'),
								'default'   => array('url' => $sample_patterns_url . 'favicon/favicon-114x114.png'),
							),
							
							array(
								'id'        => 'favicon-ipad',
								'type'      => 'media',
								'url'       => false,
								'title'     => __('Favicon iPad', 'zoner-framework'),
								'subtitle'  => __('Upload a custom favicon for iPad (72x72 pixel png).', 'zoner-framework'),
								'default'   => array('url' => $sample_patterns_url . 'favicon/favicon-72x72.png'),
							),
							
							array(
								'id'        => 'favicon-ipad-retina',
								'type'      => 'media',
								'url'       => false,
								'title'     => __('Favicon iPad Retina', 'zoner-framework'),
								'subtitle'  => __('Upload a custom favicon for iPhone retina (144x144 pixel png).', 'zoner-framework'),
								'default'   => array('url' => $sample_patterns_url . 'favicon/favicon-144x144.png'),
							),
				)
			);	
			
			
			/*Display options Section*/
			$this->sections[] = array(
                'title'     => __('Blog options', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/display-options.png',
				'icon_type'	=> 'image',
                'fields'    => array (
					array(
                        'id'        => 'pp-comments',
                        'type'      => 'select',
                        'title'     => __('Display Comments', 'zoner-framework'),
                        'subtitle'  => __('Choose where users are allowed to post comment in your website.', 'zoner-framework'),
						'std'		=> 'post',
                        
                        'options'   => array(
                            'post'  => __('Posts Only', 'zoner-framework'), 
                            'page'  => __('Pages Only', 'zoner-framework'), 
							'both'  => __('Posts/Pages show', 'zoner-framework'), 
							'none'	=> __('Hide all', 'zoner-framework'), 
                        ),
                        'default'   => 'post'
                    ),
					array(
                        'id'        => 'pp-breadcrumbs',
                        'type'      => 'checkbox',
                        'title'     => __('Display Breadcrumbs', 'zoner-framework'),
                        'subtitle'  => __('Display dynamic breadcrumbs on each page of your website.', 'zoner-framework'),
                        'desc'      => __('Yes', 'zoner-framework'),
						'class'		=> 'icheck',
                        'default'   => '1'
                    ),		
					array(
                        'id'        => 'pp-post',
                        'type'      => 'image_select',
                        'title'     => __('Single post layout', 'zoner-framework'),
                        'subtitle'  => __('Select main content and sidebar alignment.', 'zoner-framework'),
                        'options'   => array(
                            '1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
                            '3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                        ),
                        'default'   => '3'
                    ),
					array(
                        'id'        => 'pp-date',
                        'type'      => 'checkbox',
                        'title'     => __('Display date for posts', 'zoner-framework'),
                        'default'   => true,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'zoner-framework'),
                    ),	
					array(
                        'id'        => 'pp-thumbnail',
                        'type'      => 'checkbox',
                        'title'     => __('Display thumbnails for posts', 'zoner-framework'),
                        'default'   => true,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'zoner-framework'),
                    ),	
					array(
                        'id'        => 'pp-tags',
                        'type'      => 'checkbox',
                        'title'     => __('Display tags for posts', 'zoner-framework'),
                        'default'   => true,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'zoner-framework'),
                    ),	
					array(
                        'id'        => 'pp-authors',
                        'type'      => 'checkbox',
                        'title'     => __('Display authors for posts', 'zoner-framework'),
                        'default'   => true,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'zoner-framework'),
                    ),	
				)	
			);	
				
			/*Styling options Section*/
			$this->sections[] = array(
                'title'     => __('Styling', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/styling.png',
				'icon_type'	=> 'image',
                'fields'    => array (
					array(
                        'id'        => 'body-background',
                        'type'      => 'background',
                        'output'    => array('body'),
                        'title'     => __('Body Background', 'zoner-framework'),
                        'subtitle'  => __('Body background with image, color, etc.', 'zoner-framework'),
						'transparent'	=> false,
						'default'   => array(
							'background-color' => '#ffffff',
							'background-repeat'	=> 'inherit',
							'background-attachment'	=> 'inherit',
							'background-position'	=> 'top center',
							'background-size'		=> 'inherit',
						)
                    ),
					
					array(
							'id'       => 'global-color-scheme',
							'type'     => 'image_select', 
							'presets'  => true,
							'title'    => __('Color Scheme', 'zoner-framework'),
							'subtitle' => __('Choose main color scheme.', 'zoner-framework'),
							'default'  => '0',
							'std'	   => '0',
							'options'  => array(
									'0'  => array(
														'alt'   => __('Blue', 'zoner-framework'),
														'img'   => $sample_patterns_url.'images/blue.png',
														'presets' => array(
															'zoner-searchbox-advancedcolor' 	=> '#1396e2',
															'underline-item-color'				=> '#1396e2',
															'footer-thumbnails-mask-color' 		=> '#1396e2',
															
															'submenu-itembg-color' => array(    
																						'regular'   => '#f3f3f3',
																						'hover'     => '#1396e2',
																						'active'    => '#1396e2',
																						),
															'content-link-color' => array (	
																						'regular'   => '#1396e2',
																						'hover'     => '#2a6496',
																						'active'    => '#2a6496',
																					),
														)
													),
									'1' => array(
														'alt'   => __('Brown', 'zoner-framework'),
														'img'   => $sample_patterns_url.'images/brown.png',
														'presets' => array(
															'zoner-searchbox-advancedcolor' 	=> '#998675',
															'underline-item-color'				=> '#998675',
															'footer-thumbnails-mask-color' 		=> '#998675',
															
															'submenu-itembg-color' => array(    
																						'regular'   => '#f3f3f3',
																						'hover'     => '#998675',
																						'active'    => '#998675',
																						),
															'content-link-color' => array (	
																						'regular'   => '#998675',
																						'hover'     => '#2a6496',
																						'active'    => '#2a6496',
															),
														)
													),
									'2' => array(
														'alt'   => __('Green', 'zoner-framework'),
														'img'   => $sample_patterns_url.'images/green.png',
														'presets' => array(
															'zoner-searchbox-advancedcolor' => '#00c109',
															'underline-item-color'			=> '#00c109',
															'footer-thumbnails-mask-color' 	=> '#00c109',
															'submenu-itembg-color' => array(    
																						'regular'   => '#f3f3f3',
																						'hover'     => '#00c109',
																						'active'    => '#00c109',
																						),
															'content-link-color' => array (	
																						'regular'   => '#00c109',
																						'hover'     => '#2a6496',
																						'active'    => '#2a6496',
															),
														)
													),
									'3' => array(
														'alt'   => __('Grey', 'zoner-framework'),
														'img'   => $sample_patterns_url.'images/grey.png',
														'presets' => array(
															'zoner-searchbox-advancedcolor' => '#707070',
															'underline-item-color'	=> '#707070',
															'footer-thumbnails-mask-color' => '#707070',
															
															'submenu-itembg-color' => array(    
																						'regular'   => '#f3f3f3',
																						'hover'     => '#707070',
																						'active'    => '#707070',
																						),
															'content-link-color' => array (	
																						'regular'   => '#707070',
																						'hover'     => '#2a6496',
																						'active'    => '#2a6496',
															),
														)
													),
									'4' => array(
														'alt'   => __('Magenta', 'zoner-framework'),
														'img'   => $sample_patterns_url.'images/magenta.png',
														'presets' => array(
															'zoner-searchbox-advancedcolor' => '#e83183',
															'underline-item-color'			=> '#e83183',
															'footer-thumbnails-mask-color' 	=> '#e83183',
															
															'submenu-itembg-color' => array(    
																						'regular'   => '#f3f3f3',
																						'hover'     => '#e83183',
																						'active'    => '#e83183',
																						),
															'content-link-color' => array (	
																						'regular'   => '#e83183',
																						'hover'     => '#2a6496',
																						'active'    => '#2a6496',
															),
														)
													),
									'5' => array(
														'alt'   => __('Orange', 'zoner-framework'),
														'img'   => $sample_patterns_url.'images/orange.png',
														'presets' => array(
															'zoner-searchbox-advancedcolor' => '#f7941d',
															'underline-item-color'			=> '#f7941d',
															'footer-thumbnails-mask-color' 	=> '#f7941d',
															
															'submenu-itembg-color' => array(    
																						'regular'   => '#f3f3f3',
																						'hover'     => '#f7941d',
																						'active'    => '#f7941d',
																						),
															'content-link-color' => array (	
																						'regular'   => '#f7941d',
																						'hover'     => '#2a6496',
																						'active'    => '#2a6496',
															),
														)
													),
									'6' => array(
														'alt'   => __('Red', 'zoner-framework'),
														'img'   => $sample_patterns_url.'images/red.png',
														'presets' => array(
															'zoner-searchbox-advancedcolor' => '#e2372f',
															'underline-item-color'			=> '#e2372f',
															'footer-thumbnails-mask-color' 	=> '#e2372f',
															
															'submenu-itembg-color' => array(    
																						'regular'   => '#f3f3f3',
																						'hover'     => '#e2372f',
																						'active'    => '#e2372f',
																						),
															'content-link-color' => array (	
																						'regular'   => '#e2372f',
																						'hover'     => '#2a6496',
																						'active'    => '#2a6496',
															),
														)
													),
									'7' => array(
														'alt'   => __('Violet', 'zoner-framework'),
														'img'   => $sample_patterns_url.'images/violet.png',
														'presets' => array(
															'zoner-searchbox-advancedcolor' => '#7c00c3',
															'underline-item-color'			=> '#7c00c3',
															'footer-thumbnails-mask-color' 	=> '#7c00c3',
															
															'submenu-itembg-color' => array(    
																						'regular'   => '#f3f3f3',
																						'hover'     => '#7c00c3',
																						'active'    => '#7c00c3',
																						),
															'content-link-color' => array (	
																						'regular'   => '#7c00c3',
																						'hover'     => '#2a6496',
																						'active'    => '#2a6496',
															),
														)
													)																			
							
							)							
						)	
	  
				)
				
			);
				
			

			$header_sections_fileds   = array();
			$header_sections_fileds[] =	array(
                        'id'        => 'header-background-color',
                        'type'      => 'color',
                        'title'     => __('Header background color', 'zoner-framework'),
                        'default'   => '#FFFFFF',
                        'validate'  => 'color',
						'transparent'	=> false
                    );
					
			$header_sections_fileds[] =	array(
						'id'       => 'header-phone',
						'type'     => 'text',
						'title'    => __('Phone', 'zoner-framework'), 
						'subtitle'      => __('Edit phone', 'zoner-framework'),
						'validate'  => 'no_html',
					);	
			
			$header_sections_fileds[] =	array(
						'id'       => 'header-email',
						'type'     => 'text',
						'title'    => __('Email', 'zoner-framework'), 
						'subtitle'      => __('Edit email', 'zoner-framework'),
						'validate'  => 'email',
						'default'	=> get_bloginfo( 'admin_email' )
						
					);	
			
			if ( function_exists('icl_object_id') ) {
				$header_sections_fileds[] = array(
                        'id'        => 'wmpl-flags-box',
                        'type'      => 'checkbox',
                        'title'     => __('WPML box', 'zoner-framework'),
                        'subtitle'  => __('Select to enable WPML box.', 'zoner-framework'),
                        'desc'      => __('Yes', 'zoner-framework'),
						'class'		=> 'icheck',
                        'default'   => '1'
                    );
			}

			
			
			
			/*Header Section*/
			$this->sections[] = array(
                'title'     => __('Header', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/header.png',
				'icon_type' => 'image',
                'fields'    => $header_sections_fileds
				
			);
			
			
			/*Menu Section*/
			$this->sections[] = array(
                'title'     => __('Menu', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/menu.png',
				'icon_type' => 'image',
                'fields'    => array(
					
					array(
                        'id'        => 'menu-link-color',
                        'type'      => 'link_color',
                        'title'     => __('Menu item color', 'zoner-framework'),
                        'default'   => array(
                            'regular'   => '#2a2a2a',
                            'hover'     => '#2a2a2a',
                            'active'    => '#2a2a2a',
                        )
                    ),
					array(
                        'id'        => 'submenu-link-color',
                        'type'      => 'link_color',
                        'title'     => __('Submenu item color', 'zoner-framework'),
                        'default'   => array(
                            'regular'   => '#5a5a5a',
                            'hover'     => '#ffffff',
                            'active'    => '#ffffff',
                        )
                    ),
					
					array(
                        'id'        => 'submenu-itembg-color',
                        'type'      => 'link_color',
                        'title'     => __('Submenu item background color', 'zoner-framework'),
                        'default'   => array(
                            'regular'   => '#f3f3f3',
                            'hover'     => '#1396e2',
                            'active'    => '#1396e2',
                        )
                    ),
					
					array(
                        'id'        => 'submenu-color',
                        'type'      => 'color',
                        'title'     => __('Submenu background color', 'zoner-framework'),
                        'default'   => '#f3f3f3',
                        'validate'  => 'color',
						'transparent'	=> false
                    ),
					
					array(
                        'id'        => 'submenu-itemborder-color',
                        'type'      => 'color_rgba',
                        'title'     => __('Submenu item border color', 'zoner-framework'),
                        'default'   => array('color' => '#000000', 'alpha' => '0.1'),
                        'mode'      => 'color',
                        'validate'  => 'colorrgba',
						'transparent'	=> false
                    ),
					
					array(
                        'id'        => 'underline-item-color',
                        'type'      => 'color',
                        'title'     => __('Menu underline item color.', 'zoner-framework'),
                        'default'   => '#1396e2',
                        'validate'  => 'color',
						'transparent'	=> false
                    ),
				)
			);
				
			/*Font Styles Section*/
			$this->sections[] = array(
                'title'     => __('Font styles', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/fonts.png',
				'icon_type' => 'image',
                'fields'    => array(
						array(
								'id'          => 'general-typography',
								'type'        => 'typography', 
								'title'       => __('General Text Font Style', 'zoner-framework'),
								'google'      => true, 
								'subsets'	  => true,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'output'      => array('body'),
								'units'       =>'px',
								'subtitle'	  => __('Select typography for general text.', 'zoner-framework'),
								'default'     => array(
														'color'       => '#5a5a5a', 
														'font-weight'  => '400', 
														'font-family' => 'Roboto', 
														'google'      => true,
														'font-size'   => '14px'
												),
								'preview' => array('text' => 'sample text')				
							 ),
							array(
								'id'          => 'hone-typography',
								'type'        => 'typography', 
								'title'       => __('H1 Font Style.', 'zoner-framework'),
								'google'      => true, 
								'subsets'	  => true,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'output'      => array('h1'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H1.', 'zoner-framework'),
								'default'     => array(
														'color'       => '#5a5a5a', 
														'font-weight'  => '300', 
														'font-family' => 'Roboto', 
														'google'      => true,
														'font-size'   => '28px'
												),
								'preview' => array('text' => 'sample text')								
							 ),
							array(
								'id'          => 'htwo-typography',
								'type'        => 'typography', 
								'title'       => __('H2 Font Style.', 'zoner-framework'),
								'google'      => true, 
								'subsets'	  => true,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'output'      => array('h2'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H2.', 'zoner-framework'),
								'default'     => array(
														'color'       => '#5a5a5a', 
														'font-weight'  => '300', 
														'font-family' => 'Roboto', 
														'google'      => true,
														'font-size'   => '24px',
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 array(
								'id'          => 'hthree-typography',
								'type'        => 'typography', 
								'title'       => __('H3 Font Style.', 'zoner-framework'),
								'google'      => true, 
								'subsets'	  => true,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'output'      => array('h3'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H3.', 'zoner-framework'),
								'default'     => array(
														'color'       => '#5a5a5a', 
														'font-weight'  => '300', 
														'font-family' => 'Roboto', 
														'google'      => true,
														'font-size'   => '18px', 
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 
							 array(
								'id'          => 'hfour-typography',
								'type'        => 'typography', 
								'title'       => __('H4 Font Style.', 'zoner-framework'),
								'google'      => true, 
								'subsets'	  => true,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'output'      => array('h4'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H4.', 'zoner-framework'),
								'default'     => array(
														'color'       => '#5a5a5a', 
														'font-weight'  => '400', 
														'font-family' => 'Roboto', 
														'google'      => true,
														'font-size'   => '14px',
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 array(
								'id'          => 'hfive-typography',
								'type'        => 'typography', 
								'title'       => __('H5 Font Style.', 'zoner-framework'),
								'google'      => true, 
								'subsets'	  => true,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'output'      => array('h5'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H5.', 'zoner-framework'),
								'default'     => array(
														'color'       => '#5a5a5a', 
														'font-weight'  => '400', 
														'font-family' => 'Roboto', 
														'google'      => true,
														'font-size'   => '14px',
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 
							 array(
								'id'          => 'hsix-typography',
								'type'        => 'typography', 
								'title'       => __('H6 Font Style.', 'zoner-framework'),
								'google'      => true, 
								'subsets'	  => true,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'output'      => array('h6'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H6.', 'zoner-framework'),
								'default'     => array(
														'color'       => '#5a5a5a', 
														'font-weight'  => '400', 
														'font-family' => 'Roboto', 
														'google'      => true,
														'font-size'   => '14px',
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 
							 array(
								'id'          => 'p-typography',
								'type'        => 'typography', 
								'title'       => __('"p" Font Style.', 'zoner-framework'),
								'google'      => true, 
								'subsets'	  => true,
								'font-backup' => false,
								'line-height' => true,	
								'text-align'  => true,	
								'output'      => array('p'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for tag "p".', 'zoner-framework'),
								'default'     => array(
														'color'       => '#5a5a5a', 
														'font-weight'  => '400', 
														'font-family' => 'Arial, Helvetica, sans-serif', 
														'google'      => true,
														'font-size'   => '14px',
														'line-height' => '20px',
														'text-align'  => 'inherit'
													),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 
							 
							 array(
								'id'            => 'p-opacity',
								'type'          => 'slider',
								'title'         => __('Transparency for content', 'zoner-framework'),
								'subtitle'      => __('Set the opacity for the content part', 'zoner-framework'),
								'default'       => .7,
								'min'           => 0,
								'step'          => .1,
								'max'           => 1,
								'resolution'    => 0.1,
								'display_value' => 'label'
							),
							 
							 array(
									'id'        => 'content-link-color',
									'type'      => 'link_color',
									'title'     => __('Link style.', 'zoner-framework'),
									'subtitle'  => __('Select the typography you want for tag "a".', 'zoner-framework'),
									'output'      => array('a'),
									'default'   => array(
										'regular'   => '#1396e2',
										'hover'     => '#2a6496',
										'active'    => '#2a6496',
							)
                    ),
				)
			);
			
			
				
			/*Social Liks*/
			$this->sections[] = array(
                'title'     => __('Social Links', 'zoner-framework'),
                'desc'      => __('Add link to your social media profiles. Icons with link will be display in header or footer.', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/social-links.png',
				'icon_type' => 'image',
				'fields'    => array(
					array(
                        'id'        => 'facebook-url',
                        'type'      => 'text',
                        'title'     => __('Facebook', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'twitter-url',
                        'type'      => 'text',
                        'title'     => __('Twitter', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'linkedin-url',
                        'type'      => 'text',
                        'title'     => __('LinkedIn', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'myspace-url',
                        'type'      => 'text',
                        'title'     => __('MySpace', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'gplus-url',
                        'type'      => 'text',
                        'title'     => __('Google Plus+', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'dribbble-url',
                        'type'      => 'text',
                        'title'     => __('Dribbble', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'flickr-url',
                        'type'      => 'text',
                        'title'     => __('Flickr', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'youtube-url',
                        'type'      => 'text',
                        'title'     => __('You Tube', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'delicious-url',
                        'type'      => 'text',
                        'title'     => __('Delicious', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'deviantart-url',
                        'type'      => 'text',
                        'title'     => __('Deviantart', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'rss-url',
                        'type'      => 'text',
                        'title'     => __('RSS', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'instagram-url',
                        'type'      => 'text',
                        'title'     => __('Instagram', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'pinterest-url',
                        'type'      => 'text',
                        'title'     => __('Pinterest', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'vimeo-url',
                        'type'      => 'text',
                        'title'     => __('Vimeo', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'picassa-url',
                        'type'      => 'text',
                        'title'     => __('Picassa', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'tumblr-url',
                        'type'      => 'text',
                        'title'     => __('Tumblr', 'zoner-framework'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'email-address',
                        'type'      => 'text',
                        'title'     => __('E-mail', 'zoner-framework'),
                        'validate'  => 'email',
                        'msg'       => 'custom error message',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'skype-username',
                        'type'      => 'text',
                        'title'     => __('Skype', 'zoner-framework'),
                        'default'   => ''
                    ),
				)	
			);	
			
			
			/*Default Page*/
			$this->sections[] = array(
                'title'     => __('Default pages', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/default-pages.png',
				'icon_type' => 'image',
				'fields'    => array(
					array(
                        'id'        => 'pp-author-agents-layout',
                        'type'      => 'image_select',
                        'title'     => __('Author layout', 'zoner-framework'),
                        'subtitle'  => __('Select main content and sidebar alignment.', 'zoner-framework'),
                        'options'   => array(
                            '1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
                            '3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                        ),
                        'default'   => '3'
                    ),
					
					
					array(
                        'id'        => '404-image',
                        'type'      => 'media',
                        'title'     => __('404 background.', 'zoner-framework'),
                        'subtitle'  => __('Upload a background for your 404 page.', 'zoner-framework'),
						'default'   => array('url' =>  $sample_patterns_url . 'images/error-page-background.png'),
                    ),
					
					array(
                        'id'        => '404-text',
                        'type'      => 'text',
                        'title'     => __('404 text', 'zoner-framework'),
                        'default'   => '404'
                    ),
					
				)
			);	
			
				
			/*Footer Section*/
			$this->sections[] = array(
                'title'     => __('Footer', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/footer.png',
				'icon_type' => 'image',
				'fields'    => array(
					
					array(
                        'id'        => 'footer-text',
                        'type'      => 'editor',
                        'title'     => __('Copyright section', 'zoner-framework'),
                        'subtitle'  => __('Replace default theme copyright information and links', 'zoner-framework'),
                        'default'   => '&#169; <a target="_blank" title="WordPress Development" href="http://fruitfulcode.com/">Fruitful Code</a>, Powered by <a target="_blank" href="http://wordpress.org/">WordPress</a>',
                    ),
					
					array(
                        'id'        => 'footer-issocial',
                        'type'      => 'checkbox',
                        'title'     => __('Social icons', 'zoner-framework'),
                        'desc'      => __('Enable social icons.', 'zoner-framework'),
                        'default'   => '1',
						'class'		=> 'icheck',
                    ),
					
					array(
                        'id'        => 'footer-widget-areas',
                        'type'      => 'image_select',
                        'title'     => __('Footer column areas', 'zoner-framework'),
                        'options'   => array(
                            '0' => array('alt' => 'No widgets areas.',  'img' => $sample_patterns_url . 'images/footer-widgets-0.png'),
                            '1' => array('alt' => '1 column area.', 	'img' => $sample_patterns_url . 'images/footer-widgets-1.png'),
							'2' => array('alt' => '2 column area.', 	'img' => $sample_patterns_url . 'images/footer-widgets-2.png'),
							'3' => array('alt' => '3 column area.', 	'img' => $sample_patterns_url . 'images/footer-widgets-3.png'),
							'4' => array('alt' => '4 column area.', 	'img' => $sample_patterns_url . 'images/footer-widgets-4.png')
                        ), 
                        'default' => '4'
                    ),
					
					array(
                        'id'        => 'footer-copyright-color',
                        'type'      => 'color',
                        'title'     => __('Footer copyright part font color', 'zoner-framework'),
                        'default'   => '#ffffff',
                        'validate'  => 'color',
						'transparent'	=> false
                    ),
					
					array(
                        'id'        => 'footer-copyright-bg-color',
                        'type'      => 'color',
                        'title'     => __('Footer copyright part background color', 'zoner-framework'),
                        'default'   => '#073855',
                        'validate'  => 'color',
						'transparent'	=> false
                    ),
				)
			);
				
			
			/*Custom Section*/
			$this->sections[] = array(
                'title'     => __('Custom Code', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/custom-code.png',
				'icon_type' => 'image',
                'fields'    => array (
					array(
                        'id'        => 'custom-css',
                        'type'      => 'ace_editor',
                        'title'     => __('CSS Code', 'zoner-framework'),
                        'subtitle'  => __('Paste your CSS code here.', 'zoner-framework'),
                        'mode'      => 'css',
                        'theme'     => 'chrome',
                        'desc'      => '',
                        'default'   => ""
                    ),
					array(
                        'id'        => 'custom-js',
                        'type'      => 'ace_editor',
                        'title'     => __('JS Code', 'zoner-framework'),
                        'subtitle'  => __('Paste your JS code here.', 'zoner-framework'),
                        'mode'      => 'javascript',
                        'theme'     => 'chrome',
                        'desc'      => '',
                        'default'   => ""
                    ),
				)
			);
			
            $this->sections[] = array(
                'title'     => __('Import / Export', 'zoner-framework'),
                'desc'      => __('Import and Export your zoner Framework settings from file, text or URL.', 'zoner-framework'),
                'icon'      => $sample_patterns_url . 'images/icons/import-export.png',
				'icon_type' => 'image',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your zoner options',
                        'full_width'    => false,
                    ),
                ),
            );           

			$this->sections = apply_filters('zoner_admin_fields', $this->sections);
            
        }

        public function setHelpTabs() {}
		
        public function setArguments() {
            $theme 		 = wp_get_theme(); 
			$source_path = get_template_directory_uri() . '/includes/admin/zoner-options/patterns/';
			
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'zoner_config',        	 // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name'),      // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),   // Version that appears at the top of your panel
                'menu_type'         => 'menu',                   // Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => false,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('Zoner options', 'zoner-framework'),
                'page_title'        => __('Zoner options', 'zoner-framework'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' 	   => '861074126314', // Must be defined to add google fonts to the typography module
				'google_update_weekly' => false,
                
                'async_typography'  => false,                   // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                   // Show the time the page took to load, etc
                'customizer'        => false,                   // Enable basic customizer support
				'update_notice'     => true,
        
                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',  // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'menu-icon',         	// Icon displayed in the admin panel next to your menu_title
                'page_slug'         => 'zoner_options',      	// Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                     // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => false,                  // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                   // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                   // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                'footer_credit'     => '<span id="footer-thankyou">' . __( 'Zoner Options panel created using "Reduxe Framework".', 'zoner-framework' ). '</span>',                     // Disable the footer credit of zoner. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', 	  // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE
				'page_type'				=> 'submenu',
				
				'header_list_links'		=> array(
					array('link' => 'http://support.fruitfulcode.com/hc/en-us/requests/new', 'name' => __('Contact Support', 'zoner-framework')),
					array('link' => 'http://themes.fruitfulcode.com/zoner/documentation/', 'name' => __('Documentation', 'zoner-framework')),
					array('link' => 'http://support.fruitfulcode.com/hc/en-us/categories/200198223-Zoner', 'name' => __('Faq', 'zoner-framework')),
				),

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/Fruitfulcode',
                'title' => 'Visit us on GitHub',
                'img'   => esc_url($source_path . 'images/icons/github.png'), 
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/fruitfulc0de',
                'title' => 'Like us on Facebook',
                'img'   => esc_url($source_path . 'images/icons/facebook.png'), 
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://twitter.com/fruitfulcode',
                'title' => 'Follow us on Twitter',
                'img'   => esc_url($source_path . 'images/icons/twitter.png'), 
            );
            

            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v   = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
                 $this->args['intro_text'] = '';
			   //sprintf(__('', 'zoner-framework'), $v);
            } else {
                 $this->args['intro_text'] = '';
            }

            // Add content after the form.
            $this->args['footer_text'] 	= '';
        }

    }
}

function initZonerConfig() {
	global $zonerConfig;
	$zonerConfig = new zoner_config();
}
add_action('init', 'initZonerConfig', 1);