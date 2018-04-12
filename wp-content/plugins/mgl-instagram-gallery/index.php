<?php
/*
Plugin Name: Instagram Gallery
Plugin URI: http://instagram.mageeklab.com
Description: Plugin for displaying Instagram photo & video galleries the easy way
Author: MaGeek Lab
Version: 3.0.3
Author URI: http://www.mageeklab.com
*/

/**
 * INSTAGRAM GALLERY PLUGIN BOOTSTRAP
 * This file defines the plugin configuration and calls the main functions to load the plugin
 */

define('MGL_INSTAGRAM_GALLERY_DOMAIN', 'mgl_instagram_gallery');
define('MGL_INSTAGRAM_GALLERY_URL_BASE', plugin_dir_url(__FILE__));
define('MGL_INSTAGRAM_GALLERY_URL_ASSETS', MGL_INSTAGRAM_GALLERY_URL_BASE . 'assets/');
define('MGL_INSTAGRAM_GALLERY_FILEPATH', dirname(__FILE__));

// Call mgl_instagram_gallery_autoload function when a class is loaded
spl_autoload_register('mgl_instagram_gallery_autoload');

/**
 * Define instagram plugin autoload way
 *
 * @param $class_name
 */
function mgl_instagram_gallery_autoload($class_name)
{
    //Check if class name contains 'MGLInstagramGallery'
    if (false !== strpos($class_name, 'MGLInstagramGallery')) {
        // Obtains class dir
        $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
        // Get class path from class name
        $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

        require_once $classes_dir . $class_file;
    }
}

// BACKWARD COMPATIBILITY FIXES
// Include class-http only if is not already included
if (!class_exists('WP_Http'))
    include_once(ABSPATH . WPINC . '/class-http.php');

// Emoji compatibility
if (!function_exists('wp_encode_emoji')) {
    /**
     * wp_encode_emoji Define a custom harmless encode emoji function to avoid breaking system
     * @param $content
     * @return mixed
     */
    function wp_encode_emoji($content)
    {
        return $content;
    }
}
// END BACKWARD COMPATIBILITY FIXES

// Utils
require_once('MGL_InstagramGalleryUtiles.php');


// Load the plugin when 'plugins_loaded' is called
add_action('plugins_loaded', 'mgl_instagram_gallery_init');

/**
 * mgl_instagram_gallery_init set the plugin configuration and init the plugin through its container
 */
function mgl_instagram_gallery_init()
{
    global $mgl_ig;
    // Create the plugin container
    $mgl_ig = new MGLInstagramGallery_Plugin();

    // Set plugin info
    $mgl_ig['path'] = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR;
    $mgl_ig['url'] = plugin_dir_url(__FILE__);
    $mgl_ig['version'] = '3.0.3';

    // Set admin page properties
    $mgl_ig['settings_page_properties'] = array(
        'parent_slug' => 'options-general.php',
        'page_title' => 'Instagram Gallery',
        'menu_title' => 'Instagram Gallery',
        'capability' => 'manage_options',
        'menu_slug' => 'instagram-gallery-settings',
        'option_group' => 'MGLInstagramGallery_option_group',
        'option_name' => 'MGLInstagramGallery_option_name',
    );

    // Logger
    $mgl_ig['log_file_path'] = mgl_get_log_file_path();
    $mgl_ig['logger_active'] = mgl_is_logger_enabled();
    $mgl_ig['logger'] = new MGLInstagramGallery_Logger_Logger($mgl_ig['log_file_path'], $mgl_ig['logger_active']);

    // Assets Loader
    $mgl_ig['assetsLoader'] = new MGLInstagramGallery_AssetsLoader();

    // Flash message system
    $mgl_ig['flashmessage'] = new MGLInstagramGallery_Notice_Notice();

    // Init plugin admin configuration page
    $mgl_ig['settings_page'] = new MGLInstagramGallery_Admin_SettingsPage($mgl_ig['settings_page_properties']);

    // Main Controller
    $mgl_ig['shortcodes'] = new MGLInstagramGallery_Shortcode_Controller();

    // Register plugin widgets
    // TODO: Might need to fix widgets and translation. They are loading but not storing anything on container
    $mgl_ig['widgets'] = 'mgl_instagram_register_widgets_init';

    // Load plugin translations
    $mgl_ig['translation'] = 'mgl_instagram_gallery_translation';

    // Execute the plugin container
    $mgl_ig->run();
}

/**
 * mgl_instagram_register_widgets_init Calls WordPress widgets register function
 */
function mgl_instagram_register_widgets_init()
{
    add_action('widgets_init', 'mgl_instagram_register_widgets');
}

/**
 * mgl_instagram_register_widgets Register widgets on WorPress
 */
function mgl_instagram_register_widgets()
{
    register_widget('MGLInstagramGallery_Widgets_Gallery');
    register_widget('MGLInstagramGallery_Widgets_Card');
}


/**
 * Return the file path of plugin´s log
 * @return string Log file path
 */
function mgl_get_log_file_path()
{
    $log_file_name = 'mgl_instagram_log.log';
    $upload_dir = wp_upload_dir();
    $log_file_path = $upload_dir['basedir'] . '/' . $log_file_name;

    return $log_file_path;
}

function mgl_is_logger_enabled()
{
    global $mgl_ig;
    $plugin_settings = get_option(
        $mgl_ig['settings_page_properties']['option_name'],
        array()
    );

    if (isset($plugin_settings['configuration']['log']) && $plugin_settings['configuration']['log'] === '1') {
        return true;
    }

    return false;
}

/////////////////////////////////////////

/**
 * mgl_instagram_gallery_translation Loads plugin's translations to make it available in different languages
 */
function mgl_instagram_gallery_translation()
{
    load_plugin_textdomain('mgl_instagram_gallery', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

//INCLUDES
require_once('MGL_Instagram_LocationSearch.class.php');

//VC
require_once('vc_extend.php');
