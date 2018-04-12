<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Handle frontend forms
 *
 * @class 		MP_Form_Handler
 * @version		1.0.1
 * @package		marketplace/Classes/
 * @category	Class
 * @author 		webkul
 */
class MP_Form_Handler {
	/**
	 * Constructor
	 */

	public $child;

	 public function __construct()
	 {

	 		require_once( sprintf("%s/front/save-shipping-options.php", dirname(__FILE__)) );

	 }



	// fix author to upload image
	function marketplace_media_fix( $post_id='' )
	{
		global $frontier_post_id;
		global $post_ID;

		/* WordPress 3.4.2 fix */
		$post_ID = $post_id;

		// WordPress 3.5.1 fix
		$frontier_post_id = $post_id;
	    $p=add_filter( 'media_view_settings', array($this,'marketplace_media_fix_filter'), 10, 2 );

	}




	//Fix insert media editor button filter

	function marketplace_media_fix_filter( $settings, $post )
	{
	global $frontier_post_id;

    $settings['post']['id'] = $frontier_post_id;

    //$settings['post']['nonce'] = wp_create_nonce( 'update-post_' . $frontier_post_id );

	return $settings;
	}

// fix author to upload image  end

	function get_user_avatar($id,$avtar_type)	{
		global $wpdb;
		return $wpdb->get_results("select um.meta_value,pm.meta_value from $wpdb->usermeta um  join $wpdb->postmeta pm on um.meta_value=pm.post_id  where um.user_id=$id and um.meta_key='_thumbnail_id_".$avtar_type."' and pm.meta_key='_wp_attached_file'");
	}
	public function update_user_new_avtar($id,$post_meta_value,$avtar_type)
	{
		global $wpdb;
		$del_post_id=$wpdb->get_var("select meta_value from $wpdb->usermeta where meta_key='_thumbnail_id_".$avtar_type."' and user_id='$id'");
		$delid=delete_post_meta($del_post_id, '_wp_attached_file', $post_meta_value);
		return $delid;
	}
	public function getOrderId()
		{
			global $wpdb;
			$user_id = get_current_user_id();
			$sql="select woitems.order_item_id from {$wpdb->prefix}woocommerce_order_itemmeta woi join {$wpdb->prefix}woocommerce_order_items woitems on woitems.order_item_id=woi.order_item_id join {$wpdb->prefix}posts post on woi.meta_value=post.ID where woi.meta_key='_product_id' and post.ID=woi.meta_value and post.post_author='".$user_id."' GROUP BY order_id";
			$result=$wpdb->get_results($sql);
			$ID=array();
			foreach($result as $res)
			{
			$ID[]=$res->order_item_id;
			}
			return implode(',',$ID);
		}
	public function update_new_product_image($post_id)
	{
		global $wpdb;
		$del_post_id=get_post_meta( $post_id, '_thumbnail_id', true);
		//$delid=delete_post_meta($del_post_id, '_wp_attached_file', $post_image_meta);
		return $del_post_id;
	}

