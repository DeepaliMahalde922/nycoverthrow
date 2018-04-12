<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Gens_RAF
 * @subpackage Gens_RAF/public
 * @author     Your Name <email@example.com>
 */
class Gens_RAF_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $gens_raf    The ID of this plugin.
	 */
	private $gens_raf;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $gens_raf       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $gens_raf, $version ) {

		$this->gens_raf = $gens_raf;
		$this->version = $version;

	}


	/**
	 * Get number of referrals for a user
	 *
	 * @since    1.1.0
	 * @return   string
	 */
	public function get_number_of_referrals($user_id) {
		$number = get_user_meta($user_id, "gens_num_friends", true);
		return $number;
	}

	/**
	 * Save RAF(User) ID in Order Meta after Order is Complete
	 * woocommerce_checkout_update_order_meta hook
	 *
	 * @since    1.0.0
	 * @since    1.1.0  Added filter
	 * @return   string
	 */
	public function save_raf_id( $order_id ) {
		$not_active = get_option( 'gens_raf_disable' );
		$order = new WC_Order( $order_id );
	    $user_id = (int)$order->user_id;
	    $num_friends_refered = $this->get_number_of_referrals($user_id);
	    /*
	    $user_info = get_userdata($myuser_id);
	    $items = $order->get_items();
		*/
		// If filter returns "yes", order wont be saved as referal.
		$not_active = apply_filters('gens_raf_not_active', $not_active, $num_friends_refered, $order);

		if ( isset($_COOKIE["gens_raf"]) && $not_active != "yes" ) {
			$rafID = $_COOKIE["gens_raf"];
			update_post_meta( $order_id, '_raf_id', esc_attr($rafID));
		}
    	return $order_id;
	}

	/**
	 * Generate coupon and email it after order status has been changed to complete
	 * woocommerce_order_status_completed hook
	 *
	 * @since    1.0.0
	 * @since    1.1.0  Added filter
	 */
	public function gens_create_send_coupon($order_id) {
		$rafID = esc_attr(get_post_meta( $order_id, '_raf_id', true));
		$order = new WC_Order( $order_id );
		$order_user_id = $order->get_user_id();
		$order_total = $order->get_total();
		$order_email = $order->billing_email;
		$minimum_amount = get_option( 'gens_raf_min_ref_order' );

		$gens_users = get_users( array(
			"meta_key" => "gens_referral_id",
			"meta_value" => $rafID,
			"number" => 1, 
			"fields" => "ID"
		) );
		$user_id = $gens_users[0];

		// If rafID returns empty, coupon wont be generated
		$rafID = apply_filters('gens_rafid_to_send_coupon', $rafID, $user_id);

		if ( $gens_users && !empty($rafID) && ($user_id != $order_user_id) ) {

			if($minimum_amount && $minimum_amount > $order_total) {
				return $order_id; //exit, dont generate
			}
			
			/** Custom code added by Sharad **/
	
			// Fetch the current class credit of the users(Referrer & Referred).
			$fwc_user_camount1 = get_user_meta( $user_id, 'fwc_total_credit_amount', true );
			$fwc_user_camount2 = get_user_meta( $order_user_id, 'fwc_total_credit_amount', true );

			// Add the class credit
			$fwc_effective_user_camount1 = $fwc_user_camount1 + 1;
			$fwc_effective_user_camount2 = $fwc_user_camount2 + 1;

			// Update the current class credit of the users(Referrer & Referred).
			update_user_meta($user_id, 'fwc_total_credit_amount', $fwc_effective_user_camount1);
			update_user_meta($order_user_id, 'fwc_total_credit_amount', $fwc_effective_user_camount2);

			// Increase number of referrals
			$num_friends_refered = $this->get_number_of_referrals($user_id);
			update_user_meta( $user_id, 'gens_num_friends', (int)$num_friends_refered + 1 );

			//return;

			/** End! added by Sharad **/

			// Generate Coupon and returns it
			$coupon_code = $this->generate_coupons( $user_id  );
			$coupon_code = 'NA';

			// Send coupon to buyer as well?
			$buyer = get_option( 'gens_raf_friend_enable' );
			if($buyer === "yes") {
				$friend_coupon_code = $this->generate_buyer_coupon( $order_email  );
				$friend_coupon_code = 'NA';
				
				$this->gens_send_buyer_email( $order_email, $friend_coupon_code );
			}
			// Increase number of referrals
			$num_friends_refered = $this->get_number_of_referrals($user_id);
			update_user_meta( $user_id, 'gens_num_friends', (int)$num_friends_refered + 1 );
			// Send via Email
			$this->gens_send_email( $user_id, $coupon_code );
		}
		return $order_id;
	}

	/**
	 * Send Email to user
	 *
	 * @since    1.0.0
	 */
	public function gens_send_email($user_id,$coupon_code) {

		if ( !$user_id || !$coupon_code) {
			return false;
		}

		global $woocommerce;
		$mailer = $woocommerce->mailer();

		$user_info = get_userdata($user_id);
		$user_email = $user_info->user_email;
		$user_message = get_option( 'gens_raf_email_message' );
		$subject = get_option( 'gens_raf_email_subject' );
		ob_start();
		wc_get_template( 'emails/email-header.php', array( 'email_heading' => $subject ) );
		echo str_replace( '{{code}}', $coupon_code, $user_message );
		wc_get_template( 'emails/email-footer.php' );
		$message = ob_get_clean();
		// Debug wp_die($user_email);
		$mailer->send( $user_email, $subject, $message);
	}

	/**
	 * Send Email to buyer
	 *
	 * @since    1.0.0
	 */
	public function gens_send_buyer_email($order_email,$coupon_code) {

		if ( !$order_email || !$coupon_code) {
			return false;
		}

		global $woocommerce;
		$mailer = $woocommerce->mailer();
		
		$user_message = get_option( 'gens_raf_buyer_email_message' );
		$subject = get_option( 'gens_raf_buyer_email_subject' );
		ob_start();
		wc_get_template( 'emails/email-header.php', array( 'email_heading' => $subject ) );
		echo str_replace( '{{code}}', $coupon_code, $user_message );
		wc_get_template( 'emails/email-footer.php' );
		$message = ob_get_clean();
		// Debug wp_die($user_email);
		$mailer->send( $order_email, $subject, $message);
	}

	/**
	 * Show or call to generate new referal ID
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public function get_referral_id($user_id) {

		if ( !$user_id ) {
			return false;
		}
		$referralID = get_user_meta($user_id, "gens_referral_id", true);
		if($referralID && $referralID != "") {
			return $referralID;
		} else {
			do{
			    $referralID = $this->generate_referral_id();
			} while ($this->exists_ref_id($referralID));
			update_user_meta( $user_id, 'gens_referral_id', $referralID );
			return $referralID;
		}

	}

	/**
	 * Check if ID already exists
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public function exists_ref_id($referralID) {

		$args = array('meta_key' => "gens_referral_id", 'meta_value' => $referralID );
		if (get_users($args)) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Generate a new Referral ID
	 *
	 * @since    1.0.0
	 * @return string
	 */
	function generate_referral_id($randomString="ref")
	{

	    $characters = "0123456789";
	    for ($i = 0; $i < 7; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}


	/**
	 * Generate a coupon for userID
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public function generate_coupons( $user_id ) {
		$user_info = get_userdata($user_id);
		$user_email = $user_info->user_email;
		$coupon_code = "RAF-".substr( md5( time() ), 19); // Code
		$amount = get_option( 'gens_raf_coupon_amount' );
		$duration = get_option( 'gens_raf_coupon_duration' );
		$individual = get_option( 'gens_raf_individual_use' );
		$discount_type = get_option( 'gens_raf_coupon_type' );
		$minimum_amount = get_option( 'gens_raf_min_order' );
		$product_ids = get_option( 'gens_raf_product_ids' );
		$exclude_product_categories = get_option( 'gens_raf_exclude_product_categories' );
		$exclude_product_categories = array_map('intval', explode(',', $exclude_product_categories));
		$product_categories = get_option( 'gens_raf_product_categories' );
		$product_categories = array_map('intval', explode(',', $product_categories));

		$coupon = array(
			'post_title' => $coupon_code,
			'post_excerpt' => 'Referral coupon for: '.$user_email,
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type'		=> 'shop_coupon'
		);
							
		$new_coupon_id = wp_insert_post( $coupon );

		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
		update_post_meta( $new_coupon_id, 'individual_use', $individual );
		update_post_meta( $new_coupon_id, 'exclude_product_categories', $exclude_product_categories );
		update_post_meta( $new_coupon_id, 'product_categories', $product_categories );
		update_post_meta( $new_coupon_id, 'product_ids', $product_ids );
		update_post_meta( $new_coupon_id, 'customer_email', $user_email );
		update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
		update_post_meta( $new_coupon_id, 'usage_limit', '1' ); // Only one coupon
		if($duration) {
			update_post_meta( $new_coupon_id, 'expiry_date', date('Y-m-d', strtotime('+'.$duration.' days')));			
		}
		update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
		update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
		update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

		if($new_coupon_id) {
			return $coupon_code;			
		} else {
			return "Error creating coupon";
		}

	}

	/**
	 * Generate a coupon for buyer if checked
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public function generate_buyer_coupon( $order_email ) {

		$coupon_code = substr( "abcdefghijklmnopqrstuvwxyz123456789", mt_rand(0, 50) , 1) .substr( md5( time() ), 1); // Code
		$amount = get_option( 'gens_raf_friend_coupon_amount' );
		$duration = get_option( 'gens_raf_friend_coupon_duration' );
		$individual = get_option( 'gens_raf_friend_individual_use' );
		$discount_type = get_option( 'gens_raf_friend_coupon_type' );
		$minimum_amount = get_option( 'gens_raf_friend_min_order' );
		$product_ids = get_option( 'gens_raf_friend_product_ids' );
		$exclude_product_categories = get_option( 'gens_raf_friend_exclude_product_categories' );
		$exclude_product_categories = array_map('intval', explode(',', $exclude_product_categories));
		$product_categories = get_option( 'gens_raf_friend_product_categories' );
		$product_categories = array_map('intval', explode(',', $product_categories));

		$coupon = array(
			'post_title' => $coupon_code,
			'post_excerpt' => 'Referral coupon for: '.$order_email,
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type'		=> 'shop_coupon'
		);
							
		$new_coupon_id = wp_insert_post( $coupon );

		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
		update_post_meta( $new_coupon_id, 'individual_use', $individual );
		update_post_meta( $new_coupon_id, 'exclude_product_categories', $exclude_product_categories );
		update_post_meta( $new_coupon_id, 'product_categories', $product_categories );
		update_post_meta( $new_coupon_id, 'product_ids', $product_ids );
		update_post_meta( $new_coupon_id, 'customer_email', $order_email );
		update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
		update_post_meta( $new_coupon_id, 'usage_limit', '1' ); // Only one coupon
		if($duration) {
			update_post_meta( $new_coupon_id, 'expiry_date', date('Y-m-d', strtotime('+'.$duration.' days')));			
		}
		update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
		update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
		update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

		if($new_coupon_id) {
			return $coupon_code;			
		} else {
			return "Error creating coupon";
		}

	}

	/**
	 * Remove Cookie after checkout if Setting is set
	 * woocommerce_thankyou hook
	 *
	 * @since    1.0.0
	 */
	public function remove_cookie_after( $order_id ) {
		$remove = get_option( 'gens_raf_cookie_remove' );
		if (isset($_COOKIE['gens_raf']) && $remove == "yes") {
		    unset($_COOKIE['gens_raf']);
		    setcookie('gens_raf', '', time() - 3600, '/'); // empty value and old timestamp
		}
	}

	/**
	 * Show Unique URL - get referral id and create link
	 * woocommerce_before_my_account hook
	 *
	 * @since    1.0.0
	 */
	public function account_page_show_link() {
		$link = get_home_url();
		$my_account_options_url = get_option( 'gens_raf_my_account_url' );
		if($my_account_options_url != "") {
			$link = $my_account_options_url;
		}
		$referral_id = $this->get_referral_id( get_current_user_id() );
		$refLink = esc_url(add_query_arg( 'raf', $referral_id, $link ));
		do_action('before_referral_url');
	?>
		<div id="gens-raf-message" class="woocommerce-message"><?php _e( 'Your Referral URL:','gens-raf'); ?> <a href="<?php echo $refLink; ?>" ><?php echo $refLink; ?></a></div>
	<?php
		do_action('after_referral_url');
	}

	/**
	 * Account page - list unused referral coupons
	 * woocommerce_before_my_account hook
	 *
	 * @since    1.0.0
	 */
	public function account_page_show_coupons() {
		$user_info = get_userdata(get_current_user_id());
		$user_email = $user_info->user_email;
		$date_format = get_option( 'date_format' );
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'shop_coupon',
		    'post_status'      => 'publish',
			'meta_query' => array (
			    array (
				  'key' => 'customer_email',
				  'value' => $user_email,
	              'compare' => 'LIKE'
			    )
			),
		);
		    
		$coupons = get_posts( $args );

		if($coupons) { ?>

			<h2><?php echo apply_filters( 'wpgens_raf_title', __( 'Unused Refer a Friend Coupons', 'gens-raf' ) ); ?></h2>
			<table class="shop_table shop_table_responsive">
				<tr>
					<th><?php _e('Coupon code','gens-raf'); ?></th>
					<th><?php _e('Coupon discount','gens-raf'); ?></th>
					<th><?php _e('Expiry date','gens-raf'); ?></th>
				</tr>
		<?php
			foreach ( $coupons as $coupon ) {
				$discount = esc_attr(get_post_meta($coupon->ID, "coupon_amount" ,true));
				$discount_type = esc_attr(get_post_meta($coupon->ID, "discount_type" ,true));
				$usage_count = esc_attr(get_post_meta($coupon->ID, "usage_count" ,true));
				$expiry_date = esc_attr(get_post_meta($coupon->ID,"expiry_date",true));
				if($expiry_date == "") {
					$expiry_date = __('No expiry date','gens-raf');
				}
				if($discount_type == "percent_product" || $discount_type == "percent") {
					$discount = $discount."%";
				}
				
				if($usage_count == 0) { // If coupon isnt used yet.
					echo '<tr>';
					echo '<td>'.$coupon->post_title.'</td>';
					echo '<td>'.$discount.'</td>';
					echo '<td>'.$expiry_date.'</td>';
					echo '</tr>';
				} 

			}
			echo '</table>';
		}
	}

	/**
	 * Register RAF link as ContactForm7 shortcode.
	 *
	 * @since    1.0.0
	 */
	public function wpcf7_gens_shortcode_handler() {

		$referral_id = $this->get_referral_id( get_current_user_id() );
		$refLink = esc_url(add_query_arg( 'raf', $referral_id, get_home_url() )); 
		return '<input type="hidden" name="gens_raf" value="'.$refLink.'" />';
	}

	/**
	 * Register RAF link as simple shortcode.
	 *
	 * @since    1.0.0
	 */
	public function main_raf_shortcode_handler($atts, $content = null) {
		extract(shortcode_atts(array(
	      'id' => get_current_user_id(),
	    ), $atts));

		$referral_id = $this->get_referral_id( $id );
		$refLink = esc_url(add_query_arg( 'raf', $referral_id, get_home_url() )); 
		return '<a href="'.$refLink.'" >'.$refLink.'</a>';
	}

	/**
	 * Register Advance shortcode.
	 *
	 * @since    1.0.0
	 */
	public function advance_raf_shortcode_handler($atts, $content = null ) {
	   extract(shortcode_atts(array(
	      'share_text' => 'Share your referral URL with friends:',
	      'guest_text' => 'Please register to get your referral link.',
	      'friends_text' => 'friends have joined.'
	   ), $atts));
		$title = get_option( 'gens_raf_twitter_title' );
		$twitter_via = get_option( 'gens_raf_twitter_via' );

		if(is_user_logged_in()) {
			$referral_id = $this->get_referral_id( get_current_user_id() );
			$refLink = esc_url(add_query_arg( 'raf', $referral_id, get_home_url() ));
			$num_friends_refered = $this->get_number_of_referrals(get_current_user_id());
			if($num_friends_refered == "") {
				$num_friends_refered = 0;
			}			
		} else {
			$num_friends_refered = 0;
			$refLink = __($guest_text,"gens-raf");
		}

		$return = "<div id='raf_advance_shortcode'>";
			$return .= '<input value="'.$refLink.'" autocomplete="off" readonly class="gens_raf_share_url" title="copy me" name="gens_raf_share">';
			$return .= '<span class="share_text">'.__($share_text,"gens-raf").'</span>';
			$return .= '<ul class="gens_raf_share">';
				$return .= '<li><a href="'.$refLink.'" title="Share on Facebook" class="gens_raf_fb" target="_blank"><i class="gens_raf_icn-facebook"></i></a></li>';
				$return .= '<li><a href="'.$refLink.'" data-via="'.$twitter_via.'" data-title="'.$title.'" title="Share on Twitter" class="gens_raf_tw" target="_blank" ><i class="gens_raf_icn-twitter"></i></a></li>';
				$return .= '<li><a href="'.$refLink.'" title="Share on Google Plus" class="gens_raf_gplus" target="_blank"><i class="gens_raf_icn-gplus"></i></a></li>';
				$return .= '<li><a href="'.$refLink.'" title="Share via Email" class="gens_raf_email" data-bodytext="" data-title="'.$title.'" target="_blank"><i class="gens_raf_icn-email"></i></a></li>';
				$return .= '<li class="rafwhatsapp"><a href="'.$refLink.'" title="Share via WhatsApp" class="gens_raf_whatsapp" data-title="'.$title.'" target="_blank"><i class="gens_raf_icn-whatsapp"></i></a></li>';
			$return .= '</ul>';
			if(is_user_logged_in()) {
				$return .= '<span class="gens_num_friends">'.$num_friends_refered.' '.__($friends_text,"gens-raf").'</span>';
			}
		$return .= '</div>';
		return $return;
	}

	public function raf_tab($tabs) {

		$tabs_hide = get_option( 'gens_raf_tabs_disable' );

		if($tabs_hide != "yes") {
			$tabs['refer_tab'] = array(
		        'title'     => __( 'Refer to a friend', 'gens-raf' ),
		        'priority'  => 40,
		        'callback'  => array($this,'raf_tab_content')
		    );			
		}


	    return $tabs;
	}

	public function raf_tab_content() {

		$share_text = __(get_option( 'gens_raf_share_text' ),'gens-raf');
	    $guest_text = __(get_option( 'gens_raf_guest_text' ),'gens-raf');
	    $friends_text = __(get_option( 'gens_raf_friends_text' ),'gens-raf');
		$title = get_option( 'gens_raf_twitter_title' );
		$twitter_via = get_option( 'gens_raf_twitter_via' );
		global $wp;

		if(is_user_logged_in()) {
			$referral_id = $this->get_referral_id( get_current_user_id() );
			$refLink = esc_url(home_url(add_query_arg(array('raf' => $referral_id),$wp->request)));
			$refText = esc_url(home_url(add_query_arg(array('raf' => $referral_id),$wp->request)));
			$num_friends_refered = $this->get_number_of_referrals(get_current_user_id());		
			if($num_friends_refered == "") {
				$num_friends_refered = 0;
			}		
		} else {
			$num_friends_refered = 0;
			$refLink = esc_url(home_url(add_query_arg(array(),$wp->request)));
			$refText = __($guest_text,"gens-raf");
		}
		$return = "";
		$return = apply_filters('gens_tab_filter_before', $return);
		$return .= "<div id='raf_advance_shortcode'>";
			$return .= '<input value="'.$refText.'" autocomplete="off" readonly class="gens_raf_share_url" title="copy me" name="gens_raf_share">';
			$return .= '<span class="share_text">'.__($share_text,"gens-raf").'</span>';
			$return .= '<ul class="gens_raf_share">';
				$return .= '<li><a href="'.$refLink.'" title="Share on Facebook" class="gens_raf_fb" target="_blank"><i class="gens_raf_icn-facebook"></i></a></li>';
				$return .= '<li><a href="'.$refLink.'" data-via="'.$twitter_via.'" data-title="'.$title.'" title="Share on Twitter" class="gens_raf_tw" target="_blank" ><i class="gens_raf_icn-twitter"></i></a></li>';
				$return .= '<li><a href="'.$refLink.'" title="Share on Google Plus" class="gens_raf_gplus" target="_blank"><i class="gens_raf_icn-gplus"></i></a></li>';
				$return .= '<li><a href="'.$refLink.'" title="Share via Email" class="gens_raf_email" data-bodytext="" data-title="'.$title.'" target="_blank"><i class="gens_raf_icn-email"></i></a></li>';
				$return .= '<li class="rafwhatsapp"><a href="'.$refLink.'" title="Share via WhatsApp" class="gens_raf_whatsapp" data-title="'.$title.'" target="_blank"><i class="gens_raf_icn-whatsapp"></i></a></li>';
			$return .= '</ul>';
			if(is_user_logged_in()) {
				$return .= '<span class="gens_num_friends">'.$num_friends_refered.' '.__($friends_text,"gens-raf").'</span>';
			}
		$return .= '</div>';

		$return = apply_filters('gens_tab_filter_after', $return);

		echo $return;
	}


	public function show_admin_raf_notes($order) { 

		$referralID = esc_attr(get_post_meta( $order->id, '_raf_id', true ));

		if (!empty($referralID)) {
		
			$args = array('meta_key' => "gens_referral_id", 'meta_value' => $referralID );
			$user = get_users($args);
			?>
		    <div class="form-field form-field-wide">
		        <h4><?php _e( 'Referral details:', 'gens-raf' ); ?></h4>
		        <p><strong><?php _e( 'Referred by user:','gens-raf' ); ?></strong> <a href="<?php echo get_edit_user_link($user[0]->id); ?>"><?php echo $user[0]->user_email; ?></a></p>
		    </div>
    <?php
    	}
	}

	/**
	 * Woocommerce tools button to add missing referrals.
	 *
	 * @since    1.1.0
	 */
	public function add_missing_referrals_button($old) {
		$new = array(
			'gens_add_missing_referrals' => array(
				'name'    => __( 'Refer a friend - Create missing referral links.', 'gens-raf' ),
				'button'  => __( 'Create referrals', 'gens-raf' ),
				'desc'    => __( 'This tool will add missing referrals to your site prior to users clicking on my account page. Useful if you want to inform users about their referral link, before it was autogenerated by them visiting page with referral link. ', 'gens-raf' ),
				'callback' => array($this,'gens_add_missing_referrals')
			),
		);
		$tools = array_merge( $old, $new );
		return $tools;
	}
	
	/**
	 * Callback function for Woocommerce tools button to add missing referrals.
	 *
	 * @since    1.1.0
	 */
	function gens_add_missing_referrals() {
		$gens_user_query = new WP_User_Query(array( 'meta_key' => 'gens_referral_id', 'meta_compare' => 'NOT EXISTS' ));
		$users = $gens_user_query->get_results();
		foreach ($users as $user) {
			$this->get_referral_id($user->ID);
		}
		echo '<div class="updated inline"><p>' . count($users) . __( ' missing referral codes have been created', 'gens-raf' ) . '</p></div>';
	}

	/**
	 * Create referral code on new user registration, in case someone needs meta fields for mailchimp and such.
	 *
	 * @since    1.1.0
	 */
	function gens_new_user_registration_hook( $user_id ) {
		$this->get_referral_id($user_id);
	}


	/**
	 * Register the scripts for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->gens_raf.'_cookieJS', plugin_dir_url( __FILE__ ) . 'js/cookie.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->gens_raf, plugin_dir_url( __FILE__ ) . 'js/gens-raf-public.js', array( 'jquery' ), $this->version, false );
		$time = get_option( 'gens_raf_cookie_time' );
		$cookies = array( 'timee' => $time );
		wp_localize_script( $this->gens_raf, 'gens_raf', $cookies );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->gens_raf, plugin_dir_url( __FILE__ ) . 'css/gens-raf.css', array(), $this->version, 'all' );

	}

	/**
	 * Auto apply coupons at the cart page for referred person, if chosen.
	 *
	 * @since    1.1.0
	 */
	public  function apply_matched_coupons() {
		global $woocommerce;

		$not_active         = get_option( 'gens_raf_disable' );
		$guest_coupon_stats = get_option( 'gens_raf_guest_enable' );
		$guest_coupon_code  = get_option( 'gens_raf_guest_coupon_code' );
		$guest_coupon_msg   = get_option( 'gens_raf_guest_coupon_msg' );
		// disallow users buying for them self !!!
		$user_id = 0;
		if(isset($_COOKIE["gens_raf"])) {
			$gens_users = get_users( array(
				"meta_key" => "gens_referral_id",
				"meta_value" => $_COOKIE["gens_raf"],
				"number" => 1,
				"fields" => "ID"
			) );
			$user_id = (int)$gens_users[0];
		}

		// if some coupon is already applied, dont do anything.
		if ( get_current_user_id() !== $user_id && empty($woocommerce->cart->applied_coupons) && !empty($guest_coupon_code) && isset($_COOKIE["gens_raf"]) && $not_active != "yes" && $woocommerce->cart->cart_contents_count >= 1 && $guest_coupon_stats == "yes" ) {
			$woocommerce->cart->add_discount( $guest_coupon_code );
			wc_add_notice($guest_coupon_msg);
			wc_print_notices();
	    }
	}

	/**
	 * Remove coupon if user wants to abuse it by adding it as a guest then logging in at the checkout.
	 *
	 * @since    1.1.0
	 */
	public function checkout_form_check() {
		global $woocommerce;
		$user_id = 0;
		$guest_coupon_code  = get_option( 'gens_raf_guest_coupon_code' );
		if(isset($_COOKIE["gens_raf"])) {
			$gens_users = get_users( array(
				"meta_key" => "gens_referral_id",
				"meta_value" => $_COOKIE["gens_raf"],
				"number" => 1,
				"fields" => "ID"
			) );
			$user_id = (int)$gens_users[0];
		}

		if ( get_current_user_id() === $user_id && $woocommerce->cart->has_discount($guest_coupon_code) ) {
			$woocommerce->cart->remove_coupon( $guest_coupon_code );
			wc_print_notices();
		}

	}

}
