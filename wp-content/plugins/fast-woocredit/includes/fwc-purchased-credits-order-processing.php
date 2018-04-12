<?php

/* 
 * purchased credit record and show script
 */

function fwc_record_customers_credit_amount( $order_id ) {
    $order = wc_get_order( $order_id );
    if( !is_object($order) ) return;
    if ( is_user_logged_in() ) {
        $userid = get_current_user_id();
    } else {
        $usermail = $order->billing_email;
        $user = get_user_by( 'email', $usermail );
        $userid = $user->ID;
    }
    if( !$userid ) return;
    
    
    
    
    $users_total_credit_amount = 0;
    $count = 0;
    $fwc_total_credit_amount = get_user_meta( $userid, 'fwc_total_credit_amount', true );
    if ( !empty( $fwc_total_credit_amount ) && is_numeric( $fwc_total_credit_amount ) ) {
        $users_total_credit_amount += get_user_meta( $userid, 'fwc_total_credit_amount', true );
    }
    
    foreach ( $order->get_items() as $item ) {
        $chk_product = $order->get_product_from_item( $item );
        $cp_fwc_credit_amount = get_post_meta( $chk_product->id, 'fwc_credit_amount', true );
        $cpt_fwc_credit_amount = $cp_fwc_credit_amount * $item['qty'];
        if ( !empty( $cp_fwc_credit_amount ) && is_numeric( $cp_fwc_credit_amount ) ) {
            $users_total_credit_amount += $cpt_fwc_credit_amount;
            $count++;
        }
    }
    if ( $count>0 )
        update_user_meta( $userid, 'fwc_total_credit_amount', $users_total_credit_amount );
    
}

add_action('woocommerce_order_status_completed', 'fwc_record_customers_credit_amount' );


function fwc_show_credit_amounts() {
    
    if ( is_user_logged_in() ) {
        $userid = get_current_user_id();
    } else return;
    $users_total_credit_amount = get_user_meta( $userid, 'fwc_total_credit_amount', true );
    
    if( isset( $users_total_credit_amount ) ) { ?>
        
        <h2><?php echo apply_filters( 'fwc_my_account_credit_amount_title', __( 'Available Credit Amount: ', 'woocommerce' ) ) . $users_total_credit_amount; ?></h2>
        
<?php    

    }
}

add_action( 'woocommerce_before_my_account', 'fwc_show_credit_amounts' );



function fwc_order_item_needs_processing( $sent_value, $product, $order_id ) {
    if ( $product->product_type == 'fast-credit' )
        return false;
}

add_filter( 'woocommerce_order_item_needs_processing', 'fwc_order_item_needs_processing', 10, 3 );


function fwc_show_user_credit_amounts($user) {
    $val = get_the_author_meta('fwc_total_credit_amount', $user->ID );
    echo "<h3>Fast WooCredit Settings</h3>
        <table class='form-table'>
            <tr>
                <th><label for='fwc_credit_amnt'>Total Credit Amount:</label></th>
                <td>
                    <input type='text' name='fwc_credit_amnt' id='fwc_credit_amnt' value='$val' class='regular-text' style='width: 50px' />
                </td>
            </tr>
        </table>";
}
add_action('show_user_profile', 'fwc_show_user_credit_amounts');
add_action('edit_user_profile', 'fwc_show_user_credit_amounts');


function fwc_manual_update_user_credit_amounts($user_id) {
    if ( !current_user_can('edit_users') ) {
	return false;
    }
    update_user_meta( $user_id, 'fwc_total_credit_amount', $_POST['fwc_credit_amnt'] );
}
add_action('personal_options_update', 'fwc_manual_update_user_credit_amounts');
add_action('edit_user_profile_update', 'fwc_manual_update_user_credit_amounts');


function fwc_show_user_credit_amount_public() {
    
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $total_amount = get_user_meta( $user_id, 'fwc_total_credit_amount', true );
    }
    
    $total_amount = empty($total_amount) ? 0 : $total_amount;
    
    return $total_amount;
    
}
add_shortcode( 'fwc_amount', 'fwc_show_user_credit_amount_public' );
