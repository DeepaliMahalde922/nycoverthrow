<?php
/**
 * Plugin Name: Stripe Connect
 * Plugin URI: http://store.webkul.com/Wordpress-Plugins.html
 * Description: Wordpress WooCommerce Marketplace Stripe-Connect plugin.
 * Version: 1.0.1
 * Author: Webkul
 * Author URI: http://webkul.com
 * Domain Path: plugins/woocommerce-marketplace-stripe-connect
 * Network: true
 * License: GNU/GPL for more info see license.txt included with plugin
 * License URI: http://www.gnu.org/licenseses/gpl-2.0.html
**/

add_action( 'plugins_loaded', 'woocommerce_stripe_connect_init', 0 );

function woocommerce_stripe_connect_init() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {

    	return;
	}

define( 'PLUGIN_DIR', plugin_dir_url(__FILE__));


include_once 'assets/lib/stripe/init.php';
/**
* Stripe Connect Gateway Class
*/
class WC_Stripe_Connect extends WC_Payment_Gateway {

	function __construct() {

    	// Register plugin information
      	$this->id			    = 'stripe-connect';
      	$this->has_fields = true;
      	$this->supports   = array(
           'products',
           'subscriptions',
           'subscription_cancellation',
           'subscription_suspension',
           'subscription_reactivation',
           'subscription_date_changes',
        );

    	// Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();

		// Get setting values
		foreach ( $this->settings as $key => $val ) $this->$key = $val;

    	// Load plugin checkout icon
      	$this->icon = PLUGIN_DIR.'/payment-stripe.png';

    	// Add hooks

		add_action( 'woocommerce_receipt_stripe', array( $this, 'receipt_page' ) );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'add_stripe_scripts' ) );

		add_action('woocommerce_review_order_before_submit',array($this,'stripe_woocommerce_before_submit'));

		add_action('marketplace_payment_gateway', array($this, 'stripe_payment_details'));

		add_action('marketplace_save_seller_payment_details', array($this, 'save_stripe_seller_payment_details'));

	}
    /**
    * Check if SSL is enabled and notify the user.
    */
	function stripe_payment_ssl_check() {

		if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'no' && $this->enabled == 'yes' ) {

			echo '<div class="error"><p>' . sprintf( __('Stripe Connect is enabled and the <a href="%s">force SSL option</a> is disabled; your checkout is not secure! Please enable SSL and ensure your server has a valid SSL certificate.', 'woothemes' ), admin_url( 'admin.php?page=woocommerce' ) ) . '</p></div>';

		}

	}

   /**
   * Initialize Gateway Settings Form Fields.
   */
    function init_form_fields() {
      	$this->form_fields = array(
	      	'enabled'     => array(
	        'title'       => __( 'Enable/Disable', 'woothemes' ),
	        'label'       => __( 'Enable Stripe Connect', 'woothemes' ),
	        'type'        => 'checkbox',
	        'description' => '',
	        'default'     => 'no'
	        ),
	      	'title'       => array(
	        'title'       => __( 'Title', 'woothemes' ),
	        'type'        => 'text',
	        'custom_attributes'        => array('readonly'=>'readonly'),
	        'description' => __( 'This controls the title which the user sees during checkout.', 'woothemes' ),
	        'default'     => __( 'Credit Card (Stripe Connect)', 'woothemes' )
	        ),
	      	'description' => array(
	        'title'       => __( 'Description', 'woothemes' ),
	        'type'        => 'textarea',
	        'description' => __( 'This controls the description which the user sees during checkout.', 'woothemes' ),
	        'default'     => 'Pay with your credit card via Stripe Connect.'
	        ),
	      	'stripe_payment_mode'     => array(
	        'title'       => __( 'Test/Live', 'woothemes' ),
	        'label'       => __( 'Stripe payment Mode Enable "Test" Disable "Live"', 'woothemes' ),
	        'type'        => 'checkbox',
	        'description' => '',
	        'default'     => 'yes'
	        ),
	      	'stripe_test_client_id'     => array(
	        'title'       => __( 'Stripe Connect Test client_id', 'woothemes' ),
	        'label'       => __( 'Test client_id', 'woothemes' ),
	        'type'        => 'text',
	        'description' => 'Enter Stripe Connect Test Client Id ',
	        'default'     => ''
	        ),
	      	'stripe_live_client_id'     => array(
	        'title'       => __( 'Stripe Connect Live client_id', 'woothemes' ),
	        'label'       => __( 'Live client_id', 'woothemes' ),
	        'type'        => 'text',
	        'description' => 'Enter Stripe Connect Live Client Id ',
	        'default'     => ''
	        ),
	      	'stripe_test_secret_key'    => array(
	        'title'       => __( 'Stripe Test Secret Key', 'woothemes' ),
	        'type'        => 'text',
	        'description' => __( 'This is the API Stripe Test Secret Key generated within the Stripe Payment gateway.', 'woothemes' ),
	        'default'     => ''
	        ),
	      	'stripe_test_publishable_key'    => array(
	        'title'       => __( 'Stripe Test Publishable Key', 'woothemes' ),
	        'type'        => 'text',
	        'description' => __( 'This is the API Stripe Test Publishable Key generated within the Stripe Payment gateway.', 'woothemes' ),
	        'default'     => ''
	        ),
	      	'stripe_live_secret_key'    => array(
	        'title'       => __( 'Stripe Live Secret Key', 'woothemes' ),
	        'type'        => 'text',
	        'description' => __( 'This is the API Stripe Live Secret Key generated within the Stripe Payment gateway.', 'woothemes' ),
	        'default'     => ''
	        ),
	      	'stripe_live_publishable_key'    => array(
	        'title'       => __( 'Stripe Live Publishable Key', 'woothemes' ),
	        'type'        => 'text',
	        'description' => __( 'This is the API Stripe Live Publishable Key generated within the Stripe Payment gateway.', 'woothemes' ),
	        'default'     => ''
	        ),
	      	'salemethod'  => array(
	        'title'       => __( 'Sale Method', 'woothemes' ),
	        'type'        => 'select',
	        'description' => __( 'Select which sale method to use. Authorize Only will authorize the customers card for the purchase amount only.  Authorize &amp; Capture will authorize the customer\'s card and collect funds.', 'woothemes' ),
	        'options'     => array(
	          'sale' => 'Authorize &amp; Capture',
	          'auth' => 'Authorize Only'
	          ),
	        'default'     => 'Authorize &amp; Capture'
	        ),
	      	'cardtypes'   => array(
	        'title'       => __( 'Accepted Cards', 'woothemes' ),
	        'type'        => 'multiselect',
	        'description' => __( 'Select which card types to accept.', 'woothemes' ),
	        'default'     => '',
	        'options'     => array(
	          'MasterCard'	      => 'MasterCard',
	          'Visa'			        => 'Visa',
	          'Discover'		      => 'Discover',
	          'American Express'  => 'American Express'
	          ),
	        ),
	      	'cvv'         => array(
	        'title'       => __( 'CVV', 'woothemes' ),
	        'type'        => 'checkbox',
	        'label'       => __( 'Require customer to enter credit card CVV code', 'woothemes' ),
	        'description' => __( '', 'woothemes' ),
	        'default'     => 'yes'
	        ),
	      	'saveinfo'    => array(
	        'title'       => __( 'Billing Information Storage', 'woothemes' ),
	        'type'        => 'checkbox',
	        'label'       => __( 'Allow customers to save billing information for future use (requires Stripe Payment Customer Vault)', 'woothemes' ),
	        'description' => __( '', 'woothemes' ),
	        'default'     => 'no'
	        ),
		);
	}


  	/**
   	* UI - Admin Panel Options
   	*/
	function admin_options() {  ?>
		<h3><?php _e( 'Stripe Payment','woothemes' ); ?></h3>
	    <p><?php _e( 'The Stripe Payment Gateway is simple and powerful.  The plugin works by adding credit card fields on the checkout page, and then sending the details to Stripe Payment for verification.  <a href="https://stripe.com/">Click here to get paid like the pros</a>.', 'woothemes' ); ?></p>
	    <table class="form-table">
			<?php $this->generate_settings_html(); ?>
		</table>
	<?php }

  	/**
   	* UI - Payment page fields for Stripe Payment.
   	*/
	function payment_fields()  {         		// Description of payment method from settings
  		if ( $this->description ) { ?>
    		<p><?php echo $this->description; ?></p>
		<?php } ?>
		<fieldset  style="padding-left: 40px;">
        <?php
          	$user = wp_get_current_user();
          	//$this->check_payment_method_conversion( $user->user_login, $user->ID );
          	if ( $this->user_has_stored_data( $user->ID ) ) { ?>
				<fieldset>
					<input type="radio" name="stripe-use-stored-payment-info" id="stripe-use-stored-payment-info-yes" value="yes" checked="checked" onclick="document.getElementById('stripe-new-info').style.display='none'; document.getElementById('stripe-stored-info').style.display='block'"; />

					<label for="stripe-use-stored-payment-info-yes" style="display: inline;"><?php _e( 'Use a stored credit card', 'woocommerce' ) ?></label>

					<div id="stripe-stored-info" style="padding: 10px 0 0 40px; clear: both;">

				    <?php
				        $i = 0;
				        $method = $this->get_payment_method( $i );
				        while( $method != null ) {
				        ?>
		                    <p>
		              		<input type="radio" name="stripe-payment-method" id="<?php echo $i; ?>" value="<?php echo $i; ?>" /> &nbsp;
							<?php echo $method->cc_number; ?> (<?php
                        	$exp = $method->cc_exp;
		                    echo substr( $exp, 0, 2 ) . '/' . substr( $exp, -2 );
		              		?>)
							<br/>
		                    </p>
		          			<?php
		                  		$method = $this->get_payment_method( ++$i );
		                  	} ?>
				</fieldset>
				<fieldset>
					<p>
					<input type="radio" name="stripe-use-stored-payment-info" id="stripe-use-stored-payment-info-no" value="no" onclick="document.getElementById('stripe-stored-info').style.display='none'; document.getElementById('stripe-new-info').style.display='block'"; />

              		<label for="stripe-use-stored-payment-info-no"  style="display: inline;"><?php _e( 'Use a new payment method', 'woocommerce' ) ?></label>
                	</p>

                	<div id="stripe-new-info" style="display:none">
				</fieldset>
		<?php } else { ?>
      			<fieldset>
      				<!-- Show input boxes for new data -->
      				<div id="stripe-new-info">
      					<?php } ?>
						<!-- Credit card number -->
            			<p class="form-row form-row-first">
							<label for="ccnum"><?php echo __( 'Credit Card number', 'woocommerce' ) ?> <span class="required">*</span></label>
							<input type="text" name="ccnum" data-stripe="number" class="input-text" id="ccnum" maxlength="16" />
            			</p>
						<!-- Credit card type -->
            			<p class="form-row form-row-last">
              				<label for="cardtype"><?php echo __( 'Card type', 'woocommerce' ) ?> <span class="required">*</span></label>
              				<select name="cardtype" id="cardtype" class="woocommerce-select">
          						<?php  foreach( $this->cardtypes as $type ) { ?>
                    				<option value="<?php echo $type ?>"><?php _e( $type, 'woocommerce' ); ?></option>
          						<?php } ?>
               				</select>
            			</p>
						<div class="clear"></div>
						<!-- Credit card expiration -->
            			<p class="form-row form-row-first">
              				<label for="cc-expire-month"><?php echo __( 'Expiration date', 'woocommerce') ?> <span class="required">*</span></label>
              				<select id="expmonth" name="expmonth" class="woocommerce-select woocommerce-cc-month">
                				<option value=""><?php _e( 'Month', 'woocommerce' ) ?></option><?php
		                        $months = array();
		                        for ( $i = 1; $i <= 12; $i ++ ) {
		                          $timestamp = mktime( 0, 0, 0, $i, 1 );
		                          $months[ date( 'n', $timestamp ) ] = date( 'F', $timestamp );
		                        }
		                        foreach ( $months as $num => $name ) {
		                          printf( '<option value="%u">%s</option>', $num, $name );
		                        } ?>
              				</select>
              				<input type="hidden" size="2"  data-stripe="exp-month" id="stripe-data-exp-month">
              				<select id="expyear" name="expyear" class="woocommerce-select woocommerce-cc-year">
                				<option value=""><?php _e( 'Year', 'woocommerce' ) ?></option><?php
		                        $years = array();
		                        for ( $i = date( 'y' ); $i <= date( 'y' ) + 15; $i ++ ) {
		                          printf( '<option value="20%u">20%u</option>', $i, $i );
		                        } ?>
              				</select>
              				 <input type="hidden" size="4" data-stripe="exp-year" id="stripe-data-exp-year"/>
            			</p>
						<?php
		                    // Credit card security code
		                    if ( $this->cvv == 'yes' ) { ?>
		                      	<p class="form-row form-row-last">
		                        	<label for="cvv"><?php _e( 'Card security code', 'woocommerce' ) ?> <span class="required">*</span></label>
		                        	<input  type="text" name="cvv" class="input-text" id="cvv" maxlength="4" style="width:45px" data-stripe="cvc" />
		                        	<span class="help"><?php _e( '3 or 4 digits usually found on the signature strip.', 'woocommerce' ) ?></span>
		                      	</p><?php
		                    }
		            	?>

		            	<?php
	                    // Option to store credit card data
	                    if ( $this->saveinfo == 'yes' && ! ( class_exists( 'WC_Subscriptions_Cart' ) && WC_Subscriptions_Cart::cart_contains_subscription() ) ) { ?>
	                      	<div style="clear: both;"></div>
							<p>
                        		<label for="saveinfo"><?php _e( 'Save this billing method?', 'woocommerce' ) ?></label>
                        		<input type="checkbox" class="input-checkbox" id="saveinfo" name="saveinfo" />
                        		<span class="help"><?php _e( 'Select to store your billing information for future use.', 'woocommerce' ) ?></span>
                      		</p>
						<?php  } ?>
						<input type="hidden" id="stripe_response_token" name="stripe_response_token" value="token">
						<input type="hidden" id="stripe_response_error" name="stripe_response_error" value="">
    			</fieldset>
    	</fieldset>
	<?php
	}

	/**
	 * Process the payment and return the result.
	 */
	function process_payment( $order_id ) {

		global $woocommerce;
		global $wpdb;
		$str = '';
		$all_charges = array();
		$all_user_list = array();

		$order = new WC_Order( $order_id );
    $user = new WP_User( $order->user_id );
      	//$this->check_payment_method_conversion( $user->user_login, $user->ID );

		// Convert CC expiration date from (M)M-YYYY to MMYY
		$expmonth = $this->get_post( 'expmonth' );

		if ( $expmonth < 10 ) $expmonth = '0' . $expmonth;

		if ( $this->get_post( 'expyear' ) != null ) $expyear = substr( $this->get_post( 'expyear' ), -2 );

    // Send request and get response from server

    $stripe_payment_type = $this->stripe_payment_mode;

		if( $stripe_payment_type == 'yes' ) {
			\Stripe\Stripe::setApiKey( $this->stripe_test_secret_key );
		}
		else
		{
			\Stripe\Stripe::setApiKey( $this->stripe_live_secret_key );
		}
		try {
			/* part 1 start :find current order items and their price and store if stripe connect is selected */
			$get_item=$order->get_items();
			$order_item_meta=array();
			$admin_item_meta=array();
			$user_list=array();
			$user_list2=array();
			$none_stripe_user=array();
			foreach ($get_item as $key => $value) {

				
				$price_id='';
				if( $value->get_data()['variation_id'] != 0 ) {
					$price_id = $value->get_data()['product_id'];
					/*Following commented code not working for Variable Product*/
					//$price_id = $value->get_data()['variation_id'];
				}else{
					$price_id = $value->get_data()['product_id'];
				}
				$product = new WC_Product( $price_id );

				$product_price = $product->get_price();
				$post_id = $value->get_data()['product_id'];
				$post = get_post( $post_id );
				$user_id = $post->post_author;
				$qty = $value->get_data()['quantity'];
				$name = $value->get_data()['name'];
				$payment_method = get_user_meta( $user_id, 'mp_seller_payment_method', true );
				if( $payment_method == 'Credit Card (Stripe Connect)' ) {
					$description_info = get_user_meta($user_id,'stripe_user_id',true);
					$order_item_meta[] = array('name'=>$name,'qty'=>$qty,'price_id' => $price_id,'user_id'=>$user_id,'product_price' => $product_price, 'payment_method' => $payment_method,'description_info'=>$description_info);
					if( !in_array( $user_id, $user_list ) ) {
						$user_list[] = $user_id;
					}
					$all_user_list[] = array('seller_id' => $user_id,'product_price' => $product_price,'type' => 'stripe_connect', 'qty' => $qty );
				}
				else {
					$total_item_price = $qty*$product_price;
					if( isset( $admin_item_meta ) ) {
						$total_price = $admin_item_meta['total_price'];
						$total_price = $total_price+$total_item_price;
					}
					else {
						$total_price = $total_item_price;
					}/*add_action('woocommerce_proceed_to_checkout*/
					$admin_item_meta = array('total_price'=>$total_price);
					$all_user_list[] = array('seller_id'=>$user_id,'product_price'=>$product_price,'type'=>'direct');
					if( !in_array( $user_id, $user_list2 ) ) {
						$user_list2[] = $user_id;
					}
				}
			}
			/*:part 1 end;*/
			$stripe_card_num 			= $this->get_post( 'ccnum' );
			$stripe_cvc				= $this->get_post( 'cvv' );
			$stripe_exp_month			= $expmonth ;
			$stripe_exp_year  		= $expyear;
			$stripe_name  			= $order->billing_first_name.' '. $order->billing_last_name;
			$stripe_address_city 	    = $order->billing_city;
			$stripe_address_state 	= $order->billing_state;
			$stripe_address_zip		= $order->billing_postcode;
			$stripe_address_country 	= $order->billing_country;
			$stripe_billing_phone		= $order->billing_phone;
			$stripe_email       		= $order->billing_email;
			$stripe_order_id 			= $order_id;
			$stripe_amt 				= $order->order_total;

			$error=$this->get_post('stripe_response_error');
			if($error){
				$order->add_order_note( __( 'Stripe payment failed. Payment declined.', 'woocommerce' ) );
				wc_add_notice( __( $error, 'woocommerce' ) );
			}
			$stripe_currency=get_woocommerce_currency(get_option('woocommerce_currency'));

		/*part 2 start :calculate total price of seller with stripe connect access token */
		$price=array();
		$all_user_price_list=array();
		$mpcommision_table_data=array();
		if($order_item_meta){
			for ($i=0; $i < count($user_list); $i++) {
				$seller_id=$user_list[$i];
				foreach ($order_item_meta as $key => $value) {
					$item_total_price=$value['product_price']*$value['qty'];
					$description_info=$value['description_info'];
					if($seller_id==$value['user_id']){
						if(isset($all_user_price_list[$seller_id])){
							$total_price=$all_user_price_list[$seller_id]['total_price']+$item_total_price;
							$all_user_price_list[$seller_id]=array('total_price'=>$total_price,'description_info'=>$description_info);
						}else{
							$total_price=$item_total_price;
							$all_user_price_list[$seller_id]=array('total_price'=>$total_price,'description_info'=>$description_info);
						}
					}
				}
			}
				/*part 2 end*/
			foreach ($all_user_price_list as $key => $value) {
				$user_id=$key;
				$commision=$wpdb->get_row("select * from {$wpdb->prefix}mpcommision where seller_id='".$user_id."'");
				$total_price=$value['total_price'];
				$commision_on_seller=$commision->commision_on_seller;
				$commision_for_admin=(($total_price*$commision_on_seller)/100);
				$price_after_commision=$total_price-$commision_for_admin;
				$admin_amount=$commision->admin_amount;
				$admin_amount=$admin_amount+$commision_for_admin;
				$seller_total_ammount=$commision->seller_total_ammount;
				$seller_total_ammount=$seller_total_ammount+$price_after_commision;
				$paid_amount=$price_after_commision;
				$last_paid_ammount=$commision->last_paid_ammount;
				$last_com_on_total=$total_price;
				$description_info=$value['description_info'];
				$price[]=array('seller_id'=>$user_id,'access_token'=>$description_info,'total_price'=>$total_price,'commision_for_admin'=>$commision_for_admin,'price_after_commision'=>$price_after_commision);
				$all_info_for_commision[$user_id]=array('admin_amount'=>$admin_amount,'seller_total_ammount'=>$seller_total_ammount,'paid_amount'=>$paid_amount,'last_com_on_total'=>$last_com_on_total);
			}
			$token=$this->get_post('stripe_response_token');
			$charge=array();
			foreach ($price as $key => $value) {
				$user=get_user_by('id',$value['seller_id']);
				$seller=get_user_meta($value['seller_id'],'stripe_user_id',true);
				$charge=\Stripe\Charge::create(array(
				  "amount" => $value['price_after_commision']*100,
				  "currency" => $stripe_currency,
				  "source" => $token, // obtained with Stripe.js
				  "description" => "test-for-stripe-connect Charge for ".$user->user_email,
				  "application_fee"=> $value['commision_for_admin']*100
				  ),
				   array("stripe_account" => $seller)
				);
				$all_charges[]=$charge;
			}
		}
		/*stripe charge for total price remaining into admin's account who have not connected with stripe connect with admin*/
			if($admin_item_meta){
				/*admin's end*/
				/*calculate price and commision for non stripe sellers*/
				for ($i=0; $i < count($user_list2); $i++) {
					$seller_id=$user_list2[$i];
					foreach ($all_user_list as $key => $value) {
						$item_total_price=$value['qty']*$value['product_price'];
						if($seller_id==$value['seller_id']){
							if(isset($none_stripe_user[$seller_id])){
								$total_price=$none_stripe_user[$seller_id]['total_price']+$item_total_price;
								$none_stripe_user[$seller_id]=array('total_price'=>$total_price);
							}else{
								$total_price=$item_total_price;
								$none_stripe_user[$seller_id]=array('total_price'=>$total_price);
							}
						}
					}
				}
			foreach ($none_stripe_user as $key => $value) {
				$user_id=$key;
				$commision=$wpdb->get_row("select * from {$wpdb->prefix}mpcommision where seller_id='".$user_id."'");
				$total_price=$value['total_price'];
				$commision_on_seller=$commision->commision_on_seller;
				$commision_for_admin=(($total_price*$commision_on_seller)/100);
				$price_after_commision=$total_price-$commision_for_admin;
				$admin_amount=$commision->admin_amount;
				$admin_amount=$admin_amount+$commision_for_admin;
				$seller_total_ammount=$commision->seller_total_ammount;
				$seller_total_ammount=$seller_total_ammount+$price_after_commision;
				$last_paid_ammount=$price_after_commision;
				$all_info_for_commision[$user_id]=array('admin_amount'=>$admin_amount,'seller_total_ammount'=>$seller_total_ammount,'paid_amount'=>'','last_com_on_total'=>'');
			}
			/*admin's end*/

			/** 
			 * Code Added by TechInfini
			 * Alter for support split payment
			 * Reference URL: https://stripe.com/docs/connect/destination-charges
			 */
			if($order_id){
				$items = $order->get_items();
				$amt_bk = '';
				foreach ( $items as $item ) {
					$bk_product = get_post_meta($item['product_id'], '_bk-product_pro', true);
					if( $bk_product == 1 ) {
						$amt_bk = $amt_bk + $item['total'];
					}
				}	
			}
			$total_price_order = $order->get_total();

			if($amt_bk){
				$charge_data =	array(
					'amount'	=> $total_price_order*100,
					'currency'	=> $stripe_currency,
					'card' 		=> array(
						'number'			=>	$stripe_card_num,
						'exp_month'			=>	$stripe_exp_month,
						'exp_year'			=>	$stripe_exp_year,
						'cvc'				=>	$stripe_cvc,
						'name'				=>	$stripe_name,
						'address_line1'		=>	$stripe_address_line1,
						'address_city'		=>	$stripe_address_city,
						'address_zip'		=>	$stripe_address_zip,
						'address_state'		=>	$stripe_address_state,
						'address_country'	=>	$stripe_address_country,
					),
					"destination" => array(
					  "amount" => $amt_bk*100,
					  "account" => "acct_1AZknpC9VD9hBvqg",
					),
					'description'	=>	sprintf('#%s, %s', $stripe_order_id, $stripe_email)
				);
			}else{
				$charge_data =	array(
					'amount'	=> $total_price_order*100,
					'currency'	=> $stripe_currency,
					'card' 		=> array(
						'number'			=>	$stripe_card_num,
						'exp_month'			=>	$stripe_exp_month,
						'exp_year'			=>	$stripe_exp_year,
						'cvc'				=>	$stripe_cvc,
						'name'				=>	$stripe_name,
						'address_line1'		=>	$stripe_address_line1,
						'address_city'		=>	$stripe_address_city,
						'address_zip'		=>	$stripe_address_zip,
						'address_state'		=>	$stripe_address_state,
						'address_country'	=>	$stripe_address_country,
					),
					'description'	=>	sprintf('#%s, %s', $stripe_order_id, $stripe_email)
				);
			}
			/*= Ended Code Added by Infini */
				

				
				$response_charge = \Stripe\Charge::create($charge_data);
				$all_charges[]=$response_charge;
			}
			$response=array();
			for ($j=0; $j < count($all_charges); $j++) {
				$response_charge=$all_charges[$j];
				if($response_charge){
					$dbValues['stripe_response_transaction_id']= $response_charge->id;
					$dbValues['stripe_response_invoice'] = $response_charge->invoice;
					$dbValues['stripe_response_amount'] = $response_charge->amount;
					$dbValues['stripe_response_currency'] = $response_charge->currency;
					$dbValues['stripe_response_livemode'] = $response_charge->livemode;
					$dbValues['stripe_response_card_number'] = $stripe_card_num;
					$dbValues['stripe_response_last4'] = $response_charge->card->last4;
					$dbValues['stripe_response_brand'] = $response_charge->card->brand;
					$dbValues['stripe_response_type'] = $response_charge->card->type;
					$dbValues['stripe_response_funding'] = $response_charge->card->funding;
					$dbValues['stripe_response_exp_month'] = $response_charge->card->exp_month;
					$dbValues['stripe_response_exp_year'] = $response_charge->card->exp_year;
					$dbValues['stripe_response_fingerprint'] = $response_charge->card->fingerprint;
					$dbValues['stripe_response_country'] = $response_charge->card->country;
					$dbValues['stripe_response_name'] = $response_charge->card->name;
					$dbValues['stripe_response_address_line1'] = $response_charge->card->address_line1;
					$dbValues['stripe_response_address_city'] = $response_charge->card->address_city;
					$dbValues['stripe_response_address_state'] = $response_charge->card->address_state;
					$dbValues['stripe_response_address_zip'] = $response_charge->card->address_zip;
					$dbValues['stripe_response_address_country'] = $response_charge->card->address_country;
					$dbValues['stripe_response_cvc_check'] = $response_charge->card->cvc_check;
					$dbValues['stripe_response_address_line1_check'] = $response_charge->card->address_line1_check;
					$dbValues['stripe_response_address_zip_check'] = $response_charge->card->address_zip_check;
					$response[] = $dbValues;
				}
				else
				{
					$html_error_time_transaction = 'Stripe Failure Transaction.';
				}
			}
		} catch (\Stripe\Error $e) {
			$html_error_transaction  = $error_message= $e->getMessage().'it is error';
		}
		if($response){
			$error='';
			for ($j=0; $j < count($all_charges); $j++) {
			  	$response_charge=$all_charges[$j];
			  	if ($response_charge->paid != true) {
				  	$error='stripe payment failed. Payment Declined ';
				}
			}
			if(!$error){
			  	foreach ($all_info_for_commision as $key => $value) {
					$seller_id=$key;
					$admin_amount=$value['admin_amount'];
					$seller_total_ammount=$value['seller_total_ammount'];
					$last_paid_ammount=$value['last_paid_ammount'];
					if($value['paid_amount']!=''){
						$paid_amount=$value['paid_amount'];
						$last_com_on_total=$value['last_com_on_total'];
						$sql="UPDATE {$wpdb->prefix}mpcommision SET admin_amount='".$admin_amount."' , seller_total_ammount='$seller_total_ammount' , last_paid_ammount = '$last_paid_ammount' , paid_amount = '$paid_amount' , last_com_on_total = '$last_com_on_total' WHERE seller_id = '$seller_id' ";
					}else{
						$sql="UPDATE {$wpdb->prefix}mpcommision SET admin_amount= '$admin_amount' , seller_total_ammount = '$seller_total_ammount' , last_paid_ammount = '$last_paid_ammount' WHERE seller_id = '$seller_id' ";
					}
					$wpdb->query($sql);
				}
			}

			if($error){
			  	$order->add_order_note( __( 'Stripe payment failed. Payment declined.', 'woocommerce' ) );
				wc_add_notice( __( 'Sorry, the transaction was declined.'.$str, 'woocommerce' ) );

			}else{

			  	$order->add_order_note( __( ' Stripe payment completed. ' , 'woocommerce' ));
				$order->payment_complete();
				return array (
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);

			}
		}
		else{
			$order->add_order_note( __( 'Stripe payment failed. Payment declined. Please Check your Admin settings', 'woocommerce' ) );
			wc_add_notice( __( 'Sorry, the transaction was declined. Please Check your Admin settings', 'woocommerce' ) );
		}
	}

	function stripe_woocommerce_before_submit()
	{
		$stripe_payment_type=$this->stripe_payment_mode;
		$stripe_publishable_key='';
		if($stripe_payment_type=='yes'){
			/*\Stripe\Stripe::setApiKey($this->stripe_test_secret_key);*/
			$stripe_publishable_key=$this->stripe_test_publishable_key;
		}
		else
		{
		/*	\Stripe\Stripe::setApiKey($this->stripe_live_secret_key);*/
			$stripe_publishable_key=$this->stripe_live_publishable_key;
		}
		$expmonth = $this->get_post( 'expmonth' );
			if ( $expmonth < 10 ) $expmonth = '0' . $expmonth;
			if ( $this->get_post( 'expyear' ) != null ) $expyear = substr( $this->get_post( 'expyear' ), -2 );
		?>
		<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
		<script type="text/javascript">
		(function(wk){
			wk('.woocommerce-checkout').on('submit',function(e){
				if(wk('#stripe_response_token').val()=='token'){
					e.preventDefault();
					e.stopImmediatePropagation();
					var expmon=wk('#expmonth option:selected').val();
					if(expmon<10){
						expmon='0'+expmon;
					}
					expyr=wk('#expyear option:selected').val();
					if(wk.type(expyr)!='undefined'){
						expyr=expyr.substr(-2);
					}
					wk('#stripe-data-exp-month').val(expmon);
					wk('#stripe-data-exp-year').val(expyr);

					Stripe.setPublishableKey('<?php echo $stripe_publishable_key; ?>');
					var form = wk('.woocommerce-checkout');
			    	Stripe.card.createToken(form, stripeResponseHandler);
			    	wk('#stripe_response_token').val('new');
			    }
			});
			function stripeResponseHandler(status, response) {
			    if(response.error){
			    	var error_message=response.error.message;
			    	wk('#stripe_response_token').val('');
			    	wk('#stripe_response_error').val(error_message);
			    }else{
			    	var token = response.id;
			    	wk('#stripe_response_token').val(token);
			    	wk('#stripe_response_error').val('');
			    }
			    if(wk('#stripe_response_token').val()!='token' || wk('#stripe_response_token').val()!='new'){
			    	wk('.woocommerce-checkout').submit();
			    }
			}
		})(jQuery);
		</script>
		<?php
	}

	/**
	* Get details of a payment method for the current user from the Customer Vault
	*/
    function get_payment_method( $payment_method_number ) {

		if( $payment_method_number < 0 ) die( 'Invalid payment method: ' . $payment_method_number );

		$user = wp_get_current_user();
		$customer_vault_ids = get_user_meta( $user->ID, 'customer_vault_ids', true );
		if( $payment_method_number >= count( $customer_vault_ids ) ) return null;

		$query = array (
		'username' 		      => $this->username,
		'password' 	      	=> $this->password,
		'report_type'       => 'customer_vault',
		);

		$id = $customer_vault_ids[ $payment_method_number ];
		if( substr( $id, 0, 1 ) !== '_' ) $query['customer_vault_id'] = $id;
		else {
		$query['customer_vault_id'] = $user->user_login;
		$query['billing_id']        = substr( $id , 1 );
		$query['ver']               = 2;
		}
		$response = wp_remote_post( QUERY_URL, array(
		'body'  => $query,
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'cookies' => array(),
		'ssl_verify' => false
		)
		);

		//Do we have an error?
		if( is_wp_error( $response ) ) return null;

		// Check for empty response, which means method does not exist
		if ( trim( strip_tags( $response['body'] ) ) == '' ) return null;

		// Format result
		$content = simplexml_load_string( $response['body'] )->customer_vault->customer;
		if( substr( $id, 0, 1 ) === '_' ) $content = $content->billing;

		return $content;
    }

    /**
     * Check if a user's stored billing records have been converted to Single Billing. If not, do it now.
     */
    function check_payment_method_conversion( $user_login, $user_id ) {
      	if( ! $this->user_has_stored_data( $user_id ) && $this->get_mb_payment_methods( $user_login ) != null ) $this->convert_mb_payment_methods( $user_login, $user_id );
    }

    /**
     * Convert any Multiple Billing records stored by the user into Single Billing records
     */
    function convert_mb_payment_methods( $user_login, $user_id ) {

		$mb_methods = $this->get_mb_payment_methods( $user_login );
		foreach ( $mb_methods->billing as $method ) $customer_vault_ids[] = '_' . ( (string) $method['id'] );
		// Store the payment method number/customer vault ID translation table in the user's metadata
		add_user_meta( $user_id, 'customer_vault_ids', $customer_vault_ids );

		// Update subscriptions to reference the new records
		if( class_exists( 'WC_Subscriptions_Manager' ) ) {

			$payment_method_numbers = array_flip( $customer_vault_ids );
			foreach( (array) ( WC_Subscriptions_Manager::get_users_subscriptions( $user_id ) ) as $subscription ) {
		  		update_post_meta( $subscription['order_id'], 'payment_method_number', $payment_method_numbers[ '_' . get_post_meta( $subscription['order_id'], 'billing_id', true ) ] );
		  		delete_post_meta( $subscription['order_id'], 'billing_id' );
			}
		}
    }

    /**
     * Get the user's Multiple Billing records from the Customer Vault
     */
    function get_mb_payment_methods( $user_login ) {

      	if( $user_login == null ) return null;

      	$query = array (
        	'username' 		      => $this->username,
        	'password' 	      	=> $this->password,
        	'report_type'       => 'customer_vault',
        	'customer_vault_id' => $user_login,
        	'ver'               => '2',
        );

      	$content = wp_remote_post( QUERY_URL, array(
        	'body'  => $query,
        	'timeout' => 45,
        	'redirection' => 5,
        	'httpversion' => '1.0',
        	'blocking' => true,
        	'headers' => array(),
        	'cookies' => array(),
        	'ssl_verify' => false
        	)
    	);

      	if ( trim( strip_tags( $content['body'] ) ) == '' ) return null;
      	return simplexml_load_string( $content['body'] )->customer_vault->customer;
    }

    /**
     * Check if the user has any billing records in the Customer Vault
     */
    function user_has_stored_data( $user_id ) {
      return get_user_meta( $user_id, 'customer_vault_ids', true ) != null;
    }

    /**
     * Update a stored billing record with new CC number and expiration
     */
    function update_payment_method( $payment_method, $ccnumber, $ccexp ) {

		global $woocommerce;
		$user =  wp_get_current_user();
		$customer_vault_ids = get_user_meta( $user->ID, 'customer_vault_ids', true );

		$id = $customer_vault_ids[ $payment_method ];
		if( substr( $id, 0, 1 ) == '_' ) {
		// Copy all fields from the Multiple Billing record
		$mb_method = $this->get_payment_method( $payment_method );
		$stripe_request = (array) $mb_method[0];
		// Make sure values are strings
		foreach( $stripe_request as $key => $val ) $stripe_request[ $key ] = "$val";
		// Add a new record with the updated details
		$stripe_request['customer_vault'] = 'add_customer';
		$new_customer_vault_id = $this->random_key();
		$stripe_request['customer_vault_id'] = $new_customer_vault_id;
		} else {
		// Update existing record
		$stripe_request['customer_vault'] = 'update_customer';
		$stripe_request['customer_vault_id'] = $id;
		}

		$stripe_request['username'] = $this->username;
		$stripe_request['password'] = $this->password;
		// Overwrite updated fields
		$stripe_request['cc_number'] = $ccnumber;
		$stripe_request['cc_exp'] = $ccexp;

		$response = $this->post_and_get_response( $stripe_request );

		if( $response ['response'] == 1 ) {
		if( substr( $id, 0, 1 ) === '_' ) {
		  // Update references
		  $customer_vault_ids[ $payment_method ] = $new_customer_vault_id;
		  update_user_meta( $user->ID, 'customer_vault_ids', $customer_vault_ids );
		}
		$woocommerce->add_message( __('Successfully updated your information!', 'woocommerce') );
		} else wc_add_notice( __( 'Sorry, there was an error: ', 'woocommerce') . $response['responsetext'] );
		$woocommerce->show_messages();

    }

    /**
     * Delete a stored billing method
     */
    function delete_payment_method( $payment_method ) {

		global $woocommerce;
		$user = wp_get_current_user();
		$customer_vault_ids = get_user_meta( $user->ID, 'customer_vault_ids', true );

		$id = $customer_vault_ids[ $payment_method ];
		// If method is Single Billing, actually delete the record
		if( substr( $id, 0, 1 ) !== '_' ) {

		$stripe_request = array (
		  'username' 		      => $this->username,
		  'password' 	      	=> $this->password,
		  'customer_vault'    => 'delete_customer',
		  'customer_vault_id' => $id,
		  );
		$response = $this->post_and_get_response( $stripe_request );
		if( $response['response'] != 1 ) {
		  wc_add_notice( __( 'Sorry, there was an error: ', 'woocommerce') . $response['responsetext'] );
		  $woocommerce->show_messages();
		  return;
		}

		}

		$last_method = count( $customer_vault_ids ) - 1;

		// Update subscription references
		if( class_exists( 'WC_Subscriptions_Manager' ) ) {
			foreach( (array) ( WC_Subscriptions_Manager::get_users_subscriptions( $user->ID ) ) as $subscription ) {
				$subscription_payment_method = get_post_meta( $subscription['order_id'], 'payment_method_number', true );
				// Cancel subscriptions that were purchased with the deleted method
				if( $subscription_payment_method == $payment_method ) {
				delete_post_meta( $subscription['order_id'], 'payment_method_number' );
				WC_Subscriptions_Manager::cancel_subscription( $user->ID, WC_Subscriptions_Manager::get_subscription_key( $subscription['order_id'] ) );
				}
				else if( $subscription_payment_method == $last_method && $subscription['status'] != 'cancelled') {
				update_post_meta( $subscription['order_id'], 'payment_method_number', $payment_method );
				}
			}
		}

		// Delete the reference by replacing it with the last method in the array
		if( $payment_method < $last_method ) $customer_vault_ids[ $payment_method ] = $customer_vault_ids[ $last_method ];
		unset( $customer_vault_ids[ $last_method ] );
		update_user_meta( $user->ID, 'customer_vault_ids', $customer_vault_ids );

		$woocommerce->add_message( __('Successfully deleted your information!', 'woocommerce') );
		$woocommerce->show_messages();

    }

    /**
     * Check payment details for valid format
     */
	function validate_fields() {

      	if ( $this->get_post( 'stripe-use-stored-payment-info' ) == 'yes' ) return true;

		global $woocommerce;

		// Check for saving payment info without having or creating an account
		if ( $this->get_post( 'saveinfo' )  && ! is_user_logged_in() && ! $this->get_post( 'createaccount' ) ) {
    		wc_add_notice( __( 'Sorry, you need to create an account in order for us to save your payment information.', 'woocommerce') );
    		return false;
  		}

		$cardType            = $this->get_post( 'cardtype' );
		$cardNumber          = $this->get_post( 'ccnum' );
		$cardCSC             = $this->get_post( 'cvv' );
		$cardExpirationMonth = $this->get_post( 'expmonth' );
		$cardExpirationYear  = $this->get_post( 'expyear' );

		// Check card number
		if ( empty( $cardNumber ) || ! ctype_digit( $cardNumber ) ) {
			wc_add_notice( __( 'Card number is invalid.', 'woocommerce' ) );
			return false;
		}

		if ( $this->cvv == 'yes' ){
			// Check security code
			if ( ! ctype_digit( $cardCSC ) ) {
				wc_add_notice( __( 'Card security code is invalid (only digits are allowed).', 'woocommerce' ) );
				return false;
			}
			if ( ( strlen( $cardCSC ) != 3 && in_array( $cardType, array( 'Visa', 'MasterCard', 'Discover' ) ) ) || ( strlen( $cardCSC ) != 4 && $cardType == 'American Express' ) ) {
				wc_add_notice( __( 'Card security code is invalid (wrong length).', 'woocommerce' ) );
				return false;
			}
		}

		// Check expiration data
		$currentYear = date( 'Y' );

		if ( ! ctype_digit( $cardExpirationMonth ) || ! ctype_digit( $cardExpirationYear ) ||
			$cardExpirationMonth > 12 ||
			$cardExpirationMonth < 1 ||
			$cardExpirationYear < $currentYear ||
			$cardExpirationYear > $currentYear + 20
		) {
			wc_add_notice( __( 'Card expiration date is invalid', 'woocommerce' ) );
			return false;
		}

		// Strip spaces and dashes
		$cardNumber = str_replace( array( ' ', '-' ), '', $cardNumber );

		return true;

	}


    /**
     * Add ability to view and edit payment details on the My Account page.(The WooCommerce 'force ssl' option also secures the My Account page, so we don't need to do that.)
     */
    function add_payment_method_options() {

      	$user = wp_get_current_user();
     	// $this->check_payment_method_conversion( $user->user_login, $user->ID );
      	if ( ! $this->user_has_stored_data( $user->ID ) ) return;

      	if( $this->get_post( 'delete' ) != null ) {

        	$method_to_delete = $this->get_post( 'delete' );
        	$response = $this->delete_payment_method( $method_to_delete );

      	} else if( $this->get_post( 'update' ) != null ) {

        	$method_to_update = $this->get_post( 'update' );
        	$ccnumber = $this->get_post( 'edit-cc-number-' . $method_to_update );

        	if ( empty( $ccnumber ) || ! ctype_digit( $ccnumber ) ) {

          		global $woocommerce;
          		wc_add_notice( __( 'Card number is invalid.', 'woocommerce' ) );
          		$woocommerce->show_messages();

        	} else {

          		$ccexp = $this->get_post( 'edit-cc-exp-' . $method_to_update );
          		$expmonth = substr( $ccexp, 0, 2 );
          		$expyear = substr( $ccexp, -2 );
          		$currentYear = substr( date( 'Y' ), -2);

        		if( empty( $ccexp ) || ! ctype_digit( str_replace( '/', '', $ccexp ) ) ||
            		$expmonth > 12 || $expmonth < 1 ||
            		$expyear < $currentYear || $expyear > $currentYear + 20 )
            		{

            			global $woocommerce;
            			wc_add_notice( __( 'Card expiration date is invalid', 'woocommerce' ) );
            			$woocommerce->show_messages();

        		} else {

            		$response = $this->update_payment_method( $method_to_update, $ccnumber, $ccexp );

        		}
        	}
    	}

      	?>

      	<h2>Saved Payment Methods</h2>
      	<p>This information is stored to save time at the checkout and to pay for subscriptions.</p>

      	<?php $i = 0;
      	$current_method = $this->get_payment_method( $i );
      	while( $current_method != null ) {

        	if( $method_to_delete === $i && $response['response'] == 1 ) { $method_to_delete = null; continue; } // Skip over a deleted entry ?>

        	<header class="title">

	          	<h3>
	            	Payment Method <?php echo $i + 1; ?>
	          	</h3>
	          	<p>

	            <button style="float:right" class="button" id="unlock-delete-button-<?php echo $i; ?>"><?php _e( 'Delete', 'woocommerce' ); ?></button>

	            <button style="float:right; display:none" class="button" id="cancel-delete-button-<?php echo $i; ?>"><?php _e( 'No', 'woocommerce' ); ?></button>
	            <form action="<?php echo get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ?>" method="post" style="float:right" >
	              	<input type="submit" value="<?php _e( 'Yes', 'woocommerce' ); ?>" class="button alt" id="delete-button-<?php echo $i; ?>" style="display:none">
	              	<input type="hidden" name="delete" value="<?php echo $i ?>">
	            </form>
	            <span id="delete-confirm-msg-<?php echo $i; ?>" style="float:left_; display:none">Are you sure? (Subscriptions purchased with this card will be canceled.)&nbsp;</span>

	            <button style="float:right" class="button" id="edit-button-<?php echo $i; ?>" ><?php _e( 'Edit', 'woocommerce' ); ?></button>
	            <button style="float:right; display:none" class="button" id="cancel-button-<?php echo $i; ?>" ><?php _e( 'Cancel', 'woocommerce' ); ?></button>

	            <form action="<?php echo get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ?>" method="post" >

	              	<input type="submit" value="<?php _e( 'Save', 'woocommerce' ); ?>" class="button alt" id="save-button-<?php echo $i; ?>" style="float:right; display:none" >

	              	<span style="float:left">Credit card:&nbsp;</span>
	              	<input type="text" style="display:none" id="edit-cc-number-<?php echo $i; ?>" name="edit-cc-number-<?php echo $i; ?>" maxlength="16" />
	              	<span id="cc-number-<?php echo $i; ?>">
	                <?php echo ( $method_to_update === $i && $response['response'] == 1 ) ? ( '<b>' . $ccnumber . '</b>' ) : $current_method->cc_number; ?>
	              	</span>
	            	<br/>

	              	<span style="float:left">Expiration:&nbsp;</span>
	              	<input type="text" style="float:left; display:none" id="edit-cc-exp-<?php echo $i; ?>" name="edit-cc-exp-<?php echo $i; ?>" maxlength="5" value="MM/YY" />
	              	<span id="cc-exp-<?php echo $i; ?>">
	                <?php echo ( $method_to_update === $i && $response['response'] == 1 ) ? ( '<b>' . $ccexp . '</b>' ) : substr( $current_method->cc_exp, 0, 2 ) . '/' . substr( $current_method->cc_exp, -2 ); ?>
	              	</span>

	              	<input type="hidden" name="update" value="<?php echo $i ?>">

	            </form>

	          	</p>

        	</header><?php

        	$current_method = $this->get_payment_method( ++$i );
      	}
    }

	function receipt_page( $order ) {
		echo '<p>' . __( 'Thank you for your order.', 'woocommerce' ) . '</p>';
	}

	/**
	* Include jQuery and our scripts
	*/
    function add_stripe_scripts() {

      	if ( ! $this->user_has_stored_data( wp_get_current_user()->ID ) ) return;

      	wp_enqueue_script( 'jquery' );
      	wp_enqueue_script( 'edit_billing_details', PLUGIN_DIR . 'js/edit_billing_details.js', array( 'jquery' ), 1.0 );
      	/*wp_enqueue_script( 'stripe_connect_control', WP_PLUGIN_DIR . '/stripe-connect/assets/js/stripe_connect_control.js', array( 'jquery' ), 1.0 );*/

      	if ( $this->cvv == 'yes' ) wp_enqueue_script( 'check_cvv', PLUGIN_DIR . 'js/check_cvv.js', array( 'jquery' ), 1.0 );
    }

    /**
     * Get the current user's login name
     */
    private function get_user_login() {
      	global $user_login;
      	get_currentuserinfo();
      	return $user_login;
	}

	/**
	 * Get post data if set
	 */
	private function get_post( $name ) {
		if ( isset( $_POST[ $name ] ) ) {
			return $_POST[ $name ];
		}
		return null;
	}


	public function stripe_payment_details(){
		$paymet_gateways=WC()->payment_gateways->payment_gateways();
 		foreach ($paymet_gateways as $payment) {
 			if ($payment->get_title()=='Credit Card (Stripe Connect)') {

 				if($payment->enabled=='yes') : ?>

 					<fieldset>

						<legend><?php echo $payment->get_title();?></legend>

						<div class="social-seller-input">

							<label>Click here to connect to Stripe account</label>

							<input type="hidden" name="mp_seller_payment_stripe_method" value="<?php if(isset($stripe_unserialize_data['stripe'])) echo $stripe_unserialize_data['stripe']; ?>">
							</div>

							<div class="wkmp_profile_input mp_seller_paymet_method_description">

								<div class="wk-success-check">

									<a href="" id="mp_seller_payment_stripe_method"><img src="" id="stripe_connect_button" width="190" height="33"></a><br />

								</div>

								<label for="wkmppaydesci" id="mp_payment_description" name="mp_payment_description"><?php echo _e('Pyament Description Or Pyament Id');?></label>
								<input type="hidden" value="<?php echo $current_user->ID;?>" id="wkmppaydesci_seller_id" />
								<input type="hidden" value="<?php if(isset($stripe_unserialize_data['stripe'])) echo $stripe_unserialize_data['stripe']; ?>" id="wkmppaydesci_payment_id" />
								<input type="hidden" value="<?php echo isset($user_meta_arr['wkmppaydesci'])?$user_meta_arr['wkmppaydesci']:'';?>" id="wkmppaydesci_payment_value_id" />
								<div class="social-seller-input">
									<input type="text" value="<?php echo isset($user_meta_arr['wkmppaydesci'])?$user_meta_arr['wkmppaydesci']:'';?>" id="wkmppaydesci" name="wkmppaydesci">
								</div>

							</div>

					</fieldset>

 			<?php	endif;
 			}
 		}
	}

	public function save_stripe_seller_payment_details(){
		$stripe_val=array();
		if (isset( $_POST['mp_seller_payment_stripe_method'])){
			$stripe_val=array('stripe'=> $_POST['mp_seller_payment_stripe_method']);
		}
		if (isset( $_POST['wkmppaydesci']))
			update_user_meta( get_current_user_ID(),'wkmppaydesci', $_POST['wkmppaydesci']);
		if (isset( $_POST['mp_seller_payment_stripe_method']) && isset( $_POST['wkmppaydesci'])){
			$payment_methods=$wpdb->get_results("select seller_payment_method from {$wpdb->prefix}mpcommision where seller_id='".get_current_user_ID()."'");
			$payment_methods_s = maybe_unserialize($payment_methods);
			foreach($payment_methods_s[0] as $key=>$value){
				$value = maybe_unserialize($value);
			}
			if(!empty($value))
				$payment_methods = array_merge($value, $stripe_val);
			else
				$payment_methods = $stripe_val;

			$payment_methods = maybe_serialize($payment_methods);
			$wpdb->get_results("update {$wpdb->prefix}mpcommision  set seller_payment_method='".$payment_methods."',payment_id_desc='".$_POST['wkmppaydesci']."' where seller_id='".get_current_user_ID()."'");
		}

	}

}

	/*encrypt and decrypt method*/
	function encrypt($pure_string, $encryption_key) {
	    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
	    return $encrypted_string;
	}
	function decrypt($encrypted_string, $encryption_key) {
	    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
	    return $decrypted_string;
	}

	/**
	 * Add the gateway to woocommerce
	 */
	function add_stripe_connect_gateway( $methods ) {
		$methods[] = 'WC_Stripe_Connect';
		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_stripe_connect_gateway' );

}
add_action( 'wp_footer', 'add_stripe_connect_script');
function add_stripe_connect_script()
{
	// wp_enqueue_script ( 'stripe_connect_control' , plugins_url ( 'stripe-connect/assets/js/stripe_connect_control.js' ) );
	$stripe_connect=new WC_Stripe_Connect();
	// if(strchr(get_permalink(),'?'))
	// 	$icon='&';
	//   else
	// 	$icon='?';
	// $redirect_uri=get_permalink().$icon.'page=pedit';
	$stripe_payment_type=$stripe_connect->stripe_payment_mode;
		if($stripe_payment_type=='yes'){
			/*Stripe::setApiKey($stripe_connect->stripe_test_secret_key);*/
			$client_id= $stripe_connect->stripe_test_client_id;
		}
		else
		{
			/*Stripe::setApiKey($stripe_connect->stripe_live_secret_key);*/
			$client_id=$stripe_connect->stripe_live_client_id;
		}
	/*$client_id=$stripe_connect->stripe_platform_client_id;*/
	$stripe_connect_img=plugins_url().'/wp-stripe-connect/assets/images/stripe.png';
	$stripeConnectUrl='https://connect.stripe.com/oauth/authorize?response_type=code&client_id='.$client_id.'&stripe_landing=register&scope=read_write';
	?>
	<script type="text/javascript">
	(function(wk){

		wk(document).ready(function(){
			var payment_method=wk(this).val();
			jQuery("#mp_seller_payment_stripe_method").attr("href","<?php echo $stripeConnectUrl; ?>");
			jQuery("#mp_seller_payment_stripe_method img").attr("src","<?php echo $stripe_connect_img; ?>");


			if(wk('body').find('#mp_seller_payment_stripe_method').length==1){
				var error_warning=wk('#stripe_response_error').val();
				var success_message=wk('#stripe_response_success').val();
				var a=wk('#mp_seller_payment_method :selected').val();
				if(wk.type(error_warning)!='undefined'){
					var html ='<div class="stripe_show_error" style="color:red;">';
					html +=error_warning;
					html +='</div>';
					wk('.wk-success-check').append(html);
				}
				if(wk.type(success_message)!='undefined'){
					wk('#stripe_connect_button').parent().remove();
					var html ='<div class="stripe_show_success" style="color:green;">';
					html +=success_message;
					html +='</div>';
					wk('.wk-success-check').append(html);
					var stripe_user_id="<?php echo get_user_meta(get_current_user_ID(),'stripe_user_id',true); ?>";
					wk('#wkmppaydesci').val(stripe_user_id);

				}
			}
		});
	})(jQuery);
	</script><?php
}
add_action('plugins_loaded','get_auth_stripe');
function get_auth_stripe(){
	/*See full code example here: https://gist.github.com/3507366*/
	$error_warning='';
	$client_id='';
	$success_message='Connected Successfully';
	$code='';
	$user_id=get_current_user_ID();
	$stp_usr_id=get_user_meta($user_id,'stripe_user_id',true);

	if(empty($stp_usr_id)){

		if (isset($_GET['code'])) { // Redirect w/ code
			  $code = $_GET['code'];
			  $client_secret='';
			  $stripe_connect=new WC_Stripe_Connect();

			  $stripe_payment_type=$stripe_connect->stripe_payment_mode;
			  if($stripe_payment_type=='yes'){
			  	$client_secret=$stripe_connect->stripe_test_secret_key;
			  	$client_id=$stripe_connect->stripe_test_client_id;
			  }
			  else
			  {
			  	$client_secret=$stripe_connect->stripe_live_secret_key;
			  	$client_id=$stripe_connect->stripe_live_client_id;
			  }
			  $token_request_body = array(
			    'grant_type' => 'authorization_code',
			    'client_id' => $client_id,
			    'code' => $code,
			    'client_secret' => $client_secret
			  );
			  /*curl -X POST https://connect.stripe.com/oauth/token \
				-d client_secret=YOUR_SECRET_KEY \
				-d code=ac_69utBJgQxo5e4TLT15hG2KECW1plwvUS \
				-d grant_type=authorization_code
			  */

			  $req = curl_init('https://connect.stripe.com/oauth/token');
			  curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
			  curl_setopt($req, CURLOPT_POST, true );
			  curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
			  // TODO: Additional error handling
			  $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
			  $resp = json_decode(curl_exec($req), true);

			  if(isset($resp['error'])){
			  	$error_warning = $resp['error'].':'.$resp['error_description'];
			  }else{
			  	$access_token=$resp['access_token'];
			  	addStripeConnect($resp);
			  	$success_message='Connected Successfully';
			  }
			  curl_close($req);
		} elseif (isset($_GET['error'])) { // Error
			 $error_warning= $_GET['error_description'];
		} else { // Show OAuth link
			$authorize_request_body = array(
			    'response_type' => $code,
			    'scope' => 'read_write',
			    'client_id' => $client_id
			);


		// $url = AUTHORIZE_URI . '?' . http_build_query($authorize_request_body);
		// echo "<a href='$url'>Connect with Stripe</a>";
		}
	}else{

		if($error_warning!=''){

			echo '<input type="hidden" id="stripe_response_error" value="'.$error_warning.'">';
		}
		elseif($success_message!=''){

			echo '<input type="hidden" id="stripe_response_success" value="'.$success_message.'">';

		}
	}

}
function addStripeConnect($resp)
{
	$token_type=$resp['token_type'];
	$stripe_publishable_key=$resp['stripe_publishable_key'];
	$livemode=$resp['livemode'];
	$stripe_user_id=$resp['stripe_user_id'];
	$refresh_token=$resp['refresh_token'];
	$access_token=$resp['access_token'];
	$user_id=get_current_user_ID();
	update_user_meta($user_id,'mp_seller_payment_method', 'Credit Card (Stripe Connect)');
	update_user_meta($user_id,'stripe_token_type',$token_type);
	update_user_meta($user_id,'stripe_publishable_key',$stripe_publishable_key);
	update_user_meta($user_id,'stripe_livemode',$livemode);
	update_user_meta($user_id,'stripe_user_id',$stripe_user_id);
	update_user_meta($user_id,'stripe_refresh_token',$refresh_token);
	update_user_meta($user_id,'stripe_access_token',$access_token);
}
?>