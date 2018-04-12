<?php

/* 
 * woocommerce custom product script
 * 
 */

// add a product type
function fwc_add_custom_product_type( $types ){
    $types['fast-credit'] = __( 'FastCredit product', 'woocommerce' );
    return $types;
}
add_filter( 'product_type_selector', 'fwc_add_custom_product_type' );


// add products Class 
function fwc_create_custom_product_type(){
    
    if( class_exists( "WC_Product" ) ) {
        // declare the product class
        class WC_Product_Fast_Credit extends WC_Product{
            public function __construct( $product ) {
               $this->product_type = 'fast-credit';

               parent::__construct( $product );
               // add additional functions here

            }
            public function needs_shipping() {
                return false;
            }

            /**
             * Get the add to url used mainly in loops.
             *
             * @return string
             */
            public function add_to_cart_url() {
                    $url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );

                    return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
            }

            /**
             * Get the add to cart button text
             *
             * @return string
             */
            public function add_to_cart_text() {
                    $text = $this->is_purchasable() && $this->is_in_stock() ? __( 'Add to cart', 'woocommerce' ) : __( 'Read More', 'woocommerce' );

                    return apply_filters( 'woocommerce_product_add_to_cart_text', $text, $this );
            }

        }    
    }
}
add_action( 'plugins_loaded', 'fwc_create_custom_product_type' );


