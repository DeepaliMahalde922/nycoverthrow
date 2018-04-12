<?php
//$qode_toolbar = true;
//$qode_landing = true;

load_theme_textdomain( 'qode', get_template_directory().'/languages' );

if(isset($qode_toolbar)) {
	add_action('after_setup_theme', 'qodeStartSession', 1);
	add_action('wp_logout', 'qodeEndSession');
	add_action('wp_login', 'qodeEndSession');

	/* Start session */
	if (!function_exists('qodeStartSession')) {
		function qodeStartSession() {
			if(!session_id()) {
				session_start();
			}
			if (!empty($_GET['animation']))
				$_SESSION['qode_animation'] = $_GET['animation'];
			if (isset($_SESSION['qode_animation']))
				if ($_SESSION['qode_animation'] == "off")
					$_SESSION['qode_animation'] = "";
		}}

	/* End session */

	if (!function_exists('qodeEndSession')) {
		function qodeEndSession() {
			session_destroy ();
		}
	}
}

add_filter('widget_text', 'do_shortcode');

define('QODE_ROOT', get_template_directory_uri());
define('QODE_VAR_PREFIX', 'qode_');
include_once('includes/shortcodes/shortcodes.php');
include_once('includes/qode-options.php');
include_once('includes/import/qode-import.php');
//include_once('export/qode-export.php');
include_once('includes/custom-fields.php');
include_once('includes/custom-fields-post-formats.php');
include_once('includes/qode-breadcrumbs.php');
include_once('includes/nav_menu/qode-menu.php');
include_once('includes/sidebar/qode-custom-sidebar.php');
include_once('includes/qode-custom-post-types.php');
include_once('includes/qode-like.php' );
include_once('includes/qode-custom-taxonomy-field.php');
include_once('includes/header/qode-header-functions.php');
include_once('includes/title/qode-title-functions.php');
include_once('includes/qode-portfolio-functions.php');
include_once('includes/qode-loading-spinners.php');
/* Include comment functionality */
include_once('includes/comment/comment.php');
/* Include sidebar functionality */
include_once('includes/sidebar/sidebar.php');
/* Include pagination functionality */
include_once('includes/pagination/pagination.php');
/* Include qode carousel select box for visual composer */
include_once('includes/qode_carousel/qode-carousel.php');
/* Include font awesome icons list */
include_once('includes/font_awesome/font-awesome.php');
include_once('includes/elegant_icons/elegant-icons.php');
/** Include the TGM_Plugin_Activation class. */
require_once dirname( __FILE__ ) . '/includes/plugins/class-tgm-plugin-activation.php';
/* Include visual composer initialization */
include_once('includes/plugins/visual-composer.php');
/* Include activation for layer slider */
include_once('includes/plugins/layer-slider.php');
include_once('widgets/relate_posts_widget.php');
include_once('widgets/latest_posts_menu.php');
include_once('widgets/call_to_action_widget.php');


//does woocommerce function exists?
if(function_exists("is_woocommerce")){
	//include woocommerce configuration
	require_once( 'woocommerce/woocommerce_configuration.php' );
	//include cart dropdown widget
	include_once('widgets/woocommerce-dropdown-cart.php');
}

add_filter( 'call_to_action_widget', 'do_shortcode');


if (!function_exists('qode_styles')) {
	/**
	 * Function that includes theme's core styles
	 */
	function qode_styles() {
		global $qode_options;
		global $qode_toolbar;
        global $qode_landing;

		//init variables
		$responsiveness = 'yes';
		$vertical_area 	= "no";

		//include theme's core styles
		wp_enqueue_style("default_style", QODE_ROOT . "/style.css");
		wp_enqueue_style("stylesheet", QODE_ROOT . "/css/stylesheet.min.css");

		//define files afer which style dynamic needs to be included. It should be included last so it can override other files
		$style_dynamic_deps_array = array();
		if(qode_is_woocommerce_installed()) {
			$style_dynamic_deps_array = array('woocommerce', 'woocommerce_responsive');
		}

		wp_enqueue_style("style_dynamic", QODE_ROOT . "/css/style_dynamic.php", $style_dynamic_deps_array); //it must be included after woocommerce styles so it can override it

		//include font-awesome styles
		wp_enqueue_style("qode-font-awesome", QODE_ROOT . "/css/font-awesome/css/font-awesome.min.css");

		//include elegant font styles
		wp_enqueue_style("elegant-icons", QODE_ROOT . "/css/elegant-icons/style.min.css");

		//does responsive option exists?
		if (isset($qode_options['responsiveness'])) {
			$responsiveness = $qode_options['responsiveness'];
		}

		//is responsive option turned on?
		if ($responsiveness != "no") {
			//include proper styles
			wp_enqueue_style("responsive", QODE_ROOT . "/css/responsive.min.css");
			wp_enqueue_style("style_dynamic_responsive", QODE_ROOT . "/css/style_dynamic_responsive.php");
		}

		//does left menu option exists?
		if (isset($qode_options['vertical_area'])){
			$vertical_area = $qode_options['vertical_area'];
		}

		//is left menu activated and is responsive turned on?
		if($vertical_area == "yes" && $responsiveness != "no"){
			wp_enqueue_style("vertical_responsive", QODE_ROOT . "/css/vertical_responsive.css");
		}

		//is toolbar turned on?
		if (isset($qode_toolbar)) {
			//include toolbar specific styles
			wp_enqueue_style("toolbar", QODE_ROOT . "/css/toolbar.css");
		}

        //is landing turned on?
        if (isset($qode_landing)) {
            //include toolbar specific styles
            wp_enqueue_style("qode_landing_fancybox", get_home_url() . "/demo-files/landing/css/jquery.fancybox.css");
            wp_enqueue_style("qode_landing", get_home_url() . "/demo-files/landing/css/landing_stylesheet_stripped.css");
        }

		//include Visual Composer styles
		wp_enqueue_style( 'js_composer_front' );
		wp_enqueue_style("custom_css", QODE_ROOT . "/css/custom_css.php");
	}

	add_action('wp_enqueue_scripts', 'qode_styles');
}


