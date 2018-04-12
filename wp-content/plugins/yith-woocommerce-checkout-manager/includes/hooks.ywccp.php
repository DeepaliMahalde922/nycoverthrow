<?php
/**
 * Plugins Hooks
 *
 * @author Yithemes
 * @package YITH WooCommerce Checkout Manager
 * @version 1.0.0
 */

if ( ! defined( 'YWCCP' ) ) {
	exit;
} // Exit if accessed directly

// billing fields
add_filter( 'woocommerce_billing_fields', 'ywccp_load_custom_billing_fields', 100, 1 );
add_filter( 'woocommerce_admin_billing_fields', 'ywccp_load_custom_billing_fields_admin', 100, 1 );
// shipping fields
add_filter( 'woocommerce_shipping_fields', 'ywccp_load_custom_shipping_fields', 100, 1 );
add_filter( 'woocommerce_admin_shipping_fields', 'ywccp_load_custom_shipping_fields_admin', 100, 1 );
// additional fields
add_filter( 'woocommerce_checkout_fields', 'ywccp_add_additional_fields', 99, 1 );
add_action( 'woocommerce_checkout_update_order_meta', 'ywccp_add_additional_fields_meta', 10, 2 );
// other
add_filter( 'woocommerce_localisation_address_formats', 'ywccp_add_address_formats', 100, 1 );
add_filter( 'woocommerce_order_formatted_billing_address', 'ywccp_update_formatted_billing_address_order', 10, 2 );
add_filter( 'woocommerce_order_formatted_shipping_address', 'ywccp_update_formatted_shipping_address_order', 10, 2 );
add_filter( 'woocommerce_formatted_address_replacements', 'ywccp_update_address_replacement', 10, 2 );
add_action( 'woocommerce_email_after_order_table', 'ywccp_email_additional_fields_list', 10, 4 );

// compatibility with WooCommerce Customer Order CSV Export
add_filter( 'wc_customer_order_csv_export_order_headers', 'ywccp_customer_order_csv_export_order_headers', 1, 2 );
add_filter( 'wc_customer_order_csv_export_order_row', 'ywccp_customer_order_csv_export_order_row', 1, 3 );


if( ! function_exists( 'ywccp_load_custom_billing_fields' ) ) {
	/**
	 * Load customized billing fields function.
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param array $old
	 * @return array
	 */
	function ywccp_load_custom_billing_fields( $old ) {
		$new = ywccp_get_checkout_fields( 'billing' );

		if( empty( $new ) ) {
			return $old;
		}
		// remove disabled
		foreach( $new as $key => &$value ){
			if( isset( $value['enabled'] ) && ! $value['enabled'] ) {
				unset( $new[$key] );
			}
		}

		return $new;
	}
}

if( ! function_exists( 'ywccp_load_custom_billing_fields_admin' ) ){
	/**
	 * Load customized billing fields for admin section.
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param array $old
	 * @return array
	 */
	function ywccp_load_custom_billing_fields_admin( $old ) {

		$fields = ywccp_get_checkout_fields( 'billing' );

		if( ! is_array( $fields ) || empty( $fields ) ) {
			return $old;
		}

		return ywccp_build_fields_array_admin( $fields, $old, 'billing_' );
	}
}

if( ! function_exists( 'ywccp_load_custom_shipping_fields' ) ){
	/**
	 * Load customized shipping fields function.
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param array $old
	 * @return array
	 */
	function ywccp_load_custom_shipping_fields( $old ) {
		$new = ywccp_get_checkout_fields( 'shipping' );

		if( empty( $new ) ) {
			return $old;
		}
		// remove disabled
		foreach( $new as $key => &$value ){
			if( isset( $value['enabled'] ) && ! $value['enabled'] ) {
				unset( $new[$key] );
			}
		}

		return $new;
	}
}

if( ! function_exists( 'ywccp_load_custom_shipping_fields_admin' ) ){
	/**
	 * Load customized shipping fields for admin section.
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param array $old
	 * @return array
	 */
	function ywccp_load_custom_shipping_fields_admin( $old ) {

		$fields = ywccp_get_checkout_fields( 'shipping' );

		if( ! is_array( $fields ) || empty( $fields ) ) {
			return $old;
		}

		return ywccp_build_fields_array_admin( $fields, $old, 'shipping_' );
	}
}