	public function calling_pages()
	{
		global $current_user,$wpdb;
		$current_user=wp_get_current_user();
		$seller_info=$wpdb->get_var("SELECT user_id FROM ".$wpdb->prefix."mpsellerinfo WHERE user_id = '".$current_user->ID ."' and seller_value='seller'");
	 	$pagename=get_query_var('pagename');
	 	$main_page=get_query_var('main_page');
	 	$edit_info=get_query_var('action');
	 	$info=get_query_var('info');
	 	$edit_id=get_query_var('pid');
	 	$seller_id=get_query_var('sid');
	 	$order_id=get_query_var('order_id');
	 	$ship=get_query_var('ship');
	 	$zone_id=get_query_var('zone_id');
	 	$ship_page=get_query_var('ship_page');

		if(!empty($pagename)){

			require_once( 'templates/front/class-mp-order-functions.php' );

			if ($pagename=='seller' && $main_page == 'invoice' && !empty($order_id)) {
				if(!empty($main_page) && ($current_user->ID || $seller_info>0)) {
					wk_mp_invoice($order_id);
					die;
				}
				else {
					global $wp_query;
					$wp_query->set_404();
					status_header( 404 );
					get_template_part( 404 ); exit();
				}
			}
			if( $main_page=="profile" && ($current_user->ID || $seller_info>0))
			{
				add_shortcode('marketplace','seller_profile');
			}
			if($main_page=="profile" && $info=="edit" && ($current_user->ID && $seller_info>0))
			{
				add_shortcode('marketplace','edit_profile');
			}
			else if($main_page=="product-list" && $seller_info>0)
			{
				require 'front/product-list.php';
				add_shortcode('marketplace','product_list');
			}else if($main_page=="add-product" && $seller_info>0)
			{
				add_shortcode('marketplace','add_product');
			}
			else if(($main_page=="product" && $edit_info=="edit" && !empty($edit_id) ) && $seller_info>0)
			{
				add_shortcode('marketplace','edit_product');
			}
			else if($main_page=="change-password" && $seller_info>0)
			{
				add_shortcode('marketplace','wk_Change_password');
			}
			else if($main_page=="product" && $info=="edit" && $seller_info>0)
			{
				add_shortcode('marketplace','edit_product');
			}
			else if($main_page=="dashboard" && $seller_info>0)
			{

				wp_enqueue_script( 'google_chart', 'https://www.google.com/jsapi');

				wp_enqueue_script('bar_chart', 'https://www.gstatic.com/charts/loader.js');

				wp_enqueue_script( 'mp_chart_script_with_google_api',  WK_MARKETPLACE . '/assets/js/chart_script.js' );

				wp_enqueue_style( 'mp_dashboard_styles', WK_MARKETPLACE . '/assets/css/admin.css' );

				add_shortcode('marketplace','dashboard');
			}
			else if($main_page=="store" && !empty($info))
			{
				add_shortcode('marketplace','spreview');
			}
			else if($main_page=="seller-product" && !empty($info))
			{
				add_shortcode('marketplace','seller_all_product');
			}
			else if($main_page=="feedback"  && ($current_user->ID || $seller_info>0))
			{
				add_shortcode('marketplace','efeedback');
			}
			else if($main_page=="shop-follower"  && ($current_user->ID || $seller_info>0))
			{
				add_shortcode('marketplace','shop_followers');
			}
			else if($main_page=="order-history" && $seller_info>0)
			{
				if(!empty($order_id))
				{
					add_shortcode('marketplace','order_view');
				}
				else
				{
					add_shortcode('marketplace','order_history');
				}
			}else if($main_page=="to" && $seller_info>0)
			{
				add_shortcode('marketplace','asktoadmin');
			}
			else if(!empty($main_page) && $ship_page=='shipping' && $seller_info>0) {
				add_shortcode('marketplace','manage_shipping');
			}
			else if(!empty($main_page) && $ship=='shipping' && !empty($edit_info) && $seller_info>0) {

				if($edit_info=='edit')
					add_shortcode('marketplace','edit_shipping');
				else if($edit_info=='add')
					add_shortcode('marketplace','add_shipping');
				else
					add_shortcode( 'marketplace', 'displayForm' );
			}
			else{
				// call registration form from page
				add_shortcode( 'marketplace', 'displayForm' );
			}
		}
		else{
			// call registration form from page
			add_shortcode( 'marketplace', 'displayForm' );
		}
	}