if(!function_exists('qode_browser_specific_styles')) {
	/**
	 * Function that includes browser specific styles. Works for Chrome on Mac and for webkit browsers
	 */
	function qode_browser_specific_styles() {
		global $is_chrome;
		global $is_safari;

		//check Chrome version
		preg_match( "#Chrome/(.+?)\.#", $_SERVER['HTTP_USER_AGENT'], $match );
		if(!empty($match)) {
			$chrome_version = $match[1];
		} else{
			$chrome_version = 0;
		}

		//is Mac OS X?
		$mac_os = strpos($_SERVER['HTTP_USER_AGENT'], "Macintosh; Intel Mac OS X");

		//is Chrome on Mac with version greater than 21
		if($is_chrome && ($mac_os !== false) && ($chrome_version > 21)) {
			//include mac specific styles
			wp_enqueue_style("mac_stylesheet", QODE_ROOT . "/css/mac_stylesheet.css");
		}

		//is Chrome or Safari?
		if($is_chrome || $is_safari) {
			//include style for webkit browsers only
			wp_enqueue_style("webkit", QODE_ROOT . "/css/webkit_stylesheet.css");
		}
	}

	add_action('wp_enqueue_scripts', 'qode_browser_specific_styles');
}

if(!function_exists('qode_init_page_id')) {
	/**
	 * Function that sets global $qode_page_id variable
	 */
	function qode_init_page_id() {
		global $wp_query;
		global $qode_page_id;

		$qode_page_id = $wp_query->get_queried_object_id();
	}

	add_action('get_header', 'qode_init_page_id');
}

if(!function_exists('qode_google_fonts_styles')) {
	/**
	 * Function that includes google fonts defined anywhere in the theme
	 */
	function qode_google_fonts_styles() {
		global $qode_options;
        global $qode_toolbar;

		$font_weight_str = '100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
		$available_font_options = array(
			'google_fonts',
			'page_title_google_fonts',
            'page_subtitle_google_fonts',
            'page_breadcrumb_google_fonts',
			'h1_google_fonts',
			'h2_google_fonts',
			'h3_google_fonts',
			'h4_google_fonts',
			'h5_google_fonts',
			'h6_google_fonts',
			'text_google_fonts',
            'portfolio_filter_font_family',
            'portfolio_category_list_font_family',
			'menu_google_fonts',
			'dropdown_google_fonts',
			'dropdown_google_fonts_thirdlvl',
			'fixed_google_fonts',
			'sticky_google_fonts',
			'mobile_google_fonts',
			'button_title_google_fonts',
			'message_title_google_fonts',
			'popup_menu_google_fonts',
			'popup_menu_google_fonts_2nd',
			'tabs_nav_font_family',
			'expandable_label_font_family',
			'footer_title_font_family',
			'side_area_title_font_family',
			'pricing_table_button_font_family',
			'sidebar_title_font_family',
			'counters_font_family',
			'counters_text_font_family',
			'testimonaials_font_family',
			'testimonials_author_font_family',
			'blockquote_font_font_family',
			'slide-title-font-family',
			'slide-subtitle-font-family',
			'slide-text-font-family',
			'footer_bottom_font_family'
		);

		if(qode_is_woocommerce_installed()) {
			$available_font_options[] = 'woo_product_title_font_family';
			$available_font_options[] = 'woo_product_price_font_family';
			$available_font_options[] = 'woo_out_of_stock_font_family';
			$available_font_options[] = 'woo_product_results_text_font_family';
			$available_font_options[] = 'woo_prod_widget_title_font_family';
			$available_font_options[] = 'woo_prod_widget_price_font_family';
			$available_font_options[] = 'woo_prod_single_title_font_family';
			$available_font_options[] = 'woo_prod_single_price_font_family';
			$available_font_options[] = 'woo_prod_single_accordion_title_font_family';
		}

		//define available font options array
		$fonts_array = array();
		foreach($available_font_options as $font_option) {
			//is font set and not set to default and not empty?
			if(isset($qode_options[$font_option]) && $qode_options[$font_option] !== '-1' && $qode_options[$font_option] !== '') {
				$font_option_string = $qode_options[$font_option].':'.$font_weight_str;
				if(!in_array($font_option_string, $fonts_array)) {
					$fonts_array[] = $font_option_string;
				}

			}
		}

		//add google fonts set in slider
		$args = array( 'post_type' => 'slides', 'posts_per_page' => -1);
		$loop = new WP_Query( $args );

		//for each slide defined
		while ( $loop->have_posts() ) : $loop->the_post();

			//is font family for title option chosen?
			if(get_post_meta(get_the_ID(), "qode_slide-title-font-family", true) != "") {
				$slide_title_font_string = get_post_meta(get_the_ID(), "qode_slide-title-font-family", true) . ":".$font_weight_str;
				if(!in_array($slide_title_font_string, $fonts_array)) {
					//include that font
					array_push($fonts_array, $slide_title_font_string);
				}
			}

			//is font family defined for slide's text?
			if(get_post_meta(get_the_ID(), "qode_slide-text-font-family", true) != "") {
				$slide_text_font_string = get_post_meta(get_the_ID(), "qode_slide-text-font-family", true) . ":".$font_weight_str;
				if(!in_array($slide_text_font_string, $fonts_array)) {
					//include that font
					array_push($fonts_array, $slide_text_font_string);
				}
			}

			//is font family defined for slide's subtitle?
			if(get_post_meta(get_the_ID(), "qode_slide-subtitle-font-family", true) != "") {
				$slide_subtitle_font_string = get_post_meta(get_the_ID(), "qode_slide-subtitle-font-family", true) .":".$font_weight_str;
				if(!in_array($slide_subtitle_font_string, $fonts_array)) {
					//include that font
					array_push($fonts_array, $slide_subtitle_font_string);
				}

			}
		endwhile;

		wp_reset_query();

		$fonts_array = array_diff($fonts_array, array("-1:".$font_weight_str));
		$google_fonts_string = implode( '|', $fonts_array);

		$default_font_string = 'Source+Sans+Pro:'.$font_weight_str.'|Open+Sans:'.$font_weight_str;

		//is google font option checked anywhere in theme?
		if(count($fonts_array) > 0) {
			//include all checked fonts
			printf("<link href='//fonts.googleapis.com/css?family=".$default_font_string."|%s&subset=latin,latin-ext' rel='stylesheet' type='text/css'>\r\n", str_replace(' ', '+', $google_fonts_string));
		} else {
			//include default google font that theme is using
			printf("<link href='//fonts.googleapis.com/css?family=".$default_font_string."' rel='stylesheet' type='text/css'>\r\n");
		}

        if(isset($qode_toolbar)){
            printf("<link href='http://fonts.googleapis.com/css?family=Raleway:400,600' rel='stylesheet' type='text/css'>\r\n");
        }
    }

	add_action('wp_enqueue_scripts', 'qode_google_fonts_styles');
}


