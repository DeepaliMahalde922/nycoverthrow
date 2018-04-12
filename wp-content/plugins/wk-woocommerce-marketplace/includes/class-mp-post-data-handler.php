<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

function save_version_meta( $post_id, $post, $update){

    global $wpdb;

  	if ( isset( $_POST['blog_meta_box_nonce'] ) ) {
  	// Verify that the nonce is valid.
  	if ( wp_verify_nonce( $_POST['blog_meta_box_nonce'], 'blog_save_meta_box_data' ) ) {

       	if(!empty($_REQUEST['seller_id']) && !empty($_REQUEST['post_id'])){

       		$table_name = "{$wpdb->prefix}posts";

       		$res = $wpdb->update($table_name,array('post_author'=> $_REQUEST['seller_id']), array('ID' => $_REQUEST['post_id']), array('%d'), array('%d'));

       	}

      }

  	}

}

function save_extra_user_profile_fields( $user_id ) {

	$shop_name = $_POST['shopname'];

	$shop_url = $_POST['shopurl'];

	$role = $_POST['role'];

	if ( $role == 'wk_marketplace_seller' ) {

		$user_creds = array('ID' => $user_id, 'user_nicename' => "$shop_url" );

		wp_update_user($user_creds);

		update_user_meta( $user_id,'shop_name', $shop_name );

		update_user_meta( $user_id,'shop_address', $shop_url );

		update_user_meta( $user_id,'wk_user_address', '');

		global $wpdb;

		$seller_table = $wpdb->prefix.'mpsellerinfo';

		$res_query = $wpdb->get_results("SELECT seller_id from $seller_table where user_id = '$user_id'"
			);

		if ($res_query) {

			$seller_id = $res_query[0]->seller_id;

		}

		$seller_key = 'role';

		if (isset($seller_id) && !empty($seller_id)) {

			if(get_option('wkmp_auto_approve_seller')){
				$seller=array( 'user_id'=>$user_id,'seller_key'=>$seller_key,'seller_value'=>"seller");
			}else{
				$seller=array( 'user_id'=>$user_id,'seller_key'=>$seller_key,'seller_value'=>"customer");
			}

			$seller_res = $wpdb->update($seller_table, $seller, array('seller_id' => $seller_id));

		}

		else {

			if(get_option('wkmp_auto_approve_seller')){
				$seller=array('user_id'=>$user_id,'seller_key'=>$seller_key,'seller_value'=>"seller");
			}else{
				$seller=array('user_id'=>$user_id,'seller_key'=>$seller_key,'seller_value'=>"customer");
			}

			$seller_res = $wpdb->insert($seller_table, $seller);

		}

	}

}
