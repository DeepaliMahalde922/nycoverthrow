<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

// all countries for shipping (front)
function get_all_countries(){
	if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
 		global $woocommerce;
 		$html='';
		$continents=WC()->countries->get_continents();
		$allowed_countries=WC()->countries->get_allowed_countries();
		foreach ( $continents as $continent_code => $continent ) {
			$html .= '<li data-search-term="continent:' . esc_attr( $continent_code ) . '">' . esc_html( $continent['name'] ) . '</li>';

			$countries = array_intersect( array_keys( $allowed_countries ), $continent['countries'] );

			foreach ( $countries as $country_code ) {
				$html .= '<li data-search-term="country:' . esc_attr( $country_code ) . '" alt="' . esc_attr( $continent['name'] ) . '">' . esc_html( '&nbsp;&nbsp; ' . $allowed_countries[ $country_code ] ) . '</li>';

				if ( $states = WC()->countries->get_states( $country_code ) ) {
					foreach ( $states as $state_code => $state_name ) {
						$html .= '<li data-search-term="state:' . esc_attr( $country_code . ':' . $state_code ) . '" alt="' . esc_attr( $continent['name'] . ' ' . $allowed_countries[ $country_code ] ) . '">' . esc_html( '&nbsp;&nbsp;&nbsp;&nbsp; ' . $state_name ) . '</li>';
					}
				}
			}
		}
		echo __($html, 'marketplace');
		die;
	}
}


// Delete favourite seller
function delete_favourite_seller(){

        if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){

	       $seller= intval($_POST['seller']);

	       $customer_acc= intval($_POST['customer_acc']);

	       if(!empty($seller) && !empty($customer_acc)){

	       		$res=delete_user_meta($customer_acc,'favourite_seller',$seller);

	       }
	       else{

	       		$res=0;

	       }

	       echo $res;

		die;
	}
}


// change_favorite_status
function change_favorite_status(){

        if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){

	       $customer_checked= $_POST['customer_selected'];

	       if(!empty($customer_checked)) :

		       foreach ($customer_checked as $customer_split) {

		   			$elm=explode(",", $customer_split);

		       		delete_user_meta($elm[1],'favourite_seller',$elm[0]);

		       }

		       endif;

		       	return 1;

		       die;

		}
}


// send mail to customer
function send_mail_to_customers(){

	if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){

			$sent='';

	       	$customer_checked= $_POST['customer_list'];

	       	$form_serialized= $_POST['form_serialized'];

	       	$unseriallized_data=maybe_unserialize($form_serialized);


	       if(!empty($customer_checked)) :

		       foreach ($customer_checked as $customer_split) {

		       		$to = $customer_split;

		       		$s_id=get_userdata($unseriallized_data[2]['value']);

		       		$from =$s_id->user_email;

		       		$header = 'From: '.$from;

                    $subject = 	$unseriallized_data[0]['value'];

                    $feedback = $unseriallized_data[1]['value'];

                    $confirm = wp_mail($to, $subject, $feedback, $header);

                    if($confirm){

                      	$sent='sent';

                        // wc_print_notice( __( 'Email Sent Successfully.', 'woocommerce' ) );

                      }
                    else{

                        $sent='resend';

                        // wc_print_notice( __( 'Error sending Email.', 'error' ) );
                    }

		       }

	       endif;

	       echo $sent;

	       die;

		}

}


// Add shipping Cost to zone
function save_shipping_cost(){

	if(check_ajax_referer( 'shipping-ajaxnonce', 'nonce',false)){

	    $ship_cost=$_POST['ship_cost'];
	    $final_data=array();
	    parse_str($ship_cost, $final_data);
	   	// var_dump($final_data);
	   	$instance_id     = absint( $final_data['instance_id'] );
	   	$shipping_method = WC_Shipping_Zones::get_shipping_method( $instance_id );
		$shipping_method->set_post_data( $final_data );
		$shipping_method->process_admin_options();
		die;
	}

}