if (!function_exists('qode_scripts')) {
	/**
	 * Function that includes all necessary scripts
	 */
	function qode_scripts() {
		global $qode_options;
		global $is_chrome;
		global $is_opera;
		global $qode_toolbar;
        global $qode_landing;
		global $wp_scripts;

		//init variables
		$smooth_scroll 	= true;
		$has_ajax 		= false;
		$qode_animation = "";

		//is smooth scroll option turned on?
		if(isset($qode_options['smooth_scroll']) && $qode_options['smooth_scroll'] == "no"){
			$smooth_scroll = false;
		}

		//init theme core scripts
		wp_enqueue_script("jquery");
		wp_enqueue_script("plugins", QODE_ROOT."/js/plugins.js",array(),false,true);
		wp_enqueue_script("carouFredSel", QODE_ROOT."/js/jquery.carouFredSel-6.2.1.js",array(),false,true);
		wp_enqueue_script("lemmonSlider", QODE_ROOT."/js/lemmon-slider.js",array(),false,true);
		wp_enqueue_script("mousewheel", QODE_ROOT."/js/jquery.mousewheel.min.js",array(),false,true);
		wp_enqueue_script("touchSwipe", QODE_ROOT."/js/jquery.touchSwipe.min.js",array(),false,true);
		wp_enqueue_script("isotope", QODE_ROOT."/js/jquery.isotope.min.js",array(),false,true);
		wp_enqueue_script("default_dynamic", QODE_ROOT."/js/default_dynamic.php", array(), false, true);
		wp_enqueue_script("default", QODE_ROOT."/js/default.min.js", array(), false, true);//revert to min
		wp_enqueue_script("custom_js", QODE_ROOT."/js/custom_js.php", array(), false, true);

		//is Chome or Opera and is smooth scrolling turned on?
		if(($is_chrome || $is_opera) && $smooth_scroll){
			//include smooth scroll script
			wp_enqueue_script("smoothScroll", QODE_ROOT."/js/SmoothScroll.js",array(),false,true);
		}

		//is google map enabled on contact page template?
		if($qode_options['enable_google_map'] == "yes") {
			//include google map api script
			wp_enqueue_script("google_map_api", "https://maps.googleapis.com/maps/api/js?sensor=false", array(), false, true);
		}

		//include comment reply script
		$wp_scripts->add_data('comment-reply', 'group', 1 );
		if (is_singular()) {
			wp_enqueue_script( "comment-reply");
		}

		//is ajax set in session?
		if (isset($_SESSION['qode_theme19_page_transitions'])) {
			$qode_animation = $_SESSION['qode_theme19_page_transitions'];
		}
		if (($qode_options['page_transitions'] != "0") && (empty($qode_animation) || ($qode_animation != "no"))) {
			$has_ajax = true;
		} elseif (!empty($qode_animation) && ($qode_animation != "no"))
			$has_ajax = true;

		if ($has_ajax) {
			wp_enqueue_script("ajax", QODE_ROOT."/js/ajax.min.js",array(),false,true);
		}

		//include Visual Composer script
		wp_enqueue_script( 'wpb_composer_front_js' );

		//is google recaptcha turned on?
		if($qode_options['use_recaptcha'] == "yes") {
			//include recaptcha for ajax
			wp_enqueue_script("recaptcha_ajax", "http://www.google.com/recaptcha/api/js/recaptcha_ajax.js", array(), false, true);
		}

		//is toolbar enabled?
		if(isset($qode_toolbar)) {
			//include toolbar specific script
			wp_enqueue_script("toolbar", QODE_ROOT."/js/toolbar.js",array(),false,true);
		}

        //is landing enabled?
        if(isset($qode_landing)) {
            wp_enqueue_script("qode_landing_fancybox", get_home_url() . "/demo-files/landing/js/jquery.fancybox.js",array(),false,true);
            wp_enqueue_script("qode_landing", get_home_url() . "/demo-files/landing/js/landing_default.js",array(),false,true);
        }

	}

	add_action('wp_enqueue_scripts', 'qode_scripts');
}

if(!function_exists('qode_browser_specific_scripts')) {
	/**
	 * Function that loads browser specific scripts
	 */
	function qode_browser_specific_scripts() {
		global $is_IE;

		//is ie?
		if ($is_IE) {
			wp_enqueue_script("html5", QODE_ROOT."/js/html5.js",array(),false,false);
		}
	}

	add_action('wp_enqueue_scripts', 'qode_browser_specific_scripts');
}

if(!function_exists('qode_woocommerce_assets')) {
	/**
	 * Function that includes all necessary scripts for WooCommerce if installed
	 */
	function qode_woocommerce_assets() {
		global $qode_options;

		//is woocommerce installed?
		if(qode_is_woocommerce_installed()) {
			//get woocommerce specific scripts
			wp_enqueue_script("woocommerce-qode", QODE_ROOT."/js/woocommerce.js",array(),false,true);
			wp_enqueue_script("select2", QODE_ROOT."/js/select2.min.js",array(),false,true);

			//include theme's woocommerce styles
			wp_enqueue_style("woocommerce", QODE_ROOT . "/css/woocommerce.min.css");

			//is responsive option turned on?
			if($qode_options['responsiveness'] == 'yes') {
				//include theme's woocommerce responsive styles
				wp_enqueue_style("woocommerce_responsive", QODE_ROOT . "/css/woocommerce_responsive.min.css");
			}
		}
	}

	add_action('wp_enqueue_scripts', 'qode_woocommerce_assets');
}

if (!function_exists('qode_admin_jquery')) {
	/**
	 * Function that includes scripts for admin
	 */
	function qode_admin_jquery() {
		wp_enqueue_script('jquery');
		wp_enqueue_style('qode-admin-style', QODE_ROOT.'/css/admin/admin-style.css', false, '1.0', 'screen');
		wp_enqueue_style('colorstyle', QODE_ROOT.'/css/admin/colorpicker.css', false, '1.0', 'screen');
		wp_register_script('colorpickerss', QODE_ROOT.'/js/admin/colorpicker.js', array('jquery'), '1.0.0', false );
		wp_enqueue_script('colorpickerss');
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_media();
		wp_enqueue_script('thickbox');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-accordion');
		wp_register_script('default', QODE_ROOT.'/js/admin/default.js', array('jquery'), '1.0.0', false );
		wp_enqueue_script('default');
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');
	}

	add_action('admin_enqueue_scripts', 'qode_admin_jquery');
}

