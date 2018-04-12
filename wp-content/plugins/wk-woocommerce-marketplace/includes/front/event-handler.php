<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

$mp_form_handler = new MP_Form_Handler();

$mp_login_handler = new MP_Login_Handler();

$mp_register_handler = new MP_Register_Handler();

add_action( 'wp', array( $mp_form_handler, 'calling_pages' ) );

add_action( 'woocommerce_created_customer', array( $mp_register_handler, 'process_registration' ), 10, 2 );

add_action( 'init', array( $mp_login_handler, 'process_mp_login' ) ); // process Popup login form

add_filter( 'woocommerce_new_customer_data',array( $mp_register_handler, 'marketplace_new_customer_data' ) );

add_action( 'woocommerce_register_form', 'mp_seller_reg_form_fields' );

add_action( 'template_redirect',array($mp_login_handler, 'my_page_template_redirect' ));

// Redirect to specific page after login
add_filter('woocommerce_login_redirect',array($mp_login_handler, 'mp_login_redirect'),10,2);

add_filter( 'woocommerce_process_registration_errors', array($mp_register_handler, 'mp_seller_registration_errors' ));

add_filter( 'registration_errors', array($mp_register_handler, 'mp_seller_registration_errors' ));

// Product by Feature on product page
add_action( 'woocommerce_single_product_summary', 'woocommerce_product_by', 11 );

add_action( 'woocommerce_single_product_summary', 'add_favourite_seller_btn', 32 );


/*----------*/ /*---------->>> MP USER DATA <<<----------*/ /*----------*/

add_action( 'set_user_role', 'mp_set_user_role', 10, 3 );
