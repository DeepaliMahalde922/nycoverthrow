<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Product_Topup
 */
class WC_Product_Topup extends WC_Product {

	/**
	 * Constructor
	 */
	public function __construct( $product ) {
		parent::__construct( $product );
		$this->product_type = 'topup';
		$this->tax_status   = '';
	}

	/** Exists */
	public function exists() {
		return true;
	}

	/** Purchasable */
	public function is_purchasable() {
		return true;
	}

	/** Title */
	public function get_title() {
		return __( 'Account Funds Top-up', 'woocommerce-account-funds' );
	}

	/**
	 * Returns the tax status.
	 *
	 * @return string
	 */
	public function get_tax_status() {
		return apply_filters( 'woocommerce_account_funds_topup_get_tax_status', '' );
	}

	/**
	 * Not a visible product
	 * @return boolean
	 */
	public function is_visible() {
		return false;
	}

	/**
	 * Does not need shipping
	 *
	 * @return bool
	 */
	public function is_virtual() {
		return true;
	}

	/**
	 * Make sure topup is sold individually (no quantities).
	 *
	 * @return bool
	 */
	public function is_sold_individually() {
		return true;
	}
}