//defined content width variable
if (!isset( $content_width )) $content_width = 1060;


if (!function_exists('qode_register_menus')) {
	/**
	 * Function that registers menu locations
	 */
	function qode_register_menus() {
        global $qode_options;

        if((isset($qode_options['header_bottom_appearance']) && $qode_options['header_bottom_appearance'] != "stick_with_left_right_menu") || (isset($qode_options['vertical_area']) && $qode_options['vertical_area'] == "yes")){
            //header and left menu location
            register_nav_menus(
                array('top-navigation' => __( 'Top Navigation', 'qode')
                )
            );
        }

		//popup menu location
		register_nav_menus(
			array('popup-navigation' => __( 'Fullscreen Navigation', 'qode')
			)
		);

        if((isset($qode_options['header_bottom_appearance']) && $qode_options['header_bottom_appearance'] == "stick_with_left_right_menu") && (isset($qode_options['vertical_area']) && $qode_options['vertical_area'] == "no")){
            //header left menu location
            register_nav_menus(
                array('left-top-navigation' => __( 'Left Top Navigation', 'qode')
                )
            );

            //header right menu location
            register_nav_menus(
                array('right-top-navigation' => __( 'Right Top Navigation', 'qode')
                )
            );
        }
	}

	add_action( 'after_setup_theme', 'qode_register_menus' );
}

if ( function_exists( 'add_theme_support' ) ) {
	//add support for feed links
	add_theme_support( 'automatic-feed-links' );

	//add support for post formats
	add_theme_support('post-formats', array('gallery', 'link', 'quote', 'video', 'audio'));

	//add theme support for post thumbnails
	add_theme_support( 'post-thumbnails' );

	//define thumbnail sizes
	add_image_size( 'portfolio-square', 570, 570, true );
	add_image_size( 'portfolio-landscape', 800, 600, true );
	add_image_size( 'portfolio-portrait', 600, 800, true );
	add_image_size( 'menu-featured-post', 345, 198, true );
	add_image_size( 'qode-carousel_slider', 400, 260, true );
	add_image_size( 'portfolio_slider', 480, 320, true );
	add_image_size( 'portfolio_masonry_regular', 500, 500, true );
	add_image_size( 'portfolio_masonry_wide', 1000, 500, true );
	add_image_size( 'portfolio_masonry_tall', 500, 1000, true );
	add_image_size( 'portfolio_masonry_large', 1000, 1000, true );
    add_image_size( 'portfolio_masonry_with_space', 700);
	add_image_size( 'latest_post_boxes', 528, 420, true );
	add_image_size( 'latest_post_small_image', 125, 102, true );
}

if (!function_exists('ajax_classes')) {
	/**
	 * Function that adds classes on body for ajax transitions
	 */
	function ajax_classes($classes) {
		global $qode_options;

		//init variables
		$qode_animation="";

		//is ajax set in session
		if (isset($_SESSION['qode_animation'])) {
			$qode_animation = $_SESSION['qode_animation'];
		}

		//is ajax animation turned off in options or in session?
		if(($qode_options['page_transitions'] === "0") && ($qode_animation == "no")) {
			$classes[] = '';
		}

		//is up down animation type set?
		elseif($qode_options['page_transitions'] === "1" && (empty($qode_animation) || ($qode_animation != "no"))) {
			$classes[] = 'ajax_updown';
			$classes[] = 'page_not_loaded';
		}

		//is fade animation type set?
		elseif($qode_options['page_transitions'] === "2" && (empty($qode_animation) || ($qode_animation != "no"))) {
			$classes[] = 'ajax_fade';
			$classes[] = 'page_not_loaded';
		}

		//is up down fade animation type set?
		elseif($qode_options['page_transitions'] === "3" && (empty($qode_animation) || ($qode_animation != "no"))) {
			$classes[] = 'ajax_updown_fade';
			$classes[] = 'page_not_loaded';
		}

		//is left / right animation type set?
		elseif($qode_options['page_transitions'] === "4" && (empty($qode_animation) || ($qode_animation != "no"))) {
			$classes[] = 'ajax_leftright';
			$classes[] = 'page_not_loaded';
		}

		//is animation set only in session?
		elseif(!empty($qode_animation) && $qode_animation != "no") {
			$classes[] = 'page_not_loaded';
		}

		//animation is turned off both in options nad in session
		else {
			$classes[] ="";
		}

		return $classes;
	}

	add_filter('body_class','ajax_classes');
}

if (!function_exists('boxed_class')) {
	/**
	 * Function that adds classes on body for boxed layout
	 */
	function boxed_class($classes) {
		global $qode_options;

		//is boxed layout turned on?
		if(isset($qode_options['boxed']) && $qode_options['boxed'] == "yes") {
			$classes[] = 'boxed';
		} else {
			$classes[] ="";
		}

		return $classes;
	}

	add_filter('body_class','boxed_class');
}

if (!function_exists('vertical_menu_class')) {
	/**
	 * Function that adds classes on body element for left menu area
	 */
	function vertical_menu_class($classes) {
		global $qode_options;
		global $wp_query;

		//is left menu area turned on?
		if(isset($qode_options['vertical_area']) && $qode_options['vertical_area'] =='yes') {
			$classes[] = 'vertical_menu_enabled';
		}

		//get current page id
		$id = $wp_query->get_queried_object_id();

		if(isset($qode_options['vertical_area_transparency']) && $qode_options['vertical_area_transparency'] =='yes' && get_post_meta($id, "qode_page_vertical_area_transparency", true) != "no"){
			$classes[] = 'vertical_menu_transparency';
			$classes[] = 'vertical_menu_transparency_on';
		}else if(get_post_meta($id, "qode_page_vertical_area_transparency", true) == "yes"){
			$classes[] = 'vertical_menu_transparency';
			$classes[] = 'vertical_menu_transparency_on';
		}

		if(isset($qode_options['vertical_area_background_image']) && $qode_options['vertical_area_background_image'] !== '') {
			$classes[] = 'vertical_menu_with_bg_image';
		}
		return $classes;
	}

	add_filter('body_class','vertical_menu_class');
}

