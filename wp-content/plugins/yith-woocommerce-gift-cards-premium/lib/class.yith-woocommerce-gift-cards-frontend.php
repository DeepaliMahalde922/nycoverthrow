<?php
if ( !defined ( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists ( 'YITH_WooCommerce_Gift_Cards_Frontend' ) ) {
	/**
	 * @class   YITH_WooCommerce_Gift_Cards_Frontend
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 */
	class YITH_WooCommerce_Gift_Cards_Frontend {
		/* @var YITH_WooCommerce_Gift_Cards|YITH_WooCommerce_Gift_Cards_Premium main */
		public $main;

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 */
		public static function get_instance () {
			if ( is_null ( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		protected function __construct () {
			/**
			 * Enqueue frontend scripts
			 */
			add_action ( 'wp_enqueue_scripts' , array ( $this , 'enqueue_frontend_script' ) );

			/**
			 * Enqueue frontend styles
			 */
			add_action ( 'wp_enqueue_scripts' , array ( $this , 'enqueue_frontend_style' ) );

			/**
			 * Custom add_to_cart handler for gift card product type
			 */
			add_action ( 'woocommerce_add_to_cart_handler_gift-card' , array ( $this , 'add_to_cart_handler' ) );

			/**
			 * If the product being added to the cart should be used as a gift card, avoid adding it
			 * on cart and use a gift card instead.
			 */
			add_filter ( 'woocommerce_add_to_cart_handler' , array ( $this , 'set_product_type_before_add_to_cart' ) , 10 , 2 );

			/**
			 * Show the gift card product frontend template
			 */
			add_action ( 'woocommerce_gift-card_add_to_cart' , array ( $this , 'show_gift_card_product_template' ) , 30 );

			/**
			 * Extract data from the gift card on the cart and save these as item data
			 */
			add_filter ( 'woocommerce_get_cart_item_from_session' , array ( $this , 'update_gift_card_amount_on_cart' ) , 10 , 3 );

			/**
			 * Prevent more than one order to get the gift card amount applied
			 */
			add_action ( 'woocommerce_after_checkout_validation' , array ( $this , 'woocommerce_after_checkout_validation' ) );

			add_action ( 'woocommerce_add_order_item_meta' , array ( $this , 'append_data_to_order_item' ) , 10 , 3 );

			/**
			 * Show the gift card section for entering the discount code in the cart page
			 */
			add_action ( 'woocommerce_before_cart' , array ( $this , 'show_field_for_gift_code' ) );

			/**
			 * Show the gift card section for entering the discount code in the checkout page
			 */
			add_action ( 'woocommerce_before_checkout_form' , array ( $this , 'show_field_for_gift_code' ) );

			/**
			 * Verify if a coupon code inserted on cart page or checkout page belong to a valid gift card.
			 * In this case, make the gift card working as a temporary coupon
			 */
			add_filter ( 'woocommerce_get_shop_coupon_data' , array ( $this , 'get_gift_card_coupon_data' ) , 10 , 2 );

			/** show element on gift card product template */
			add_action ( 'yith_gift_cards_template_gift_card' , array ( $this , 'show_gift_card_add_to_cart_button' ) , 20 );

		}


		/**
		 * When a product is choosed as a starting point for creating a gift card, as in "give it as a present" function on
		 * product page, the product that will really go in the cart if a gift card, not the product that is
		 * currently shown.
		 */
		public function set_product_type_before_add_to_cart ( $product_type , $adding_to_cart ) {
			//  If a hidden input with name "gift_card_enabled" will be in POST vars array, so the real
			//  product to add to the cart is a gift card.
			if ( !isset( $_POST[ "gift_card_enabled" ] ) ) {
				return $product_type;
			}

			return YWGC_GIFT_CARD_PRODUCT_TYPE;
		}

		/**
		 * Output the add to cart button for variations.
		 */
		public function show_gift_card_add_to_cart_button () {
			global $product;
			?>
			<div class="gift_card_template_button variations_button">
				<?php woocommerce_quantity_input ( array ( 'input_value' => isset( $_POST[ 'quantity' ] ) ? wc_stock_amount ( $_POST[ 'quantity' ] ) : 1 ) ); ?>
				<button type="submit"
				        class="single_add_to_cart_button gift_card_add_to_cart_button button alt"><?php echo esc_html ( $product->single_add_to_cart_text () ); ?></button>
				<input type="hidden" name="add-to-cart" value="<?php echo absint ( $product->id ); ?>"/>
				<input type="hidden" name="product_id" value="<?php echo absint ( $product->id ); ?>"/>
			</div>
			<?php
		}

		/**
		 * Show the gift card product frontend template
		 */
		public function show_gift_card_product_template () {
			// Load the template
			wc_get_template ( 'single-product/add-to-cart/gift-card.php' ,
				'' ,
				'' ,
				trailingslashit ( YITH_YWGC_TEMPLATES_DIR ) );
		}

		/**
		 * Add frontend style to gift card product page
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function enqueue_frontend_script () {

			$suffix = defined ( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			//  register and enqueue ajax calls related script file
			wp_register_script ( "ywgc-frontend-script" ,
				YITH_YWGC_SCRIPT_URL . yit_load_js_file ( 'ywgc-frontend.js' ) ,
				array (
					'jquery' ,
					'woocommerce' ,
					'jquery-ui-datepicker' ,
				) ,
				YITH_YWGC_VERSION ,
				true );

			global $post;

			wp_localize_script ( 'ywgc-frontend-script' , 'ywgc_data' , array (
				'loader' => apply_filters ( 'yith_gift_cards_loader' , YITH_YWGC_ASSETS_URL . '/images/loading.gif' ) ,
				'ajax_url' => admin_url ( 'admin-ajax.php' ) ,
				'currency' => get_woocommerce_currency_symbol () ,
				'custom_image_max_size' => $this->main->custom_image_max_size ,
				'invalid_image_extension' => __ ( "File format is not valid, select a jpg, jpeg, png, gif or bmp file" , 'yith-woocommerce-gift-cards' ) ,
				'invalid_image_size' => __ ( "The size fo the uploaded file exceeds the maximum allowed ({$this->main->custom_image_max_size} MB)" , 'yith-woocommerce-gift-cards' ) ,
				'default_gift_card_image' => $this->main->get_header_image ( $post ) ,
				'notify_custom_image_small' => apply_filters ( "yith_gift_cards_custom_image_editor" , __ ( '<b>Attention</b>: the <b>suggested minimum</b> size of the image is 490x195' , 'yith-woocommerce-gift-cards' ) ) ,
				'multiple_recipient' => __ ( "You have selected more than one receiver: a gift card for each receiver will be generated." , 'yith-woocommerce-gift-cards' ) ,
				'missing_scheduled_date' => __ ( "Please enter a valid delivery date" , 'yith-woocommerce-gift-cards' ) ,
			) );

			wp_enqueue_script ( "ywgc-frontend-script" );
		}

		/**
		 * Add frontend style to gift card product page
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function enqueue_frontend_style () {

			wp_enqueue_style ( 'ywgc-frontend' ,
				YITH_YWGC_ASSETS_URL . '/css/ywgc-frontend.css' ,
				array () ,
				YITH_YWGC_VERSION );

			wp_enqueue_style ( 'jquery-ui-css' ,
				'//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
		}

		/**
		 * Update the value of the gift card in the cart
		 *
		 * @param array  $session_data
		 * @param array  $values
		 * @param string $key
		 *
		 * @return mixed
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function update_gift_card_amount_on_cart ( $session_data , $values , $key ) {
			if ( !isset( $session_data[ 'data' ] ) ) {
				return $session_data;
			}

			$product = $session_data[ 'data' ];

			if ( !$product instanceof WC_Product_Gift_Card ) {
				return $session_data;
			}

			if ( isset( $session_data[ 'amount' ] ) ) {
				$session_price = $session_data[ 'amount' ];
				$product->set_price ( $session_data[ 'amount' ] );
			}

			return $session_data;
		}


		/**
		 * Enable coupons in cart page when this plugin is enable, so a gift code is possible but
		 * don't permit coupon code if coupons are disabled
		 */
		public function show_field_for_gift_code (  ) {

			wc_get_template ( 'checkout/form-gift-cards.php' ,
				array () ,
				'' ,
				YITH_YWGC_TEMPLATES_DIR );
		}

		/**
		 * Verify if a coupon code inserted on cart page or checkout page belong to a valid gift card.
		 * In this case, make the gift card working as a temporary coupon
		 *
		 * @param array  $return_val
		 * @param string $code
		 *
		 * @return array
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function get_gift_card_coupon_data ( $return_val , $code ) {
			/** @var YWGC_Gift_Card_Premium|YWGC_Gift_Card $gift_card */
			$gift_card = $this->main->get_gift_card_by_code ( $code );

			if ( !$gift_card->exists () ) {
				return $return_val;
			}

			if ( $gift_card->ID ) {// it's a gift card code
				//  Check if the gift card is no more usable
				if ( !$gift_card->is_enabled () ) {
					return false;
				}

				$temp_coupon_array = array (
					'discount_type' => 'fixed_cart' ,
					'coupon_amount' => apply_filters ( 'yith_ywgc_gift_card_coupon_amount' , $gift_card->get_balance ( 'yes' === get_option ( 'woocommerce_prices_include_tax' ) ) , $gift_card ) ,
				);

				return $temp_coupon_array;
			}

			return $return_val;
		}

		/**
		 * Prevent the current order from being processed if the gift card code is no more valid
		 *
		 * @param array $posted
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function woocommerce_after_checkout_validation ( $posted ) {

			$gift_cards_used = WC ()->cart->coupon_discount_amounts;
			$save_data = true;

			if ( $gift_cards_used ) {
				foreach ( $gift_cards_used as $code => $amount ) {

					//  Check if the code belong to a gift card and there is enough credit
					//  to cover the amount requested.
					$gift = $this->main->get_gift_card_by_code ( $code );

					//  sometimes the amount is approximate, so we need to round it
					$amount = round ( $amount , 2 );
					$amount = apply_filters ( 'yith_ywgc_set_gift_card_coupon_amount_before_deduct' , $amount );

					//  check if gift card that became with no enough credit during the checkout, generate the notice.
					if ( $gift->exists () && !$gift->has_sufficient_credit ( $amount ) ) {
						$save_data = false;
						wc_add_notice ( sprintf ( __ ( "The gift card assigned to the code %s has no credit left." , 'yith-woocommerce-gift-cards' ) , $code ) , "error" );
					}
				}
			}

			if ( $save_data ) {
				foreach ( WC ()->cart->cart_contents as $key => $data ) {
					if ( !$this->main->instanceof_giftcard ( $data[ "data" ] ) ) {
						continue;
					}
				}
			}
		}

		/**
		 * Append data to order item
		 *
		 * @param $item_id
		 * @param $values
		 * @param $cart_item_key
		 *
		 * @return mixed
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function append_data_to_order_item ( $item_id , $values , $cart_item_key ) {

			if ( !isset( $values[ 'data' ] ) ) {
				return $item_id;
			}
			$product = $values[ 'data' ];

			if ( $product instanceof WC_Product_Gift_Card ) {

				//  Attach the data entered by the client to the current order item
				wc_update_order_item_meta ( $item_id , YWGC_ORDER_ITEM_DATA , $values );
			}

			return $item_id;
		}


	}
}