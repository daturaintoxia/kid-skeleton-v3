<?php
/**
 * @package Skeleton WordPress Theme Framework
 * @subpackage skeleton
 * @author Simple Themes - www.simplethemes.com
 *
 * Layout Hooks:
 *
 * skeleton_above_header // Opening header wrapper
 * skeleton_header // header tag and logo/header text
 * skeleton_header_extras // Additional content may be added to the header
 * skeleton_below_header // Closing header wrapper
 * skeleton_navbar // main menu wrapper
 * skeleton_before_content // Opening content wrapper
 * skeleton_after_content // Closing content wrapper
 * skeleton_before_sidebar // Opening sidebar wrapper
 * skeleton_after_sidebar // Closing sidebar wrapper
 * skeleton_footer // Footer
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, skeleton_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage skeleton
 * @since skeleton 2.0
 */



/*-----------------------------------------------------------------------------------*/
/* Initialize the Options Framework

/*-----------------------------------------------------------------------------------*/
// LOad Redux Options framework

if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/ReduxFramework/ReduxCore/kit-framework.php' ) ) {
    require_once( dirname( __FILE__ ) . '/ReduxFramework/ReduxCore/framework.php' );
}
if ( !isset( $redux_demo2 ) && file_exists( dirname( __FILE__ ) . '/ReduxFramework/kit-framework/sample-config.php' ) ) {
    require_once( dirname( __FILE__ ) . '/ReduxFramework/kit-framework/sample-config.php' );
}
// Load plugin required

    require_once( dirname( __FILE__ ) . '/tgm/plugg.php' );

global $redux_demo2;

/*-----------------------------------------------------------------------------------*/
/* Customizeable Color Palette Preset
/*-----------------------------------------------------------------------------------*/

if (! function_exists('skeleton_colorpicker_options'))  {

function skeleton_colorpicker_options() {
	wp_enqueue_script( 'colorpicker-options', get_template_directory_uri() . '/javascripts/colorpicker.js', array( 'jquery','wp-color-picker' ),1,true );
}
add_action( 'optionsframework_custom_scripts', 'skeleton_colorpicker_options' );

} // endif function exists


/*-----------------------------------------------------------------------------------*/
/* Define the sidebar and content widths for use in multiple functions
/* These values can be overridden on a conditional basis later on. See comments.
/*-----------------------------------------------------------------------------------*/

		global $redux_demo2;
		
if ($redux_demo2['layout'] == "mute") {
	define('SIDEBARWIDTH', 'nada');
} else {
	define('SIDEBARWIDTH', 'five');
}
if ($redux_demo2['layout'] == "mute") {
	define('CONTENTWIDTH', 'sixteen');
} else {
	define('CONTENTWIDTH', 'eleven');
}
//if (!of_get_option('content_width')) {
//	define('CONTENTWIDTH', 'eleven');
//} else {
//	define('CONTENTWIDTH', of_get_option('content_width'));
//}



// Load theme-specific shortcodes and helpers
require_once (get_template_directory() . '/shortcodes.php');

/*-----------------------------------------------------------------------------------*/
/* Register Core Stylesheets
/* These are necessary for the theme to function as intended
/* Supports the 'Better WordPress Minify' plugin to properly minimize styleshsets into one.
/* http://wordpress.org/extend/plugins/bwp-minify/
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_registerstyles' ) ) {

function skeleton_registerstyles() {

	// Set a dynamic version for cache busting
	$theme = wp_get_theme();
	if(is_child_theme()) {
		$parent = $theme->parent();
		$version = $parent['Version'];
		} else {
		$version = $theme['Version'];
	}

	$stylesheets = '';

	// register the various widths based on max_layout_width option
//	$maxwidth = of_get_option('max_layout_width');
global $redux_demo2;
$styley = $redux_demo2['css-layout'];
$design_style = $redux_demo2['stylesheet'];


$stylesheets .= wp_register_style('skeleton', get_template_directory_uri() .'/css/skeleton-'.$styley.'.css', array(), $version, 'screen, projection');
$stylesheets .= wp_register_style('design', get_template_directory_uri() .'/css/'.$design_style.'.css', array(), $version, 'screen, projection');
	// Register all other applicable stylesheets
    $stylesheets .= wp_register_style('layout', get_template_directory_uri().'/css/layout.css', array(), $version, 'screen, projection');
    $stylesheets .= wp_register_style('formalize', get_template_directory_uri().'/css/formalize.css', array(), $version, 'screen, projection');
    $stylesheets .= wp_register_style('superfish', get_template_directory_uri().'/css/superfish.css', array(), $version, 'screen, projection');
    $stylesheets .= wp_register_style('theme', get_stylesheet_directory_uri().'/style.css', array(), $version, 'screen, projection');
    $stylesheets .= wp_register_style('extra', get_stylesheet_directory_uri().'/extra.css', array(), $version, 'screen, projection');


	// hook to add additional stylesheets from a child theme
	echo apply_filters ('child_add_stylesheets',$stylesheets);

	// enqueue registered styles
	wp_enqueue_style( 'skeleton');
	wp_enqueue_style( 'design');
	wp_enqueue_style( 'theme');
	wp_enqueue_style( 'layout');
	wp_enqueue_style( 'formalize');
	wp_enqueue_style( 'superfish');
	wp_enqueue_style( 'extra');
}

add_action( 'wp_enqueue_scripts', 'skeleton_registerstyles');

}


/*-----------------------------------------------------------------------------------*/
/* Register Core Javascript
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'skeleton_header_scripts' ) ) {

	add_action('init', 'skeleton_header_scripts');
	function skeleton_header_scripts() {
		$javascripts  = wp_enqueue_script('jquery');
		$javascripts .= wp_enqueue_script('custom',get_template_directory_uri()."/javascripts/app.js",array('jquery'),'1.2.3',true);
		$javascripts .= wp_enqueue_script('superfish',get_template_directory_uri()."/javascripts/superfish.js",array('jquery'),'1.2.3',true);
		$javascripts .= wp_enqueue_script('formalize',get_template_directory_uri()."/javascripts/jquery.formalize.min.js",array('jquery'),'1.2.3',true);
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		$javascripts  =  wp_enqueue_script( 'comment-reply' );
		}
		echo apply_filters ('child_add_javascripts',$javascripts);
	}

}

/** Tell WordPress to run skeleton_setup() when the 'after_setup_theme' hook is run. */