if (!function_exists('elements_animation_on_touch_class')) {
	/**
	 * Function that adds classes on body when touch is disabled on touch devices
	 * @param $classes array classes array
	 * @return array array with added classes
	 */
	function elements_animation_on_touch_class($classes) {
		global $qode_options;

		//check if current client is on mobile
		$isMobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'.
			'|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
			'|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT'] );

		//are animations turned off on touch and client is on mobile?
		if(isset($qode_options['elements_animation_on_touch']) && $qode_options['elements_animation_on_touch'] == "no" && $isMobile == true) {
			$classes[] = 'no_animation_on_touch';
		} else {
			$classes[] ="";
		}

		return $classes;
	}

	add_filter('body_class','elements_animation_on_touch_class');
}

if(!function_exists('qode_content_min_height_class')) {
	function qode_content_min_height_class($classes) {
		global $qode_options;

		if(isset($qode_options['footer_on_bottom']) && $qode_options['footer_on_bottom'] == 'no') {
			$classes[] = 'content_with_no_min_height';
		}

		return $classes;
	}

	add_filter('body_class', 'qode_content_min_height_class');
}

if (!function_exists('qode_excerpt_more')) {
	/**
	 * Function that adds three dotes on the end excerpt
	 * @param $more
	 * @return string
	 */
	function qode_excerpt_more( $more ) {
		return '...';
	}

	add_filter('excerpt_more', 'qode_excerpt_more');
}

if (!function_exists('qode_excerpt_length')) {
	/**
	 * Function that changes excerpt length based on theme options
	 * @param $length int original value
	 * @return int changed value
	 */
	function qode_excerpt_length( $length ) {
		global $qode_options;

		if($qode_options['number_of_chars']){
			return $qode_options['number_of_chars'];
		} else {
			return 45;
		}
	}

	add_filter( 'excerpt_length', 'qode_excerpt_length', 999 );
}

if (!function_exists('the_excerpt_max_charlength')) {
	/**
	 * Function that sets character length for social share shortcode
	 * @param $charlength string original text
	 * @return string shortened text
	 */
	function the_excerpt_max_charlength($charlength) {
		global $qode_options;

		if(isset($qode_options['twitter_via']) && !empty($qode_options['twitter_via'])) {
			$via = " via " . $qode_options['twitter_via'] . " ";
		} else {
			$via = 	"";
		}

		$excerpt = get_the_excerpt();
		$charlength = 140 - (mb_strlen($via) + $charlength);

		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex = mb_substr( $excerpt, 0, $charlength);
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				return mb_substr( $subex, 0, $excut );
			} else {
				return $subex;
			}
		} else {
			return $excerpt;
		}
	}
}

if(!function_exists('qode_excerpt')) {
	/**
	 * Function that cuts post excerpt to the number of word based on previosly set global
	 * variable $word_count, which is defined in qode_set_blog_word_count function
	 */
	function qode_excerpt() {
		global $qode_options, $word_count, $post;

		if($word_count != '0') {
			$word_count = isset($word_count) && $word_count !== "" ? $word_count : $qode_options['number_of_chars'];
			$post_excerpt = $post->post_excerpt != "" ? $post->post_excerpt : strip_tags($post->post_content);
			$clean_excerpt = strlen($post_excerpt) && strpos($post_excerpt, '...') ? strstr($post_excerpt, '...', true) : $post_excerpt;

			if($clean_excerpt !== '') {
				$excerpt_word_array = explode (' ', $clean_excerpt);
				$excerpt_word_array = array_slice ($excerpt_word_array, 0, $word_count);
				$excert_postfix		= apply_filters('qode_excerpt_postfix', '...');
				$excerpt 			= implode (' ', $excerpt_word_array).$excert_postfix;

				if($excerpt !== '') {
					echo '<p class="post_excerpt">'.$excerpt.'</p>';
				}
			}
		}
	}
}

if(!function_exists('qode_set_blog_word_count')) {
	/**
	 * Function that sets global blog word count variable used by qode_excerpt function
	 */
	function qode_set_blog_word_count($word_count_param) {
		global $word_count;

		$word_count = $word_count_param;
	}
}

if (!function_exists('comparePortfolioImages')) {
	/**
	 * Function that compares two portfolio image for sorting
	 * @param $a int first image
	 * @param $b int second image
	 * @return int result of comparison
	 */
	function comparePortfolioImages($a, $b){
		if (isset($a['portfolioimgordernumber']) && isset($b['portfolioimgordernumber'])) {
			if ($a['portfolioimgordernumber'] == $b['portfolioimgordernumber']) {
				return 0;
			}
			return ($a['portfolioimgordernumber'] < $b['portfolioimgordernumber']) ? -1 : 1;
		}

		return 0;
	}
}

if (!function_exists('comparePortfolioOptions')){
	/**
	 * Function that compares two portfolio options for sorting
	 * @param $a int first option
	 * @param $b int second option
	 * @return int result of comparison
	 */
	function comparePortfolioOptions($a, $b){
		if (isset($a['optionlabelordernumber']) && isset($b['optionlabelordernumber'])) {
			if ($a['optionlabelordernumber'] == $b['optionlabelordernumber']) {
				return 0;
			}
			return ($a['optionlabelordernumber'] < $b['optionlabelordernumber']) ? -1 : 1;
		}

		return 0;
	}
}

if (!function_exists('qode_gallery_upload_get_images')){
function qode_gallery_upload_get_images(){
	$ids=$_POST['ids'];
	$ids=explode(",",$ids);
	foreach($ids as $id):
		$image = wp_get_attachment_image_src($id,'thumbnail', true);
		echo '<li class="qode-gallery-image-holder"><img src="'.$image[0].'"/></li>';
	endforeach;
	exit;  
}
}

add_action( 'wp_ajax_qode_gallery_upload_get_images', 'qode_gallery_upload_get_images');

if (!function_exists('qode_hex2rgb')) {
	/**
	 * Function that transforms hex color to rgb color
	 * @param $hex string original hex string
	 * @return array array containing three elements (r, g, b)
	 */
	function qode_hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
	}
}

