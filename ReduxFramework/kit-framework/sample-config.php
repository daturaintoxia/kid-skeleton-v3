<?php
/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
 * */

if (!class_exists("Redux_Framework_sample_config")) {

    class Redux_Framework_sample_config {

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if ( !class_exists("ReduxFramework" ) ) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( defined('TEMPLATEPATH') && strpos( Redux_Helpers::cleanFilePath( __FILE__ ), Redux_Helpers::cleanFilePath( TEMPLATEPATH ) ) !== false) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

            // Function to test the compiler hook and demo CSS output.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            // Dynamically add a section. Can be also used to modify sections/fields
            add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo "<h1>The compiler hook has run!";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
              require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
              $wp_filesystem->put_contents(
              $filename,
              $css,
              FS_CHMOD_FILE // predefined mode settings for WP files
              );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'redux-framework-demo'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = false;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = "Testing filter hook!";

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));

            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode(".", $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[] = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct = wp_get_theme();
            $this->theme = $ct;
            $item_name = $this->theme->get('Name');
            $tags = $this->theme->Tags;
            $screenshot = $this->theme->get_screenshot();
            $class = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'redux-framework-demo'), $this->theme->display('Name'));
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

                <h4>
            <?php echo $this->theme->display('Name'); ?>
                </h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'redux-framework-demo'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'redux-framework-demo'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'redux-framework-demo') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
                <?php
                if ($this->theme->parent()) {
                    printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'redux-framework-demo'), $this->theme->parent()->display('Name'));
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




            // ACTUAL DECLARATION OF SECTIONS
