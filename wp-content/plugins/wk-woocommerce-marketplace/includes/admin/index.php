<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function menu_backend() {
	add_menu_page(__('Marketplace', 'marketplace'), __('Marketplace', 'marketplace'), 'manage_marketplace', 'products', 'product_layout', WK_MARKETPLACE.'assets/images/MP.png', 55);
	add_submenu_page( 'products', __('Product List', 'marketplace'), __('Product List', 'marketplace'), 'manage_marketplace_products', 'products','product_layout');
	add_submenu_page( 'products', __('Seller List', 'marketplace'), __('Seller List', 'marketplace'), 'manage_marketplace_seller', 'sellers','seller_layout');
	add_submenu_page( 'products', __('Commissions', 'marketplace'), __('Commissions', 'marketplace'), 'manage_marketplace_commision', 'Commissions','commision_layout');
	$hook = add_submenu_page( 'products',__('Email Templates', 'marketplace') ,__('Email Templates', 'marketplace'), 'manage_marketplace', 'class-email-templates','mp_email_templates');
	add_submenu_page( 'products',__('Notification', 'marketplace'), __('Notification', 'marketplace'), 'manage_marketplace', 'mp-notification','mp_notification_tab');
	add_submenu_page( 'products', __('Settings', 'marketplace'), __('Settings', 'marketplace'), 'manage_marketplace_setting', 'Settings','settings_layout');
	add_action( "load-".$hook, 'mp_add_screen_options' );
}
function mp_add_screen_options() {

	  	$options = 'per_page';

	  	$args = array(

	    	'label' => 'Template Per Page',

	    	'default' => 20,

	    	'option' =>'template_per_page'

	  	);

	  	add_screen_option($options, $args);
	}
function marketplace_mp_login_reg_function()
	{
		register_setting('marketplace-settings-group','wkfb_mp_key_app_ID');

		register_setting('marketplace-settings-group','wkfb_mp_app_secret_key');

		register_setting('marketplace-settings-group','wkmpcom_minimum_com_onseller');

		register_setting('marketplace-settings-group','wkmpseller_ammount_to_pay');

		register_setting('marketplace-settings-group','wkmp_seller_menu_tile');

		register_setting('marketplace-settings-group','wkmp_seller_page_title');

		add_option( 'wkmp_seller_page_title', 'Seller','', 'yes' );

		register_setting('marketplace-settings-group','wkmp_seller_allow_publish');

		register_setting('marketplace-settings-group','wkmp_auto_approve_seller');

		register_setting('marketplace-settings-group', 'wkmp_show_seller_seperate_form');
	}
function commision_setting_layout()
	{
		require_once('setcommision.php');
	}
function marketplace_register_admin_page()
{
   global $_registered_pages;
   $menu_slug = plugin_basename('setcommision.php');
   $hookname = get_plugin_page_hookname($menu_slug,'');
  if (!empty($hookname))
  {
     add_action($hookname, 'commision_setting_layout');
  }
  $_registered_pages[$hookname] = true;
}

function product_layout()
{
	add_filter( 'admin_footer_text', 'wk_mp_admin_footer_text' );
require_once('product.php');
}
function seller_layout() {
	add_filter( 'admin_footer_text', 'wk_mp_admin_footer_text' );
	require_once('sellerlist.php');
}
function commision_layout()
{
	add_filter( 'admin_footer_text', 'wk_mp_admin_footer_text' );
require_once('commision.php');
}
function settings_layout()
{
	add_filter( 'admin_footer_text', 'wk_mp_admin_footer_text' );
require_once('settings/settings.php');
}
function mp_email_templates()
{
	if ($_GET['page'] == 'class-email-templates' && isset($_GET['action']) && $_GET['action'] == 'add') {
			require_once('class-add-email-template.php');
	}
	else {
			require_once('class-email-templates.php');
	}
	add_filter( 'admin_footer_text', 'wk_mp_admin_footer_text' );
}
function mp_notification_tab() {
		require_once('class-mp-notifications.php');
		add_filter( 'admin_footer_text', 'wk_mp_admin_footer_text' );
}
function wk_mp_admin_footer_text( $text ) {
	return sprintf( __( 'If you like <strong>Marketplace</strong> please leave us a <a href="https://codecanyon.net/item/wordpress-woocommerce-marketplace-plugin/reviews/19214408" target="_blank" class="wc-rating-link" data-rated="Thanks :)">★★★★★</a> rating. A huge thanks in advance!', 'marketplace' ) );
}

// add link to admin bar
function mp_add_toolbar_items( $admin_bar )
{
		require_once( 'mp-notifications-bar.php' );		
}

?>
