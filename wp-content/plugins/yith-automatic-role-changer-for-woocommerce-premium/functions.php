<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! function_exists( 'yith_initialize_plugin_fw' ) ) {
    /**
     * Initialize plugin-fw
     */
    function yith_initialize_plugin_fw( $plugin_dir ) {
        if ( ! function_exists( 'yit_deactive_free_version' ) ) {
            require_once $plugin_dir . 'plugin-fw/yit-deactive-plugin.php';
        }

        if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
            require_once $plugin_dir . 'plugin-fw/yit-plugin-registration-hook.php';
        }

        /* Plugin Framework Version Check */
        if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( $plugin_dir . 'plugin-fw/init.php' ) ) {
            require_once( $plugin_dir . 'plugin-fw/init.php' );
        }
    }
}

if ( ! function_exists( 'yith_ywarc_install_woocommerce_admin_notice' ) ) {

    function yith_ywarc_install_woocommerce_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'YITH Automatic Role Changer for WooCommerce is enabled but not effective. It requires WooCommerce in order to work.', 'yit' ); ?></p>
        </div>
        <?php
    }
}

if ( ! function_exists( 'yith_ywarc_install' ) ) {
    /**
     * Install the plugin
     */
    function yith_ywarc_install() {

        if ( ! function_exists( 'WC' ) ) {
            add_action( 'admin_notices', 'yith_ywarc_install_woocommerce_admin_notice' );
        } else {
            do_action( 'yith_ywarc_init' );
        }
    }
}

if ( ! function_exists( 'yith_ywarc_init' ) ) {
    /**
     * Start the plugin
     */
    function yith_ywarc_init() {
        /**
         * Load text domain
         */
        load_plugin_textdomain( 'yith-automatic-role-changer-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        

        /** include plugin's files */

        require_once( YITH_WCARC_PATH . 'includes/class.yith-role-changer.php' );
        require_once( YITH_WCARC_PATH . 'includes/class.yith-role-changer-premium.php' );
        require_once( YITH_WCARC_PATH . 'includes/class.yith-ywarc-plugin-fw-loader.php' );

        YITH_YWARC_Plugin_FW_Loader::get_instance();
        YITH_Role_Changer();
    }
}

if ( ! function_exists( 'YITH_Role_Changer' ) ) {
    /**
     * Unique access to instance of YITH_Role_Changer class
     *
     * @return YITH_Role_Changer
     * @since 1.0.0
     */
    function YITH_Role_Changer() {
        return YITH_Role_Changer_Premium::instance();
    }
}
add_action( 'yith_ywarc_init', 'yith_ywarc_init' );




