<?php
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

require_once( YITH_YWGC_DIR . 'lib/class-yith-woocommerce-gift-cards.php' );
require_once( YITH_YWGC_DIR . 'lib/class-yith-woocommerce-gift-cards-premium.php' );
require_once( YITH_YWGC_DIR . 'lib/class-yith-ywgc-backend.php' );
require_once( YITH_YWGC_DIR . 'lib/class-yith-ywgc-backend-premium.php' );
require_once( YITH_YWGC_DIR . 'lib/class-yith-ywgc-frontend.php' );
require_once( YITH_YWGC_DIR . 'lib/class-yith-ywgc-frontend-premium.php' );

require_once( YITH_YWGC_DIR . 'lib/class-yith-ywgc-gift-card.php' );
require_once( YITH_YWGC_DIR . 'lib/class-yith-ywgc-gift-card-premium.php' );

/** Define constant values */
defined( 'YWGC_CUSTOM_POST_TYPE_NAME' ) || define( 'YWGC_CUSTOM_POST_TYPE_NAME', 'gift_card' );
defined( 'YWGC_GIFT_CARD_PRODUCT_TYPE' ) || define( 'YWGC_GIFT_CARD_PRODUCT_TYPE', 'gift-card' );
defined( 'YWGC_PRODUCT_PLACEHOLDER' ) || define( 'YWGC_PRODUCT_PLACEHOLDER', '_ywgc_placeholder' );
defined( 'YWGC_CATEGORY_TAXONOMY' ) || define( 'YWGC_CATEGORY_TAXONOMY', 'giftcard-category' );

/*  plugin actions */
defined( 'YWGC_ACTION_RETRY_SENDING' ) || define( 'YWGC_ACTION_RETRY_SENDING', 'retry-sending' );
defined( 'YWGC_ACTION_ENABLE_CARD' ) || define( 'YWGC_ACTION_ENABLE_CARD', 'enable-gift-card' );
defined( 'YWGC_ACTION_DISABLE_CARD' ) || define( 'YWGC_ACTION_DISABLE_CARD', 'disable-gift-card' );
defined( 'YWGC_ACTION_ADD_DISCOUNT_TO_CART' ) || define( 'YWGC_ACTION_ADD_DISCOUNT_TO_CART', 'add-discount' );
defined( 'YWGC_ACTION_VERIFY_CODE' ) || define( 'YWGC_ACTION_VERIFY_CODE', 'verify-code' );

/*  gift card post_metas */
defined( 'YWGC_META_GIFT_CARD_ORDERS' ) || define( 'YWGC_META_GIFT_CARD_ORDERS', '_ywgc_orders' );
defined( 'YWGC_META_GIFT_CARD_CUSTOMER_USER' ) || define( 'YWGC_META_GIFT_CARD_CUSTOMER_USER', '_ywgc_customer_user' );
defined( 'YWGC_ORDER_ITEM_DATA' ) || define( 'YWGC_ORDER_ITEM_DATA', '_ywgc_order_item_data' );

/*  order item metas    */
defined( 'YWGC_META_GIFT_CARD_POST_ID' ) || define( 'YWGC_META_GIFT_CARD_POST_ID', '_ywgc_gift_card_post_id' );
defined( 'YWGC_META_GIFT_CARD_STATUS' ) || define( 'YWGC_META_GIFT_CARD_STATUS', '_ywgc_gift_card_status' );

/* Gift card status */




if ( ! function_exists( 'ywgc_get_status_label' ) ) {
	/**
	 * Retrieve the status label for every gift card status
	 *
	 * @param YITH_YWGC_Gift_Card $gift_card
	 *
	 * @return string
	 */
	function ywgc_get_status_label( $gift_card ) {
		return $gift_card->get_status_label();
	}
}

if ( ! function_exists( 'ywgc_get_order_item_giftcards' ) ) {
	/**
	 * Retrieve the gift card ids associated to an order item
	 *
	 * @param int $order_item_id
	 *
	 * @return string|void
	 * @author Lorenzo Giuffrida
	 * @since  1.0.0
	 */
	function ywgc_get_order_item_giftcards( $order_item_id ) {

		/*
		 * Let third party plugin to change the $order_item_id
		 * 
		 * @since 1.3.7
		 */
		$order_item_id = apply_filters( 'yith_get_order_item_gift_cards', $order_item_id );
		$gift_ids      = wc_get_order_item_meta( $order_item_id, YWGC_META_GIFT_CARD_POST_ID );

		if ( is_numeric( $gift_ids ) ) {
			$gift_ids = array( $gift_ids );
		}

		if ( ! is_array( $gift_ids ) ) {
			$gift_ids = array();
		}

		return $gift_ids;
	}
}

if ( ! function_exists( 'ywgc_set_order_item_giftcards' ) ) {
	/**
	 * Retrieve the gift card ids associated to an order item
	 *
	 * @param int   $order_item_id the order item
	 * @param array $ids           the array of gift card ids associated to the order item
	 *
	 * @return string|void
	 * @author Lorenzo Giuffrida
	 * @since  1.0.0
	 */
	function ywgc_set_order_item_giftcards( $order_item_id, $ids ) {

		$ids = apply_filters( 'yith_ywgc_set_order_item_meta_gift_card_ids', $ids, $order_item_id );

		wc_update_order_item_meta( $order_item_id, YWGC_META_GIFT_CARD_POST_ID, $ids );

		do_action( 'yith_ywgc_set_order_item_meta_gift_card_ids_updated', $order_item_id, $ids );
	}
}

if ( ! function_exists( 'wc_help_tip' ) && function_exists( 'WC' ) && version_compare( WC()->version, '2.5.0', '<' ) ) {

	/**
	 * Display a WooCommerce help tip. (Added for compatibility with WC 2.4)
	 *
	 * @since  2.5.0
	 *
	 * @param  string $tip        Help tip text
	 * @param  bool   $allow_html Allow sanitized HTML if true or escape
	 *
	 * @return string
	 */
	function wc_help_tip( $tip, $allow_html = false ) {
		if ( $allow_html ) {
			$tip = wc_sanitize_tooltip( $tip );
		} else {
			$tip = esc_attr( $tip );
		}

		return '<span class="woocommerce-help-tip" data-tip="' . $tip . '"></span>';
	}

}


if ( ! function_exists( 'yith_get_attachment_image_url' ) ) {

	/**
	 * @return string
	 */
	function yith_get_attachment_image_url( $attachment_id, $size = 'thumbnail' ) {

		if ( function_exists( 'wp_get_attachment_image_url' ) ) {
			$header_image_url = wp_get_attachment_image_url( $attachment_id, $size );
		} else {
			$header_image     = wp_get_attachment_image_src( $attachment_id, $size );
			$header_image_url = $header_image['url'];
		}

		return $header_image_url;
	}

}