<?php
if ( !defined( 'ABSPATH' ) || !defined( 'YITH_WCBM_PREMIUM' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Implements features of FREE version of YITH WooCommerce Badge Management
 *
 * @class   YITH_WCBM_Premium
 * @package YITH WooCommerce Badge Management
 * @since   1.0.0
 * @author  Yithemes
 */

if ( !class_exists( 'YITH_WCBM_Premium' ) ) {
    /**
     * YITH WooCommerce Badge Management
     *
     * @since 1.0.0
     */
    class YITH_WCBM_Premium extends YITH_WCBM {

        /**
         * Returns single instance of the class
         *
         * @return \YITH_WCBM
         * @since 1.0.0
         */
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Constructor
         *
         * @return mixed| YITH_WCBM_Admin | YITH_WCBM_Frontend
         * @since 1.0.0
         */
        public function __construct() {

            //parent::__construct();
            // Load Plugin Framework
            add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );

            YITH_WCBM_Compatibility();
            
            YITH_WCBM_Post_Types::init();

            if ( is_admin() && ( !isset( $_REQUEST[ 'action' ] ) || ( isset( $_REQUEST[ 'action' ] ) && $_REQUEST[ 'action' ] != 'yith_load_product_quick_view' ) ) ) {
                YITH_WCBM_Admin_Premium();
            }

            $is_ajax_request = defined( 'DOING_AJAX' ) && DOING_AJAX;
            if ( !is_admin() || $is_ajax_request ) {
                // Call this class in frontend and in ajax request ( to add badges in case of ajax request of product images )
                YITH_WCBM_Frontend_Premium();
            }
        }
    }
}

/**
 * Unique access to instance of YITH_WCBM_Premium class
 *
 * @return \YITH_WCBM_Premium
 * @since 1.0.0
 */
function YITH_WCBM_Premium() {
    return YITH_WCBM_Premium::get_instance();
}

?>