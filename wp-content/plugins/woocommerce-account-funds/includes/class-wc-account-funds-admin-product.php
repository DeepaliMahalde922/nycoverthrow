<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WC_Account_Funds_Admin_Product
 */
class WC_Account_Funds_Admin_Product {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter( 'product_type_selector', array( $this, 'product_types' ) );
        add_action( 'woocommerce_process_product_meta_deposit', array( $this, 'process_product_deposit' ), 10 );
        add_action( 'woocommerce_product_write_panels', array( $this, 'product_write_panel' ) );
    }

    /**
     * Add deposit product type
     * @param  array $types
     * @return array
     */
    public function product_types( $types ) {
        $types['deposit'] = __( 'Account Funds Deposit', 'woocommerce-account-funds' );
        return $types;
    }

    /**
     * Save deposit product
     * @param  int $post_id
     */
    public function process_product_deposit( $post_id ) {
        update_post_meta( $post_id, '_virtual', 'yes' );
    }

    /**
     * Hide fields with JS
     */
    public function product_write_panel() {
        ?>
        <script type="text/javascript">
            jQuery('.show_if_simple').addClass( 'show_if_deposit' );
            jQuery('#_virtual, #_downloadable').closest('label').addClass( 'hide_if_deposit' );
        </script>
        <?php
    }
}

new WC_Account_Funds_Admin_Product();