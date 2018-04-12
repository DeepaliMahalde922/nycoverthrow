<?php

/**
 * Plugin Name: Fast WooCredit
 * Plugin URI: http://www.fastflow.io
 * Description: Virtual credit for WooCommerce
 * Version: 1.0.2b1
 * Author: FastFlow
 * Author URI: http://www.fastflow.io
 * 
 */


if( !defined('ABSPATH') ) {
    exit;
}

/**
 * CONSTANTS AND GLOBALS
 */
if(!defined('FWC_BASE_URL')) {
    define('FWC_BASE_URL', plugin_dir_url(__FILE__));
}
if(!defined('FWC_BASE_DIR')) {
    define('FWC_BASE_DIR', dirname(__FILE__));
}
if(!defined('PLUGIN_DIRR')) {
    define('PLUGIN_DIRR', dirname(dirname(__FILE__)));
}

/**
 * INCLUDE SCRIPTS
 */

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
    include_once FWC_BASE_DIR . '/includes/fwc-custom-product-type.php';

    include_once FWC_BASE_DIR . '/includes/fwc-products-meta.php';

    include_once FWC_BASE_DIR . '/includes/fwc-purchased-credits-order-processing.php';

    include_once FWC_BASE_DIR . '/includes/fwc-products-purchase-button.php';

    include_once FWC_BASE_DIR . '/includes/fwc-product-purchase-order.php';

}



/** Check for Update  **/

$fwc_api_url = 'http://updates.fastflow.io/api/plugins/fast-woocredit/Y4D5FDWJ6AN9E2SK65A8H25/';
$fwc_plugin_slug = 'fast-woocredit';

// Take over the update check
add_filter('pre_set_site_transient_update_plugins', 'fwc_check_update');
// Take over the Plugin info screen
add_filter('plugins_api', 'fwc_api_call', 10, 3);



function fwc_check_update( $checked_data ) {
    global $fwc_api_url, $fwc_plugin_slug;
    //echo var_dump($checked_data);
    if ( empty( $checked_data->checked ) ) {
        error_log("Got empty from Fast WooCredit update check");
        return $checked_data;
    }
    $current_version = $checked_data->checked[$fwc_plugin_slug .'/'. $fwc_plugin_slug .'.php'];

    $request_param = fwc_prepare_request('check_update');

    // Start checking for an update
    $raw_response = wp_remote_post($fwc_api_url, $request_param);

    if ( is_wp_error( $raw_response ) ) {
        error_log("Got error from Fast WooCredit update check remote request");
    }
	
    if ( !is_wp_error( $raw_response ) && ( $raw_response['response']['code'] == 200 ) ) {
        error_log("Got data from Fast WooCredit update check remote request");
        $response = unserialize($raw_response['body']);
    }

    if ( is_object( $response ) && !empty( $response ) ) { // Feed the update data into WP updater
        if ( version_compare( $current_version, $response->new_version, '<' ) ) {
            $obj = new stdClass();
            $obj->slug = $fwc_plugin_slug;
            $obj->new_version = $response->new_version;
            $obj->url = $response->url;
            $obj->plugin = $fwc_plugin_slug .'/'. $fwc_plugin_slug .'.php';
            $obj->package = $response->package;
            $checked_data->response[$fwc_plugin_slug .'/'. $fwc_plugin_slug .'.php'] = $obj;
        }
    }

    return $checked_data;
}

function fwc_api_call($def, $action, $args) {
    global $fwc_plugin_slug, $fwc_api_url;
    error_log("Got plugins-api call from Fast WooCredit");
    if ( empty($args->slug) || $args->slug != $fwc_plugin_slug ) {
        return false;
    }
    error_log("Got correct plugins-api call from Fast WooCredit: '$fwc_plugin_slug'");	

    // Get the current version
    $plugin_info = get_site_transient('update_plugins');
    $current_version = $plugin_info->checked[$fwc_plugin_slug .'/'. $fwc_plugin_slug .'.php'];
    $args->version = $current_version;

    $request_param = fwc_prepare_request('plugin_information');

    $request = wp_remote_post($fwc_api_url, $request_param);
	
    if ( is_wp_error( $request ) ) {
        error_log("Got error from Fast WooCredit info check remote request");
        $res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
    } else {
        $res = unserialize($request['body']);
        error_log("Got data from Fast WooCredit info check remote request: '$request[body]'");
        if ( $res === false ) {
            $res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
        }  
    }
	
    return $res;
}

function fwc_prepare_request($action) {
    return array(
        'body' => array(
            'action' => $action
        ),
    );	
}

