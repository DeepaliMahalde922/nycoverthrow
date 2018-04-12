<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Account_Funds_Order_Manager
 */
class WC_Account_Funds_Order_Manager {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'woocommerce_before_checkout_process', array( $this, 'force_registration_during_checkout' ), 10 );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'woocommerce_checkout_update_order_meta' ), 10, 2 );
		add_action( 'woocommerce_payment_complete', array( $this, 'maybe_remove_funds' ) );
		add_action( 'woocommerce_order_status_processing', array( $this, 'maybe_remove_funds' ) );
		add_action( 'woocommerce_order_status_on-hold', array( $this, 'maybe_remove_funds' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'maybe_remove_funds' ) );
		add_action( 'woocommerce_order_status_cancelled', array( $this, 'maybe_restore_funds' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'maybe_increase_funds' ) );
		add_filter( 'woocommerce_get_order_item_totals', array( $this, 'woocommerce_get_order_item_totals' ), 10, 2 );
		add_filter( 'woocommerce_order_amount_total', array( 'WC_Account_Funds_Order_Manager', 'adjust_total_to_include_funds' ), 10, 2 );
	}

	public function maybe_remove_funds( $order_id ) {
		if ( null !== WC()->session ) {
			WC()->session->set( 'use-account-funds', false );
			WC()->session->set( 'used-account-funds', false );
		}

		$order       = wc_get_order( $order_id );
		$customer_id = $order->get_user_id();

		if ( $customer_id && ! get_post_meta( $order_id, '_funds_removed', true ) ) {
			if ( $funds = get_post_meta( $order_id, '_funds_used', true ) ) {
				WC_Account_Funds::remove_funds( $customer_id, $funds );
				$order->add_order_note( sprintf( __( 'Removed %s funds from user #%d', 'woocommerce-account-funds' ), wc_price( $funds ), $customer_id ) );
			}
			update_post_meta( $order_id, '_funds_removed', 1 );
		}
	}

	/**
	 * Remove user funds when an order is created
	 * 
	 * @param  int $order_id
	 */
	public function woocommerce_checkout_update_order_meta( $order_id, $posted ) {
		if ( $posted['payment_method'] !== 'accountfunds' && WC_Account_Funds_Cart_Manager::using_funds() ) {
			$used_funds = WC_Account_Funds_Cart_Manager::used_funds_amount();
			update_post_meta( $order_id, '_funds_used', $used_funds );
			add_post_meta( $order_id, '_funds_removed', 0 );
		}
	}

	/**
	 * Restore user funds when an order is cancelled
	 * 
	 * @param  int $order_id
	 */
	public function maybe_restore_funds( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( $funds = get_post_meta( $order_id, '_funds_used', true ) ) {
			WC_Account_Funds::add_funds( $order->get_user_id(), $funds );
			$order->add_order_note( sprintf( __( 'Restored %s funds to user #%d', 'woocommerce-account-funds' ), wc_price( $funds ), $order->get_user_id() ) );
		}
	}

	/**
	 * See if an order contains a deposit
	 * 
	 * @param  int $order_id
	 * @return bool
	 */
	public static function order_contains_deposit( $order_id ) {
		$order           = wc_get_order( $order_id );
		$deposit_product = false;

		foreach ( $order->get_items() as $item ) {
			$product = $order->get_product_from_item( $item );

			if ( $product->is_type( 'deposit' ) || $product->is_type( 'topup' ) ) {
				$deposit_product = true;
				break;
			}
		}

		return $deposit_product;
	}

	/**
	 * Handle order complete events
	 * 
	 * @param  int $order_id
	 */
	public function maybe_increase_funds( $order_id ) {
		$order          = wc_get_order( $order_id );
		$items          = $order->get_items();
		$customer_id    = $order->get_user_id();

		if ( $customer_id && ! get_post_meta( $order_id, '_funds_deposited', true ) ) {
			foreach ( $items as $item ) {
				$product = $order->get_product_from_item( $item );
				if ( ! is_a( $product, 'WC_Product' ) ) {
					continue;
				}

				$funds = 0;
				if ( $product->is_type( 'deposit' ) || $product->is_type( 'topup' ) ) {
					$funds = $item['line_total'];
				} else {
					continue;
				}

				WC_Account_Funds::add_funds( $customer_id, $funds );

				$order->add_order_note( sprintf( __( 'Added %s funds to user #%d', 'woocommerce-account-funds' ), wc_price( $funds ), $customer_id ) );

				update_post_meta( $order_id, '_funds_deposited', 1 );
			}
		}
	}

	/**
	 * Order total display
	 */
	public function woocommerce_get_order_item_totals( $rows, $order ) {
		if ( $_funds_used = get_post_meta( $order->id, '_funds_used', true ) ) {
			$rows['funds_used'] = array(
				'label' => __( 'Funds Used:', 'woocommerce-account-funds' ),
				'value'	=> wc_price( $_funds_used )
			);
		}
		return $rows;
	}

	/**
	 * Adjust total to include amount paid with funds
	 *
	 * @return float
	 */
	public static function adjust_total_to_include_funds( $total, $order ) {
		// Don't interfere with total while paying for order.
		if ( is_checkout_pay_page() ) {
			return $total;
		}
		$_funds_used = get_post_meta( $order->id, '_funds_used', true );
		return floatval( $order->order_total ) + floatval( $_funds_used );
	}

	/**
	 * Forces account registration during checkout for deposit prducts
	 */
	public function force_registration_during_checkout() {
		if ( WC_Account_Funds_Cart_Manager::cart_contains_deposit() && ! is_user_logged_in() ) {
			$_POST['createaccount'] = 1;
		}
	}
}

new WC_Account_Funds_Order_Manager();