add_action( 'after_setup_theme', 'skeleton_setup' );

if ( ! function_exists( 'skeleton_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override skeleton_setup() in a child theme, add your own skeleton_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails, custom-header and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Skeleton 1.0
 */
function skeleton_setup() {
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

 	// Use Regenerate Thumbnails Plugin to create these images on an existing install..
	// Set default thumbnail size
  	set_post_thumbnail_size( 150, 150 );
	// 150px square
	add_image_size( $name = 'squared150', $width = 150, $height = 150, $crop = true );
	// 250px square
	add_image_size( $name = 'squared250', $width = 250, $height = 250, $crop = true );
	// 4:3 Video
	add_image_size( $name = 'video43', $width = 320, $height = 240, $crop = true );
	// 16:9 Video
	add_image_size( $name = 'video169', $width = 320, $height = 180, $crop = true );


	// Register the available menus
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'smpl' ),
		'footer'	=> __( 'Footer Navigation', 'smpl' )
	));

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'smpl', get_template_directory() . '/languages' );


}
endif; // end skeleton_setup


/*-----------------------------------------------------------------------------------*/
// Main opening theme wrapper
/*-----------------------------------------------------------------------------------*/

// Hook to add content before header

if ( !function_exists( 'skeleton_above_header' ) ) {

function skeleton_above_header() {
    do_action('skeleton_above_header');
}

} // endif


if ( !function_exists( 'skeleton_wrapper_open' ) ) {

	function skeleton_wrapper_open() {
		echo "<div id=\"wrap\" class=\"container\">";
		//closed in skeleton_after_footer()
	}

} // endif

add_action('skeleton_above_header','skeleton_wrapper_open', 1);


/*-----------------------------------------------------------------------------------*/
// Opening #header
/*-----------------------------------------------------------------------------------*/

// Primary Header Function

if ( !function_exists( 'skeleton_header' ) ) {

	function skeleton_header() {
		do_action('skeleton_header');
	}

}

if ( !function_exists( 'skeleton_header_open' ) ) {

	function skeleton_header_open() {
	  	echo "<div id=\"header\" class=\"fullspan\">\n<div class=\"inner\">\n";
	}

} // endif

add_action('skeleton_header','skeleton_header_open', 1);

/*-----------------------------------------------------------------------------------*/
// Hookable theme option field to add add'l content to header
// such as social icons, phone number, widget, etc...
// Child Theme Override: child_header_extras();
/*-----------------------------------------------------------------------------------*/


//if ( !function_exists( 'skeleton_header_extras' ) ) {

//	function skeleton_header_extras() {
//		if (of_get_option('header_extra')) {
//			$extras  = "<div class=\"header_extras\">";
//			$extras .= of_get_option('header_extra');
//			$extras .= "</div>";
//			echo apply_filters ('child_header_extras',$extras);
//		}
//	}

// } // endif

// add_action('skeleton_header','skeleton_header_extras', 2);


/*-----------------------------------------------------------------------------------*/
/* SEO Logo
/* Displays H1 or DIV based on whether we are on the home page or not (for SEO)
/*-----------------------------------------------------------------------------------*/
 if ( !function_exists( 'skeleton_logo' ) ) {
	global $redux_demo2 ;
	 if ($redux_demo2['logo_opt'] == true) {
		function skeleton_logo() {
				$heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'h2';
					if (class_exists("ReduxFramework")) {
					global $redux_demo2;
					$logg = $redux_demo2['sitelogo']['url'];
					$poso = $redux_demo2['logo-foat'];
					$class="graphic";
					$skeleton_logo  = '<'.$heading_tag.' id="site-title" class="'.$class.'"><a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'"><img class="scale-with-grid '.$poso.' " alt="'.esc_attr( get_bloginfo('name','display')).'" src="'.$logg.'"/></a></'.$heading_tag.'>'. "\n";
					$skeleton_logo .= '<span class="site-desc '.$class.'"><h2>'.get_bloginfo('description').'</h2></span>'. "\n";
		echo apply_filters ( 'child_logo', $skeleton_logo);
				} 
		}
	add_action('skeleton_header','skeleton_logo', 3);

	} // endif

	 if ($redux_demo2['logo_opt'] == false) {
				function skeleton_logo() {
					$heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'h2';
					$class="text";
					$skeleton_logo  = '<'.$heading_tag.' id="site-title" class="'.$class.'"><a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'">'.get_bloginfo('name').'</a></'.$heading_tag.'>'. "\n";
					$skeleton_logo .= '<span class="site-desc '.$class.'"><h2>'.get_bloginfo('description').'</h2></span>'. "\n";
					echo apply_filters ( 'child_logo', $skeleton_logo);
		
				}
	add_action('skeleton_header','skeleton_logo', 3);

	} // endif
}
//echo $redux_demo2['sitelogo']['url'];
/*-----------------------------------------------------------------------------------*/
// Example of child theme logo replacement override
/*-----------------------------------------------------------------------------------*/