// Delete shipping Class
function delete_shipping_class(){
	if(check_ajax_referer( 'shipping-ajaxnonce', 'nonce',false)){

	    $term_id=$_POST['get-zone'];
	     if(!empty($term_id)){

		  	$term_id=intval(wc_clean($term_id));
	        $res=wp_delete_term( $term_id, 'product_shipping_class' );
		     echo $res;
		 }

		die;
	}

}


// Add shipping Class
function add_shipping_class(){
	if(check_ajax_referer( 'shipping-ajaxnonce', 'nonce',false)){

	    $data=$_POST['data'];
	    $final_data=array();
	    $arr=array();
	    $new_arr=array();
	    parse_str($data, $final_data);
			$i=0;
			$j=0;

		foreach ($final_data as $skey => $svalue) {
			$i=0;
			$j=0;
			foreach ($svalue as $main_key => $main_value) {
				if (is_int($main_key)) {
						$arr[$i][$skey]=$main_value;
						$i++;
					}
					else{
						$new_arr[$j][$skey]=$main_value;
						$j++;
					}
			}


		}

		foreach ($arr as $arr_value) {
			if(array_key_exists("term_id", $arr_value)){
				wp_update_term( $arr_value['term_id'], 'product_shipping_class', $arr_value );
			}
		}
		foreach ($new_arr as $new_arr_value) {
				$term = wp_insert_term( $new_arr_value['name'], 'product_shipping_class', $new_arr_value);
				$user_id = get_current_user_id();
				$seller_sclass=get_user_meta($user_id,'shipping-classes',true);
				if(!empty($seller_sclass)){
					$seller_sclass=maybe_unserialize($seller_sclass);
					array_push($seller_sclass, $term['term_id']);
					$seller_sclass_update=maybe_serialize($seller_sclass);
					update_user_meta($user_id,'shipping-classes',$seller_sclass_update);
				}
				else{
					$term_arr[]=$term['term_id'];
					$term_arr=maybe_serialize($term_arr);
					add_user_meta($user_id,'shipping-classes',$term_arr);

				}
			}

		die;


	}

}


// Add shipping method to zone
function add_shipping_method(){

	if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){

	    $zone_id=$_POST['zone-id'];

	    $ship_method=$_POST['ship-method'];

		$current_zone = new WC_Shipping_Zone( $zone_id );

		$confirm=$current_zone->add_shipping_method($ship_method);

		echo $confirm;

		die;
	}

}


// Delete Zone details list ajax
function del_zone(){

    if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
        global $wpdb;

        $zone_id = intval($_POST['zone-id']);

		$table_name = $wpdb->prefix . "mpseller_meta";

		// Using where formatting.

		$wpdb->delete( $table_name, array( 'zone_id' => $zone_id ), array( '%d' ) );

        WC_Shipping_Zones::delete_zone($zone_id);

		die;
	}
}


// seller  approvement ajax
function wk_admin_seller_approve(){
  global $wpdb;
  $seller_id=explode('_mp',$_POST['seller_app']);
  if($seller_id[2]==1)
  {
    $sel_val='customer';
  }
  else{
    $sel_val='seller';
  }
  $data=$wpdb->get_results("UPDATE {$wpdb->prefix}mpsellerinfo SET seller_value = '".$sel_val."' WHERE user_id = '".$seller_id[1]."'");
  if($seller_id[2]==0)
  {
    $wp_user_object = new WP_User($seller_id[1]);
    $wp_user_object->set_role('wk_marketplace_seller');
    $data=$wpdb->get_results("UPDATE {$wpdb->prefix}posts SET post_status = 'publish' WHERE post_author = '".$seller_id[1]."'");
    $tableName='woocommerce_new_temp_settings';
		$data=$seller_id[1];

			apply_filters('woocommerce_approve_seller',$tableName,$data);
  }
  else{
    $wp_user_object = new WP_User($seller_id[1]);
    $wp_user_object->set_role(get_option('default_role'));
    $data=$wpdb->get_results("UPDATE {$wpdb->prefix}posts SET post_status = 'draft' WHERE post_author = '".$seller_id[1]."'");
  }
    echo $seller_id[1].':'.$seller_id[2];
  die;
}


