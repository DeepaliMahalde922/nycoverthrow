<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Flatsome Compatibility Class
 *
 * @class   YITH_WCBM_Flatsome_Compatibility
 * @package Yithemes
 * @since   1.2.27
 * @author  Yithemes
 *
 */
class YITH_WCBM_Flatsome_Compatibility {
    /**
     * Single instance of the class
     *
     * @var \YITH_WCBM_Flatsome_Compatibility
     */
    private static $instance;


    /**
     * Returns single instance of the class
     *
     * @return \YITH_WCBM_Flatsome_Compatibility
     * @since 1.0.0
     */
    public static function get_instance() {
        return !is_null( self::$instance ) ? self::$instance : self::$instance = new self();
    }

    private function __construct() {
        $badge_frontend = YITH_WCBM_Frontend_Premium();

        add_action( 'flatsome_before_product_images', array( $badge_frontend, 'theme_badge_container_start' ) );
        add_action( 'flatsome_after_product_images', array( $badge_frontend, 'theme_badge_container_end' ) );
    }
}