//	function my_custom_logo() {
//		$skeleton_logo = '<img src="http://placehold.it/320x150/000/FFF" alt="Logo"/>';
//		return $skeleton_logo;
//	}
//
//	add_filter('skeleton_logo','my_custom_logo');



/*-----------------------------------------------------------------------------------*/
/* Output CSS for Graphic Logo
/*-----------------------------------------------------------------------------------*/

// if ( !function_exists( 'skeleton_logostyle' ) ) {

// function skeleton_logostyle() {
//	if (of_get_option('use_logo_image')) {
	//	echo '<style type="text/css">#header #site-title.graphic a {background-image: url('.of_get_option('header_logo').');width: '.of_get_option('logo_width').'px;height: '.of_get_option('logo_height').'px;}</style>';
//	}
// }
// add_action('wp_head', 'skeleton_logostyle');

// } //endif


/*-----------------------------------------------------------------------------------*/
/* Closes the #header markup
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_header_close' ) ) {

	function skeleton_header_close() {
		echo "</div>"."\n";
		echo "</div>"."\n";
		echo "<!--/#header-->"."\n";
	}
	add_action('skeleton_header','skeleton_header_close', 4);

} //endif


/*-----------------------------------------------------------------------------------*/
/* Hook to add custom content immediately after #header
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_below_header' ) ) {

	function skeleton_below_header() {
		do_action('skeleton_below_header');
	}

} //endif


/*-----------------------------------------------------------------------------------*/
/* Navigation Hook
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_navbar' ) ) {

	function skeleton_navbar() {
		do_action('skeleton_navbar');
	}

} //endif


if ( !function_exists( 'skeleton_main_menu' ) ) {

	function skeleton_main_menu() {
		echo '<div id="navigation" class="fullspan">';
		wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary'));
		echo '</div><!--/#navigation-->';
	}

	add_action('skeleton_navbar','skeleton_main_menu', 1);

} //endif

    


// Kit slider
global $redux_demo2 ;
if ($redux_demo2['slider'] == TRUE) {
	function kit_slider(){
	//if ( !class_exists( 'kit_slider' ) && file_exists( dirname( __FILE__ ) . '/kit-responsive-slider/kit-responsive-slider.php' ) ) {
    //require_once( dirname( __FILE__ ) . '/kit-responsive-slider/kit-responsive-slider.php' );
//}
		if (function_exists('kit_responsive_slider')){
			if (is_home() || is_front_page()){
				echo '<div id="kit-slider" class="fullspan">';
				echo do_shortcode( '[kit_responsive_slider]' );
				echo '</div>';
			}
		}
	}
add_action('skeleton_navbar', 'kit_slider', 6);
}
// Kit Column Widget
global $redux_demo2 ;
if ($redux_demo2['above-3'] == TRUE) {
	if (!function_exists('kit_column_aria')){
		function kit_column_aria(){
			if (is_home() || is_front_page()){
			echo '<div id="kit-super-widget" class="sixteen alpha omega">';
				get_sidebar( 'kit' );
				echo '</div>';
			}
		}
	}
	add_action('skeleton_navbar','kit_column_aria', 7);
}
/*-----------------------------------------------------------------------------------*/
/* Before Content - skeleton_before_content();
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'skeleton_before_content' ) ) {
	function skeleton_before_content() {
		do_action('skeleton_before_content');
	}
}

if ( !function_exists( 'skeleton_content_wrapper_open' ) ) {

	function skeleton_content_wrapper_open() {
		echo "<div id=\"content-wraper\" class=\"container\">";
		//closed in skeleton_after_footer()
	}

} // endif

add_action('skeleton_before_content','skeleton_content_wrapper_open', 1);
/*-----------------------------------------------------------------------------------*/
/* Filterable utility function to set the content width - skeleton_content_width()
/* Specifies the column classes via conditional statements
/* See http://codex.wordpress.org/Conditional_Tags for a full list
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'skeleton_content_width' ) ) {

	function skeleton_content_width() {

		global $post;

		// Single Posts
		if ( is_single() ) {
			$post_wide = get_post_meta($post->ID, "sidebars", $single = true) ==  "false";

			// make sure no Post widgets are active
			if ( !is_active_sidebar('primary-widget-area') || $post_wide ) {
				$columns = 'sixteen';
			// widgets are active
			} elseif ( is_active_sidebar('primary-widget-area') && !$post_wide ) {
				$columns = CONTENTWIDTH;
			}

		// Single Pages
		} elseif ( is_page() ) {
			$page_wide = is_page_template('onecolumn-page.php');

			// make sure no Page widgets are active
			if ( !is_active_sidebar('secondary-widget-area') || $page_wide ) {
				$columns = 'sixteen';
			// widgets are active
			} elseif ( is_active_sidebar('secondary-widget-area') && !$page_wide ) {
				$columns = CONTENTWIDTH;
			}

		// All Others
		} else {
			$columns = CONTENTWIDTH;
		}

		return $columns;

	}
	// Create filter
	add_filter('skeleton_set_colwidth', 'skeleton_content_width', 10, 1);

}


/*-----------------------------------------------------------------------------------*/
// Content Wrap Markup - skeleton_content_wrap()
// Be sure to add the excess of 16 to skeleton_before_sidebar() as well
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'skeleton_content_wrap' ) )  {

	function skeleton_content_wrap() {

	$columns = '';
	$columns = apply_filters('skeleton_set_colwidth', $columns, 1);


	// Apply the markup
	echo '<a id="top"></a>';
	echo '<div id="content" class="'.$columns.' columns">';

	}
	// hook to skeleton_before_content()
	add_action( 'skeleton_before_content', 'skeleton_content_wrap', 1 );

} //endif