if(!function_exists('qode_addslashes')) {
	/**
	 * Function that checks if magic quotes are turned on (for older versions of php) and returns escaped string
	 * @param $str string string to be escaped
	 * @return string escaped string
	 */
	function qode_addslashes($str) {
		$str = addslashes($str);

		//return escaped string
		return $str;
	}
}

if(!function_exists('qode_is_archive_page')) {
	/**
	 * Function that checks if current page archive page, search, 404 or default home blog page
	 * @return bool
	 *
	 * @see is_archive()
	 * @see is_search()
	 * @see is_404()
	 * @see is_front_page()
	 * @see is_home()
	 */
	function qode_is_archive_page() {
		return is_archive() || is_search() || is_404() || (is_front_page() && is_home());
	}
}

if(!function_exists('qode_is_woocommerce_installed')) {
	/**
	 * Function that checks if woocommerce is installed
	 * @return bool
	 */
	function qode_is_woocommerce_installed() {
		return function_exists('is_woocommerce');
	}
}

if(!function_exists('qode_is_woocommerce_page')) {
	/**
	 * Function that checks if current page is woocommerce shop, product or product taxonomy
	 * @return bool
	 *
	 * @see is_woocommerce()
	 */
	function qode_is_woocommerce_page() {
		return function_exists('is_woocommerce') && is_woocommerce();
	}
}

if(!function_exists('qode_is_woocommerce_shop')) {
	/**
	 * Function that checks if current page is shop or product page
	 * @return bool
	 *
	 * @see is_shop()
	 */
	function qode_is_woocommerce_shop() {
		return function_exists('is_shop') && is_shop();
	}
}

if(!function_exists('qode_get_woo_shop_page_id')) {
	/**
	 * Function that returns shop page id that is set in WooCommerce settings page
	 * @return int id of shop page
	 */
	function qode_get_woo_shop_page_id() {
		if(qode_is_woocommerce_installed()) {
			return get_option('woocommerce_shop_page_id');
		}
	}
}

if(!function_exists('qode_get_page_template_name')) {
	/**
	 * Returns current template file name without extension
	 * @return string name of current template file
	 */
	function qode_get_page_template_name() {
		$file_name = '';
		$file_name_without_ext = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename(get_page_template()));

		if($file_name_without_ext !== '') {
			$file_name = $file_name_without_ext;
		}

		return $file_name;
	}
}

if(!function_exists('qode_is_contact_page_template')) {
	/**
	 * Checks if current template page is contact page.
	 * @param string current page. Optional parameter. If not passed qode_get_page_template_name() function will be used
	 * @return bool
	 *
	 * @see qode_get_page_template_name()
	 */
	function qode_is_contact_page_template($current_page = '') {
		if($current_page == '' && !is_archive()) {
			$current_page = qode_get_page_template_name();
		}

		return in_array($current_page, array('contact-page'));
	}
}

if(!function_exists('qode_has_shortcode')) {
	/**
	 * Function that checks whether shortcode exists on current page / post
	 * @param string shortcode to find
	 * @param string content to check. If isn't passed current post content will be used
	 * @return bool whether content has shortcode or not
	 */
	function qode_has_shortcode($shortcode, $content = '') {
		$has_shortcode = false;

		if ($shortcode) {
			//if content variable isn't past
			if($content == '') {
				//take content from current post
				$current_post = get_post(get_the_ID());
				$content = $current_post->post_content;
			}

			//does content has shortcode added?
			if (stripos($content, '[' . $shortcode) !== false) {
				$has_shortcode = true;
			}
		}

		return $has_shortcode;
	}
}

if(!function_exists('qode_rgba_color')) {
	/**
	 * Function that generates rgba part of css color property
	 * @param $color string hex color
	 * @param $transparency float transparency value between 0 and 1
	 * @return string generated rgba string
	 */
	function qode_rgba_color($color, $transparency) {
		if($color !== '' && $transparency !== '') {
			$rgba_color = '';

			$rgb_color_array = qode_hex2rgb($color);
			$rgba_color .= 'rgba('.implode(', ', $rgb_color_array).', '.$transparency.')';

			return $rgba_color;
		}
	}
}

if (!function_exists('theme_version_class')) {
	/**
	 * Function that adds classes on body for version of theme
	 */
	function theme_version_class($classes) {
		$current_theme = wp_get_theme();

		//is child theme activated?
		if($current_theme->parent()) {
			//add child theme version
			$classes[] = 'select-child-theme-ver-'.$current_theme->get('Version');

			//get parent theme
			$current_theme = $current_theme->parent();
		}

		if($current_theme->exists() && $current_theme->get('Version') != "") {
			$classes[] = 'select-theme-ver-'.$current_theme->get('Version');
		}

		return $classes;
	}

	add_filter('body_class','theme_version_class');
}

if(!function_exists('qode_get_title_text')) {
	/**
	 * Function that returns current page title text. Defines qode_title_text filter
	 * @return string current page title text
	 *
	 * @see is_tag()
	 * @see is_date()
	 * @see is_author()
	 * @see is_category()
	 * @see is_home()
	 * @see is_search()
	 * @see is_404()
	 * @see get_queried_object_id()
	 * @see qode_is_woocommerce_installed()
	 *
	 */
	function qode_get_title_text() {
		global $qode_options;

		$id 	= get_queried_object_id();
		$title 	= '';

		//is current page tag archive?
		if (is_tag()) {
			//get title of current tag
			$title = single_term_title("", false)." Tag";
		}

		//is current page date archive?
		elseif (is_date()) {
			//get current date archive format
			$title = get_the_time('F Y');
		}

		//is current page author archive?
		elseif (is_author()) {
			//get current author name
			$title = __('Author:', 'qode') . " " . get_the_author();
		}

		//us current page category archive
		elseif (is_category()) {
			//get current page category title
			$title = single_cat_title('', false);
		}

		//is current page blog post page and front page? Latest posts option is set in Settings -> Reading
		elseif (is_home() && is_front_page()) {
			//get site name from options
			$title = get_option('blogname');
		}

		//is current page search page?
		elseif (is_search()) {
			//get title for search page
			$title = __('Search', 'qode');
		}

		//is current page 404?
		elseif (is_404()) {
			//is 404 title text set in theme options?
			if($qode_options['404_title'] != "") {
				//get it from options
				$title = $qode_options['404_title'];
			} else {
				//get default 404 page title
				$title = __('404 - Page not found', 'qode');
			}
		}

		//is WooCommerce installed and is shop or single product page?
		elseif(qode_is_woocommerce_installed() && (is_shop() || is_singular('product'))) {
			//get shop page id from options table
			$shop_id = get_option('woocommerce_shop_page_id');

			//get shop page and get it's title if set
			$shop = get_post($shop_id);
			if(isset($shop->post_title) && $shop->post_title !== '') {
				$title = $shop->post_title;
			}

		}

		//is WooCommerce installed and is current page product archive page?
		elseif(qode_is_woocommerce_installed() && (is_product_category() || is_product_tag())) {
			global $wp_query;

			//get current taxonomy and it's name and assign to title
			$tax 			= $wp_query->get_queried_object();
			$category_title = $tax->name;
			$title 			= $category_title;
		}

		//is current page some archive page?
		elseif (is_archive()) {
			$title = __('Archive','qode');
		}

		//current page is regular page
		else {
			$title = get_the_title($id);
		}

		$title = apply_filters('qode_title_text', $title);

		return $title;
	}
}