// Re setup commission
function wk_commission_resetup(){

  global $wpdb;

  if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
    $seller_main=$_POST['seller_main'];
    $amt_rem=$_POST['amt_rem'];
    $notify_seller=$_POST['notify_seller'];
    $paid_amt=0;
    $res=$wpdb->query($wpdb->prepare("update {$wpdb->prefix}mpcommision set paid_amount='".$amt_rem."',seller_total_ammount='".$paid_amt."' where seller_id='".$seller_main."'"));
    if($res){
      echo $res;
      if($notify_seller){
        $user_info = get_userdata($seller_main);

        $user_login=$user_info->user_login;
              $message  = __('Hello '.$user_login.',') . "\r\n\r\n";
              $message .= sprintf( __('Your account has been created with : $%s', 'marketplace'),  $amt_rem) . "\r\n\r\n";
              $message .= sprintf( __('Please check your account for further details', 'marketplace'),  $amt_rem) . "\r\n\r\n";
              $message .= sprintf( __('If you have any problems, please contact me at %s', 'marketplace'), get_option('admin_email') ) . "\r\n\r\n";
              $message .= __('Adios!', 'marketplace');
        wp_mail(
                $user_info->user_email,
                sprintf(__('[%s] Seller Notification Regarding Payment', 'marketplace'), get_option('blogname') ),
                $message
            );
      }
    }

    exit;

  }

}


// selller approve metn ajax end
function product_sku_validation(){

  if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
    global $wpdb;

    $chk_sku=$_POST['psku'];

    $data=$wpdb->get_results("select meta_value from $wpdb->postmeta where meta_key='_sku'");

    foreach($data as $d)
    {
      $sku[]=$d->meta_value;
    }

    if(!empty($sku))
    {
      if(in_array($chk_sku,$sku))
      {
        echo __("SKU already exist please select another SKU", 'marketplace');
      }

      else
      {
        echo __("SKU is OK", 'marketplace');
      }
    }

    else
    {
      echo __("SKU is OK", 'marketplace');
    }

    die;
  }

}


//delete image from gallery
function productgallary_image_delete(){

  if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
    $ip=explode('i_',$_POST['img_id']);

    $img_id=get_post_meta( $ip[0], '_product_image_gallery', true );

    $arr = array_diff(explode(',',$img_id), array($ip[1]));

    $remain_ids=implode(',',$arr);

    update_post_meta($ip[0],'_product_image_gallery',$remain_ids);

    echo $remain_ids;

    die;
  }
}


// existing user validation
function existing_user(){

  if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
    global $wpdb;

    $user_name=$_POST['exist_user'];

    $data=$wpdb->get_results("select ID from $wpdb->users where user_login='".$user_name."'");

    if(count($data)==0)
    {
      //echo "user name available";
      echo 1;
    }
    else
    {
      //echo "user name already taken";
      echo 0;
    }
  }
  die;

}


//user email id existing
function seller_email_availability(){

  if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
    global $wpdb;

    $user_email=$_POST['seller_email'];

    $data=$wpdb->get_results("select ID from $wpdb->users where user_email='".$user_email."'");

    if(count($data)==0)
    {
      echo 0;
    }
    else
    {
      echo 1;
    }
  }
  die;

}


