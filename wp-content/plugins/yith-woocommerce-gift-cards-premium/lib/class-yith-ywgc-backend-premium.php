<?php
if ( ! defined ( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( ! class_exists ( 'YITH_YWGC_Backend_Premium' ) ) {
	
	/**
	 *
	 * @class   YITH_YWGC_Backend_Premium
	 *
	 * @since   1.0.0
	 * @author  Lorenzo Giuffrida
	 */
	class YITH_YWGC_Backend_Premium extends YITH_YWGC_Backend {
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
		public static function get_instance() {
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
		protected function __construct() {
			
			parent::__construct ();
			
			/**
			 * Set the CSS class 'show_if_gift-card in 'sold indidually' section
			 */
			add_action ( 'woocommerce_product_options_inventory_product_data', array(
				$this,
				'show_sold_individually_for_gift_cards'
			) );
			
			/**
			 * manage CSS class for the gift cards table rows
			 */
			add_filter ( 'post_class', array( $this, 'add_cpt_table_class' ), 10, 3 );
			
			add_action ( 'init', array( $this, 'redirect_gift_cards_link' ) );
			
			add_action ( 'load-upload.php', array( $this, 'set_gift_card_category_to_media' ) );
			
			add_action ( 'edited_term_taxonomy', array( $this, 'update_taxonomy_count' ), 10, 2 );
			
			/**
			 * Show icon that prompt the admin for a pre-printed gift cards buyed and whose code is not entered
			 */
			add_action ( 'manage_shop_order_posts_custom_column', array(
				$this,
				'show_warning_for_pre_printed_gift_cards'
			) );
			
			/*
			 * Save additional product attribute when a gift card product is saved
			 */
			add_action ( 'yith_gift_cards_after_product_save', array(
				$this,
				'save_gift_card_product'
			) );
			
			/**
			 * Show inventory tab in product tabs
			 */
			add_filter ( 'woocommerce_product_data_tabs', array(
				$this,
				'show_inventory_tab'
			) );
			
			add_action ( 'yith_ywgc_gift_card_email_sent', array(
				$this,
				'manage_bcc'
			) );
			
			add_action ( 'yith_ywgc_product_settings_after_amount_list', array(
				$this,
				'show_advanced_product_settings'
			) );
			
			/**
			 * Show gift cards code and amount in order's totals section, in edit order page
			 */
			add_action ( 'woocommerce_admin_order_totals_after_tax', array(
				$this,
				'show_gift_cards_total_before_order_totals'
			) );
		}
		
		/**
		 * Show gift cards code and amount in order's totals section, in edit order page
		 *
		 * @param int $order_id
		 */
		public function show_gift_cards_total_before_order_totals( $order_id ) {
			
			$order            = wc_get_order ( $order_id );
			$order_gift_cards = yit_get_prop ( $order, YITH_YWGC_Cart_Checkout::ORDER_GIFT_CARDS, true );
			$currency         = version_compare ( WC ()->version, '3.0', '<' ) ? $order->get_order_currency () : $order->get_currency ();
			
			if ( $order_gift_cards ) :
				foreach ( $order_gift_cards as $code => $amount ): ?>
					<tr>
						<td class="label"><?php _e ( 'Gift card: ' . $code, 'yith-woocommerce-gift-cards' ); ?>:</td>
						<td width="1%"></td>
						<td class="total">
							<?php echo wc_price ( $amount, array( 'currency' => $currency ) ); ?>
						</td>
					</tr>
				<?php endforeach;
			endif;
		}
		
		/**
		 * Send a copy of gift card email to additional recipients, if set
		 *
		 * @param $gift_card
		 */
		public function manage_bcc( $gift_card ) {
			//  Check if the option is set
			if ( ! YITH_YWGC ()->blind_carbon_copy ) {
				return;
			}
			
			$recipients = apply_filters ( 'yith_ywgc_bcc_additional_recipients', array( get_option ( 'admin_email' ) ) );
			if ( ! $recipients ) {
				return;
			}
			
			WC ()->mailer ();
			
			foreach ( $recipients as $recipient ) {
				//  Send a copy of the gift card to the recipient
				$gift_card->recipient = $recipient;
				do_action ( 'ywgc-email-send-gift-card_notification', $gift_card );
			}
		}
		
		/**
		 * Show inventory section for gift card products
		 *
		 * @param array $tabs
		 *
		 * @return mixed
		 */
		public function show_inventory_tab( $tabs ) {
			if ( isset( $tabs['inventory'] ) ) {
				
				$tabs['inventory']['class'][] = 'show_if_gift-card';
			}
			
			return $tabs;
			
		}
		
		/**
		 * Save additional product attribute when a gift card product is saved
		 *
		 * @param int $post_id current product id
		 */
		public function save_gift_card_product( $post_id ) {
			//	Save the flag for manual amounts when the product is saved
			if ( isset( $_POST["manual_amount_mode"] ) ) {
				$product = new WC_Product_Gift_Card( $post_id );
				
				$product->update_manual_amount_status ( $_POST["manual_amount_mode"] );
			}
		}
		
		/**
		 * Show icon on backend page "orders" for order where there is file uploaded and waiting to be confirmed.
		 *
		 * @param string $column current column being shown
		 */
		public function show_warning_for_pre_printed_gift_cards( $column ) {
			//  If column is not of type order_status, skip it
			if ( 'order_status' !== $column ) {
				return;
			}
			
			global $the_order;
			$count = $this->pre_printed_cards_waiting_count ( $the_order );
			if ( $count ) {
				$message = _n ( "This order contains one pre-printed gift card that needs to be filled", sprintf ( "This order contains %d pre-printed gift cards that needs to be filled", $count ), $count, 'yith-woocommerce-gift-cards' );
				echo '<img class="ywgc-pre-printed-waiting" src="' . YITH_YWGC_ASSETS_IMAGES_URL . 'waiting.png" title="' . $message . '" />';
			}
		}
		
		/**
		 * Retrieve the number of pre-printed gift cards that are not filled
		 *
		 * @param WC_Order $order
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 * @return int
		 */
		private function pre_printed_cards_waiting_count( $order ) {
			$order_items = $order->get_items ( 'line_item' );
			$count       = 0;
			
			foreach ( $order_items as $order_item_id => $order_data ) {
				$gift_ids = ywgc_get_order_item_giftcards ( $order_item_id );
				
				if ( empty( $gift_ids ) ) {
					return;
				}
				
				foreach ( $gift_ids as $gift_id ) {
					
					$gc = new YWGC_Gift_Card_Premium( array( 'ID' => $gift_id ) );
					
					if ( $gc->is_pre_printed () ) {
						$count ++;
					}
				}
			}
			
			return $count;
		}
		
		/**
		 * Fix the taxonomy count of items
		 *
		 * @param $term_id
		 * @param $taxonomy_name
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function update_taxonomy_count( $term_id, $taxonomy_name ) {
			//  Update the count of terms for attachment taxonomy
			if ( YWGC_CATEGORY_TAXONOMY != $taxonomy_name ) {
				return;
			}
			
			//  update now
			global $wpdb;
			$count = (int) $wpdb->get_var ( $wpdb->prepare ( "SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts p1 WHERE p1.ID = $wpdb->term_relationships.object_id AND ( post_status = 'publish' OR ( post_status = 'inherit' AND (post_parent = 0 OR (post_parent > 0 AND ( SELECT post_status FROM $wpdb->posts WHERE ID = p1.post_parent ) = 'publish' ) ) ) ) AND post_type = 'attachment' AND term_taxonomy_id = %d", $term_id ) );
			
			$wpdb->update ( $wpdb->term_taxonomy, compact ( 'count' ), array( 'term_taxonomy_id' => $term_id ) );
		}
		
		
		public function set_gift_card_category_to_media() {
			
			//  Skip all request without an action
			if ( ! isset( $_REQUEST['action'] ) && ! isset( $_REQUEST['action2'] ) ) {
				return;
			}
			
			//  Skip all request without a valid action
			if ( ( '-1' == $_REQUEST['action'] ) && ( '-1' == $_REQUEST['action2'] ) ) {
				return;
			}
			
			$action = '-1' != $_REQUEST['action'] ? $_REQUEST['action'] : $_REQUEST['action2'];
			
			//  Skip all request that do not belong to gift card categories
			if ( ( 'ywgc-set-category' != $action ) && ( 'ywgc-unset-category' != $action ) ) {
				return;
			}
			
			//  Skip all request without a media list
			if ( ! isset( $_REQUEST['media'] ) ) {
				return;
			}
			
			$media_ids = $_REQUEST['media'];
			
			//  Check if the request if for set or unset the selected category to the selected media
			$action_set_category = ( 'ywgc-set-category' == $action ) ? true : false;
			
			//  Retrieve the category to be applied to the selected media
			$category_id = '-1' != $_REQUEST['action'] ? intval ( $_REQUEST['categories1_id'] ) : intval ( $_REQUEST['categories2_id'] );
			
			foreach ( $media_ids as $media_id ) {
				
				// Check whether this user can edit this post
				//if ( ! current_user_can ( 'edit_post', $media_id ) ) continue;
				
				if ( $action_set_category ) {
					$result = wp_set_object_terms ( $media_id, $category_id, YWGC_CATEGORY_TAXONOMY, true );
				} else {
					$result = wp_remove_object_terms ( $media_id, $category_id, YWGC_CATEGORY_TAXONOMY );
				}
				
				if ( is_wp_error ( $result ) ) {
					return $result;
				}
			}
		}
		
		/**
		 * manage CSS class for the gift cards table rows
		 *
		 * @param array  $classes
		 * @param string $class
		 * @param int    $post_id
		 *
		 * @return array|mixed|void
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function add_cpt_table_class( $classes, $class, $post_id ) {
			
			if ( YWGC_CUSTOM_POST_TYPE_NAME != get_post_type ( $post_id ) ) {
				return $classes;
			}
			
			$gift_card = new YWGC_Gift_Card_Premium( array( 'ID' => $post_id ) );
			
			if ( ! $gift_card->exists () ) {
				return $class;
			}
			
			$classes[] = $gift_card->status;
			
			return apply_filters ( 'yith_gift_cards_table_class', $classes, $post_id );
		}
		
		
		/**
		 * Make some redirect based on the current action being performed
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function redirect_gift_cards_link() {
			
			/**
			 * Check if the user ask for retrying sending the gift card email that are not shipped yet
			 */
			if ( isset( $_GET[ YWGC_ACTION_RETRY_SENDING ] ) ) {
				
				$gift_card_id = $_GET['id'];
				
				YITH_YWGC_Emails::get_instance ()->send_gift_card_email ( $gift_card_id, false );
				$redirect_url = remove_query_arg ( array( YWGC_ACTION_RETRY_SENDING, 'id' ) );
				
				wp_redirect ( $redirect_url );
				exit;
			}
			
			/**
			 * Check if the user ask for enabling/disabling a specific gift cards
			 */
			if ( isset( $_GET[ YWGC_ACTION_ENABLE_CARD ] ) || isset( $_GET[ YWGC_ACTION_DISABLE_CARD ] ) ) {
				$gift_card_id = $_GET['id'];
				$enabled      = isset( $_GET[ YWGC_ACTION_ENABLE_CARD ] );
				
				$gift_card = new YWGC_Gift_Card_Premium( array( 'ID' => $gift_card_id ) );
				
				if ( ! $gift_card->is_dismissed () ) {
					
					$current_status = $gift_card->is_enabled ();
					
					if ( $current_status != $enabled ) {
						
						$gift_card->set_enabled_status ( $enabled );
						do_action ( 'yith_gift_cards_status_changed', $gift_card, $enabled );
					}
					
					wp_redirect ( remove_query_arg ( array(
						YWGC_ACTION_ENABLE_CARD,
						YWGC_ACTION_DISABLE_CARD,
						'id'
					) ) );
					die();
				}
			}
			
			if ( ! isset( $_GET["post_type"] ) || ! isset( $_GET["s"] ) ) {
				return;
			}
			
			
			if ( 'shop_coupon' != ( $_GET["post_type"] ) ) {
				return;
			}
			
			if ( preg_match ( "/(\w{4}-\w{4}-\w{4}-\w{4})(.*)/i", $_GET["s"], $matches ) ) {
				wp_redirect ( admin_url ( 'edit.php?s=' . $matches[1] . '&post_type=gift_card' ) );
				die();
			}
		}
		
		
		public function show_sold_individually_for_gift_cards() {
			?>
			<script>
				jQuery("#_sold_individually").closest(".options_group").addClass("show_if_gift-card");
				jQuery("#_sold_individually").closest(".form-field").addClass("show_if_gift-card");
			</script>
			<?php
		}
		
		/**
		 * Show advanced product settings
		 *
		 * @param int $thepostid
		 */
		public function show_advanced_product_settings( $thepostid ) {
			$this->show_manual_amount_settings ( $thepostid );
			$this->show_custom_header_image_settings ( $thepostid );
			$this->show_template_design_settings ( $thepostid );
		}
		
		
	}
}