//==================================== KIPER SETTINGS
 $this->sections[] = array(
     'title' => __('Kiper Settings', 'redux-framework-demo'),
      'desc' => __('Kiper Design gör Wordpress teman som går att anpassa efter behov : <a href="https://kiperdesign.se">KIíper Design</a>', 'redux-framework-demo'),
      'icon' => 'el-icon-home',
  // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
         'fields' => array(

					      array(
                        'id' => 'exerpt',
                        'type' => 'text',
                        'title' => __('Exerpt legth', 'redux-framework-demo'),
                        'subtitle' => __('This must be numeric.', 'redux-framework-demo'),
                        'desc' => __('Exerpt length in words.', 'redux-framework-demo'),
                        'validate' => 'numeric',
                        'default' => '30',
                        'class' => 'small-text'
                    ),
						array(
                        'id' => 'gfont',
                        'type' => 'text',
                        'title' => __('Google Api-key', 'redux-framework-demo'),
                        'subtitle' => __('Paste your  Google Api-key here.', 'redux-framework-demo'),
                        'desc' => __('Paste your  Google Api-key here.', 'redux-framework-demo'),
                        'validate' => 'no_special_chars',
                        'default' => '',
                        'class' => 'small-text'
                    ),
					 $field = array(
						'id'       => 'logo_opt',
						'type'     => 'switch',
						'title'    => __('Logo image', 'redux-framework-demo'),
						'subtitle' => __('Use Logo image?', 'redux-framework-demo'),
						'desc'     => __('Check the Yes or No.', 'redux-framework-demo'),
                        'default'  => false,
                    ),
					 array(
                        'id' => 'sitelogo',
                        'type' => 'media',
                        'output' => array('.scale-with-grid'),
                        'title' => __('Site logo', 'redux-framework-demo'),
						'background-image' => true,
							//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
							'desc'=> __('Choose an image from the Media bin or upload a new one.', 'redux-framework-demo'),
							'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
							'default'=>array('url'=>get_template_directory_uri() . '/images/kiperdesign_logo.png'),
                ),
				array(
                        'id' => 'logo-foat',
                        'type' => 'image_select',
                        'compiler' => true,
						'output' => array('#header img.scale-with-grid'),
                        'title' => __('Logo alignment', 'redux-framework-demo'),
                        'subtitle' => __('Select layout. ', 'redux-framework-demo'),
                        'options' => array(
                            'left' => array('alt' => '960px', 'img' => ReduxFramework::$_url . '../kit-framework/img/left.png',$css = "float:left;"),
                            'center' => array('alt' => '1140px', 'img' => ReduxFramework::$_url . '../kit-framework/img/center.png',$css = "margin-left:auto; margin:right:auto;"),
                            'right' => array('alt' => '1200px', 'img' => ReduxFramework::$_url . '../kit-framework/img/right.png',$css = "float:right;")

                        ),
                        'default' => 'left'
                    ),
				
					 array(
                        'id' => 'site-icon-s',
                        'type' => 'media',
                        'output' => array('.scale-with-grid'),
                        'title' => __('Site favicon small', 'redux-framework-demo'),
						'background-image' => true,
							//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
							'desc'=> __('Favicon image.', 'redux-framework-demo'),
							'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
							'default'=>array('url'=>get_stylesheet_directory_uri() . '/images/favicon.png'),
                ),

					 array(
                        'id' => 'site-icon-m',
                        'type' => 'media',
                        'output' => array('.scale-with-grid'),
                        'title' => __('Site favicon medium', 'redux-framework-demo'),
						'background-image' => true,
							//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
							'desc'=> __('Apple-touch-icon-72x72', 'redux-framework-demo'),
							'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
							'default'=>array('url'=>get_stylesheet_directory_uri() . '/images/apple-touch-icon-72x72.png'),
                ),

					 array(
                        'id' => 'site-icon-l',
                        'type' => 'media',
                        'output' => array('.scale-with-grid'),
                        'title' => __('Site favicon large', 'redux-framework-demo'),
						'background-image' => true,
							//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
							'desc'=> __('Apple-touch-icon-114x114.', 'redux-framework-demo'),
							'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
							'default'=>array('url'=>get_stylesheet_directory_uri() . '/images/apple-touch-icon-114x114.png'),
                ),				
                    array(
                        'id' => 'layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => __('Sidebar Layout', 'redux-framework-demo'),
                        'subtitle' => __('Select sidebar alignment. ', 'redux-framework-demo'),
                        'options' => array(
                            'mute' => array('alt' => '1 Column', 'img' => ReduxFramework::$_url . '../kit-framework/img/1col.png'),
                            'left' => array('alt' => '2 Column Left', 'img' => ReduxFramework::$_url . '../kit-framework/img/2cl.png'),
                            'right' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . '../kit-framework/img/2cr.png')
                           // 'center' => array('alt' => '3 Column Middle', 'img' => ReduxFramework::$_url . 'assets/img/3cm.png'),
                          //  '5' => array('alt' => '3 Column Left', 'img' => ReduxFramework::$_url . 'assets/img/3cl.png'),
                         //   '6' => array('alt' => '3 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/3cr.png')
                        ),
                        'default' => 'right'
                    ),
						                    array(
                        'id' => 'css-layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => __('page width Layout', 'redux-framework-demo'),
                        'subtitle' => __('Select layout. ', 'redux-framework-demo'),
                        'options' => array(
                            '960' => array('alt' => '960px', 'img' => ReduxFramework::$_url . '../kit-framework/img/960.png'),
                            '1140' => array('alt' => '1140px', 'img' => ReduxFramework::$_url . '../kit-framework/img/1140.png'),
                            '1200' => array('alt' => '1200px', 'img' => ReduxFramework::$_url . '../kit-framework/img/1200.png')
                           // 'center' => array('alt' => '3 Column Middle', 'img' => ReduxFramework::$_url . 'assets/img/3cm.png'),
                          //  '5' => array('alt' => '3 Column Left', 'img' => ReduxFramework::$_url . 'assets/img/3cl.png'),
                         //   '6' => array('alt' => '3 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/3cr.png')
                        ),
                        'default' => '1140'
                    ),
						//=============
						 $field = array(
						'id'       => 'widget_opt',
						'type'     => 'switch',
						'title'    => __('Use widget for Category as news feed', 'redux-framework-demo'),
						'subtitle' => __('Use widget (kit-widget) for Category news feed', 'redux-framework-demo'),
						'desc'     => __('Check the Yes or No.', 'redux-framework-demo'),
                        'default'  => false,
                    ),
						//===================
						array(
                        'id' => 'widget_cat',
                        'type' => 'text',
                        'title' => __('Category ID', 'redux-framework-demo'),
                        'subtitle' => __('Category ID.', 'redux-framework-demo'),
                        'desc' => __('.', 'redux-framework-demo'),
                        'validate' => 'text',
                        'default' => '',
                        'class' => 'small-text'
						),
						
                    array(
                        'id' => 'footer-text1',
                        'type' => 'editor',
                        'title' => __('Footer Text', 'redux-framework-demo'),
                        'subtitle' => __('Use the editor to insert image, text or HTML into the footer.', 'redux-framework-demo'),
                        'default' => '<a href="http://skeleton.kiperweb.se/wp-content/uploads/2014/03/kiperdesign_icon.png"><img class="alignnone size-full wp-image-28" alt="kiperdesign_icon" src="http://skeleton.kiperweb.se/wp-content/uploads/2014/03/kiperdesign_icon.png" width="24" height="24" /></a><h4> KiperDesign</h4>',
						    'editor_options'   => array(
							'teeny'            => true,
							'textarea_rows'    => 5
							)
                    ),
						 $field = array(
						'id'       => 'year_opt',
						'type'     => 'switch',
						'title'    => __('Show year?', 'redux-framework-demo'),
						'subtitle' => __('Show year in the bottom of page?', 'redux-framework-demo'),
						'desc'     => __('show year in the end of footer text.', 'redux-framework-demo'),
                        'default'  => false,
                    ),

		),
	);