/*-----------------------------------------------------------------------------------*/
/* After Content Hook
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_after_content' ) ) {

	function skeleton_after_content() {
		do_action('skeleton_after_content');
	}

} //endif



/*-----------------------------------------------------------------------------------*/
// After Content Wrap Markup - skeleton_content_wrap_close()
/*-----------------------------------------------------------------------------------*/


if (! function_exists('skeleton_content_wrap_close'))  {

    function skeleton_content_wrap_close() {
    	echo "\t\t</div><!-- /.columns (#content) -->\n";
    }

    add_action( 'skeleton_after_content', 'skeleton_content_wrap_close', 1 );
}



/*-----------------------------------------------------------------------------------*/
/* Before Sidebar Hook - skeleton_before_sidebar()
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'skeleton_before_sidebar' ) ) {

	function skeleton_before_sidebar() {
		do_action('skeleton_before_sidebar');
	}

} //endif


/*-----------------------------------------------------------------------------------*/
/* Filterable utility function to set the sidebar width - skeleton_sidebar_width()
/* Specifies the column classes via conditional statements
/* See http://codex.wordpress.org/Conditional_Tags for a full list
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'skeleton_sidebar_width' ) ) {

	function skeleton_sidebar_width() {
	global $post;

	if ( is_single() ) {
		// Posts: check for custom field of sidebars => false
		$post_wide = get_post_meta($post->ID, "sidebars", $single = true) ==  "false";

		// make sure no Post widgets are active
		if ( !is_active_sidebar('primary-widget-area') || $post_wide ) {
			$columns = false;
		// widgets are active
		} elseif ( is_active_sidebar('primary-widget-area') && !$post_wide ) {
			$columns = SIDEBARWIDTH;
		}

	} elseif ( is_page() ) {
		// Pages: check for custom page template
		$page_wide = is_page_template('onecolumn-page.php');

		// make sure no Page widgets are active
		if ( !is_active_sidebar('secondary-widget-area') || $page_wide ) {
			$columns = false;
		// widgets are active
		} elseif ( is_active_sidebar('secondary-widget-area') && !$page_wide ) {
			$columns = SIDEBARWIDTH;
		}

	} else {
		$columns = SIDEBARWIDTH;
	}

	return $columns;


	}
	// Create filter
	add_filter('skeleton_set_sidebarwidth', 'skeleton_sidebar_width', 10, 1);

} //endif


/*-----------------------------------------------------------------------------------*/
// Sidebar Wrap Markup - skeleton_sidebar_wrap()
// Be sure to add the excess of 16 to skeleton_content_wrap() as well
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'skeleton_sidebar_wrap' ) )  {

	function skeleton_sidebar_wrap() {

	$columns = '';
	$columns = apply_filters('skeleton_set_sidebarwidth', $columns, 1);


	// Apply the markup
		global $redux_demo2;
		$kitclass = $redux_demo2['layout'];
	echo '<div id="sidebar" class="'.$columns.' columns '.$kitclass.'" role="complementary">';

	}
	// hook to skeleton_before_content()
	add_action( 'skeleton_before_sidebar', 'skeleton_sidebar_wrap', 1 );

} //endif


/*-----------------------------------------------------------------------------------*/
/* After Sidebar Hook - skeleton_after_sidebar()
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'skeleton_after_sidebar' ) ) {

	function skeleton_after_sidebar() {
		do_action('skeleton_after_sidebar');
	}

} //endif


/*-----------------------------------------------------------------------------------*/
/* After Sidebar Markup
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_sidebar_wrap_close' ) ) {
	function skeleton_sidebar_wrap_close() {
	// Additional Content could be added here
	   echo '</div><!-- #sidebar -->';
	}
} //endif

add_action( 'skeleton_after_sidebar', 'skeleton_sidebar_wrap_close',1);

if (!function_exists('content_wrapper_close'))  {
    function content_wrapper_close() {
		echo "</div><!--/#content_wrap.container-->"."\n";	
    }
    add_action('skeleton_after_sidebar', 'content_wrapper_close',2);
}

/*-----------------------------------------------------------------------------------*/
// Sidebar Positioning Utility (sidebar-left | sidebar-right)
// Sets a body class for source ordered sidebar positioning
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_sidebar_position' ) ) {

function skeleton_sidebar_position($class) {
		global $post;
		global $redux_demo2;
		$class[] = 'sidebar-'.$redux_demo2['layout'];
		return $class;
	}
	add_filter('body_class','skeleton_sidebar_position');
 }  // endif 