if( ! function_exists( 'ywccp_add_address_formats' ) ) {
	/**
	 * Update address formats for all formatted address and all nations
	 *
	 * @param $formats array Array of available formats, indexed for nation code
	 * @return array Filtered array of available formats
	 * @since 1.0.0
	 * @author Francesco Licandro
	 */
	function ywccp_add_address_formats( $formats ) {

		$new_replacement = ywccp_get_fields_localisation_address_formats( 'all' );

		foreach ( $formats as $country => &$value ) {
			$value .= $new_replacement;
		}

		return $formats;
	}
}

if( ! function_exists( 'ywccp_update_formatted_billing_address_order' ) ) {
	/**
	 * Adds field to formatted address for order's admin view
	 *
	 * @access public
	 *
	 * @param $billing_fields array Array of fields to be used in formatted address
	 * @param \WC_Order Order object
	 *
	 * @return array Array of filtered fields
	 * @since 1.0.0
	 */
	function ywccp_update_formatted_billing_address_order( $billing_fields, $order ) {

		$custom_fields = ywccp_get_custom_fields_key_filtered( 'billing' );
		if( empty( $custom_fields ) ) {
			return $billing_fields;
		}

		foreach( $custom_fields as $custom_field ) {
			$billing_fields[ $custom_field ] = get_post_meta( $order->id, '_billing_' . $custom_field, true );
		}

		return $billing_fields;
	}
}

if( ! function_exists( 'ywccp_update_formatted_shipping_address_order' ) ) {
	/**
	 * Adds field to formatted address for order's admin view
	 *
	 * @access public
	 *
	 * @param $shipping_fields array Array of fields to be used in formatted address
	 * @param \WC_Order Order object
	 *
	 * @return array Array of filtered fields
	 * @since 1.0.0
	 */
	function ywccp_update_formatted_shipping_address_order( $shipping_fields, $order ) {

		$custom_fields = ywccp_get_custom_fields_key_filtered( 'shipping' );
		if( empty( $custom_fields ) ) {
			return $shipping_fields;
		}

		foreach( $custom_fields as $custom_field ) {
			$shipping_fields[ $custom_field ] = get_post_meta( $order->id, '_shipping_' . $custom_field, true );
		}

		return $shipping_fields;
	}
}

if( ! function_exists( 'ywccp_update_address_replacement' ) ) {
	/**
	 * Update address replacement for all site address formats
	 *
	 * @access public
	 *
	 * @param $replacements array Array of available replacements
	 * @param $args array Array of arguments to use in replacements
	 *
	 * @return array Filtered array of replacements
	 * @since 1.0.0
	 */
	function ywccp_update_address_replacement( $replacements, $args ) {

		$custom_fields = ywccp_get_fields_localisation_address_formats( 'all', true );

		if( empty( $custom_fields ) ) {
			return $replacements;
		}

		foreach ( $custom_fields as $value ) {
			$replacements['{'.$value.'}'] = isset( $args[$value] ) ? $args[$value] : '';
		}

		return $replacements;
	}
}

if( ! function_exists( 'ywccp_add_additional_fields' ) ) {
	/**
	 * Add additional fields to checkout form
	 *
	 * @author Francesco Licandro
	 * @since 1.0.0
	 * @param $fields
	 * @return array
	 */
	function ywccp_add_additional_fields( $fields ) {

		$fields_new = ywccp_get_checkout_fields( 'additional' );

		if ( empty( $fields_new ) || ! isset( $fields['order'] ) ) {
			return $fields;
		}
		// remove disabled
		foreach ( $fields_new as $key => &$value ) {
			if ( isset( $value['enabled'] ) && ! $value['enabled'] ) {
				unset( $fields_new[ $key ] );
			}
		}

		$fields['order'] = $fields_new;

		return $fields;
	}
}