// ============================ STYLING OPTIONS

            $this->sections[] = array(
                'icon' => 'el-icon-braille',
                'title' => __('Styling Options', 'redux-framework-demo'),
                'fields' => array(
                    array(
                        'id' => 'stylesheet',
                        'type' => 'select',
                        'title' => __('Theme Stylesheet', 'redux-framework-demo'),
                        'subtitle' => __('Basic color scheme. To do specific changes in css use the extra.css in the theme-root folder.', 'redux-framework-demo'),
                        'options' => array(__('default') => 'default', __('dark') => 'dark', __('light') => 'light', __('color') => 'color'),
                        'default' => 'default',
                    ),
					array(
						'id'    => 'body-info',
						'type'  => 'info',
						'title' => 'Body',
						'subtitle' => __('Set background image or color.', 'redux-framework-demo'),
					),
                    array(
                        'id' => 'set-background',
                        'type' => 'background',
                        'output' => array('body'),
                        'title' => __('Body Background Image and Color', 'redux-framework-demo'),
                        'subtitle' => __('Pick a background color for the theme (default: #fff).', 'redux-framework-demo'),
                        'default' => '',
						'background-image' => true,
                      
                    ),
					array(
						'id'    => 'header-info',
						'type'  => 'info',
						'title' => 'Header',
						'subtitle' => __('Header options.', 'redux-framework-demo'),
					),
					array(
                        'id' => 'header-background',
                        'type' => 'background',
						'mode' => 'background',
                        'output' => array('#header'),
                        'title' => __('Header Background Color', 'redux-framework-demo'),
                        'subtitle' => __('Pick a background color for the theme (default: #fff).', 'redux-framework-demo'),
                        'default' => '',
						'background-image' => true,
                    ),
					array(
                        'id' => 'header-dimensions',
                        'type' => 'dimensions',
						'output' => array('#header'),
						'height' =>true,
						'width' => false,
                        'units' => array('Px' => 'px','Em' => 'em'),
                        'units_extended' => 'true', // Allow users to select any type of unit
                        'title' => __('Dimensions (Height) Option', 'redux-framework-demo'),
                        'subtitle' => __('Allow your users to choose width, height, and/or unit.', 'redux-framework-demo'),
                        'desc' => __('You can enable or disable any piece of this field. Width, Height, or Units.', 'redux-framework-demo'),
                        'default'  => array(
							'Height'  => '130'
						),
                    ),   
                    array(
                        'id' => 'header-border',
                        'type' => 'border',
                        'title' => __('Header Border Option', 'redux-framework-demo'),
                        'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
                        'output' => array('#header'), // An array of CSS selectors to apply this font style to
                        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'default' => array('border-color' => '', 'border-style' => '', 'border-top' => '0px', 'border-right' => '0px', 'border-bottom' => '0px', 'border-left' => '0px')
                    ),
					array(
						'id'    => 'wrap-info',
						'type'  => 'info',
						'title' => 'Wrapper',
						'subtitle' => __('Wraper options.', 'redux-framework-demo'),
					),
					array(
                        'id' => 'wraper-background',
                        'type' => 'background',
						'mode' => 'background',
                        'output' => array('#wrap'),
                        'title' => __('Wrapper Background Color', 'redux-framework-demo'),
                        'subtitle' => __('Pick a background color for the theme (default: #fff).', 'redux-framework-demo'),
                        'default' => '',
						'background-image' => true,
                        
                    ),
                    array(
                        'id' => 'wrap-border',
                        'type' => 'border',
                        'title' => __('Wrapper Border Option', 'redux-framework-demo'),
                        'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
                        'output' => array('#wrap'), // An array of CSS selectors to apply this font style to
                        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'default' => array('border-color' => '', 'border-style' => '', 'border-top' => '0px', 'border-right' => '0px', 'border-bottom' => '0px', 'border-left' => '0px')
                    ),
					array(
						'id'    => 'footer-info',
						'type'  => 'info',
						'title' => 'Footer',
						'subtitle' => __('Footer options.', 'redux-framework-demo'),
					),
					array(
                        'id' => 'footer-background',
                        'type' => 'color',
						'mode' => 'background',
                        'output' => array('#footer'),
                        'title' => __('Footer Background Color', 'redux-framework-demo'),
                        'subtitle' => __('Pick a background color for the theme (default: #fff).', 'redux-framework-demo'),
						'validate' => 'colorrgba',
                        'default' => '',
                        
                    ), 
					array(
						'id'    => 'menu-info',
						'type'  => 'info',
						'title' => 'Navigation Menu',
						'subtitle' => __('Menu options.', 'redux-framework-demo'),
					),
					// MENY BAKGRUND
					array(
                        'id' => 'menu-text-color2',
                        'type' => 'color',
						'mode' => 'color',
                        'output' => array('#navigation ul a','#navigation ul li a','#navigation ul.sub-menu li a','#navigation ul.children li a'),
                        'title' => __('Menu text color', 'redux-framework-demo'),
                        'subtitle' => __('Pick a text color for the theme .', 'redux-framework-demo'),
						'validate' => 'colorrgba',
                        'default' => '',
                        
                    ), 
					array(
                        'id' => 'menu-text-hover-color',
                        'type' => 'color',
						'mode' => 'color',
                        'output' => array('#navigation ul a:hover','#navigation ul li a:hover','#navigation ul.sub-menu li a:hover','#navigation ul.children li a:hover'),
                        'title' => __('Menu text hover color', 'redux-framework-demo'),
                        'subtitle' => __('Pick a text hover color for the theme .', 'redux-framework-demo'),
						'validate' => 'colorrgba',
                        'default' => '',
                        
                    ), 
					array(
                        'id' => 'menu-background',
                        'type' => 'color',
						'mode' => 'background',
                        'output' => array('#navigation ul','#navigation ul li'),
                        'title' => __('Menu background color', 'redux-framework-demo'),
                        'subtitle' => __('Pick a background color for the theme .', 'redux-framework-demo'),
						'validate' => 'colorrgba',
                        'default' => '',
                        
                    ), 
					// SUB MENY BAKGRUND
					array(
                        'id' => 'menu-first-background',
                        'type' => 'color',
						'mode' => 'background',
                         'output' => array('#navigation ul.sub-menu li a','#navigation ul.children li a'),
                        'title' => __('SubMenu background','redux-framework-demo'),
                        'subtitle' => __('Pick a background color for the theme (default: #fff).', 'redux-framework-demo'),
						'validate' => 'colorrgba',
                        'default' => '',
                        
                    ), 
					// MENY HOVER
					array(
                        'id' => 'menu-hover-background',
                        'type' => 'color',
						'mode' => 'background',
                        'output' => array('#navigation ul li a:hover'),
                        'title' => __('Menu hover color','redux-framework-demo'),
                        'subtitle' => __('Pick a background color for the theme (default: #fff).', 'redux-framework-demo'),
						'validate' => 'colorrgba',
                        'default' => '',
                        
                    ), 
					// SUB-MENY HOVER
					array(
                        'id' => 'submenu-hover-background',
                        'type' => 'color',
						'mode' => 'background',
                        'output' => array('#navigation ul.sub-menu li a:hover','#navigation ul.children li a:hover'),
                        'title' => __('Submenu hover color','redux-framework-demo'),
                        'subtitle' => __('Pick a background color for the theme (default: #fff).', 'redux-framework-demo'),
						'validate' => 'colorrgba',
                        'default' => '',
                        
                    ), 
					// Current page meny text
					array(
                        'id' => 'meny-c-c',
                        'type' => 'color',
						'mode' => 'color',
                        'output' => array('#navigation ul li.active a'),
                        'title' => __('Current page text color','redux-framework-demo'),
                        'subtitle' => __('Pick a  color for the current-page meny text color (default: #fff).', 'redux-framework-demo'),
						'validate' => 'colorrgba',
                        'default' => '',
                        
                    ), 
										// current page meny backgrouns
					array(
                        'id' => 'meny-c-b',
                        'type' => 'color',
						'mode' => 'background',
                        'output' => array('#navigation ul li.active a'),
                        'title' => __('Current page text color','redux-framework-demo'),
                        'subtitle' => __('Pick a  color for the current-page menu background (default: #fff).', 'redux-framework-demo'),
						'validate' => 'colorrgba',
                        'default' => '',
                        
                    ), 
					
					array(
						'id'    => 'linx-info',
						'type'  => 'info',
						'title' => 'TEXT OPTIONS',
						'subtitle' => __('Text options.', 'redux-framework-demo'),
					),
                    array(
                        'id' => 'link-color',
                        'type' => 'link_color',
                        'title' => __('Links Color Option', 'redux-framework-demo'),
                        'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
                        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        //'regular' => false, // Disable Regular Color
                        //'hover' => false, // Disable Hover Color
                        //'active' => false, // Disable Active Color
                        //'visited' => true, // Enable Visited Color
						'output' => array('a','a:hover','a:active'),
                        'default' => array(
                            'regular' => '',
                            'hover' => '',
                            'active' => '',
                        )
                    ),

$fields = array(
    'id'       => 'button-color',
    'type'     => 'button_set',
    'title'    => __('Button Color'),
    'subtitle' => __('Set button color', 'redux-framework-demo'),
    'desc'     => __('Set button color.', 'redux-framework-demo'),
    //Must provide key => value pairs for options
    'options' => array(
        'orange' => 'Orange',
        'red' => 'Red',
        'teal' => 'Teal',
		 'magenta' => 'Magenta',
        'green' => 'Green',
        'blue' => 'Blue',
        'black' => 'Black',
        'gray' => 'Gray',
        'white' => 'White'
     ),
    'default' => 'gray'
),
                    
                )
            );