/*-----------------------------------------------------------------------------------*/
// Global hook for footer actions
/*-----------------------------------------------------------------------------------*/

function skeleton_footer() {
	do_action('skeleton_footer');
}
add_action('wp_footer', 'skeleton_footer',1);


/*-----------------------------------------------------------------------------------*/
/* Before Footer
/*-----------------------------------------------------------------------------------*/

if (!function_exists('skeleton_before_footer'))  {
    function skeleton_before_footer() {
			$footerwidgets = is_active_sidebar('first-footer-widget-area') + is_active_sidebar('second-footer-widget-area') + is_active_sidebar('third-footer-widget-area') + is_active_sidebar('fourth-footer-widget-area');
			$class = ($footerwidgets == '0' ? 'noborder' : 'normal');
			echo '<div class="clear"></div><div id="footer" class="'.$class.' sixteen columns">';
			

    }
    add_action('skeleton_footer', 'skeleton_before_footer',1);
}


/*-----------------------------------------------------------------------------------*/
// Footer Widgets
/*-----------------------------------------------------------------------------------*/

if (! function_exists('skeleton_footer_widgets'))  {
	function skeleton_footer_widgets() {
		//loads sidebar-footer.php
		get_sidebar( 'footer' );
	}
	add_action('skeleton_footer', 'skeleton_footer_widgets',2);
}


/*-----------------------------------------------------------------------------------*/
/* After Footer
/*-----------------------------------------------------------------------------------*/

if (!function_exists('skeleton_after_footer'))  {

    function skeleton_after_footer() {
			echo "</div><!--/#footer-->"."\n";
			echo "</div><!--/#wrap.container-->"."\n";
			echo '<div id="credits">';
		//	 ('footer_text');
		global $redux_demo2;
		echo ' ' . $redux_demo2['footer-text1']; 

		//echo 'test '. $redux_demo2['logo_opt'];	
		// TEST SCRIPT START
			 global $redux_demo2 ;
			 if ($redux_demo2['year_opt'] == TRUE) {
							echo '<div id="year">';
							echo date(Y);
							echo "</div><!--/#fyear-->"."\n";
			}
// TEST SCRIPT START
			//global $redux_demo2;
			//echo $redux_demo2['widget_cat'];
   

    }
	add_action('skeleton_footer', 'skeleton_after_footer',4);
}

/*-----------------------------------------------------------------------------------*/
//	Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
//	To override skeleton_widgets_init() in a child theme, remove the action hook and add your own
//	function tied to the init hook.
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_widgets_init' ) ) {

function skeleton_widgets_init() {
		// Area 1, located at the top of the sidebar.
		register_sidebar( array(
		'name' => __( 'Posts Widget Area', 'smpl' ),
		'id' => 'primary-widget-area',
		'description' => __( 'Shown only in Blog Posts, Archives, Categories, etc.', 'smpl' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );


	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Pages Widget Area', 'smpl' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'Shown only in Pages', 'smpl' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'smpl' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'smpl' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'smpl' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'smpl' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'smpl' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'smpl' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'smpl' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'smpl' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	global $redux_demo2 ;
	if ($redux_demo2['above-3'] == TRUE) {
		// Register top-text  widget 
		register_sidebar( array(
		'name' => __( 'Top Page Widget Aria', 'skeleton' ),
		'id' => 'top-widget-area',
		'description' => __( 'The top text widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title kit-spec">',
		'after_title' => '</h3>',
	) );

	// Register first column  widget 
		register_sidebar( array(
		'name' => __( 'First Page Column Widget Aria', 'skeleton' ),
		'id' => 'first-column-widget-area',
		'description' => __( 'The first column widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title kit-spec">',
		'after_title' => '</h3>',
	) );
	// Register second column  widget 
		register_sidebar( array(
		'name' => __( 'Second Page Column Widget Aria', 'skeleton' ),
		'id' => 'second-column-widget-area',
		'description' => __( 'The second column fourth footer widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title kit-spec">',
		'after_title' => '</h3>',
	) );
	// Register third column  widget 
		register_sidebar( array(
		'name' => __( 'Third Page Column Widget Aria', 'skeleton' ),
		'id' => 'third-column-widget-area',
		'description' => __( 'The third column widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title kit-spec">',
		'after_title' => '</h3>',
	) );
	}
}

/** Register sidebars by running skeleton_widgets_init() on the widgets_init hook. */

add_action( 'widgets_init', 'skeleton_widgets_init' );

}





/*-----------------------------------------------------------------------------------*/
// Sets the post excerpt length to 40 characters.
// To override this length in a child theme, remove the filter and add your own
// function tied to the excerpt_length filter hook.
/*-----------------------------------------------------------------------------------*/


function custom_wp_trim_excerpt($text) {
	global $redux_demo2;

$raw_excerpt = $text;
if ( '' == $text ) {
    //Retrieve the post content.
    $text = get_the_content('');
 
    //Delete all shortcode tags from the content.
    $text = strip_shortcodes( $text );
 
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
     
    $allowed_tags = '<p>,<a>,<em>,<strong>,<img>,<iframe>'; 
    $text = strip_tags($text, $allowed_tags);
     
  //  $excerpt_word_count =   /*** MODIFY THIS. change the excerpt word count to any integer you like.***/
    $excerpt_length = apply_filters('excerpt_length', $redux_demo2['exerpt']);
     
    $excerpt_end = ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'skeleton' ) . '</a>'; 
    $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);
     
    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
    if ( count($words) > $excerpt_length ) {
        array_pop($words);
        $text = implode(' ', $words);
        $text = $text . $excerpt_more;
    } else {
        $text = implode(' ', $words);
    }
}
return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'custom_wp_trim_excerpt');



/*-----------------------------------------------------------------------------------*/
// Returns a "Continue Reading" link for excerpts
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_continue_reading_link' ) ) {

	function skeleton_continue_reading_link() {
		return ' <a href="'. get_permalink() . '">' . __( 'Läs mer <span class="meta-nav">&rarr;</span>', 'smpl' ) . '</a>';
	}
}


/*-----------------------------------------------------------------------------------*/
// Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis
// and skeleton_continue_reading_link().
//
// To override this in a child theme, remove the filter and add your own
// function tied to the excerpt_more filter hook.
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_auto_excerpt_more' ) ) {

	function skeleton_auto_excerpt_more( $more ) {
		return ' &hellip;' . skeleton_continue_reading_link();
	}
	add_filter( 'excerpt_more', 'skeleton_auto_excerpt_more' );

}

/*-----------------------------------------------------------------------------------*/
// Adds a pretty "Continue Reading" link to custom post excerpts.
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'skeleton_custom_excerpt_more' ) ) {

	function skeleton_custom_excerpt_more( $output ) {
		if ( has_excerpt() && ! is_attachment() ) {
			$output .= skeleton_continue_reading_link();
		}
		return $output;
	}
	add_filter( 'get_the_excerpt', 'skeleton_custom_excerpt_more' );

}



