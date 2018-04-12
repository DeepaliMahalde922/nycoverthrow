<?php
/**
 * Plugin Name: Marketplace
 * Plugin URI: https://store.webkul.com/Wordpress-Woocommerce-Marketplace.html
 * Description: WordPress WooCommerce Marketplace convert your wordpress woocommerce store in to Marketplace with separate seller product collection and separate seller.
 * Version: 4.5.0
 * Author: Webkul
 * Author URI: http://webkul.com
 * License: GNU/GPL for more info see license.txt included with plugin
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: marketplace
**/
//BACKEND
/*---------------------------------------------------------------------------------------------*/
if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}
add_action( 'admin_init', 'check_woocommerce_is_installed' );

/*
	Check if woocommerce plugin is already installed.
*/
function check_woocommerce_is_installed() {
	ob_start();
	if ( !function_exists( 'WC' ) ) {
  		// echo 'please install or activate woocommerce plugin to use this plugin';
		add_action( 'admin_notices', 'wkmp_woocommerce_missing_notice' );
  		// exit;
	}
}

/*
	Function to show message if woocommerce is not installed.
*/
function wkmp_woocommerce_missing_notice() {
		echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Marketplace depends on the last version of %s or later to work!', 'marketplace' ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">' . __( 'WooCommerce 3.0', 'woocommerce-colors' ) . '</a>' ) . '</p></div>';
}

define( 'MP_VERSION', '4.3.2' );

define( 'MP_PLUGIN_FILE', __FILE__ );

define( 'MARKETPLACE_VERSION', MP_VERSION );

define( 'WK_MARKETPLACE', plugin_dir_url(__FILE__));

define( 'WK_MARKETPLACE_DIR', plugin_dir_path(__FILE__));