	//update product category
	public static function update_pro_category($cat_id,$postid)
	{
		if( is_array( $cat_id ) && array_key_exists( '1', $cat_id ) ){

				wp_set_object_terms($postid, $cat_id, 'product_cat');

		}
		else if( is_array( $cat_id ) ){

				$term = get_term_by('slug', $cat_id[0], 'product_cat');

				wp_set_object_terms($postid, $term->term_id, 'product_cat');

		}

	}

public function redirect_to_productpage()
{
	$params = array( 'page' => "List" );
	$url = add_query_arg( $params, get_permalink() );
	return $url;
}

//profile edit redirection
public function profile_edit_redirection()
{
	global $current_user,$wpdb;
	$stripe_val=array();
	$current_user=wp_get_current_user();

	if(isset( $_POST['wk_username'] ) && isset( $_POST['wk_firstname'] ) &&isset( $_POST['wk_lastname'] ) && wp_verify_nonce( $_POST['wk_user_nonece'], 'edit_profile' ) )
	{

	$userdata = array('ID'=>$current_user->ID,'user_email'=>$_POST['user_email'],'user_login'=>$_POST['wk_username']);

		if(wp_update_user( $userdata )>0)
		{
		update_user_meta( $current_user->ID, 'first_name', $this->mp_check_input_data($_POST['wk_firstname']) );

		update_user_meta( $current_user->ID,'last_name', $this->mp_check_input_data($_POST['wk_lastname']));

		update_user_meta( $current_user->ID,'shop_name', $this->mp_check_input_data($_POST['wk_storename']));

		update_user_meta( $current_user->ID,'about_shop', $_POST['wk_marketplace_about_shop']);

		update_user_meta( $current_user->ID,'shop_address', $_POST['wk_storeurl']);

		update_user_meta( $current_user->ID,'wk_user_address', $_POST['wk_user_address']);

		update_user_meta( $current_user->ID,'social_facebook', $_POST['settings']['social']['fb']);

		update_user_meta( $current_user->ID,'social_twitter',$_POST['settings']['social']['twitter']);

		update_user_meta( $current_user->ID,'social_gplus', $_POST['settings']['social']['gplus']);

		update_user_meta( $current_user->ID,'social_linkedin', $_POST['settings']['social']['linked']);

		update_user_meta( $current_user->ID,'social_youtube', $_POST['settings']['social']['youtube']);

		if (isset( $_POST['mp_seller_payment_method']) && !empty($_POST['mp_seller_payment_method'])) {
			$stripe_val['standard']= $_POST['mp_seller_payment_method'];
			update_user_meta( $current_user->ID,'mp_seller_payment_method', $stripe_val);
		}

		//echo 'profile'.$_FILES['mp_useravatar']['name'];
				$shop_banner_arr=$shop_logo_arr=$_FILES;

				if ( $_FILES['mp_useravatar']['name']!='' )
				{
					$this->upload_avatar($current_user->ID,$_FILES,'avatar');
				}
				if ( $shop_banner_arr['wk_mp_shop_banner']['name']!='' )
				{

				      	$this->upload_avatar($current_user->ID,$shop_banner_arr,'shop_banner');
				}
				if ( $shop_logo_arr['mp_company_logo']['name']!='' )
				{

				      	$this->upload_avatar($current_user->ID,$shop_logo_arr,'company_logo');
				}

		}

		do_action('marketplace_save_seller_payment_details');
	}
 }


 public function produt_by_seller_ID($seller,$str)
 {
  global $wpdb;
  $seque='';
			$product_by='';
			if(!empty($str))
			{
				$arr=explode('_',$str);
				if($arr[0]=='price')
				$product_by='_sale_price';
				else
				$product_by='post_title';
				if($arr[1]=='h')
				$seque='desc';
				else
				$seque='asc';
			}
			if(isset($arr[0]) && $arr[0]=='price')
			{
			$products=$wpdb->get_results("select post.post_title,post.post_name,post.ID,pmeta.meta_value as sale_price,pmeta1.meta_value as regular_price from $wpdb->posts as post join $wpdb->postmeta as pmeta on post.ID=pmeta.post_id join $wpdb->postmeta as pmeta1 on post.ID=pmeta1.post_id where post.post_author=$seller and post.post_type='product' and pmeta1.meta_key='_regular_price' and pmeta.meta_key='_sale_price' order by sale_price $seque");
			}
			else{
			$products=$wpdb->get_results("select post.post_title,post.post_name,post.ID,pmeta.meta_value as sale_price,pmeta1.meta_value as regular_price from $wpdb->posts as post join $wpdb->postmeta as pmeta on post.ID=pmeta.post_id join $wpdb->postmeta as pmeta1 on post.ID=pmeta1.post_id where post.post_author=$seller and post.post_type='product' and pmeta1.meta_key='_regular_price' and pmeta.meta_key='_sale_price' order by post.post_title $seque");
			}
 return $products;
 }