/*-----------------------------------------------------------------------------------*/
// Removes the page jump when read more is clicked through
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'remove_more_jump_link' ) ) {

	function remove_more_jump_link($link) {
		$offset = strpos($link, '#more-');
		if ($offset) {
		$end = strpos($link, '"',$offset);
		}
		if ($end) {
		$link = substr_replace($link, '', $offset, $end-$offset);
		}
		return $link;
	}
	add_filter('the_content_more_link', 'remove_more_jump_link');

}



/*-----------------------------------------------------------------------------------*/
//	Comment Styles
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'skeleton_comments' ) ) :
	function skeleton_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="single-comment clearfix">
			<div class="comment-author vcard"> <?php echo get_avatar($comment,$size='64'); ?></div>
			<div class="comment-meta commentmetadata">
				<?php if ($comment->comment_approved == '0') : ?>
				<em><?php _e('Comment is awaiting moderation','smpl');?></em> <br />
				<?php endif; ?>
				<h6><?php echo __('By','smpl').' '.get_comment_author_link(). ' '. get_comment_date(). '  -  ' . get_comment_time(); ?></h6>
				<?php comment_text() ?>
				<?php edit_comment_link(__('Edit comment','smpl'),'  ',''); ?>
				<?php comment_reply_link(array_merge( $args, array('reply_text' => __('Reply','smpl'),'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
			</div>
		</div>
		<!-- </li> -->
	<?php  }
endif;


if ( ! function_exists( 'skeleton_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Skeleton 1.0
 */
function skeleton_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'smpl' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'smpl' ), get_the_author() ),
			get_the_author()
		)
	);
}

endif;

if ( ! function_exists( 'skeleton_posted_in' ) ) :

	 // Prints HTML with meta information for the current post (category, tags and permalink).
	function skeleton_posted_in() {
		// Retrieves tag list of current post, separated by commas.
		$tag_list = get_the_tag_list( '', ', ' );
		if ( $tag_list ) {
			$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'smpl' );
		} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
			$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'smpl' );
		} else {
			$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'smpl' );
		}
		// Prints the string, replacing the placeholders.
		printf(
			$posted_in,
			get_the_category_list( ', ' ),
			$tag_list,
			get_permalink(),
			the_title_attribute( 'echo=0' )
		);
}

endif;


/*-----------------------------------------------------------------------------------*/
/* Enable Shortcodes in excerpts and widgets
/*-----------------------------------------------------------------------------------*/


add_filter('widget_text', 'do_shortcode');
add_filter( 'the_excerpt', 'do_shortcode');
add_filter('get_the_excerpt', 'do_shortcode');


/*-----------------------------------------------------------------------------------*/
/* Override default embeddable content width
/*-----------------------------------------------------------------------------------*/

