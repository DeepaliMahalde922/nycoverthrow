<?php

/* 
 * adding button script for purchase using credit balance
 * and remove Add To Cart buttom if setting option selected
 */

function fwc_check_purchase( $product ) {
    
    $customer_orders = get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => 'shop_order',
        'post_status' => 'wc-completed'
    ) );

    if ( $customer_orders ) {
        foreach ( $customer_orders as $customer_order ) {
            $order = wc_get_order( $customer_order );
            foreach ( $order->get_items() as $item ) {
                $chk_product = $order->get_product_from_item( $item );
                if ( ( $product->id == $chk_product->id )  
                    && ( $product->downloadable == 'yes' ) ) {
                        return true;
                }
            }
        }
    }

    return false;
}



function fwc_credit_button(){
    global $product;
    $product_id = $product->id;
    $cp_fwc_credit_value = get_post_meta( $product_id, 'fwc_credit_value', true );
    if ( empty( $cp_fwc_credit_value ) || !is_numeric( $cp_fwc_credit_value ) ) {
        if ( !in_array( 'fast-woodownload/fast-woodownload.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            wc_get_template( 'single-product/add-to-cart/simple.php' );
        }
        return;
    }
    
    $userid = get_current_user_id();
    
    $users_total_credit_amount = get_user_meta( $userid, 'fwc_total_credit_amount', true );
    if( !empty($users_total_credit_amount) && intval($users_total_credit_amount)>0 && 
        $cp_fwc_credit_value <= $users_total_credit_amount ) {
            $cp_fwc_remove_atc = get_post_meta( $product_id, 'fwc_remove_atc', true );
            if ( ( empty( $cp_fwc_remove_atc ) || $cp_fwc_remove_atc == 'no' ) &&
                    !in_array( 'fast-woodownload/fast-woodownload.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                wc_get_template( 'single-product/add-to-cart/simple.php' );
            }
            $cp_fwc_credit_url = get_post_meta( $product_id, 'fwc_credit_conf_url', true );
            $dwnld_btn_txt = get_post_meta( $product_id, 'fwc_credit_btn_txt', true );
            if ( !isset( $dwnld_btn_txt ) || $dwnld_btn_txt == "" ) {
                $dwnld_btn_txt = "Use Credit";
            }
            if ( empty( $cp_fwc_credit_url ) || $cp_fwc_credit_url == "" ) {
                $cp_fwc_credit_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
            }
            $cp_fwc_remaining_credit = $users_total_credit_amount - $cp_fwc_credit_value;
            if ( fwc_check_purchase( $product ) == true &&
                    in_array( 'fast-woodownload/fast-woodownload.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                return;
            }
            echo '<form class="cart" method="post" enctype="multipart/form-data">';
            echo '<input type="hidden" name="fwc-credit-user" value="' . $userid . '" />';
            echo '<input type="hidden" name="fwc-credit-product" value="' . $product_id . '" />';
            echo '<input type="hidden" name="fwc-credit-url" value="' . $cp_fwc_credit_url . '" />';
            echo '<input type="hidden" name="fwc-credit-value" value="' . $cp_fwc_credit_value . '" />';
            echo '<span style="float: left; margin-right: 10px;">Credit Value:&nbsp;' . $cp_fwc_credit_value . '</span>&nbsp;&nbsp;<br/><br/>';
            echo '<span style="float: left; margin-right: 10px;" id="fwc-total-credit-req">Total Value:&nbsp;';
            if ( isset( $_POST['quantity'] ) ) { echo ($_POST['quantity'] * $cp_fwc_credit_value); } else { echo $cp_fwc_credit_value; }
            echo '</span>&nbsp;&nbsp;<br/><br/>';
            echo '<script type="text/javascript"> jQuery(document).ready(function(){'
             . ' var totalReq = jQuery( "input[name=\'quantity\']" ).val()*' . $cp_fwc_credit_value . '; '
                    . ' var totalCredit = ' . $users_total_credit_amount . '; '
                    . 'if( totalReq > totalCredit ) {'
                    . ' document.getElementById( "fwc-total-credit-req" ).innerHTML = "<b>Insufficient credit balance.</b>"; '
                    . ' } else { '
                    . ' document.getElementById( "fwc-total-credit-req" ).innerHTML = "<b>Total Value:&nbsp;" + jQuery( "input[name=\'quantity\']" ).val()*' . $cp_fwc_credit_value . ' + "</b>"; '
                    . ' } '
            . ' jQuery( "input[name=\'quantity\']" ).change(function() { '
                    . ' var totalReq2 = jQuery( "input[name=\'quantity\']" ).val()*' . $cp_fwc_credit_value . '; '
                    . ' var totalCredit2 = ' . $users_total_credit_amount . '; '
                    . 'if( totalReq2 > totalCredit2 ) {'
                    . ' document.getElementById( "fwc-total-credit-req" ).innerHTML = "<b>Insufficient credit balance.</b>"; '
                    . ' } else { '
                    . ' document.getElementById( "fwc-total-credit-req" ).innerHTML = "<b>Total Value:&nbsp;" + jQuery( "input[name=\'quantity\']" ).val()*' . $cp_fwc_credit_value . ' + "</b>"; '
                    . ' } '
                    . '}); '
                    . '  }); </script>';
            echo '<div class="fwc-qty-btn-cont">';
            echo woocommerce_quantity_input( array(
	 				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
	 				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
	 				'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
	 			) );
            echo '<button type="submit" class="single_add_to_cart_button button alt">' . $dwnld_btn_txt . '</button>'
                    . '</div></form>';
        } else {
            if ( fwc_check_purchase( $product ) == true &&
                    in_array( 'fast-woodownload/fast-woodownload.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                return;
            }
            wc_get_template( 'single-product/add-to-cart/simple.php' );
        }
    return;
}

/*function fwc_loop_dwnld_button( $content, $product ) {
    
}*/


function fwc_loop_dwnld_button( $content, $product ) {
    $product_id = $product->id;
    $cp_fwc_credit_value = get_post_meta( $product_id, 'fwc_credit_value', true );
    if ( empty( $cp_fwc_credit_value ) || !is_numeric( $cp_fwc_credit_value ) ) {
        return $content;
    }
    
    $userid = get_current_user_id();
    
    if ( fwc_check_purchase( $product ) == true &&
        in_array( 'fast-woodownload/fast-woodownload.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
        return $content;
    $users_total_credit_amount = get_user_meta( $userid, 'fwc_total_credit_amount', true );
    if( !empty($users_total_credit_amount) && intval($users_total_credit_amount)>0 && 
        $cp_fwc_credit_value <= $users_total_credit_amount ) {
            $cp_fwc_remove_atc = get_post_meta( $product_id, 'fwc_remove_atc', true );
            $cp_fwc_credit_url = get_post_meta( $product_id, 'fwc_credit_conf_url', true );
            $dwnld_btn_txt = get_post_meta( $product_id, 'fwc_credit_btn_txt', true );
            if ( !isset( $dwnld_btn_txt ) || $dwnld_btn_txt == "" ) {
                $dwnld_btn_txt = "Use Credit";
            }
            if ( empty( $cp_fwc_credit_url ) || $cp_fwc_credit_url == "" ) {
                $cp_fwc_credit_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
            }
            if (  !empty( $cp_fwc_remove_atc ) && $cp_fwc_remove_atc == 'yes' ) {
                $cp_fwc_remaining_credit = $users_total_credit_amount - $cp_fwc_credit_value;
                $return_credit_html = '';
                $return_credit_html .= '<form class="cart" method="post" enctype="multipart/form-data">';
                $return_credit_html .= '<input type="hidden" name="fwc-credit-user" value="' . $userid . '" />';
                $return_credit_html .= '<input type="hidden" name="fwc-credit-product" value="' . $product_id . '" />';
                $return_credit_html .= '<input type="hidden" name="fwc-credit-url" value="' . $cp_fwc_credit_url . '" />';
                $return_credit_html .= '<input type="hidden" name="fwc-remaining-credit" value="' . $cp_fwc_remaining_credit . '" />';
                $return_credit_html .= '<span style="float: left; margin-right: 10px;">Credit Value:&nbsp;' . $cp_fwc_credit_value . '</span>&nbsp;&nbsp;';
                $return_credit_html .= '<button type="submit" class="single_add_to_cart_button button alt">' . $dwnld_btn_txt . '</button></form>';
                return $return_credit_html;
            }
    }
    return $content;
}


function fwc_product_credit_data_use() {
    if ( is_user_logged_in() ) {
        remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
        add_action( 'woocommerce_simple_add_to_cart', 'fwc_credit_button', 30 );
        add_filter( 'woocommerce_loop_add_to_cart_link', 'fwc_loop_dwnld_button', 12, 2 );
    }
}
add_action( 'init', 'fwc_product_credit_data_use' );

function fwc_single_add_to_cart() {
    wc_get_template( 'single-product/add-to-cart/simple.php' );
}
add_action( 'woocommerce_fast-credit_add_to_cart', 'fwc_single_add_to_cart' );

function fwc_loop_cart_button_scripts() {
    if( is_shop() ) {
        wp_enqueue_script('fwc-loop-cart-button', FWC_BASE_URL . 'assets/js/fwc-loop-cart-button.js');
    }
}
add_action( 'wp_enqueue_scripts', 'fwc_loop_cart_button_scripts' );