 //category tree nmanagement
 public function get_level($parent_term,$level)
 {
 global $wpdb;
 	if($parent_term!=0)
	{
	$level++;
	$term=$wpdb->get_var("select parent from $wpdb->term_taxonomy where term_id=".$parent_term);
	return $this->get_level($term,$level);
	}
	else
	{
	return $level;
	}
 }
 function get_page_id($page_name){

		 global $wpdb;
		 $page_ID = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '".$page_name."'");
		 return $page_ID;

 }
 //seller prview function
 function spreview($seller_id)
 {
	global $wpdb;
	if($seller_id!='')
	{
	$seller_data= $wpdb->get_results("SELECT umeta.* FROM {$wpdb->prefix}usermeta as umeta join {$wpdb->prefix}mpsellerinfo as mpseller on umeta.user_id=mpseller.user_id WHERE umeta.user_id = '$seller_id' and mpseller.seller_value='seller'");
	return $seller_data;
	}
 }
  //seller preview function
 public function seller_product($seller_id)
	{
		global $wpdb;
		$seller_product= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts where post_author = '$seller_id' and post_type='product' order by ID desc");
		return $seller_product;
	}
	public function seller_product_meta($seller_id)
	{
		global $wpdb;
		return $wpdb->get_results("SELECT pmeta.meta_key,pmeta.meta_value FROM {$wpdb->prefix}postmeta as pmeta join {$wpdb->prefix}posts as post on pmeta.post_id=post.ID WHERE post.post_author = '$seller_id'");
	}

 //manage multiple categories


 function mp_product_categories1($parent,$obj_id)
 {
	global $wpdb;
	$product_category=$wpdb->get_results("select wpt.*,wptt.*,wptt.parent as cat_parent from $wpdb->terms wpt join $wpdb->term_taxonomy wptt on wpt.term_id=wptt.term_id where wptt.taxonomy='product_cat' and wptt.parent=$parent");
	$this_pro_cat=$wpdb->get_results("select term_taxonomy_id from $wpdb->term_relationships where object_id=$obj_id");
				static $prod_cat=array();
				foreach($this_pro_cat as $t)
				$prod_cat[]=$t->term_taxonomy_id;

							foreach($product_category as $procat){
								$opt_selected ='';
								$wk_sp=$this->get_level($procat->cat_parent,0);
							   for($i=0;$i<$wk_sp;$i++){
							   $wk_space.='&nbsp;&nbsp;';}
							   if(in_array($procat->term_id,$prod_cat))
							   {
								 $opt_selected='selected="selected"';
							   }
								echo $res="<option value='".$procat->term_id ."' $opt_selected >".$wk_space.$procat->name ."</option>";
							   $this->mp_product_categories1($procat->term_id,0);
								}
 }

	public function process_reset_password() {

		if(isset($_POST['user_login'])){

			$result = pass_reset();

			if ( is_wp_error($result) )

				echo '<div class="jerror">'.$result->get_error_message().'</div>';

		}

	}



	function inform_marketplace_seller($pid)
	{
		global $wpdb;
			$query="select user_email from {$wpdb->prefix}users as user join {$wpdb->prefix}posts as post on post.post_author=user.ID where post.ID=$pid";
			return $wpdb->get_results($query);
	}
	// new customer Order
	public function seller_email_order_items($order,$items)
	{

	foreach ( $items as $item_id => $item ) :

	$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );

	$item_meta    = new WC_Order_Item_Product( $item, $_product );


	$tr_data ='<tr>
		<td style="text-align:left; vertical-align:middle; border: 1px solid #eee; word-wrap:break-word;">';
		$show_image=false;
			// Show title/image etc
			if ( $show_image ) {
				$tr_data.=apply_filters( 'woocommerce_order_item_thumbnail', '<img src="' . ( $_product->get_image_id() ? current( wp_get_attachment_image_src( $_product->get_image_id(), 'thumbnail') ) : wc_placeholder_img_src() ) .'" alt="' . __( 'Product Image', 'woocommerce' ) . '" height="' . esc_attr( $image_size[1] ) . '" width="' . esc_attr( $image_size[0] ) . '" style="vertical-align:middle; margin-right: 10px;" />', $item );
			}

