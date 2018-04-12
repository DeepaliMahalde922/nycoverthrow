<?php
/*
Plugin Name: marketplace
Description: A custom wordpress plugin for displaying login form thrugh shortcode. Just use <strong>[login_form]</strong> in your post to get the login form.
Version: 1.0.1
Author: webkul
Author URI: www.webkul.com
*/
/* generate url link to redirect */
function getLostLink($c)
{
	$params = array( 'action' => "lostpassword" );
	$url = add_query_arg( $params, get_permalink() );
	return $url;
}
add_filter('lostpassword_url','getLostLink');
/*
||==============================||
||  method for activation link  ||
||==============================||
*/
function getRegisterLink($c)
{
$params= array('action' => "register");
$url=add_query_arg($params,get_permalink());
return $url;
}
add_filter('register_url','getRegisterLink');

function logout_redirect()
{
		wp_redirect(site_url().'/my-account');
		exit;
}

add_filter('wp_logout','logout_redirect');
 // new user mail send end

  	function displayForm($atts) {
  		global $wpdb;
  		$table_name = $wpdb->prefix . 'mpsellerinfo';
		$user_status=is_user_logged_in();

		if(!$user_status && get_option('wkmp_show_seller_seperate_form') ) {
			/* seperate seller login form */
			 wp_enqueue_script( 'wc-password-strength-meter' );
			 echo do_shortcode('[woocommerce_my_account]');
		}
		if(!$user_status && !get_option('wkmp_show_seller_seperate_form')){
				echo __("<h3>Want to sell your own products...!</h3><br>", "marketplace");
				echo "<h3><a href='".get_permalink( get_option('woocommerce_myaccount_page_id') )."'>Login Here</a>  Or  <a href='".get_permalink( get_option('woocommerce_myaccount_page_id') )."'>Register</a></h3>";
	  	}
		else if($user_status)
		{
			global $wpdb;
			$wpmp_obj1=new MP_Form_Handler();
			$current_user = wp_get_current_user();
			// MP_Form_Handler::profile_edit_redirection();
			$wpmp_obj1->profile_edit_redirection();
			// $avatar=MP_Form_Handler::get_user_avatar($current_user->ID,'avatar');
			$avatar=$wpmp_obj1->get_user_avatar($current_user->ID,'avatar');
			// $seller_detail=MP_Form_Handler::seller_details($current_user->ID);
			$seller_detail=get_seller_details($current_user->ID);
			$seller_info=$wpdb->get_var("SELECT user_id FROM {$wpdb->prefix}mpsellerinfo WHERE user_id = '".$current_user->data->ID ."' and seller_value='seller'");
			echo __('<h2>Profile</h2>', 'marketplace');
			echo '<div class="wk_profileclass">';
			echo '<form method="post">';
			if($current_user->data->ID && $seller_info>0)
			{
				if(isset($avatar[0]->meta_value))
				{
					echo '<div class="wkmp_profileimg"><img src="'.content_url().'/uploads/'.$avatar[0]->meta_value.'" /></div>';
				}
				else
				{
				echo '<div class="wkmp_profileimg"><img src="'.WK_MARKETPLACE.'assets/images/genric-male.png" /></div>';
				}
			}
		    echo '<div class="wkmp_profileinfo"><div class="wkmp_profiledata"><label>'.__("Username", "marketplace" ).' </label> : &nbsp;&nbsp;' . $current_user->user_login . '</div>';
		    echo '<div class="wkmp_profiledata"><label>'.__("E-mail", "marketplace").' </label> : &nbsp;&nbsp;' . $current_user->user_email . '</div>';
		    echo '<div class="wkmp_profiledata"><label>'.__("Name", "marketplace").' </label> : &nbsp;&nbsp;' . $current_user->user_firstname . '&nbsp;' . $current_user->user_lastname . '</div>';
		    echo '<div class="wkmp_profiledata"><label>'.__("Display name", "marketplace").' </label> : &nbsp;&nbsp;' . $current_user->display_name . '</div>';
			if($current_user->data->ID && $seller_info>0)
			{
			echo '<div class="wkmp_profiledata"><label>'.__("Shop Name ", "marketplace").'</label> : &nbsp;&nbsp;' . $seller_detail['shop_name'][0]. '</div>';
			echo '<div class="wkmp_profiledata"><label>'.__("Address ", "marketplace").' </label> : &nbsp;&nbsp;' . $seller_detail['wk_user_address'][0]. '</div>';
			echo '<div class="wkmp_profiledata"><label>'.__("Shop Address ", "marketplace").' </label> : &nbsp;&nbsp;' . $seller_detail['shop_address'][0]. '</div>';
			if(strchr(get_permalink(),'?'))
				$icon='&';
			else
				$icon='?';
			echo '<div class="wkmp_profile_btn"><a href="'.get_permalink().'profile/edit" title="Edit Profile" class="button">Edit</a>&nbsp;&nbsp;</div>';
			}
			//echo '<a href="'.wp_logout_url().'" title="Logout" class="button">Logout</a><br /></div>';
			echo '</form></div>
			</div>';
		}

}
