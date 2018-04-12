<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class MP_Widget_Seller extends WP_Widget {

	public function __construct() {

        parent::__construct(
            'mp_marketplace-widget',
            __( 'Display seller panel.', 'marketplace' ),
            array(
                'classname'   => 'mp_marketplace',
                'description' => __('Marketplace Seller Panel.', 'marketplace' )
                )
          );

      }

 function widget($args, $instance){
	global $wpdb;
	$obbj = new MP_Form_Handler();
	$user_id = get_current_user_id();
	$shop_address=get_user_meta($user_id,'shop_address',true);
	$page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_name ='".get_option('wkmp_seller_page_title')."'");
	 $seller_info=$wpdb->get_var("SELECT user_id FROM ".$wpdb->prefix."mpsellerinfo WHERE user_id = '".$user_id ."' and seller_value='seller'");
	if($seller_info>0)  {
			do_action('chat_with_me');
			echo __('<div class="wkmp_seller"><h2>'.get_option('wkmp_seller_menu_tile').'</h2>', 'marketplace');
			echo __('<ul class="wkmp_sellermenu">', 'marketplace');
			echo '<li class="wkmp-selleritem"><a href="'.home_url("/".$page_name."/profile").'">';
			echo __('My Profile', 'marketplace');
			echo '</a></li>
				 <li class="wkmp-selleritem"><a href="'.home_url("/".$page_name."/add-product").'">';
			echo __('Add Product', 'marketplace');
			echo '</a></li>
				 <li class="wkmp-selleritem"><a href="'.home_url("/".$page_name."/product-list").'">';
			echo __('Product List', 'marketplace');
			echo '</a></li>
				 <li class="wkmp-selleritem"><a href="'.home_url("/".$page_name."/order-history").'">';
			echo __('Order History', 'marketplace');
			echo '</a></li>
				 <li class="wkmp-selleritem"><a href="'.home_url("/".$page_name."/".$shop_address."/shipping").'">';
			echo __('Manage Shipping', 'marketplace');
			echo '</a></li>';
			do_action('marketplace_list_seller_option',$page_name);
			echo '<li class="wkmp-selleritem"><a href="'.home_url("/".$page_name."/shop-follower").'">';
			echo __('Shop Follower', 'marketplace');
			echo '</a></li>
				 <li class="wkmp-selleritem"><a href="'.home_url("/".$page_name."/dashboard").'">';
			echo __('Dashboard', 'marketplace');
			echo '</a></li>
				 <li class="wkmp-selleritem"><a href="'.home_url("/".$page_name."/change-password").'">';
			echo __('Change Password', 'marketplace');
			echo '</a></li>
				 <li class="wkmp-selleritem"><a href="'.home_url("/".$page_name."/to").'">';
			echo __('Ask To Admin', 'marketplace');
			echo '</a></li>
				 <li class="wkmp-selleritem"><a href="'.wp_logout_url().'" title="Logout">';
			echo __('Logout', 'marketplace');
			echo '</a></li></ul></div>';
		  }
		  if($user_id>0 && $seller_info==0)
		  {
		  	echo __('<div class="wkmp_seller"><h2>Buyer Menu</h2>', 'marketplace');
				echo __('<ul class="wkmp_sellermenu">', 'marketplace');
				echo '<li class="wkmp-selleritem"><a href="'.home_url("/seller/profile").'">';
				echo __('My Profile', 'marketplace');
				echo '</a></li>
					 <li class="wkmp-selleritem"><a href="'.wp_logout_url().'" title="Logout">';
				echo __('Logout', 'marketplace');
				echo '</a></li>
				 	</ul></div>';
		  }
		  // getting order id to inform seller
			$order_id=get_query_var('order-received');

			if(!empty($order_id) && $order_id>0)
			{
				$order = new WC_Order( $order_id);
				$obbj->send_mail_to_inform_seller($order);
			}
		// end getting order id to inform seller
 }
}
register_widget( 'MP_Widget_Seller' );
?>