			// Product name
			$tr_data.=apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
			$show_sku=true;
			// SKU
			if ( $show_sku && is_object( $_product ) && $_product->get_sku() ) {
				$tr_data.=' (#' . $_product->get_sku() . ')';
			}
			$show_download_links=false;
			// File URLs
			if ( $show_download_links && is_object( $_product ) && $_product->exists() && $_product->is_downloadable() ) {

				$download_files = $order->get_item_downloads( $item );
				$i              = 0;

				foreach ( $download_files as $download_id => $file ) {
					$i++;

					if ( count( $download_files ) > 1 ) {
						$prefix = sprintf( __( 'Download %d', 'marketplace' ), $i );
					} elseif ( $i == 1 ) {
						$prefix = __( 'Download', 'marketplace' );
					}

					$tr_data.='<br/><small>' . $prefix . ': <a href="' . esc_url( $file['download_url'] ) . '" target="_blank">' . esc_html( $file['name'] ) . '</a></small>';
				}
			}

			// Variation
			if ( $item_meta->get_meta() ) {
				$tr_data.= __('<br/><small>' . nl2br( $item_meta->display( true, true ) ) . '</small>', 'marketplace');
			}

		$tr_data.='</td>
		<td style="text-align:left; vertical-align:middle; border: 1px solid #eee;">'.$item['qty'].'</td>';
		$tr_data.='<td style="text-align:left; vertical-align:middle; border: 1px solid #eee;">'.$order->get_formatted_line_subtotal( $item ).'</td></tr>';
		$show_purchase_note=false;
		if ( $show_purchase_note && is_object( $_product ) && $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) :
		$tr_data.= __('<tr>
			<td colspan="3" style="text-align:left; vertical-align:middle; border: 1px solid #eee;">'.wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ).'</td></tr>', 'marketplace');
		endif;
		endforeach;
		return $tr_data;
	}

		public function product_from_diffrent_seller($items)
		{
			$mp_product_author=array();
			foreach($items as $key=> $item)
			{
			 	$item_id=$item['product_id'];
				$author_email=$this->inform_marketplace_seller($item_id);
				$send_to=$author_email[0]->user_email;
				if(in_array($send_to,$mp_product_author))
				{
				 	$mp_product_author[$send_to][]=$item;
				}
				else
				{
					$mp_product_author[$send_to][]=$item;
				}
			}
			return $mp_product_author;
		}
	public function marketplace_new_customer_order($order,$per_seller_items)
	{
	$msg="<div style='border:1px solid green;'><div style='background-color:green; height:40px;margin:0;padding:0;'><h2>".$order->get_order_number().'Ordered By '.$order->get_billing_first_name() . ' ' . $order->get_billing_last_name()." </h2></div>";
	$msg.="<div><p>You have received an order from <b>".$order->get_billing_last_name() . ' ' . $order->get_billing_last_name()."</b> Their order is as follows:<p>";
	$msg.="<h2>".$order->get_order_number().' '.date_i18n( 'c', strtotime( $order->get_date_created() ) ). ' '. date_i18n( wc_date_format(), strtotime( $order->get_date_created() ) )."</h2>";
	$msg.="<table cellspacing='0' cellpadding='6' border='1' style='width:100%;border:1px solid #eee'>";
	$msg.="<thead>";
	$msg.="<tr>
		<th style='text-align:left;border:1px solid #eee' scope='col'>Product</th>
		<th style='text-align:left;border:1px solid #eee' scope='col'>Quantity</th>
		<th style='text-align:left;border:1px solid #eee' scope='col'>Price</th>
		</tr>
	</thead>
	<tbody>".$this->seller_email_order_items($order,$per_seller_items)."</tbody>";
	$msg.="<tfoot>";
		if ( $totals = get_orders($per_seller_items,$order) )
		{
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;

		$msg.="<tr>
						<th scope='row' colspan='2' style='text-align:left; border: 1px solid #eee;";
						if ( $i == 1 ) $msg.="border-top-width: 4px;'>".$total['label']."</th><td style='text-align:left; border: 1px solid #eee;";
						if ( $i == 1 ) $msg.="border-top-width: 4px;'>".$total['value']."</td></tr>";
				}
			}
$msg.="</tfoot>
</table>";
do_action( 'woocommerce_email_after_order_table', $order, true, false );
do_action( 'woocommerce_email_order_meta', $order, true, false );
$msg.="<h2>Customer Details</h2><br>";
if ( $order->get_billing_phone() ) :
$msg.="<p><strong>Email&nbsp;:&nbsp;&nbsp;</strong>".$order->get_billing_email()."</p>";
endif;
if ( $order->get_billing_phone() ) :
$msg.="<p><strong>Contact&nbsp:&nbsp;&nbsp;</strong>".$order->get_billing_phone()."</p>";
endif;
//$msg.="<p>".wc_get_template( 'emails/email-addresses.php', array( 'order' => $order ) )."</p>";
$msg.="<table cellspacing='0' cellpadding='0' style='width: 100%; vertical-align: top;' border='0'>