/* login with facebook */
function mp_login_with_facebook(){

    if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
        if($_POST['facebook_info']['status'] =='connected')
        {
            if(isset($_POST['username']) && isset($_POST['name']) && isset($_POST['email']))
            {
                $user_creds=array();
                $user_pass=wp_generate_password();
                $user_nick=$_POST['name'];
                $username=$_POST['username'];
                $user_email=$_POST['email'];
                $user_creds=array('user_login'=>"$username",'user_pass'=>"$user_pass",'user_nicename'=>"$user_nick",'user_email'=>"$user_email",'display_name'=>"$user_nick");
                try
                  {

                    if(!email_exists( $user_email )){
                        $newuser_id=wp_insert_user($user_creds);

                        if ( is_wp_error( $newuser_id ) && isset($_POST['user_email']) ) {
                                  throw new Exception( $newuser_id->get_error_message() );
                            }
                        // include('includes/class-mp-form-handler.php');
                        // $wpmp_obj11=new MP_Form_Handler();
                        // $wpmp_obj11->mp_user_welcome($newuser_id,$user_pass);
                        $data=array("user_login"=>$user_email,"user_email"=>$newuser_id,"user_pass"=>$user_pass);
                        $tableName='woocommerce_new_seller_settings';
                        apply_filters('new_seller_registration',$tableName,$data);
                        wp_set_current_user($newuser_id); // set the current wp user
                        wp_set_auth_cookie($newuser_id);
                        /*$page_id=MP_Form_Handler::get_page_id(get_option('wkmp_seller_login_page_title'));*/

                        exit;
                    }
                    else{
                              $newuser_id= email_exists( $user_email );

                        if(!is_wp_error($newuser_id))
                            {
                                wp_set_current_user($newuser_id); // set the current wp user
                                wp_set_auth_cookie($newuser_id);

                                throw new Exception('success');

                                exit;
                            }

                    }

                }
                  catch (Exception $e)
                  {
                    if($e->getMessage()=='success')
                    {
                          wc_add_notice( apply_filters('register_errors', 'Registration complete check your mail for password!' ),$e->getMessage() );
                    }
                    else
                    {
                          wc_add_notice( apply_filters('register_errors', $e->getMessage() ), 'error' );
                    }
                  }
            }

        }
        else{
            exit;
        }
    }
    die;
}


function wk_check_myshop_value(){

        if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){

	        $url_slug = $_POST['shop_slug'];
	        $check    = false;
	        $user     = get_user_by( 'slug', $url_slug );

	        if ( empty( $url_slug ) ) {
	            $check=false;
	        }
	        else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬]/', $url_slug))
			{
				$check=false;
	        }
	        else if(ctype_space($_POST['shopurl'])){
	        	$check=false;
	        }
	        else if ( $user != '' ) {
	            $check = 2;
	        }
	        else{
	        	$check=true;
	        }

	        echo $check;

			die;
	}
}


/* marketplace variation function */
function marketplace_attributes_variation($var_id) {

  if(isset($_POST['product']) && !empty($_POST['product']))
  {
    $wk_pro_id=$_POST['product'];
    $post_title='Variation #'.$wk_pro_id.' of Product';
    $post_name='product-'.$wk_pro_id.'-variation';
    $product_data=array(
      'post_author'=>get_current_user_id(),
        'post_date'=>'',
        'post_date_gmt'=>'',
        'post_content'=>'',
      'post_content_filtered'=>'',
      'post_title'=>$post_title,
      'post_excerpt'=>'',
      'post_status'=>'publish',
        'post_type'=>'product_variation',
        'comment_status'=>'open',
        'ping_status'=>'open',
        'post_password'=>'',
        'post_name'=>$post_name,
        'to_ping'=>'',
        'pinged'=>'',
        'post_modified'=>'',
        'post_modified_gmt'=>'',
        'post_parent'=>$wk_pro_id,
        'menu_order'=>'',
        'guid'=>''
    );
    $var_id = wp_insert_post($product_data);
    require_once( WK_MARKETPLACE_DIR . 'includes/templates/front/single-product/variations.php' );
    die;
  }
  else
  {
    $wk_pro_id=$var_id;
    $args = array(
      'post_parent' => $wk_pro_id,
      'post_type'   => 'product_variation',
      'posts_per_page' => -1,
      'post_status' => 'publish'
    );
    $children_array = get_children( $args);
    $i=0;
    foreach($children_array as $var_att)
    {
      attribute_variation_data($var_att->ID,$wk_pro_id);
      $i++;
    }
  }
  if(isset($_POST['product']))
  wp_die();
}


function mpattributes_variation_remove(){

    if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
      $var_id=$_POST['var_id'];
      wp_delete_post($var_id);
    }
    die;
}


