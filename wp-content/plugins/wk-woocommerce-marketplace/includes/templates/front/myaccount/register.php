<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

function mp_seller_reg_form_fields(){

  if( !get_option('wkmp_show_seller_seperate_form') || empty(get_option('wkmp_show_seller_seperate_form')) ){

    if(isset($_POST)){
      $postdata = $_POST;
      $role = isset( $postdata['role'] ) ? $postdata['role'] : 'customer';
      $role_style = ( $role == 'customer' ) ? ' style="display:none"' : '';
      include_once 'registration.php';
    }

  }
  elseif(get_option('wkmp_show_seller_seperate_form')){

    $page_slug = get_query_var('pagename');
    if( $page_slug === 'seller' ){
      include_once 'seller-seperate-form.php';
    }
  }
}
