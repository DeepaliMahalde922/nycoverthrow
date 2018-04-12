<?php

/* 
 * order processing script for purchase using credit balance  
 */

function fwc_product_purchase_with_credit() {
    if( isset($_POST['fwc-credit-product']) && isset($_POST['fwc-credit-user'])  && isset($_POST['fwc-credit-value']) ) {
        $order_customer_id = sanitize_text_field( $_POST['fwc-credit-user'] );
        $users_total_credit_amount = get_user_meta( $order_customer_id, 'fwc_total_credit_amount', true );
        $fwc_order_qty = ( isset( $_POST['quantity'] ) ) ? sanitize_text_field( $_POST['quantity'] ) : 1;
        $fwc_credit_value = sanitize_text_field( $_POST['fwc-credit-value'] );
        $fwc_order_req = $fwc_credit_value * $fwc_order_qty;
        if( $fwc_order_req >$users_total_credit_amount ) { return; }
        $order = wc_create_order( array( 'status' => 'pending',"customer_id" => $order_customer_id ) );
        $product_id = sanitize_text_field( $_POST['fwc-credit-product'] );
        $product = wc_get_product($product_id);
        $order->add_product( $product, 1 );
        $billing_email = get_userdata( $_POST['fwc-credit-user'] )->user_email;
        $billing_address = array(
            'email'      => $billing_email
        );
        if( !empty( $billing_email ) ) $order->set_address( $billing_address, 'billing' );
        update_post_meta( $order->id, '_payment_method_title', 'FastCredit' );
        $order->update_status( 'completed' );
        $fwc_user_credit_remain = $users_total_credit_amount - $fwc_order_req;
        update_user_meta( $order_customer_id, 'fwc_total_credit_amount', $fwc_user_credit_remain );
        $redir_url = sanitize_text_field( $_POST['fwc-credit-url'] );
        wp_redirect( $redir_url );
        exit;
    }
}
add_action( 'init', 'fwc_product_purchase_with_credit' );