	<tr>

		<td valign='top' width='50%'>

			<h3>Billing address</h3>

			<p>".$order->get_formatted_billing_address()."</p>

		</td>";

		if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ( $shipping = $order->get_formatted_shipping_address() ) ) :

	$msg.="<td valign='top' width='50%'>
			<h3>Shipping address</h3>
			<p>".$shipping."</p>

		</td>";
endif;
$msg.="</tr>
	</table></div></div>";
	return __($msg, 'marketplace');
}


public function get_seller_subtotal_to_display( $order,$per_seller_items,$compound = false, $tax_display = '' ) {

		$subtotal = 0;

		if ( ! $compound ) {
			foreach ( $per_seller_items as $item ) {

				if ( ! isset( $item['line_subtotal'] ) || ! isset( $item['line_subtotal_tax'] ) ) {
					return '';
				}

				$subtotal += $item['line_subtotal'];

				if ( 'incl' == $tax_display ) {
					$subtotal += $item['line_subtotal_tax'];
				}
			}

			$subtotal = wc_price( $subtotal, array('currency' => $order->get_currency()) );

			/*if ( $tax_display == 'excl' && $this->prices_require_tax ) {
				$subtotal .= ' <small>' . WC()->countries->ex_tax_or_vat() . '</small>';
			}*/
			$prices_require_tax=false;
			if ( $tax_display == 'excl' && $prices_require_tax ) {
				$subtotal .= ' <small>' . WC()->countries->ex_tax_or_vat() . '</small>';
			}

		} else {

			if ( 'incl' == $tax_display ) {
				return '';
			}

			foreach ( $this->get_items() as $item ) {

				$subtotal += $item['line_subtotal'];

			}

			// Add Shipping Costs
			$subtotal += $this->get_total_shipping();

			// Remove non-compound taxes
			foreach ( $this->get_taxes() as $tax ) {

				if ( ! empty( $tax['compound'] ) ) {
					continue;
				}

				$subtotal = $subtotal + $tax['tax_amount'] + $tax['shipping_tax_amount'];

			}

			// Remove discounts
			$subtotal = $subtotal - $this->get_cart_discount();

			$subtotal = wc_price( $subtotal, array('currency' => $this->get_order_currency()) );
		}

		return apply_filters( 'woocommerce_order_subtotal_to_display', $subtotal, $compound, $this );
	}


