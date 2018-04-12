<?php
/**
 * GENERAL ARRAY OPTIONS
 */
if ( ! defined( 'YWCCP' ) ) {
	exit;
} // Exit if accessed directly

$general = array(

	'general' => array(

		array(
			'title' => __( 'General options', 'yith-woocommerce-checkout-manager' ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => 'ywccp-general-options'
		),

		array(
			'title'   => __( 'Enable JS validation', 'yith-woocommerce-checkout-manager' ),
			'desc'    => __( 'Enable JavaScript field validation.', 'yith-woocommerce-checkout-manager' ),
			'type'    => 'checkbox',
			'default' => 'no',
			'id'      => 'ywccp-enable-js-error-check'
		),

		array(
			'title'   => __( 'Enable VAT JS Validation (EU)', 'yith-woocommerce-checkout-manager' ),
			'desc'    => __( 'Enable JavaScript VAT field validation. "Country" field is also required. This option is available only for European countries', 'yith-woocommerce-checkout-manager' ),
			'type'    => 'checkbox',
			'default' => 'no',
			'id'      => 'ywccp-enable-js-vat-check'
		),

		array(
			'title'   => __( 'Enable tooltip', 'yith-woocommerce-checkout-manager' ),
			'desc'    => __( 'Enable tooltip on checkout fields. Don\'t forget to set the tooltip text in single field edit window, otherwise the tooltip will not appear', 'yith-woocommerce-checkout-manager' ),
			'type'    => 'checkbox',
			'default' => 'no',
			'id'      => 'ywccp-enable-tooltip-check'
		),

		array(
			'type' => 'sectionend',
			'id'   => 'ywccp-end-general-options'
		),

		array(
			'title' => __( 'Style options', 'yith-woocommerce-checkout-manager' ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => 'ywccp-style-options'
		),

		array(
			'title'   => __( 'Checkout style', 'yith-woocommerce-checkout-manager' ),
			'desc'    => __( 'Show checkout form in one column instead of using two-column default layout.', 'yith-woocommerce-checkout-manager' ),
			'type'    => 'checkbox',
			'default' => 'no',
			'id'      => 'ywccp-field-checkout-columns'
		),

		array(
			'title'   => __( 'Input field height', 'yith-woocommerce-checkout-manager' ),
			'desc'    => __( 'Set input field height (px)', 'yith-woocommerce-checkout-manager' ),
			'type'    => 'number',
			'default' => '40',
			'id'      => 'ywccp-field-input-height'
		),

		array(
			'title'   => __( 'Input field border', 'yith-woocommerce-checkout-manager' ),
			'desc'    => __( 'Set a color for input field border.', 'yith-woocommerce-checkout-manager' ),
			'type'    => 'color',
			'default' => '#d1d1d1',
			'id'      => 'ywccp-field-border-color'
		),

		array(
			'title'   => __( 'Input field border on focus', 'yith-woocommerce-checkout-manager' ),
			'desc'    => __( 'Set a color for input field border on focus.', 'yith-woocommerce-checkout-manager' ),
			'type'    => 'color',
			'default' => '#d1d1d1',
			'id'      => 'ywccp-field-border-color-focus'
		),

		array(
			'title'   => __( 'Input field border (correct info)', 'yith-woocommerce-checkout-manager' ),
			'desc'    => __( 'Set a color for input field border when info entered is correct.', 'yith-woocommerce-checkout-manager' ),
			'type'    => 'color',
			'default' => '#69bf29',
			'id'      => 'ywccp-field-border-color-success'
		),

		array(
			'title'   => __( 'Input field border (wrong info)', 'yith-woocommerce-checkout-manager' ),
			'desc'    => __( 'Set a color for input field border when info entered is wrong.', 'yith-woocommerce-checkout-manager' ),
			'type'    => 'color',
			'default' => '#a00a00',
			'id'      => 'ywccp-field-border-color-error'
		),

		array(
			'title'   => __( 'Error message color', 'yith-woocommerce-checkout-manager' ),
			'desc'    => __( 'Set a color for error messages.', 'yith-woocommerce-checkout-manager' ),
			'type'    => 'color',
			'default' => '#a00a00',
			'id'      => 'ywccp-field-error-color'
		),

		array(
			'type' => 'sectionend',
			'id'   => 'ywccp-end-style-options'
		),
	)
);

return apply_filters( 'ywccp_panel_general_options', $general );