<?php

/**
 * Class MGLInstagramGallery_AssetsLoader
 */
class MGLInstagramGallery_AssetsLoader
{
    public function __construct()
    {
        // Add jQuery to public side of the web
        if (get_option('mgl_instagram_jquery', true) == true) {
            // Enqueue jQuery to public web
            add_action('wp_print_scripts', array($this, 'enqueueJQueryToPublicPartWeb'));
        }

        //Register Styles
        add_action('init', array($this, 'registerStyles'));

        // Register Scripts
        add_action('init', array($this, 'registerScripts'));

        // Enqueue styles
        add_action('init', array($this, 'enqueueStyles'));

        // Enqueue Scripts
        add_action('init', array($this, 'enqueueScripts'));
    }

    public function registerStyles()
    {
        // Get plugin container
        global $mgl_ig;

        // Styles path
        $styles_path = $mgl_ig['url'] . 'assets/css/';

        // Register Gallery styles
        wp_register_style('mgl_instagram_gallery', $styles_path . 'mgl_instagram_gallery.css', null, $mgl_ig['version']);
    }

    public function enqueueStyles()
    {
        if (!is_admin() && $this->isObserverActive()) {
            // Enqueue gallery styles
            wp_enqueue_style('mgl_instagram_gallery');
        }
    }

    public function registerScripts()
    {
        // Get plugin container
        global $mgl_ig;

        // Scripts path
        $scripts_path = $mgl_ig['url'] . 'assets/js/';

        // Register Magnific Popup (Lightbox) library
        wp_register_script('mgl_instagram_gallery_magnific', $scripts_path . 'magnific.js', array('jquery'), $mgl_ig['version']);

        // Register video player controller
        wp_register_script('mgl_instagram_gallery_video', $scripts_path . 'video.js', null, $mgl_ig['version']);

        // Register Gallery Controller
        wp_register_script(
            'mgl_instagram_gallery_controller', $scripts_path . 'mgl_instagram_controller.js',
            array('jquery', 'mgl_instagram_gallery_magnific', 'mgl_instagram_gallery_video')
        );

        if (!is_admin() && $this->isObserverActive()) {
            // Register DOM Changed library. Detects when DOM changes
            wp_register_script('mgl_instagram_gallery_domchanged', $scripts_path . 'jquery.domchanged.min.js', array('jquery'), $mgl_ig['version']);

            // Register Gallery loader
            wp_register_script(
                'mgl_instagram_gallery_loader',
                $scripts_path . 'mgl_instagram_loader_prevent_ajax.js',
                array(
                    'mgl_instagram_gallery_controller',
                    'mgl_instagram_gallery_domchanged',
                    'mgl_instagram_gallery_magnific',
                    'mgl_instagram_gallery_video'
                ),
                $mgl_ig['version']
            );
        } else {
            // Register gallery loader
            wp_register_script('mgl_instagram_gallery_loader', $scripts_path . 'mgl_instagram_loader.js', array('mgl_instagram_gallery_controller'), $mgl_ig['version']);
        }
    }

    /**
     * Register and enqueue scripts and styles
     */
    public function enqueueScripts()
    {
        if (!is_admin() && $this->isObserverActive()) {
            // Enqueue Magnific Popup library
            wp_enqueue_script('mgl_instagram_gallery_magnific');

            // Enqueue Video player library
            wp_enqueue_script('mgl_instagram_gallery_video');

            // Enqueue Instagram Gallery loader
            wp_enqueue_script('mgl_instagram_gallery_loader');
        }

        // Make ajax_object available on javascript
        wp_localize_script('mgl_instagram_gallery_loader', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    /**
     * Check if configuration's option observer is active
     * @return bool
     */
    public function isObserverActive()
    {
        // Init observerActive as false
        $observerActive = false;

        // Get plugin options
        $plugin_options = get_option('MGLInstagramGallery_option_name');

        if (isset($plugin_options['configuration']['observer'])) {
            // If observer option is set and equal 1. Set observerActive as TRUE
            $observerActive = $plugin_options['configuration']['observer'] == '1';
        }

        return $observerActive;
    }

    /**
     * Enqueue jQuery library to the public part web
     */
    public function enqueueJQueryToPublicPartWeb()
    {
        if (!is_admin()) {
            wp_enqueue_script("jquery");
        }
    }
}