//==================================TEXT OPTIONS==============================================================================
            $this->sections[] = array(
                'icon' => 'el-icon-font',
                'title' => __('Text Options', 'redux-framework-demo'),
                'fields' => array(
//=====
				   array(
                        'id' => 'body-font2',
                        'type' => 'typography',
                        'title' => __('Body Font', 'redux-framework-demo'),
                        'subtitle' => __('Specify the body font properties.', 'redux-framework-demo'),
						'output' => array('body'),
                        'google' => true,
                        'default' => array(
                            'color' => '',
                            'font-size' => '15px',
                            'font-family' => '',
							'text-align' => '',
                            'font-weight' => 'Normal',
                        ),
                    ),
                    array(
                        'id' => 'p-font2',
                        'type' => 'typography',
                        'title' => __('paragraf Font', 'redux-framework-demo'),
                        'subtitle' => __('Specify the <p> font properties.', 'redux-framework-demo'),
						'output' => array('p'),
                        'google' => true,
                        'default' => array(
                            'color' => '',
                            'font-size' => '15px',
                            'font-family' => '',
							'text-align' => '',
                            'font-weight' => 'Normal',
                        ),
                    ),
					    array(
                        'id' => 'ul-font2',
                        'type' => 'typography',
                        'title' => __('List Item', 'redux-framework-demo'),
                        'subtitle' => __('Specify the <ul li a> font properties.', 'redux-framework-demo'),
						'output' => array('ul li a'),
                        'google' => true,
                        'default' => array(
                            'color' => '',
                            'font-size' => '17px',
                            'font-family' => '',
							'text-align' => '',
                            'font-weight' => 'Normal',
                        ),
                    ),
                    array(
                        'id' => 'h1-font',
                        'type' => 'typography',
						'output' => array('#header div#site-title a, #header h1#site-title, #header h1#site-title a'),
                        'title' => __('Site Title', 'redux-framework-demo'),
                        'subtitle' => __('Specify the body font properties.', 'redux-framework-demo'),
                        'google' => true,
						'text-align' => true,
                        'default' => array(
                            'color' => '',
                            'font-size' => '30px',
                            'font-family' => '',
							'text-align' => '',
                            'font-weight' => 'Normal',
                        ),
						),
						array(
                        'id' => 'title-spacing',
                        'type' => 'spacing',
                        'output' => array('#header div#site-title a, #header h1#site-title, #header h1#site-title a'), // An array of CSS selectors to apply this font style to
                        'mode' => 'letter-spacing', // absolute, padding, margin, defaults to padding
                        'all' => true, // Have one field that applies to all
                        //'top' => false, // Disable the top
                        //'right' => false, // Disable the right
                        //'bottom' => false, // Disable the bottom
                        //'left' => false, // Disable the left
                        //'units' => 'em', // You can specify a unit value. Possible: px, em, %
                        //'units_extended'=> 'true', // Allow users to select any type of unit
                        //'display_units' => 'false', // Set to false to hide the units if the units are specified
                        'title' => __('Site-title Letter-spacing', 'redux-framework-demo'),
                        'subtitle' => __('Allow your users to choose the spacing or margin they want.', 'redux-framework-demo'),
                        'desc' => __('You can enable or disable any piece of this field. Top, Right, Bottom, Left, or Units.', 'redux-framework-demo'),
                        'default' => array(
                            'letter-spacing' => '1px'
                        )
                    ),
						 array(
                        'id' => 'site-desc',
                        'type' => 'typography',
						'output' => array('#header .inner span.site-desc h2'),
                        'title' => __('Site Descripion', 'redux-framework-demo'),
                        'subtitle' => __('Specify the body font properties.', 'redux-framework-demo'),
                        'google' => true,
						'text-align' => true,
                        'default' => array(
                            'color' => '',
                            'font-size' => '24px',
                            'font-family' => '',
							'text-align' => '',
                            'font-weight' => 'Normal',
                        ),
						),
					array(
                        'id' => 'h11-font',
                        'type' => 'typography',
						'output' => array('h1'),
                        'title' => __('H1 Font', 'redux-framework-demo'),
                        'subtitle' => __('Specify the body font properties.', 'redux-framework-demo'),
                        'google' => true,
						'text-align' => true,
                        'default' => array(
                            'color' => '',
                            'font-size' => '24px',
                            'font-family' => '',
							'text-align' => '',
                            'font-weight' => 'Normal',
                        ),
						),
					array(
                        'id' => 'h2-font',
                        'type' => 'typography',
						'output' => array('h2'),
                        'title' => __('H2 Font', 'redux-framework-demo'),
                        'subtitle' => __('Specify the body font properties.', 'redux-framework-demo'),
                        'google' => true,
						'text-align' => true,
                        'default' => array(
                            'color' => '',
                            'font-size' => '22px',
                            'font-family' => '',
							'text-align' => '',
                            'font-weight' => 'Normal',
                        ),
						),
					array(
                        'id' => 'h3-font',
                        'type' => 'typography',
						'output' => array('h3'),
                        'title' => __('H3 Font', 'redux-framework-demo'),
                        'subtitle' => __('Specify the body font properties.', 'redux-framework-demo'),
                        'google' => true,
						'text-align' => true,
                        'default' => array(
                            'color' => '',
                            'font-size' => '20px',
                            'font-family' => '',
							'text-align' => '',
                            'font-weight' => 'Normal',
                        ),
						),
					array(
                        'id' => 'h4-font',
                        'type' => 'typography',
						'output' => array('h4,h5,h6'),
                        'title' => __('H4 H5 H6 Font', 'redux-framework-demo'),
                        'subtitle' => __('Specify the body font properties.', 'redux-framework-demo'),
                        'google' => true,
						'text-align' => true,
                        'default' => array(
                            'color' => '',
                            'font-size' => '17px',
                            'font-family' => '',
							'text-align' => '',
                            'font-weight' => 'Normal',
                        ),
						),

                                  
				   
//=====					
                )
            );
            $this->sections[] = array(
                'type' => 'divide',
            );
