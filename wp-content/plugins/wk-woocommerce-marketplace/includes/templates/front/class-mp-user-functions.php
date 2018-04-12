<?php

if( ! defined ( 'ABSPATH' ) )

    exit;



function mp_set_user_role( $user_id, $role, $old ) {

  global $wpdb;

  $seller_table = $wpdb->prefix.'mpsellerinfo';

  $seller_id = $wpdb->get_results("SELECT seller_id from $seller_table where user_id = '$user_id'"
    );

  $seller_id = $seller_id[0]->seller_id;

    foreach ($old as $key => $value) {

      if ($value == 'wk_marketplace_seller') {

        $seller = array( 'seller_value'=>"customer" );

        $seller_res = $wpdb->update($seller_table, $seller, array('seller_id' => $seller_id ));

      }

    }

}

function asktoadmin(){
    require_once('myaccount/ask-to-admin.php');
}

function wk_Change_password(){
    require_once('myaccount/forgot-password.php');
}

function shop_followers(){
    require_once('myaccount/shop-followers.php');
}

function spreview(){
    require_once('myaccount/preview.php');
}

function seller_all_product(){
    require_once('myaccount/user-product.php');
}

function efeedback(){
    require_once('myaccount/feedback.php');
}

function seller_profile($atts){
    require_once('myaccount/profile.php');
}

function edit_profile(){
    require_once('myaccount/prof_edit.php');
}

function dashboard(){

  require_once('myaccount/dashboard.php');

	$wprd_obj1=new MP_Report_Dashboard();

	$wprd_obj1->mp_dashboard_page();

}