function mp_downloadable_file_add(){

  if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
    $y=$_POST['var_id'];
    $i=$_POST['eleme_no'];
    ?>
    <div class="tr_div">
    <div>
    <label for="downloadable_upload_file_name_<?php echo $y.'_'.$i;?>"><?php echo __('NAME', 'marketplace'); ?></label>
    <input type="text" class="input_text" placeholder="File Name" id="downloadable_upload_file_name_<?php echo $y.'_'.$i;?>" name="_mp_variation_downloads_files_name[<?php echo $y; ?>][<?php echo $i;?>]" value="<?php echo $pro_downloadable['name'];?>">
    </div>
    <div class="file_url">
    <label for="downloadable_upload_file_url_<?php echo $y.'_'.$i;?>"><?php echo __('File Url', 'marketplace'); ?></label>
    <input type="text" class="input_text" placeholder="http://" id="downloadable_upload_file_url_<?php echo $y.'_'.$i;?>" name="_mp_variation_downloads_files_url[<?php echo $y; ?>][<?php echo $i;?>]" value="<?php echo $pro_downloadable['file'];?>">
    <a href="javascript:void(0);" class="button wkmp_downloadable_upload_file" id="<?php echo $y.'_'.$i;?>"><?php echo __('Choose&nbsp;file', 'marketplace'); ?></a>
    <a href="javascript:void(0);" class="delete mp_var_del" id="mp_var_del_<?php echo $y.'_'.$i;?>"><?php echo __('Delete', 'marketplace'); ?></a>
    </div>
    <div class="file_url_choose">

    </div>
    </div>
      <?php
    die;
  }
}


function marketplace_statndard_payment(){

  if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
    global $wpdb;
    $seller_id=$_POST['seller_id'];
    $query = "select com.payment_id_desc,com.seller_total_ammount,com.paid_amount,com.seller_payment_method,user.user_nicename from {$wpdb->prefix}mpcommision as com join {$wpdb->prefix}users as user on com.seller_id=user.ID where com.seller_id=$seller_id";
    $seller_data = $wpdb->get_results($query);
    $remain_ammount=$seller_data[0]->seller_total_ammount;
    $seller_paypal_id=$seller_data[0]->payment_id_desc;
    $currency_code=get_option('woocommerce_currency');
    $cur_symbol=get_woocommerce_currency_symbol($currency_code);
    $payable_ammount=get_option('wkmpseller_ammount_to_pay');
    if($payable_ammount>$remain_ammount)
    {
    $payable_ammount=$remain_ammount;
    }
    $seller_get_payment_method=$seller_data[0]->seller_payment_method;

    if($seller_get_payment_method=='PayPal')
    {
      $paypal_settings=get_option("woocommerce_paypal_settings");
      if($paypal_settings['enabled']=='yes' && $paypal_settings['testmode']=='yes')
      {
      $url='https://www.sandbox.paypal.com/cgi-bin/webscr';
      }
      else
      {
        $url='https://www.paypal.com/cgi-bin/webscr';
      }
    }
    else
    {
      $url='';
    }
    ?>
    <div class="standard-pay-header">
      <h3><?php echo _e("Standard Payment Method","Marketplace"); ?></h3>
      <span class="standard-pay-close">x</span>
      <span style="clear:both;"></span>
    </div>
    <form id="Standard-Payment-form" method="post" action="<?php echo $url;?>">

    <input type="hidden" name="cmd" value="_xclick">
      <!-- Paypal implementation -->
      <input type="hidden" name="business" value="<?php echo $seller_paypal_id;?> ">
      <input type="hidden" name="item_name" value="seller-<?php echo $seller_id;?>">
        <input type="hidden" name="item_number" value="<?php echo $seller_id;?>">
        <input type="hidden" name="currency_code" value="<?php echo $currency_code;?>">
      <input type="hidden" name="notify_url" value="<?php echo get_home_url();?>/wp-admin/admin.php?page=Commisions">
        <input type="hidden" name="return" value="<?php echo get_home_url();?>/wp-admin/admin.php?page=Commisions&sid=<?php echo $seller_id;?>">
        <input type="hidden" name="cancel_return" value="<?php echo get_home_url();?>/wp-admin/admin.php?page=Commisions">
        <!-- <input type="hidden" name="amount" value="<?php //echo $payable_ammount;?>">  -->
        <!-- Paypal implementation -->
        <input type="hidden" id="mp_payment_url" value="<?php echo $url;?>">
      <p><span class="label"><?php echo _e("Name",'marketplace'); ?> :</span>
      <input id='mp_paying_acc_id' type="hidden" value="<?php echo $seller_id;?>"/>
      <input id='mp_pay_seller_name' name="mp_pay_seller_name" type="text" value="<?php echo $seller_data[0]->user_nicename;?>" readonly />
      </p>
      <p><span class="label"><?php echo _e("Remain Ammount",'marketplace'); ?>:</span>
      <input id='mp_remain_ammount' type="text" value="<?php echo $remain_ammount;?>" name="mp_remain_ammount" readonly /><span><?php echo $cur_symbol;?></span>
      </p>
      <p><span class="label"><?php echo _e("Paying Ammount",'marketplace'); ?>:</span>
      <input id='mp_paying_ammount'  name="amount" type="text" value="<?php echo $payable_ammount;?>"><span><?php echo $cur_symbol;?></span>
      <span class="label">&nbsp;</span><span  id="mp_paying_ammount_error" class="error-class"></span>
      </p>
      <div class="wkmp-modal-footer">
      <input id="MakePaymentbtn" type="button" value="Make Payment" class="button">
      <span style="clear:both;"></span>
      </div>
    </form>
    <?php
  }
  die;
}