//================================= CONTENT OPTIONS======================
            $this->sections[] = array(
                'icon' => 'el-icon-align-justify',
                'title' => __('Content Options', 'redux-framework-demo'),
                'fields' => array(
					array(
						'id'    => 'slider-option',
						'type'  => 'info',
						'title' => 'Kit-slider option',
					),
						 $field = array(
						'id'       => 'slider',
						'type'     => 'switch',
						'title'    => __('Kit-slider', 'redux-framework-demo'),
						'subtitle' => __('Use kit Slider?', 'redux-framework-demo'),
						'desc'     => __('Kit responsive slider', 'redux-framework-demo'),
                        'default'  => false,					
                ),	
					array(
						'id'    => 'content-info',
						'type'  => 'info',
						'title' => 'GENERAL OPTIONS for general loop and archives',
					),
						 $field = array(
						'id'       => 'above-3',
						'type'     => 'switch',
						'title'    => __('3 columns above', 'redux-framework-demo'),
						'subtitle' => __('3 columns above?', 'redux-framework-demo'),
						'desc'     => __('3 columns above?', 'redux-framework-demo'),
                        'default'  => false,					
                ),	
						 $field = array(
						'id'       => 'entry-meta',
						'type'     => 'switch',
						'title'    => __('Entry-Meta', 'redux-framework-demo'),
						'subtitle' => __('Show Entry-meta (Posted on and posted by)?', 'redux-framework-demo'),
						'desc'     => __('Show Entry-meta (Posted on and posted by)?', 'redux-framework-demo'),
                        'default'  => true,
                    ),	
//						 $field = array(
//						'id'       => 'entry-date',
//						'type'     => 'switch',
//						'title'    => __('Entry-date', 'redux-framework-demo'),
//						'subtitle' => __('Show Entry-date (Posted on )?', 'redux-framework-demo'),
//						'desc'     => __('Show Entry-date (Posted on )?', 'redux-framework-demo'),
//                        'default'  => true,
//                    ),						
//						 $field = array(
//						'id'       => 'entry-author',
//						'type'     => 'switch',
//						'title'    => __('Entry-author', 'redux-framework-demo'),
//						'subtitle' => __('Show Entry-author (posted by)?', 'redux-framework-demo'),
//						'desc'     => __('Show Entry-meta (posted by)?', 'redux-framework-demo'),
//                        'default'  => true,
//                    ),					
	
						 $field = array(
						'id'       => 'entry-utility',
						'type'     => 'switch',
						'title'    => __('Entry-utility', 'redux-framework-demo'),
						'subtitle' => __('Show entry-utility(Posted in category and leave comment)?', 'redux-framework-demo'),
						'desc'     => __('Show entry-utility(Posted in category and leave comment)?', 'redux-framework-demo'),
                        'default'  => true,
                    ),			
						 $field = array(
						'id'       => 'nav-above',
						'type'     => 'switch',
						'title'    => __('Nav-above', 'redux-framework-demo'),
						'subtitle' => __('Show Nav-above?', 'redux-framework-demo'),
						'desc'     => __('Show Nav-above?', 'redux-framework-demo'),
                        'default'  => true,
                    ),		
						 $field = array(
						'id'       => 'nav-below',
						'type'     => 'switch',
						'title'    => __('Nav-below', 'redux-framework-demo'),
						'subtitle' => __('Show Nav-below?', 'redux-framework-demo'),
						'desc'     => __('Show Nav-below?', 'redux-framework-demo'),
                        'default'  => true,					
					),	

					
//============= OPTIONS AUTO IMAGES=====================
					array(
						'id'    => 'auto-img-info',
						'type'  => 'info',
						'title' => 'OPTIONS FOR AUTO IMAGES',
					),		
						 $field = array(
						'id'       => 'auto-featured',
						'type'     => 'switch',
						'title'    => __('Auto featured image', 'redux-framework-demo'),
						'subtitle' => __('Auto featured image?', 'redux-framework-demo'),
						'desc'     => __('Sets auto featured image for first uploaded image. (Will force theme to use featured image)', 'redux-framework-demo'),
                        'default'  => false,					
                ),						
//==============	
//============= OPTIONS FOR SINGLE POST=====================
					array(
						'id'    => 'post-info',
						'type'  => 'info',
						'title' => 'OPTIONS FOR SINGLE POST',
					),
					$field = array(
						'id'       => 'author-post-single',
						'type'     => 'switch',
						'title'    => __('Author', 'redux-framework-demo'),
						'subtitle' => __('Show Author (Posted on and posted by)?', 'redux-framework-demo'),
						'desc'     => __('Show Author (Posted on and posted by)?', 'redux-framework-demo'),
                        'default'  => true,
                    ),			
						$field = array(
						'id'       => 'entry-meta-single',
						'type'     => 'switch',
						'title'    => __('Entry-Meta', 'redux-framework-demo'),
						'subtitle' => __('Show Entry-meta (Posted on and posted by)?', 'redux-framework-demo'),
						'desc'     => __('Show Entry-meta (Posted on and posted by)?', 'redux-framework-demo'),
                        'default'  => true,
                    ),				
	
						 $field = array(
						'id'       => 'entry-utility-post',
						'type'     => 'switch',
						'title'    => __('Entry-utility', 'redux-framework-demo'),
						'subtitle' => __('Show entry-utility(Posted in category and leave comment)?', 'redux-framework-demo'),
						'desc'     => __('Show entry-utility(Posted in category and leave comment)?', 'redux-framework-demo'),
                        'default'  => true,
                    ),			
						 
						 $field = array(
						'id'       => 'nav-below-single',
						'type'     => 'switch',
						'title'    => __('Nav-below', 'redux-framework-demo'),
						'subtitle' => __('Show Nav-below?', 'redux-framework-demo'),
						'desc'     => __('Show Nav-below?', 'redux-framework-demo'),
                        'default'  => true,					
                ),	

//==============		
                )
            );
            $this->sections[] = array(
                'type' => 'divide',
            );

