<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Gateway_Account_Funds class.
 *
 * @extends WC_Payment_Gateway
 */
class WC_Gateway_Account_Funds extends WC_Payment_Gateway {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id                 = 'accountfunds';
		$this->method_title       = __( 'Account Funds', 'woocommerce-account-funds' );
		$this->method_description = __( 'This gateway takes full payment using a logged in user\'s account funds.', 'woocommerce-account-funds' );
		$this->supports           = array(
			'products',
			'subscriptions',
			'subscription_cancellation',
			'subscription_reactivation',
			'subscription_suspension',
			'subscription_amount_changes',
			'subscription_payment_method_change',
			'subscription_date_changes'
		);

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		$this->title        = $this->settings['title'];
		$wcaf_settings      = get_option( 'wcaf_settings' );

		$description = sprintf( __( "Available balance: %s", 'woocommerce-account-funds'), WC_Account_Funds::get_account_funds() );

		if ( 'yes' === get_option( 'account_funds_give_discount' ) ) {
			$amount      = floatval( get_option( 'account_funds_discount_amount' ) );
			$amount      = 'fixed' === get_option( 'account_funds_discount_type' ) ? wc_price( $amount ) : $amount . '%';
			$description .= '<br/><em>' . sprintf( __( 'Use your account funds and get a %s discount on your order.', 'woocommerce-account-funds' ), $amount ) . '</em>';
		}

		$this->description = $description;

		// Subscriptons
		add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );
		add_filter( 'woocommerce_my_subscriptions_recurring_payment_method', array( $this, 'subscription_payment_method_name' ), 10, 3 );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Check if the gateway is available for use
	 *
	 * @return bool
	 */
	public function is_available() {
		$is_available = ( 'yes' === $this->enabled ) ? true : false;

		// Check cart when it's front-end request.
		$is_frontend_request = (
			(
				! is_admin()
				||
				( defined( 'DOING_AJAX' ) && DOING_AJAX )
			)
			&&
			(
				! defined( 'DOING_CRON' )
				||
				( defined( 'DOING_CRON' ) && ! DOING_CRON )
			)
		);
		if ( $is_frontend_request ) {
			if ( WC_Account_Funds_Cart_Manager::cart_contains_deposit() || WC_Account_Funds_Cart_Manager::using_funds() ) {
				$is_available = false;
			}
		}

		return $is_available;
	}

	/**
	 * Settings
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'woothemes' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable', 'woothemes' ),
				'default' => 'yes'
			),
			'title' => array(
				'title'       => __( 'Title', 'woothemes' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woothemes' ),
				'default'     => __( 'Account Funds', 'woocommerce-account-funds' )
			)
		);
	}

	/**
	 * Process a payment
	 */
	public function process_payment( $order_id ) {
		$order  = wc_get_order( $order_id );

		if ( ! is_user_logged_in() ) {
			wc_add_notice( __( 'Payment error:', 'woocommerce-account-funds' ) . ' ' . __( 'You must be logged in to use this payment method', 'woocommerce-account-funds' ), 'error' );
			return;
		}

		$available_funds = WC_Account_Funds::get_account_funds( $order->get_user_id(), false, $order_id );

		if ( $available_funds < $order->get_total() ) {
			wc_add_notice( __( 'Payment error:', 'woocommerce-account-funds' ) . ' ' . __( 'Insufficient account balance', 'woocommerce-account-funds' ), 'error' );
			return;
		}

		// deduct amount from account funds
		WC_Account_Funds::remove_funds( $order->get_user_id(), $order->get_total() );
		update_post_meta( $order_id, '_funds_used', $order->get_total() );
		update_post_meta( $order_id, '_funds_removed', 1 );
		$order->set_total( 0 );

		// Payment complete
		$order->payment_complete();

		// Remove cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result'    => 'success',
			'redirect'  => $this->get_return_url( $order )
		);
	}

	/**
	 * @param float $amount
	 * @param WC_Order $order
	 * @param int $product_id
	 * @return bool|WP_Error
	 */
	public function scheduled_subscription_payment( $amount, $order ) {
		$ret = true;

		// The WC_Subscriptions_Manager will generates order for the renewal.
		// However, the total will not be cleared and replaced with amount of
		// funds used. The set_renewal_order_meta will fix that.
		add_action( 'woocommerce_subscriptions_renewal_order_created', array( $this, 'set_renewal_order_meta' ), 10, 2 );

		try {
			$user_id = $order->get_user_id();
			if ( ! $user_id ) {
				throw new Exception( __( 'Customer not found', 'woocommerce-account-funds' ) );
			}

			$funds = WC_Account_Funds::get_account_funds( $user_id, false );
			if ( $amount > $funds ) {
				throw new Exception( __( 'Insufficient funds', 'woocommerce-account-funds' ) );
			}

			WC_Account_Funds::remove_funds( $order->get_user_id(), $amount );
			WC_Subscriptions_Manager::process_subscription_payments_on_order( $order );

		} catch ( Exception $e ) {
			WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );
			$ret = new WP_Error( 'accountfunds', $e->getMessage() );
		}

		remove_action( 'woocommerce_subscriptions_renewal_order_created', array( $this, 'set_renewal_order_meta' ), 10, 2 );

		return $ret;
	}

	/**
	 * Set renewal order meta.
	 *
	 * Set the total to zero as it will be replaced by `_funds_used`.
	 *
	 * @param WC_Order $renewal_order Order from renewal payment
	 *
	 * @return void
	 */
	public function set_renewal_order_meta( $renewal_order ) {
		// Use total from post meta directly to avoid filter in total amount.
		// The _order_total meta is already calculated for total subscription
		// to pay of given order.
		update_post_meta( $renewal_order->id, '_funds_used', get_post_meta( $renewal_order->id, '_order_total', true ) );

		$renewal_order->set_total( 0 );
		$renewal_order->add_order_note( __( 'Account Funds subscription payment completed', 'woocommerce-account-funds' ) );
	}

	/**
	 * Payment method name
	 */
	public function subscription_payment_method_name( $payment_method_to_display, $subscription_details, $order ) {
		if ( $this->id !== $order->recurring_payment_method || ! $order->customer_user ) {
			return $payment_method_to_display;
		}
		return sprintf( __( 'Via %s', 'woocommerce-account-funds' ), $this->method_title );
	}
}