if(!function_exists('qode_title_text')) {
	/**
	 * Function that echoes title text.
	 *
	 * @see qode_get_title_text()
	 */
	function qode_title_text() {
		echo qode_get_title_text();
	}
}

if(!function_exists('qode_wp_title')) {
	/**
	 * Function that sets page's title. Hooks to wp_title filter
	 * @param $title string current page title
	 * @param $sep string title separator
	 * @return string changed title text if SEO plugins aren't installed
	 *
	 * @version 0.3
	 */
	function qode_wp_title($title, $sep) {
		global $qode_options;

		//is SEO plugin installed?
		if(qode_seo_plugin_installed()) {
			//don't do anything, seo plugin will take care of it
		} else {
			//get current post id
			$id = get_queried_object_id();
			$sep = ' | ';
			$title_prefix = get_bloginfo('name');
			$title_suffix = '';

			//is WooCommerce installed and is current page shop page?
			if(qode_is_woocommerce_installed() && qode_is_woocommerce_shop()) {
				//get shop page id
				$id = qode_get_woo_shop_page_id();
			}

			//set unchanged title variable so we can use it later
			$unchanged_title = $title;

			//is qode seo enabled?
			if(isset($qode_options['disable_qode_seo']) && $qode_options['disable_qode_seo'] !== 'yes') {
				//get current post seo title
				$seo_title = get_post_meta($id, "qode_seo_title", true);

				//is current post seo title set?
				if($seo_title !== '') {
					$title_suffix = $seo_title;
				}
			}

			//title suffix is empty, which means that it wasn't set by qode seo
			if(empty($title_suffix)) {
				//if current page is front page append site description, else take original title string
				$title_suffix = is_front_page() ? get_bloginfo('description') : $unchanged_title;
			}

			//concatenate title string
			$title  = $title_prefix.$sep.$title_suffix;

			//return generated title string
			return $title;
		}
	}

	add_filter('wp_title', 'qode_wp_title', 10, 2);
}

if(!function_exists('qode_ajax_meta')) {
	/**
	 * Function that echoes meta data for ajax
	 *
	 * @since 4.3
	 * @version 0.2
	 */
	function qode_ajax_meta() {
		global $qode_options;
		global $wp_query;

		$seo_description = get_post_meta($wp_query->get_queried_object_id(), "qode_seo_description", true);
		$seo_keywords = get_post_meta($wp_query->get_queried_object_id(), "qode_seo_keywords", true);
		?>

		<div class="seo_title"><?php wp_title(''); ?></div>

		<?php if($seo_description !== ''){ ?>
			<div class="seo_description"><?php echo $seo_description; ?></div>
		<?php } else if($qode_options['meta_description']){?>
			<div class="seo_description"><?php echo $qode_options['meta_description']; ?></div>
		<?php } ?>
		<?php if($seo_keywords !== ''){ ?>
			<div class="seo_keywords"><?php echo $seo_keywords; ?></div>
		<?php }else if($qode_options['meta_keywords']){?>
			<div class="seo_keywords"><?php echo $qode_options['meta_keywords']; ?></div>
		<?php }
	}

	add_action('qode_ajax_meta', 'qode_ajax_meta');
}

if(!function_exists('qode_header_meta')) {
	/**
	 * Function that echoes meta data if our seo is enabled
	 */
	function qode_header_meta() {
		global $qode_options;
		global $wp_query;

		if(isset($qode_options['disable_qode_seo']) && $qode_options['disable_qode_seo'] == 'no') {
			$seo_description = get_post_meta($wp_query->get_queried_object_id(), "qode_seo_description", true);
			$seo_keywords = get_post_meta($wp_query->get_queried_object_id(), "qode_seo_keywords", true);
			?>

			<?php if($seo_description) { ?>
				<meta name="description" content="<?php echo $seo_description; ?>">
			<?php } else if($qode_options['meta_description']){ ?>
				<meta name="description" content="<?php echo $qode_options['meta_description'] ?>">
			<?php } ?>

			<?php if($seo_keywords) { ?>
				<meta name="keywords" content="<?php echo $seo_keywords; ?>">
			<?php } else if($qode_options['meta_keywords']){ ?>
				<meta name="keywords" content="<?php echo $qode_options['meta_keywords'] ?>">
			<?php }
		}

	}

	add_action('qode_header_meta', 'qode_header_meta');
}

if(!function_exists('qode_get_attachment_meta')) {
	/**
	 * Function that returns attachment meta data from attachment id
	 * @param $attachment_id
	 * @param array $keys sub array of attachment meta
	 * @return array|mixed
	 */
	function qode_get_attachment_meta($attachment_id, $keys = array()) {
		$meta_data = array();

		//is attachment id set?
		if(!empty($attachment_id)) {
			//get all post meta for given attachment id
			$meta_data = get_post_meta($attachment_id, '_wp_attachment_metadata', true);

			//is subarray of meta array keys set?
			if(is_array($keys) && count($keys)) {
				$sub_array = array();

				//for each defined key
				foreach($keys as $key) {
					//check if that key exists in all meta array
					if(array_key_exists($key, $meta_data)) {
						//assign key from meta array for current key to meta subarray
						$sub_array[$key] = $meta_data[$key];
					}
				}

				//we want meta array to be subarray because that is what used whants to get
				$meta_data = $sub_array;
			}
		}

		//return meta array
		return $meta_data;
	}
}