if( ! function_exists( 'ywccp_add_additional_fields_meta' ) ) {
	/**
	 * Add order meta for additional fields
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param int $order_id
	 * @param array $posted
	 */
	function ywccp_add_additional_fields_meta( $order_id, $posted ) {

		// get additional fields key
		$fields = ywccp_get_checkout_fields( 'additional' );
		$default_keys = ywccp_get_default_fields_key( 'additional' );

		foreach ( $fields as $key => $field ) {
			if( in_array( $key, $default_keys ) || empty( $posted[$key] ) ){
				continue;
			}

			update_post_meta( $order_id, $key, $posted[$key] );
		}
	}
}

if( ! function_exists( 'ywccp_email_additional_fields_list' ) ) {
	/**
	 * Add the additional fields list on order email
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param object $order
	 * @param boolean $sent_to_admin
	 * @param boolean $plain_text
	 * @param $email
	 */
	function ywccp_email_additional_fields_list( $order, $sent_to_admin, $plain_text, $email = false ) {

		$fields = ywccp_get_custom_fields( 'additional' );

		// build template content
		$content = array();
		foreach ( $fields as $key => $field ) {
			// check if value exists for order
			$value = get_post_meta( $order->id, $key, true );
			if( $value && $field['show_in_email'] ) {
				$content[$key] = array(
					'label' => $field['label'],
					'value' => $value
				);
			}
		}

		if( empty( $content ) ) {
			return;
		}

		if( $plain_text ){
			wc_get_template( 'ywccp-additional-fields-list.php', array( 'fields' => $content ), '', YWCCP_TEMPLATE_PATH . '/email/plain/' );
		}
		else {
			wc_get_template( 'ywccp-additional-fields-list.php', array( 'fields' => $content ), '', YWCCP_TEMPLATE_PATH . '/email/' );
		}
	}
}

if( ! function_exists( 'ywccp_customer_order_csv_export_order_headers' ) ) {
	/**
	 * Add headers for customer order csv export plugins
	 *
	 * @since 1.0.3
	 * @author Francesco Licandro
	 * @param array $headers
	 * @param object $class WC_Customer_Order_CSV_Export_Generator
	 * @return array
	 */
	function ywccp_customer_order_csv_export_order_headers( $headers, $class ) {
		
		$custom_fields = ywccp_get_all_custom_fields();
		$csv_format = get_option( 'wc_customer_order_csv_export_order_format' );
		$use_label = ! in_array( $csv_format, array( 'legacy_import', 'import', 'default', 'default_one_row_per_item' ) );
		$new_headers = array();

		foreach ( $headers as $key => $value ){

			if( $key == 'billing_country' ) {
				foreach ( $custom_fields['billing'] as $key_custom => $value_custom ){
					$new_headers[ $key_custom ] = ( $use_label && ! empty( $value_custom['label'] ) ) ? 'Billing ' . $value_custom['label'] : $key_custom;
				}
			}
			elseif( $key == 'shipping_country' ) {
				foreach ( $custom_fields['shipping'] as $key_custom => $value_custom ){
					$new_headers[ $key_custom ] = ( $use_label && ! empty( $value_custom['label'] ) ) ? 'Shipping ' . $value_custom['label'] : $key_custom;
				}
			}
			elseif( $key == 'customer_note' ) {
				foreach ( $custom_fields['additional'] as $key_custom => $value_custom ){
					$new_headers[ $key_custom ] = ( $use_label && ! empty( $value_custom['label'] ) ) ? $value_custom['label'] : $key_custom;
				}
			}

			$new_headers[ $key ] = $value;
		}

		return $new_headers;
	}
}

if( ! function_exists( 'ywccp_customer_order_csv_export_order_row' ) ) {
	/**
	 * Modify order row for ccsv export
	 * 
	 * @since 1.0.3
	 * @author Francesco Licandro
	 * @param array $order_data
	 * @param object $order
	 * @param object $class WC_Customer_Order_CSV_Export_Generator
	 * @return array
	 */
	function ywccp_customer_order_csv_export_order_row( $order_data, $order, $class ) {
		$custom_fields = ywccp_get_all_custom_fields();

		foreach ( $custom_fields as $section => $fields ){
			foreach ( $fields as $key => $options ) {
				$meta_key = ( $section == 'additional' ) ? $key : '_'.$key;
				$order_data[ $key ] = get_post_meta( $order->id, $meta_key, true );
			}
		}

		return $order_data;
	}
}