function wk_search_seller(){
      if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
        if (!empty($_POST['seller_char'])) {

          global $wpdb;
          $res=array();
          $search = $_POST['seller_char'];

          $query = "select meta.meta_value, A.user_id from {$wpdb->prefix}mpsellerinfo as A join {$wpdb->prefix}usermeta as meta on A.user_id=meta.user_id where A.seller_value = 'seller' and meta.meta_key='first_name' and meta.meta_value like '%".$search."%'";

          $result = $wpdb->get_results($query);

          foreach ($result as $key=>$value) {
            $res['user_id'][$key] = $value->user_id;
            $res['first_name'][$key] = $value->meta_value;
          }

          if(count($res)>0){
          echo json_encode(array(
              'user_id' => $res['user_id'],
                'first_name' => $res['first_name']
          ));
        }
        else {
            echo 0;
        }

         exit;

        }

      }

}


function wk_search_group(){

      if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
        if (!empty($_POST['group_char'])) {

          global $wpdb;
          $res=array();
          $search = $_POST['group_char'];

          $query = "Select term_relation.object_id, post.ID, post.post_title, meta.meta_value from {$wpdb->prefix}posts as post join {$wpdb->prefix}term_relationships as term_relation join {$wpdb->prefix}postmeta as meta on term_relation.object_id = post.ID and meta.post_id = term_relation.object_id where term_relation.term_taxonomy_id = 3 and meta.meta_key = '_sku' and post.post_title like '%".$search."%'";

          $result = $wpdb->get_results($query);

          foreach ($result as $key=>$value) {
            $res['id'][$key] = $value->object_id;
            $res['_sku'][$key] = $value->meta_value;
            $res['post_title'][$key] = $value->post_title;
          }
          if(count($res)>0){
          echo json_encode(array(
              '_sku' => $res['_sku'],
                'post_title' => $res['post_title'],
                'id'	=> $res['id']
          ));
        }
        else {
            echo 0;
        }

         exit;

        }

      }

}


function marketplace_mp_make_payment(){

  if(check_ajax_referer( 'ajaxnonce', 'nonce',false)){
    global $wpdb;
    $id=$_POST['seller_acc'];
    $remain=$_POST['remain'];
    $pay=$_POST['pay'];
    $query = "select * from {$wpdb->prefix}mpcommision where seller_id=$id";
    $seller_data = $wpdb->get_results($query);
    $paid_ammount=$seller_data[0]->paid_amount+$pay;
    $seller_total_ammount=$seller_data[0]->seller_total_ammount-$pay;
    $last_paid_ammount=$pay;
    $seller_money=$seller_data[0]->last_com_on_total-$seller_data[0]->admin_amount;
    $remain_ammount=$seller_money-$paid_ammount;//seller total amount
    $wpdb->get_results("update {$wpdb->prefix}mpcommision set paid_amount='".$paid_ammount."',seller_total_ammount='".$seller_total_ammount."',seller_total_ammount='".$remain_ammount."',last_paid_ammount='".$last_paid_ammount."'where seller_id='".$id."'");

  }

}
