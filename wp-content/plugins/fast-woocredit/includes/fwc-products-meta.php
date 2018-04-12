<?php

/* 
 * woocommerce products meta script
 */

function fwc_add_custom_product_options( $options ) {
    $options['virtual'] = array(
                            'id'            => '_virtual',
                            'wrapper_class' => 'show_if_fast-credit show_if_simple',
                            'label'         => __( 'Virtual', 'woocommerce' ),
                            'description'   => __( 'Virtual products are intangible and aren\'t shipped.', 'woocommerce' ),
                            'default'       => 'no'
                        );
    $options['fwc_sold_individually'] = array( 
                            'id' => '_fwc_sold_individually', 
                            'wrapper_class' => 'show_if_only_fast-credit', 
                            'label' => __( 'Sold Individually', 'woocommerce' ), 
                            'description' => __( 'Enable this to only allow one of this item to be bought in a single order', 'woocommerce' ),
                        );
    $options['fwc_credit_purchase'] = array(
                            'id'            => '_fwc_credit_purchase',
                            'wrapper_class' => 'show_if_simple',
                            'label'         => __( 'Cedit Purchase', 'woocommerce' ),
                            'description'   => __( 'Enable this to allow this item to be purchased using credit balance.', 'woocommerce' ),
                            'default'       => 'no'
                        );
    return $options;
}

add_filter( 'product_type_options', 'fwc_add_custom_product_options' );


function fwc_create_custom_product_options() {
    
    // Credit Amount
    woocommerce_wp_text_input( 
            array( 
                'id'            => 'fwc_credit_amount', 
                'wrapper_class' => 'show_if_only_fast-credit', 
                'label'         => __( 'Credit Amount', 'woocommerce' ), 
                'placeholder'   => '',
                'desc_tip'      => 'true',
                'description'   => __( 'Enter credit amount of this WooCredit Product.', 'woocommerce' ),
                'data_type'     => 'number' 
            ) );
    
    echo '</div>';
    
    echo '<div class="options_group show_if_credit_purchase" style="display: none;">';
    
    // Credit Amount
    woocommerce_wp_text_input( 
            array( 
                'id'            => 'fwc_credit_value', 
                'label'         => __( 'Credit Value', 'woocommerce' ), 
                'placeholder'   => '',
                'data_type'     => 'number' 
            ) );
    
    // Credit Confirm URL
    woocommerce_wp_text_input( 
            array( 
                'id'            => 'fwc_credit_conf_url', 
                'label'         => __( 'Credit Confirm URL', 'woocommerce' ), 
                'placeholder'   => '',
                'desc_tip'      => 'true',
                'description'   => __( 'Leave blank to go to default My Account page', 'woocommerce' ),
                'data_type'     => 'text' 
            ) );
    
    // Credit Button text
    woocommerce_wp_text_input( 
            array( 
                'id'            => 'fwc_credit_btn_txt', 
                'label'         => __( 'Credit Button Text', 'woocommerce' ), 
                'placeholder'   => '',
                'desc_tip'      => 'true',
                'description'   => __( 'Leave blank for default valur "Use Credit"', 'woocommerce' ),
                'data_type'     => 'text' 
            ) );
    
    // Hide/Show add_to_cart button
    woocommerce_wp_checkbox( 
            array(  
                'id'            => 'fwc_remove_atc',
                'label'         => __( 'Remove Add To Cart', 'woocommerce' ), 
                'description'   => __( 'Enable this to only allow access to this product using credit balance', 'woocommerce' ) 
            ) );
    
    wp_enqueue_script('fwc-credit-purchase', FWC_BASE_URL . 'assets/js/fwc-credit-purchase.js');
    
}

add_action( 'woocommerce_product_options_pricing', 'fwc_create_custom_product_options', 5 );


function fwc_save_custom_settings( $post_id ) {
    // save credit amount field
    $fwc_product_credit_amount = $_POST['fwc_credit_amount'] ;
    if( !empty( $fwc_product_credit_amount ) ) {
        $fwc_product_credit_amount = sanitize_text_field( $_POST['fwc_credit_amount'] );
        update_post_meta( $post_id, 'fwc_credit_amount', $fwc_product_credit_amount );
    }
    
    if ( isset( $_POST['product-type'] ) && 'fast-credit' == sanitize_title( stripslashes( $_POST['product-type'] ) ) ) {
        // save sold individually option
        $fwc_sold_individually = isset( $_POST['_fwc_sold_individually'] ) ? 'yes' : 'no';
        update_post_meta( $post_id, '_sold_individually', $fwc_sold_individually );
        update_post_meta( $post_id, '_fwc_sold_individually', $fwc_sold_individually );
    }
    
    // save credit purchase option
    $fwc_credit_purchase = isset( $_POST['_fwc_credit_purchase'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_fwc_credit_purchase', $fwc_credit_purchase );
    
    // save credit value field
    $fwc_product_credit_value = $_POST['fwc_credit_value'] ;
    if( !empty( $fwc_product_credit_value ) ) {
        $fwc_product_credit_value = sanitize_text_field( $_POST['fwc_credit_value'] );
        update_post_meta( $post_id, 'fwc_credit_value', $fwc_product_credit_value );
    }
    
    // save credit URL field
    $fwc_credit_conf_url = $_POST['fwc_credit_conf_url'] ;
    if( !empty( $fwc_credit_conf_url ) ) {
        $fwc_credit_conf_url = sanitize_text_field( $_POST['fwc_credit_conf_url'] );
        update_post_meta( $post_id, 'fwc_credit_conf_url', $fwc_credit_conf_url );
    }
    
    // save credit button text field
    $fwc_credit_btn_txt = $_POST['fwc_credit_btn_txt'] ;
    if( !empty( $fwc_credit_btn_txt ) ) {
        $fwc_credit_btn_txt = sanitize_text_field( $_POST['fwc_credit_btn_txt'] );
        update_post_meta( $post_id, 'fwc_credit_btn_txt', $fwc_credit_btn_txt );
    }
    
    // save remove add_to_cart field
    $fwc_remove_atc = isset( $_POST['fwc_remove_atc'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, 'fwc_remove_atc', $fwc_remove_atc );
}

add_action( 'woocommerce_process_product_meta', 'fwc_save_custom_settings', 30 );