if(!function_exists('qode_get_attachment_id_from_url')) {
	/**
	 * Function that retrieves attachment id for passed attachment url
	 * @param $attachment_url
	 * @return null|string
	 */
	function qode_get_attachment_id_from_url($attachment_url) {
		global $wpdb;
		$attachment_id = '';

		//is attachment url set?
		if($attachment_url !== '') {
			//prepare query
			$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$attachment_url'";

			//get attachment id
			$attachment_id = $wpdb->get_var($query);
		}

		//return it
		return $attachment_id;
	}
}

if(!function_exists('qode_get_attachment_meta_from_url')) {
	/**
	 * Function that returns meta array for give attachment url
	 * @param $attachment_url
	 * @param array $keys sub array of attachment meta
	 * @return array|mixed
	 *
	 * @see qode_get_attachment_id_from_url()
	 * @see qode_get_attachment_meta()
	 *
	 * @version 0.1
	 */
	function qode_get_attachment_meta_from_url($attachment_url, $keys = array()) {
		$attachment_meta = array();

		//get attachment id for attachment url
		$attachment_id 	= qode_get_attachment_id_from_url($attachment_url);

		//is attachment id set?
		if(!empty($attachment_id)) {
			//get post meta
			$attachment_meta = qode_get_attachment_meta($attachment_id, $keys);
		}

		//return post meta
		return $attachment_meta;
	}
}

if(!function_exists('qode_get_image_dimensions')) {
	/**
	 * Function that returns image sizes array. First looks in post_meta table if attachment exists in the database,
	 * if it doesn't than it uses getimagesize PHP function to get image sizes
	 * @param $url string url of the image
	 * @return array array of image sizes that containes height and width
	 *
	 * @see qode_get_attachment_meta_from_url()
	 * @uses getimagesize
	 *
	 * @version 0.1
	 */
	function qode_get_image_dimensions($url) {
		$image_sizes = array();

		//is url passed?
		if($url !== '') {
			//get image sizes from posts meta if attachment exists
			$image_sizes = qode_get_attachment_meta_from_url($url, array('width', 'height'));

			//image does not exists in post table, we have to use PHP way of getting image size
			if(!count($image_sizes)) {
				//can we open file by url?
				if(ini_get('allow_url_fopen') == 1) {
					list($width, $height, $type, $attr) = getimagesize($url);
				} else {
					//we can't open file directly, have to locate it with relative path.
					$image_obj = parse_url($url);
					$image_relative_path = $_SERVER['DOCUMENT_ROOT'].$image_obj['path'];

					if(file_exists($image_relative_path)) {
						list($width, $height, $type, $attr) = getimagesize($image_relative_path);
					}
				}

				//did we get width and height from some of above methods?
				if(isset($width) && isset($height)) {
					//set them to our image sizes array
					$image_sizes = array(
						'width' => $width,
						'height' => $height
					);
				}
			}
		}

		return $image_sizes;
	}
}

if(!function_exists('qode_set_logo_sizes')) {
	/**
	 * Function that sets logo image dimensions to global qode options array so it can be used in the theme
	 */
	function qode_set_logo_sizes() {
		global $qode_options;

		//get logo image size
		$logo_image_sizes = qode_get_image_dimensions($qode_options['logo_image']);
		$qode_options['logo_width'] = 280;
		$qode_options['logo_height'] = 130;

		//is image width and height set?
		if(isset($logo_image_sizes['width']) && isset($logo_image_sizes['height'])) {
			//set those variables in global array
			$qode_options['logo_width'] = $logo_image_sizes['width'];
			$qode_options['logo_height'] = $logo_image_sizes['height'];
		}
	}

	//not used at the moment, so there is no need for action
	//add_action('wp', 'qode_set_logo_sizes', 0);
}

if(!function_exists('qode_seo_plugin_installed')) {
	/**
	 * Function that checks if popular seo plugins are installed
	 * @return bool
	 */
	function qode_seo_plugin_installed() {
		//is YOAST installed?
		if(defined('WPSEO_VERSION')) {
			return true;
		}

		return false;
	}
}

if(!function_exists('qode_remove_yoast_json_on_ajax')) {
	/**
	 * Function that removes yoast json ld script
	 * that stops page transition to work on home page
	 * Hooks to disable_wpseo_json_ld_search in order to disable json ld script
	 * @return bool
	 *
	 * @version 0.1
	 */
	function qode_remove_yoast_json_on_ajax() {
		//is yoast installed and is current request made through ajax?
		if(qode_is_ajax()) {
			//disable json ld script
			return true;
		}

		return false;
	}

	//is yoast installed and it's version is greater or equal of 1.6?
	if(defined('WPSEO_VERSION') && version_compare(WPSEO_VERSION, '1.6') >= 0) {
		add_filter('disable_wpseo_json_ld_search', 'qode_remove_yoast_json_on_ajax');
	}
}

if(!function_exists('qode_is_ajax')) {
	/**
	 * Function that checks if current request is ajax request
	 * @return bool whether it's ajax request or not
	 *
	 * @version 0.1
	 */
	function qode_is_ajax() {
		return !empty( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ]) && strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ]) == 'xmlhttprequest';
	}
}


function rewrite_rules_on_theme_activation() {
	flush_rewrite_rules();
}

//when theme is switched flush rewrite rules
add_action( 'after_switch_theme', 'rewrite_rules_on_theme_activation' );


if (!function_exists('qode_remove_vc_grid_element')) {
	/**
	 * Function that removes Grid Elements Post Type
	 * that comes with Visual Composer from version 4.4.2
	 */
	function qode_remove_vc_grid_element() {

		remove_action( 'init', 'vc_grid_item_editor_create_post_type' );

	}

	add_action('vc_after_init', 'qode_remove_vc_grid_element', 12);

}

if(!function_exists('qode_get_vc_version')) {
    /**
     * Return Visual Composer version string
     *
     * @return bool|string
     */
    function qode_get_vc_version() {
        if(qode_visual_composer_installed()) {
            return WPB_VC_VERSION;
        }

        return false;
    }
}

if(!function_exists('qode_visual_composer_installed')) {
    /**
     * Function that checks if visual composer installed
     * @return bool
     */
    function qode_visual_composer_installed() {
        //is Visual Composer installed?
        if(class_exists('WPBakeryVisualComposerAbstract')) {
            return true;
        }

        return false;
    }
}