public function send_mail_to_inform_seller($order)
{
	$items = $order->get_items();
	$per_seller_items=$this->product_from_diffrent_seller($items);
	$recent_user = wp_get_current_user();
	$cur_email=$recent_user->user_email;
	foreach($per_seller_items as $key=>$items)
	{
		$message=$this->marketplace_new_customer_order($order,$items);
		if($cur_email)
		{
			$headers = "From: " . strip_tags($cur_email) . "\r\n";
			$headers .= "Reply-To: ". strip_tags($cur_email) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		}
		else
		{
			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		}
		$attachments='';
		wp_mail( $key , 'New Customer Order',$message, $headers, $attachments );
	}
}


				//get product image

	public function get_product_image($pro_id,$meta_value)
			{
			global $wpdb;
			 $p=get_post_meta( $pro_id, $meta_value, true);
			if($p==null){
				return '';
			}
			$product_image=get_post_meta( $p, '_wp_attached_file', true);
			return $product_image;
			}


	public function insert_avatar_attachment($file_avt,$user_id,$avtar_type,$setthumb='false')
	{
		  global $wpdb;
		  // check to make sure its a successful upload
		  if ($_FILES[$file_avt]['error'] !== UPLOAD_ERR_OK) __return_false();
		  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		  require_once(ABSPATH . "wp-admin" . '/includes/media.php');


		 $attach_id = media_handle_upload( $file_avt, $user_id );
				$profile_image=$this->get_user_avatar($user_id,$avtar_type);
				if(!empty($profile_image[0]->meta_value))
				   {
				    $del=$this->update_user_new_avtar($user_id,$profile_image[0]->meta_value,$avtar_type);
					   if($del)
					   {
						update_user_meta( $user_id, '_thumbnail_id_'.$avtar_type,$attach_id);
						}
				   }
				   else
					{
					$data_usermeta=array('user_id'=>$user_id,'meta_key'=>'_thumbnail_id_'.$avtar_type,'meta_value'=>$attach_id);
					$wpdb->insert("$wpdb->usermeta",$data_usermeta);
					}
	  return $attach_id;
	}

	public function upload_avatar($user_id,$imagfile,$avtar_type)
			{
				if($avtar_type=='shop_banner')
				{
					$files = $imagfile['wk_mp_shop_banner'];
				}
				if($avtar_type=='avatar')
				{
					$files = $imagfile['mp_useravatar'];
				}
				if($avtar_type=='company_logo')
				{
					$files = $imagfile['mp_company_logo'];
				}
				$_FILES = array("upload_attachment" => $files);
				foreach ($_FILES as $file => $array)
				{
				$newupload = $this->insert_avatar_attachment($file,$user_id,$avtar_type);
				}

			}

		public static function admin_ask($email,$subject,$ask)
			{
			   apply_filters('asktoadmin_mail',$email,$subject,$ask);
			// global $wpdb, $current_site;
			// $admin=$wpdb->get_var("select user_email from $wpdb->users where ID='1'");
			// if ( empty( $email ) )
			// {
			// return __("Enter e-mail address.", 'marketplace');
			// }
			// if ( strpos( $email, '@' ) )
			// {
			// 	 $user_data = get_user_by( 'email', trim( $email ) );
			// 	if ( empty( $user_data ) )
			// 	{
			// 	$invalid_user= __("There is no user registered with this email ID", 'marketplace');
			// 	return $invalid_user;
			// 	}
			// }
			// if ( !$user_data )
			// {
			// $invalid_user= __("Invalid user name", 'marketplace');
			// return $invalid_user;
			// }
			// 	$message = "E-mail  :".$email."\r\n";
			// 	$message .='Question  :'.$ask."\r\n";
			// 	//$message .='From  :'. network_site_url() . "\r\n\r\n";

			// 	if ( $message && !wp_mail($admin, $subject, $message) ){
			// 			$mail_desabled= __('<strong>ERROR</strong>: The e-mail could not be sent. <br /> Possible reason: your host may have disabled the mail() function...', 'marketplace');
			// 		return $mail_desabled;
			// 		}
			// 		else
			// 		{
			// 		return __("Mail has been sent", "marketplace");
			// 		}
			// 	return true;
			}

			function update_marketplace_seller_roles($user_id)
			{
				$user = new WP_User($user_id);
	 			$user->remove_role('owner');
	 			echo get_option('default_role');
	 			exit;
	 			$user->add_role('administrator');
			}

			// check user inputs
			function mp_check_input_data( $data ) {
					return htmlspecialchars( $data );
			}
}
new MP_Form_Handler();