if (!function_exists('skeleton_content_width'))  {
	function skeleton_content_width() {
		$content_width = 580;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Filters wp_title to print a proper <title> tag based on content
/*-----------------------------------------------------------------------------------*/
if (!function_exists('skeleton_wp_title'))  {

	function skeleton_wp_title( $title, $sep ) {
		global $page, $paged;

		if ( is_feed() )
			return $title;

		// Add the blog name
		$title .= get_bloginfo( 'name' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title .= " $sep $site_description";

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( __( 'Page %s', 'skeleton' ), max( $paged, $page ) );

		return apply_filters ('skeleton_child_wp_title',$title);
	}
}
add_filter( 'wp_title', 'skeleton_wp_title', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/* Override default filter for theme options 'textarea' sanitization.
/*-----------------------------------------------------------------------------------*/


function optionscheck_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'skeleton_custom_sanitize_textarea' );
}

add_action('admin_init','optionscheck_change_santiziation', 100);


function skeleton_custom_sanitize_textarea($input) {
    global $allowedposttags;
    	$custom_allowedtags["embed"] = array(
    		"src" => array(),
    		"type" => array(),
    		"allowfullscreen" => array(),
    		"allowscriptaccess" => array(),
    		"height" => array(),
    		"width" => array()
    	);
    	$custom_allowedtags["script"] = array();
    	$custom_allowedtags["a"] = array('href' => array(),'title' => array());
    	$custom_allowedtags["img"] = array('src' => array(),'title' => array(),'alt' => array());
    	$custom_allowedtags["br"] = array();
    	$custom_allowedtags["em"] = array();
    	$custom_allowedtags["strong"] = array();
      	$custom_allowedtags = array_merge($custom_allowedtags, $allowedposttags);
      	$output = wp_kses( $input, $custom_allowedtags);
      	return $output;
}



/*-----------------------------------------------------------------------------------*/
/* Theme Customization Options
/*-----------------------------------------------------------------------------------*/
if (!function_exists('skeleton_options_styles'))  {

	function skeleton_options_styles() {

		// build an array of styleable heading tags
		$headings = array(
			'body' => 'body',
			'#site-title a' => 'header',
			'.site-desc.text' => 'tagline',
			'h1' => 'h1',
			'h2' => 'h2',
			'h3' => 'h3',
			'h4' => 'h4',
			'h5' => 'h5'
		);

		$stackarray = array(
			'helvetica'  => '"HelveticaNeue", "Helvetica Neue", Helvetica, Arial, sans-serif',
			'arial' 	 => 'Arial, Helvetica, sans-serif',
			'georgia' 	 => 'Constantia, "Lucida Bright", Lucidabright, "Lucida Serif", Lucida, "DejaVu Serif", "Bitstream Vera Serif", "Liberation Serif", Georgia, serif',
			'cambria' 	 => 'Cambria, "Hoefler Text", Utopia, "Liberation Serif", "Nimbus Roman No9 L Regular", Times, "Times New Roman", serif',
			'tahoma' 	 => 'Tahoma, Verdana, Segoe, sans-serif',
			'palatino' 	 => '"Palatino Linotype", Palatino, Baskerville, Georgia, serif',
			'droidsans'  => '"Droid Sans", sans-serif',
			'droidserif' => '"Droid Serif", serif',
		);


		echo '<style type="text/css">';

		foreach ($headings as $key => $selector) {
			$item = $selector.'_typography';
	//		$property = of_get_option($item);
			$face = $property['face'];
			echo $key.' {';
			echo 'color:'.$property['color'].';';
			echo 'font-size:'.$property['size'].';';
			echo 'font-family:'.$stackarray[$property['face']].';';
			if ($property['style'] == "bold italic") {
			echo 'font-weight:bold;';
			echo 'font-style:italic;';
			} else {
			echo 'font-weight:'.$property['style'].';';
			}
			echo '}'."\n";
		}

		// Body Background
		echo 'body {';
		// Custom Background
	//	$body_background = of_get_option('body_background');
		if ($body_background) {
			if ($body_background['image']) {
				echo 'background:'.$body_background['color'].' url('.$body_background['image'].') '.$body_background['repeat'].' '.$body_background['position'].' '.$body_background['attachment'].';';
			} elseif ($body_background['color']) {
				echo 'background-color:'.$body_background['color'].';';
			}
		}
		// End Body Styles
		echo '}'."\n";
//		echo 'a { color: '.of_get_option('link_color', '#000').';}';
		echo '</style>';
	}
}
add_action('wp_head','skeleton_options_styles',10);


			 global $redux_demo2 ;
			 if ($redux_demo2['widget_opt'] == TRUE) {
    include(get_template_directory() . '/kitwidget.php');
}
			 if ($redux_demo2['above-3'] == TRUE) {
   include(get_template_directory() . '/super-page-widget.php');
}
//		$kitwidget = $redux_demo2['widget_opt']['options'];
//			if ( $kitwidget = 1)
//			require_once (get_template_directory() . '/kitwidget.php');
//		    } else {
//       return null;
// 3.5+ media gallery...
//================================================ KID GALLERY STYLE
remove_shortcode('gallery', 'gallery_shortcode');

add_shortcode('gallery', 'kid_gallery_shortcode');

/**
 * The Gallery shortcode.
 *
 * This implements the functionality of the Gallery Shortcode for displaying
 * WordPress images on a post.
 *
 * @since 2.5.0
 *
 * @param array $attr {
 *     Attributes of the gallery shortcode.
 *
 *     @type string $order      Order of the images in the gallery. Default 'ASC'. Accepts 'ASC', 'DESC'.
 *     @type string $orderby    The field to use when ordering the images. Default 'menu_order ID'.
 *                              Accepts any valid SQL ORDERBY statement.
 *     @type int    $id         Post ID.
 *     @type string $itemtag    HTML tag to use for each image in the gallery.
 *                              Default 'dl', or 'figure' when the theme registers HTML5 gallery support.
 *     @type string $icontag    HTML tag to use for each image's icon.
 *                              Default 'dt', or 'div' when the theme registers HTML5 gallery support.
 *     @type string $captiontag HTML tag to use for each image's caption.
 *                              Default 'dd', or 'figcaption' when the theme registers HTML5 gallery support.
 *     @type int    $columns    Number of columns of images to display. Default 3.
 *     @type string $size       Size of the images to display. Default 'thumbnail'.
 *     @type string $ids        A comma-separated list of IDs of attachments to display. Default empty.
 *     @type string $include    A comma-separated list of IDs of attachments to include. Default empty.
 *     @type string $exclude    A comma-separated list of IDs of attachments to exclude. Default empty.
 *     @type string $link       What to link each image to. Default empty (links to the attachment page).
 *                              Accepts 'file', 'none'.
 * }
 * @return string HTML content to display gallery.
 */
function kid_gallery_shortcode( $attr ) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	/**
	 * Filter the default gallery shortcode output.
	 *
	 * If the filtered output isn't empty, it will be used instead of generating
	 * the default gallery template.
	 *
	 * @since 2.5.0
	 *
	 * @see gallery_shortcode()
	 *
	 * @param string $output The gallery output. Default empty.
	 * @param array  $attr   Attributes of the gallery shortcode.
	 */
	$output = apply_filters( 'post_gallery', '', $attr );
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	$html5 = current_theme_supports( 'html5', 'gallery' );
	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => $html5 ? 'figure'     : 'dl',
		'icontag'    => $html5 ? 'div'        : 'dt',
		'captiontag' => $html5 ? 'figcaption' : 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
	), $attr, 'gallery'));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'dl';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'dd';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'dt';

	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';

	/**
	 * Filter whether to print default gallery styles.
	 *
	 * @since 3.1.0
	 *
	 * @param bool $print Whether to print default gallery styles.
	 *                    Defaults to false if the theme supports HTML5 galleries.
	 *                    Otherwise, defaults to true.
	 */
	if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>\n\t\t";
	}

	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

	/**
	 * Filter the default gallery shortcode CSS styles.
	 *
	 * @since 2.5.0
	 *
	 * @param string $gallery_style Default gallery shortcode CSS styles.
	 * @param string $gallery_div   Opening HTML div container for the gallery shortcode output.
	 */
	$output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		if ( ! empty( $link ) && 'file' === $link )
			$image_output = wp_get_attachment_link( $id, $size, false, false );
		elseif ( ! empty( $link ) && 'none' === $link )
			$image_output = wp_get_attachment_image( $id, $size, false );
		else
			$image_output = wp_get_attachment_link( $id, $size, true, false );

		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) )
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

		$output .= "<{$itemtag} class='gallery-item one_fourth'>";
		$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
			$output .= '<br style="clear: both" />';
		}
	}

	if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
		$output .= "
			<br style='clear: both' />";
	}

	$output .= "
		</div>\n";

	return $output;
}
// END KID GALLERY SHORTCODE