if ( ! class_exists( 'Marketplace' ) ) :

	final class Marketplace{

			protected static $_instance = null;

			public $session = null;

			public $query = null;

			public $MP_Seller=null;

			private $MP_login=null;

			public static function instance() {

				if ( is_null( self::$_instance ) ) {

					self::$_instance = new self();

				}
				return self::$_instance;

			}

			private function includes(){

					require_once( 'includes/class-mp-install.php' );

					require_once( 'includes/class-mp-uninstall.php' );

					require_once( 'includes/class-mp-query-functions.php' );

					require_once( 'includes/class-mp-ajax-hooks.php' );

					require_once( 'includes/class-mp-ajax-functions.php' );

					require_once( 'includes/class-mp-post-data-handler.php' );
					require_once( 'includes/class-mp-save-notifications.php');
					if(is_admin())

					{

							require_once( 'includes/templates/admin/class-mp-product-templates.php' );

							require_once( 'includes/templates/admin/class-mp-order-templates.php' );

							require_once( 'includes/templates/admin/class-mp-profile-templates.php' );

							require_once('includes/admin/index.php');

							add_action('admin_enqueue_scripts',array($this,'admin_load_style'));

							require_once('includes/admin/event-handler.php');

					}
					//FRONTEND

					if(!is_admin())

					{

						 	$this->frontend_includes();

							if(isset($_GET['act']))
							{
									require_once('includes/front/profile.php');
							}
							else
							{
									require_once('includes/front/index.php');
							}

					}

				require_once('includes/class-mp-global-hooks.php');
			}

			public function admin_load_style(){

					wp_register_style( 'marketplace', WK_MARKETPLACE. 'assets/css/admin.css');

					wp_enqueue_style( 'marketplace');
			}

			public function frontend_includes(){

				require_once( 'includes/class-mp-form-handler.php' );

				require_once( 'includes/class-favourite-seller.php' );

				require_once( 'includes/class-mp-frontend-scripts.php' );

				require_once( 'includes/templates/front/class-mp-shipping-functions.php' );

				require_once( 'includes/templates/front/class-mp-product-templates.php' );

				require_once( 'includes/templates/front/class-mp-user-functions.php' );

				require_once( 'includes/front/class-mp-product-functions.php' );

				require_once( 'includes/front/class-mp-user-functions.php' );

				require_once( 'includes/front/class-mp-order-functions.php' );

				require_once( 'includes/templates/front/myaccount/register.php' );

				require_once( 'includes/front/handlers/class-mp-login-handler.php' );

				require_once( 'includes/front/handlers/class-mp-register-handler.php' );

				require_once( 'includes/templates/front/single-product/favourite-seller.php' );

				require_once( 'includes/templates/front/single-product/product-author.php' );

				require_once( 'includes/front/event-handler.php' );

			}

			public function include_widgets(){

					require_once( 'includes/widgets/class-mp-sellerpanel.php' );

					require_once( 'includes/widgets/class-mp-sellerlist.php' );
			}

			public function __construct(){

					// Auto-load classes on demand
					if ( function_exists( "__autoload" ) ) {

						spl_autoload_register( "__autoload" );

					}
					$this->includes();

					add_action( 'plugins_loaded',array($this, 'myplugin_load_textdomain') );

					add_action( 'widgets_init', array( $this, 'include_widgets' ) );

					add_action( 'init', array( $this, 'init' ), 0 );

					add_action('admin_enqueue_scripts',array($this,'user_load_script'));

					add_action('wp_enqueue_scripts',array($this,'front_enqueue_script'));

					do_action( 'marketplace_loaded' );

					add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'wk_mp_plugin_settings_link' ) );

					add_filter( 'new_seller_registration', array( $this, 'seller_created' ), 10, 2 );

					add_filter( 'new_custom_mail', array( $this, 'custom_mail' ), 10, 1);

					add_filter( 'woocommerce_approve_seller', array( $this, 'approve_seller' ), 10, 2 );

					add_filter( 'new_seller_registration', array( $this, 'admin_mail' ), 10, 2 );

					add_filter( 'woocommerce_email_classes', array( $this, 'add_new_email_notification' ), 10, 1 );

					add_filter( 'woocommerce_email_classes', array( $this, 'add_new_email_asktoadmin_notification' ), 10, 1 );

					add_filter( 'woocommerce_email_classes', array( $this, 'add_new_email_custom_notification' ), 10, 1 );

					add_action( 'admin_init', array( $this, 'preview_emails' ) );

					add_filter('woocommerce_email_header_custom',array($this,'email_header'),10,1);

					add_filter('woocommerce_email_footer_custom',array($this,'email_footer'),10,1);

					add_filter('woocommerce_select_file',array($this,'email_file'),10,3);

					add_filter('asktoadmin_mail',array($this,'asktoadmin'),10,3);

			}

			/**
			 * Load plugin textdomain.
			 *
			 * @since 1.0.0
			 */
			function myplugin_load_textdomain() {

			  load_plugin_textdomain( 'marketplace', false, basename( dirname( __FILE__ ) ) . '/languages' );

			}

			function wk_mp_plugin_settings_link($links) {

				$url = 'https://wordpressdemo.webkul.com';

				$settings_link = '<a href="'.$url.'" target="_blank" style="color:green;">' . __( 'Add-ons', 'marketplace' ) . '</a>';

				$links[] = $settings_link;

				return $links;

			}

			function preview_emails(){

            $tableName='woocommerce_new_seller_settings';
			if ( isset( $_GET['preview_woocommerce_mail'] ) ) {
			$msg=apply_filters('woocommerce_email_header_custom',$tableName);

			$msg.=include  plugin_dir_path( __FILE__ ).'woocommerce/templates/emails/html-email-template-preview.php';

			$msg.=	apply_filters('woocommerce_email_footer_custom',$tableName);

		    echo $msg;

			die;
			}
	}

	function asktoadmin($email,$ubject,$ask){

		$data=array("email"=>$email,"subject"=>$ubject,"ask"=>$ask);

		 $tableName='woocommerce_new_Query_settings';

		 $messages=apply_filters('woocommerce_email_header_custom',$tableName);

		  $filename='asktoadmin';

	     $messages.=apply_filters('woocommerce_select_file',$tableName,$filename,$data);

		 $messages.=apply_filters('woocommerce_email_footer_custom',$tableName);

       $userEmail= get_option('admin_email');

		$headers  = "MIME-Version: 1.0" . "\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\n";
		$headers .= "X-Priority: 1 (Higuest)\n";
		$headers .= "X-MSMail-Priority: High\n";
		$headers .= "Importance: High\n";

         @wp_mail(
				$userEmail,
				'MarketPlace Notification',
				$messages,$headers
		 );

	 ?>

	 		<script>

				jQuery('#ask-data').hide();

				var redirecturl = the_mpajax_script.site_url + '/' + the_mpajax_script.seller_page + '/profile';

        jQuery(location).attr('href',redirecturl);

			</script>

	 <?php
	}

	function custom_mail($data){

		 $tableName='woocommerce_new_temp_settings';

		 $messages=apply_filters('woocommerce_email_header_custom',$tableName);

		  $filename='custom-mail';

	     $messages.=apply_filters('woocommerce_select_file',$tableName,$filename,$data);

		 $messages.=apply_filters('woocommerce_email_footer_custom',$tableName);

       $userEmail= $data['user_email'];

		$headers  = "MIME-Version: 1.0" . "\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\n";
		$headers .= "X-Priority: 1 (Higuest)\n";
		$headers .= "X-MSMail-Priority: High\n";
		$headers .= "Importance: High\n";

         @wp_mail(
				$userEmail,
				'seller registration',
				$messages,$headers
		 );


	}

	function seller_created($tableName,$data){


		 $messages=apply_filters('woocommerce_email_header_custom',$tableName);

		 $filename='customer-new-account';

	     $messages.=apply_filters('woocommerce_select_file',$tableName,$filename,$data);

		 $messages.=apply_filters('woocommerce_email_footer_custom',$tableName);


         $userEmail= $data['user_email'];

		$headers  = "MIME-Version: 1.0" . "\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\n";
		$headers .= "X-Priority: 1 (Higuest)\n";
		$headers .= "X-MSMail-Priority: High\n";
		$headers .= "Importance: High\n";

         @wp_mail(
				$userEmail,
				'seller registration',
				$messages,$headers
		 );


	}

	function approve_seller($tableName,$data){


		global $wpdb;

        $table_name = $wpdb->prefix . 'users';

       	$user_details=$wpdb->get_results("SELECT * FROM $table_name  WHERE ID = $data");

		$user_details=$user_details[0];

		$userEmail=$user_details->user_email;

		$messages=apply_filters('woocommerce_email_header_custom',$tableName);

		$filename='approve-seller';

		$messages.=apply_filters('woocommerce_select_file',$tableName,$filename,$userEmail);

		$messages.=apply_filters('woocommerce_email_footer_custom',$tableName);


		$headers  = "MIME-Version: 1.0" . "\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\n";
		$headers .= "X-Priority: 1 (Higuest)\n";
		$headers .= "X-MSMail-Priority: High\n";
		$headers .= "Importance: High\n";

	    wp_mail(
				$userEmail,
				'approved account notification',
				$messages,$headers
		 );

	}


	function admin_mail($tableName,$data){

		 $tableName='woocommerce_new_seller_settings';

		 $filename='admin-mail';

		 $messages=apply_filters('woocommerce_email_header_custom',$tableName);

	     $messages.=apply_filters('woocommerce_select_file',$tableName,$filename,$data);

		 $messages.=apply_filters('woocommerce_email_footer_custom',$tableName);

       $userEmail= get_option('admin_email');

		$headers  = "MIME-Version: 1.0" . "\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\n";
		$headers .= "X-Priority: 1 (Higuest)\n";
		$headers .= "X-MSMail-Priority: High\n";
		$headers .= "Importance: High\n";

         @wp_mail(
				$userEmail,
				'seller registration',
				$messages,$headers
		 );


	}

	function email_file($tableName,$filename,$data){


			 include  plugin_dir_path( __FILE__ ).'woocommerce/templates/emails/'.$filename.'.php';

			 return $result;

	}

	function email_header($tableName){


			 include  plugin_dir_path( __FILE__ ).'woocommerce/templates/emails/email-header.php';

			 return $result;

	}
	function email_footer($tableName){


			 include  plugin_dir_path( __FILE__ ).'woocommerce/templates/emails/email-footer.php';

			 return $result;

	}


     function add_new_email_asktoadmin_notification($email){

		 $email['WC_Email_AskToAdmin'] 	 =  include('class-wc-email-asktoadmin.php' );

		return $email;
	}

	 function add_new_email_custom_notification($email){

		 $email['WC_Email_customTemplate'] 	 =  include('class-wc-email-custom-template.php' );

		return $email;
	}

    function add_new_email_reset_password_notification($email){

		 $email['WC_Email_Reset_Password'] 	 =  include('class-wc-email-reset-password.php' );

		return $email;
	}

	function add_new_email_notification($email) {

        $email['WC_Email_Seller_register'] 	 =  include('class-wc-email-seller-register.php' );

		return $email;

	}



			function front_enqueue_script(){

				wp_enqueue_media();

				global $wpdb;

				$page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_title ='".get_option('wkmp_seller_page_title')."'");

				wp_enqueue_script( 'marketplace', WK_MARKETPLACE. 'assets/js/plugin.js', array( 'jquery' ) );

				wp_enqueue_script( 'mp-front-ajax', WK_MARKETPLACE. 'assets/js/front-ajax-handler.js');

				wp_enqueue_script( 'marketplace-shipping', WK_MARKETPLACE. 'assets/js/shipping-class.js', array( 'jquery' ) );

				if(is_page('seller')){

						wp_enqueue_script('select2-js', plugins_url().'/woocommerce/assets/js/select2/select2.min.js');

						wp_enqueue_style('select2-css', plugins_url().'/woocommerce/assets/css/select2.css');

				}

				wp_localize_script( 'marketplace-shipping', 'the_mpajax_shipping_script', array( 'shippingajaxurl' => admin_url( 'admin-ajax.php' ), 'shippingNonce' => wp_create_nonce('shipping-ajaxnonce')));

				wp_localize_script( 'marketplace', 'the_mpajax_script', array( 'mpajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce('ajaxnonce'), 'seller_page' => $page_name , 'site_url' =>site_url() ));
			}


			public function user_load_script()
			{

				wp_enqueue_media();

				wp_enqueue_style('wp-color-picker');

				wp_enqueue_script( 'marketplace', WK_MARKETPLACE. 'assets/js/mpadminajax.js', array( 'jquery' ,'wp-color-picker') );

				wp_localize_script( 'marketplace', 'the_mpadminajax_script', array(
				'mpajaxurl' => admin_url( 'admin-ajax.php' ),
		  		'nonce' => wp_create_nonce('ajaxnonce')
				) );
			}

			public function make_seller_existing_user(){

				global $wpdb;
				$query="select ID from {$wpdb->prefix}users";
				$user_id=$wpdb->get_results($query);
				$mp_seller_query="select user_id from {$wpdb->prefix}mpsellerinfo";
				$seller_id=$wpdb->get_results($mp_seller_query);

				$mp_seller=array();
				foreach($seller_id as $seller)
				{
					$mp_seller[]=$seller->user_id;
				}
				foreach($user_id as $id)
				{
					$user_query =  new WP_User($id->ID);
					$mp_user_role=$user_query->roles[0];
					if(!in_array($id->ID,$mp_seller) && $mp_user_role=='wk_marketplace_seller')
					{
						//echo '1';
						$wpdb->get_results("insert into {$wpdb->prefix}mpsellerinfo (user_id,seller_key,seller_value)VALUES ($id->ID,'role','seller')");
					}
					if(in_array($id->ID,$mp_seller) && $mp_user_role!='wk_marketplace_seller')
					{
						//echo '2';
						$wpdb->get_results("update {$wpdb->prefix}mpsellerinfo set seller_value='0' where user_id=$id->ID");
					}
					if(in_array($id->ID,$mp_seller) && $mp_user_role=='wk_marketplace_seller')
					{
						//echo '3';
						$wpdb->get_results("update {$wpdb->prefix}mpsellerinfo set seller_value='seller' where user_id=$id->ID");
					}
				}

			}

			public function init() {

					add_action('pre_get_posts', array('Marketplace', 'marketplace_restrict_media_library'));

					add_action( 'woocommerce_payment_complete', array('Marketplace','marketplace_inform_seller' ));

					do_action( 'before_marketplace_init' );

					do_action( 'marketplace_init' );

			}

			static function marketplace_restrict_media_library( $wp_query_obj ){

			    global $current_user, $pagenow;
			    if( !is_a( $current_user, 'WP_User') )
			    return;
			    if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
			    return;
			    if( !current_user_can('manage_media_library') )
			    $wp_query_obj->set('author', $current_user->ID );
			    return;

			}

}

endif;

function MP() {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		return marketplace::instance();
	}else{
		add_shortcode('marketplace','woocommerce_not_installed');
	}
}

function woocommerce_not_installed()
{
	echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Marketplace depends on the last version of %s or later to work!', 'marketplace' ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">' . __( 'WooCommerce 3.0', 'woocommerce-colors' ) . '</a>' ) . '</p></div>';
}



$GLOBALS['marketplace'] = MP();
//seller approvement
$mp_obj=MP();


function get_product_image_mp($pro_id,$meta_value){
	global $wpdb;

	$p=get_post_meta( $pro_id, $meta_value, true);

	if($p==null){
		return '';
	}

	$product_image=get_post_meta( $p, '_wp_attached_file', true);

	return $product_image;

}