//=================== DOCUMENTATION========================
            $theme_info = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'redux-framework-demo') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'redux-framework-demo') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'redux-framework-demo') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'redux-framework-demo') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            if (file_exists(dirname(__FILE__) . '/../README.md')) {
                $this->sections['theme_docs'] = array(
                    'icon' => 'el-icon-list-alt',
                    'title' => __('Documentation', 'redux-framework-demo'),
                    'fields' => array(
                        array(
                            'id' => '17',
                            'type' => 'raw',
                            'markdown' => false,
                            'content' => file_get_contents(dirname(__FILE__) . '/kit-doc.html')
                        ),
                    ),
                );
            }//if
           
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon' => 'el-icon-info-sign',
                'title' => __('Theme Information', 'redux-framework-demo'),
                'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
                'fields' => array(
                    array(
                        'id' => 'raw_new_info',
                        'type' => 'raw',
                        'content' => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon' => 'el-icon-book',
                    'title' => __('Documentation', 'redux-framework-demo'),
                    'content' => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id' => 'redux-opts-1',
                'title' => __('Theme Information 1', 'redux-framework-demo'),
                'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            $this->args['help_tabs'][] = array(
                'id' => 'redux-opts-2',
                'title' => __('Theme Information 2', 'redux-framework-demo'),
                'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.
			global $redux_demo2;
			
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'redux_demo2', // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $theme->get('Name'), // Name that appears at the top of your panel
                'display_version' => $theme->get('Version'), // Version that appears at the top of your panel
                'menu_type' => 'menu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true, // Show the sections below the admin menu item or not
                'menu_title' => __('Theme Options', 'redux-framework-demo'),
                'page_title' => __('Theme Options', 'redux-framework-demo'),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => 'gfont', // Must be defined to add google fonts to the typography module
                //'async_typography' => true, // Use a asynchronous font on the front end or font string
                //'admin_bar' => false, // Show the panel pages on the admin bar
                'global_variable' => '', // Set a different name for your global variable other than the opt_name
                'dev_mode' => false, // Show the time the page took to load, etc
				// What to print by the field's title if the value shown is default
                'update_notice'      => false,
                'customizer' => true, // Enable basic customizer support
                // OPTIONAL -> Give you extra features
                'page_priority' => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options', // Permissions needed to access the options panel.
                'menu_icon' => get_stylesheet_directory_uri() . '/images/kid-icon17.png', // Specify a custom URL to an icon echo get_stylesheet_directory_uri()  /images/favicon.png
                'last_tab' => '', // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
                'page_slug' => '_options', // Page slug used to denote the panel
                'save_defaults' => true, // On load save the defaults to DB before user clicks save or not
                'default_show' => false, // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '', // What to print by the field's title if the value shown is default. Suggested: *
                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                //'domain'             	=> 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
                //'footer_credit'      	=> '', // Disable the footer credit of Redux. Please leave if you can help it.
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'show_import_export' => false, // REMOVE
                'system_info' => false, // REMOVE
                'help_tabs' => array(),
                'help_sidebar' => '', // __( '', $this->args['domain'] );
                'hints' => array(
                    'icon'              => 'icon-question-sign',
                    'icon_position'     => 'right',
                    'icon_color'        => 'lightgray',
                    'icon_size'         => 'normal',

                    'tip_style'         => array(
                        'color'     => 'light',
                        'shadow'    => true,
                        'rounded'   => false,
                        'style'     => '',
                    ),
                    'tip_position'      => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect' => array(
                        'show' => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'mouseover',
                        ),
                        'hide' => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url' => 'https://github.com/ReduxFramework/ReduxFramework',
                'title' => 'Visit us on GitHub',
                'icon' => 'el-icon-github'
                    // 'img' => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url' => 'https://www.facebook.com/kitkonsult',
                'title' => 'Like us on Facebook',
                'icon' => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url' => 'https://twitter.com/kitkonsult',
                'title' => 'Follow us on Twitter',
                'icon' => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url' => 'https://www.linkedin.com/company/kiper-it-konsult',
                'title' => 'Find us on LinkedIn',
                'icon' => 'el-icon-linkedin'
            );
            $this->args['share_icons'][] = array(
                'url' => 'https://plus.google.com/u/0/+KiperdesignSe/posts',
                'title' => 'Find us on Google+',
                'icon' => 'el-icon-googleplus'
            );



            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace("-", "_", $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('<p>KIPER IT-KONSULT</p>', 'redux-framework-demo'), $v);
            } else {
                $this->args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo');
            }

            // Add content after the form.
            $this->args['footer_text'] = __('<p>This theme is developed by Kiper it-konsult theme and are build with Redux framework and on Skeleton </p>', 'redux-framework-demo');
        }

    }

    new Redux_Framework_sample_config();
}


/**

  Custom function for the callback referenced above

 */
if (!function_exists('redux_my_custom_field')):

    function redux_my_custom_field($field, $value) {
        print_r($field);
        print_r($value);
    }

endif;

/**

  Custom function for the callback validation referenced above

 * */
if (!function_exists('redux_validate_callback_function')):

    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';
        /*
          do your validation

          if(something) {
          $value = $value;
          } elseif(something else) {
          $error = true;
          $value = $existing_value;
          $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }


endif;