//====================================================================================
add_action( 'wp_enqueue_media', 'mgzc_media_gallery_zero_columns' );
function mgzc_media_gallery_zero_columns(){
    add_action( 'admin_print_footer_scripts', 'mgzc_media_gallery_zero_columns_script', 999);
}
function mgzc_media_gallery_zero_columns_script(){
?>
<script type="text/javascript">
jQuery(function(){
    if(wp.media.view.Settings.Gallery){
        wp.media.view.Settings.Gallery = wp.media.view.Settings.extend({
            className: "gallery-settings",
            template: wp.media.template("gallery-settings"),
            render: function() {
                wp.media.View.prototype.render.apply( this, arguments );
                // Append an option for 0 (zero) columns if not already present...
                var $s = this.$('select.columns');
                if(!$s.find('option[value="0"]').length){
                    $s.append('<option value="0">0</option>');
                }
                // Select the correct values.
                _( this.model.attributes ).chain().keys().each( this.update, this );
                return this;
            }
        });
    }
});
</script>';
<?php
}
add_action( 'after_setup_theme', 'default_attachment_display_settings' );
/**
 * Set the Attachment Display Settings "Link To" default to "none"
 *
 * This function is attached to the 'after_setup_theme' action hook.
 */
function default_attachment_display_settings() {
	update_option( 'image_default_align', 'left' );
	update_option( 'image_default_link_type', 'file' );
	update_option( 'image_default_size', 'medium' );
}	
//first image to thumbnail
 global $redux_demo2 ;
if ($redux_demo2['auto-featured'] == TRUE) {
	function autoset_featured() {
          global $post;
          $already_has_thumb = has_post_thumbnail($post->ID);
              if (!$already_has_thumb)  {
              $attached_image = get_children( "post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );
                          if ($attached_image) {
                                foreach ($attached_image as $attachment_id => $attachment) {
                                set_post_thumbnail($post->ID, $attachment_id);
                                }
                           } else {
                                set_post_thumbnail($post->ID, '414');
                           }
                        }
    }  //end function

add_action('the_post', 'autoset_featured');
add_action('save_post', 'autoset_featured');
add_action('draft_to_publish', 'autoset_featured');
add_action('new_to_publish', 'autoset_featured');
add_action('pending_to_publish', 'autoset_featured');
add_action('future_to_publish', 'autoset_featured');
}


function so_comment_button() {
global $redux_demo2;
$bcol = $redux_demo2['button-color'];
    echo '<input name="submit" class="'.$bcol.'" type="submit" value="' . __( 'Post Comment', 'textdomain' ) . '" />';
    
}

add_action( 'comment_form', 'so_comment_button' );
	
